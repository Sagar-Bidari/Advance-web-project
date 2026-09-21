<?php
$pageTitle = 'Clinics';
$basePath = '';
require 'includes/header.php';
require 'config/database.php';

$clinics = $pdo->query(
    'SELECT * FROM clinics ORDER BY clinic_name'
)->fetchAll();
?>

<section class="section clinics-page">

    <!-- Page Header -->
    <div class="clinics-header">
        <div>
            <span class="section-label">HEALTHCARE SERVICES</span>

            <h1>Find a Clinic</h1>

            <p>
                Explore our available clinics and choose the right
                healthcare provider for your appointment.
            </p>
        </div>
    </div>


    <!-- Clinic List -->
    <?php if (count($clinics) > 0): ?>

        <div class="grid clinic-grid">

            <?php foreach ($clinics as $clinic): ?>

                <div class="card clinic-card">

                    <div class="clinic-icon">
                        +
                    </div>

                    <div class="clinic-content">

                        <h3>
                            <?= htmlspecialchars($clinic['clinic_name']) ?>
                        </h3>

                        <div class="clinic-info">

                            <p>
                                <span>📍</span>
                                <strong>Address</strong>
                                <?= htmlspecialchars($clinic['address']) ?>
                            </p>

                            <p>
                                <span>☎</span>
                                <strong>Phone</strong>
                                <?= htmlspecialchars($clinic['phone']) ?>
                            </p>

                            <p>
                                <span>✉</span>
                                <strong>Email</strong>
                                <?= htmlspecialchars($clinic['email']) ?>
                            </p>

                        </div>

                        <a
                            class="btn clinic-btn"
                            href="patient/book.php?clinic_id=<?= $clinic['clinic_id'] ?>"
                        >
                            Book Appointment
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="empty-clinics">

            <div class="empty-icon">+</div>

            <h3>No Clinics Available</h3>

            <p>
                There are currently no clinics available for booking.
                Please check again later.
            </p>

        </div>

    <?php endif; ?>

</section>

<?php require 'includes/footer.php'; ?>


