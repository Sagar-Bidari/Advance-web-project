<?php
$pageTitle = 'Book Appointment';
$basePath = '../';

require '../includes/header.php';
require '../config/database.php';

if (empty($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit;
}

$clinics = $pdo->query(
    'SELECT * FROM clinics ORDER BY clinic_name'
)->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $clinic = (int)($_POST['clinic_id'] ?? 0);
    $date = $_POST['appointment_date'] ?? '';
    $time = $_POST['appointment_time'] ?? '';

    if (!$clinic || !$date || !$time) {

        $error = 'Please select clinic, date and time.';

    } elseif ($date < date('Y-m-d')) {

        $error = 'Please select a future date.';

    } else {

        $check = $pdo->prepare(
            "SELECT appointment_id
             FROM appointments
             WHERE clinic_id=?
             AND appointment_date=?
             AND appointment_time=?
             AND status IN ('Pending','Confirmed')"
        );

        $check->execute([$clinic, $date, $time]);

        if ($check->fetch()) {

            $error = 'That time is already booked. Please choose another time.';

        } else {

            $stmt = $pdo->prepare(
                'INSERT INTO appointments
                (patient_id,clinic_id,appointment_date,appointment_time)
                VALUES(?,?,?,?)'
            );

            $stmt->execute([
                $_SESSION['patient_id'],
                $clinic,
                $date,
                $time
            ]);

            $success = 'Appointment request submitted successfully.';
        }
    }
}
?>


<div class="booking-page">

    <div class="booking-card">

        <!-- HEADER -->

        <div class="booking-header">

            <div class="booking-icon">⚕</div>

            <div>

                <span class="section-label">
                    PATIENT PORTAL
                </span>

                <h1>Book an Appointment</h1>

                <p>
                    Choose your clinic, preferred date and time
                    to request an appointment.
                </p>

            </div>

        </div>


        <!-- SUCCESS -->

        <?php if ($success): ?>

        <div class="booking-success">

            <span>✓</span>

            <div>
                <strong>Appointment Submitted</strong>

                <p>
                    <?= htmlspecialchars($success) ?>
                </p>

                <a href="appointments.php">
                    View My Appointments →
                </a>
            </div>

        </div>

        <?php endif; ?>


        <!-- ERROR -->

        <?php if ($error): ?>

        <div class="alert error">
            <?= htmlspecialchars($error) ?>
        </div>

        <?php endif; ?>


        <!-- FORM -->

        <form method="post" class="booking-form">

            <div class="form-group">

                <label for="clinic">
                    Select Clinic
                </label>

                <select name="clinic_id" id="clinic" required>

                    <option value="">
                        Choose a clinic
                    </option>

                    <?php foreach ($clinics as $c): ?>

                    <option value="<?= $c['clinic_id'] ?>" <?= (
                                ($_GET['clinic_id'] ?? '') ==
                                $c['clinic_id']
                                ? 'selected'
                                : ''
                            ) ?>>

                        <?= htmlspecialchars(
                                $c['clinic_name']
                            ) ?>

                    </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <div class="booking-row">

                <div class="form-group">

                    <label for="date">
                        Appointment Date
                    </label>

                    <input type="date" id="date" name="appointment_date" min="<?= date(
                            'Y-m-d',
                            strtotime('+1 day')
                        ) ?>" required>

                </div>


                <div class="form-group">

                    <label for="time">
                        Preferred Time
                    </label>

                    <input type="time" id="time" name="appointment_time" required>

                </div>

            </div>


            <div class="booking-note">

                <span>ℹ</span>

                <p>
                    Your appointment will be submitted as a request.
                    The clinic will confirm the appointment after reviewing
                    the selected time.
                </p>

            </div>


            <button type="submit" class="btn booking-btn">
                Confirm Appointment
            </button>

        </form>


        <div class="booking-footer">

            <a href="appointments.php">
                ← Back to My Appointments
            </a>

        </div>

    </div>

</div>


<?php require '../includes/footer.php'; ?>
