<?php
$pageTitle = 'My Appointments';
$basePath = '../';

require '../includes/header.php';
require '../config/database.php';

if (empty($_SESSION['patient_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT a.*, c.clinic_name
     FROM appointments a
     JOIN clinics c ON a.clinic_id = c.clinic_id
     WHERE a.patient_id = ?
     ORDER BY a.appointment_date DESC, a.appointment_time DESC"
);

$stmt->execute([$_SESSION['patient_id']]);
$appointments = $stmt->fetchAll();
?>

<section class="section appointments-page">

    <!-- PAGE HEADER -->

    <div class="appointment-hero">

        <div class="hero-content">

            <span class="hero-label">PATIENT DASHBOARD</span>

            <h1>My Appointments</h1>

            <p>
                Keep track of your upcoming clinic visits
                and appointment history.
            </p>

        </div>

        <div class="hero-medical-icon">
            ⚕
        </div>

    </div>


    <?php if (!$appointments): ?>

    <!-- EMPTY STATE -->

    <div class="empty-appointments">

        <div class="empty-icon">⚕</div>

        <h2>No Appointments Yet</h2>

        <p>
            You don't have any appointments scheduled.
            Book your first appointment and take the next
            step toward better healthcare.
        </p>

        <a href="book.php" class="btn">
            Book an Appointment
        </a>

    </div>


    <?php else: ?>

    <div class="appointment-heading">

        <div>
            <span class="section-label">YOUR RECORD</span>
            <h2>Appointment History</h2>
        </div>

        <a href="book.php" class="btn">
            + New Appointment
        </a>

    </div>


    <!-- APPOINTMENTS -->

    <div class="appointment-list">

        <?php foreach ($appointments as $index => $a): ?>

        <div class="appointment-card" style="--delay: <?= $index * 0.08 ?>s">

            <!-- DATE -->

            <div class="appointment-date">

                <span>
                    <?= date(
                                'M',
                                strtotime($a['appointment_date'])
                            ) ?>
                </span>

                <strong>
                    <?= date(
                                'd',
                                strtotime($a['appointment_date'])
                            ) ?>
                </strong>

                <small>
                    <?= date(
                                'Y',
                                strtotime($a['appointment_date'])
                            ) ?>
                </small>

            </div>


            <!-- DETAILS -->

            <div class="appointment-details">

                <div class="appointment-clinic">

                    <div class="clinic-symbol">
                        ⚕
                    </div>

                    <div>

                        <span class="small-label">
                            CLINIC
                        </span>

                        <h3>
                            <?= htmlspecialchars(
                                        $a['clinic_name']
                                    ) ?>
                        </h3>

                    </div>

                </div>


                <div class="appointment-info">

                    <div>
                        <span>🕐</span>

                        <div>
                            <small>TIME</small>

                            <strong>
                                <?= htmlspecialchars(
                                            substr(
                                                $a['appointment_time'],
                                                0,
                                                5
                                            )
                                        ) ?>
                            </strong>
                        </div>
                    </div>


                    <div>
                        <span>📅</span>

                        <div>
                            <small>DATE</small>

                            <strong>
                                <?= htmlspecialchars(
                                            $a['appointment_date']
                                        ) ?>
                            </strong>
                        </div>
                    </div>

                </div>

            </div>


            <!-- STATUS -->

            <div class="appointment-status">

                <span class="status-dot"></span>

                <?= htmlspecialchars($a['status']) ?>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <?php endif; ?>

</section>


<?php require '../includes/footer.php'; ?>