<?php
$pageTitle='Clinic Dashboard';
$basePath='../';

require '../includes/header.php';
require '../config/database.php';

if(empty($_SESSION['clinic_admin'])){
    header('Location: login.php');
    exit;
}

$appointments=$pdo->query("
    SELECT a.*,p.name patient_name,c.clinic_name
    FROM appointments a
    JOIN patients p ON a.patient_id=p.patient_id
    JOIN clinics c ON a.clinic_id=c.clinic_id
    ORDER BY a.appointment_date,a.appointment_time
")->fetchAll();


if(isset($_POST['status'],$_POST['id']) &&
   in_array($_POST['status'],['Pending','Confirmed','Cancelled','Completed'],true)){

    $stmt=$pdo->prepare(
        'UPDATE appointments SET status=? WHERE appointment_id=?'
    );

    $stmt->execute([
        $_POST['status'],
        (int)$_POST['id']
    ]);

    header('Location: dashboard.php');
    exit;
}


/* Dashboard statistics */

$total=count($appointments);
$pending=0;
$confirmed=0;
$completed=0;
$cancelled=0;

foreach($appointments as $a){

    if($a['status']==='Pending') $pending++;
    if($a['status']==='Confirmed') $confirmed++;
    if($a['status']==='Completed') $completed++;
    if($a['status']==='Cancelled') $cancelled++;
}
?>

<style>
.actions-small form { display: inline-block; margin: 0; }
.actions-small button {
    background: none;
    border: none;
    padding: 0;
    font: inherit;
    cursor: pointer;
}
.actions-small button.confirm { color: inherit; }
.actions-small button.cancel { color: inherit; }
.actions-small button.complete { color: inherit; }
</style>

<section class="section dashboard">

    <!-- HEADER -->

    <div class="dashboard-header">

        <div>

            <span class="dashboard-label">
                CLINIC MANAGEMENT
            </span>

            <h1>Clinic Dashboard</h1>

            <p>
                Manage appointments and keep track of patient visits.
            </p>

        </div>

        <a class="logout-btn" href="../logout.php">
            Logout
        </a>

    </div>


    <!-- STATISTICS -->

    <div class="dashboard-stats">

        <div class="stat-card total">

            <div class="stat-icon">📋</div>

            <div>
                <small>Total</small>
                <strong><?= $total ?></strong>
            </div>

        </div>


        <div class="stat-card pending">

            <div class="stat-icon">⏳</div>

            <div>
                <small>Pending</small>
                <strong><?= $pending ?></strong>
            </div>

        </div>


        <div class="stat-card confirmed">

            <div class="stat-icon">✓</div>

            <div>
                <small>Confirmed</small>
                <strong><?= $confirmed ?></strong>
            </div>

        </div>


        <div class="stat-card completed">

            <div class="stat-icon">★</div>

            <div>
                <small>Completed</small>
                <strong><?= $completed ?></strong>
            </div>

        </div>

    </div>


    <!-- APPOINTMENT HEADER -->

    <div class="appointment-title">

        <div>

            <span>APPOINTMENTS</span>

            <h2>Patient Schedule</h2>

        </div>

        <div class="appointment-count">
            <?= $total ?> Records
        </div>

    </div>


    <!-- APPOINTMENTS -->

    <div class="table-card dashboard-table">

        <table>

            <thead>

                <tr>
                    <th>Patient</th>
                    <th>Clinic</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>

            </thead>

            <tbody>

            <?php if(!$appointments): ?>

                <tr>

                    <td colspan="6">

                        <div class="no-records">

                            <div>⚕</div>

                            <strong>No appointment records yet.</strong>

                            <span>
                                Patient appointments will appear here.
                            </span>

                        </div>

                    </td>

                </tr>

            <?php else: ?>

                <?php foreach($appointments as $index=>$a): ?>

                    <tr style="--delay:<?= $index * .05 ?>s">

                        <td>

                            <div class="patient">

                                <div class="patient-avatar">
                                    <?= strtoupper(
                                        substr($a['patient_name'],0,1)
                                    ) ?>
                                </div>

                                <strong>
                                    <?= htmlspecialchars(
                                        $a['patient_name']
                                    ) ?>
                                </strong>

                            </div>

                        </td>


                        <td>
                            <span class="clinic-name">
                                ⚕ <?= htmlspecialchars(
                                    $a['clinic_name']
                                ) ?>
                            </span>
                        </td>


                        <td>
                            <?= htmlspecialchars(
                                $a['appointment_date']
                            ) ?>
                        </td>


                        <td>
                            <strong class="time">
                                <?= htmlspecialchars(
                                    substr(
                                        $a['appointment_time'],
                                        0,
                                        5
                                    )
                                ) ?>
                            </strong>
                        </td>


                        <td>

                            <span class="status status-<?= strtolower(
                                $a['status']
                            ) ?>">

                                <i></i>

                                <?= htmlspecialchars(
                                    $a['status']
                                ) ?>

                            </span>

                        </td>


                        <td>

                            <div class="actions-small">

                                <?php if($a['status']!=='Confirmed'): ?>

                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= (int)$a['appointment_id'] ?>">
                                        <input type="hidden" name="status" value="Confirmed">
                                        <button type="submit" class="confirm">Confirm</button>
                                    </form>

                                <?php endif; ?>


                                <?php if($a['status']!=='Cancelled'): ?>

                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= (int)$a['appointment_id'] ?>">
                                        <input type="hidden" name="status" value="Cancelled">
                                        <button type="submit" class="cancel">Cancel</button>
                                    </form>

                                <?php endif; ?>


                                <?php if($a['status']!=='Completed'): ?>

                                    <form method="post">
                                        <input type="hidden" name="id" value="<?= (int)$a['appointment_id'] ?>">
                                        <input type="hidden" name="status" value="Completed">
                                        <button type="submit" class="complete">Complete</button>
                                    </form>

                                <?php endif; ?>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</section>


<?php require '../includes/footer.php'; ?>
