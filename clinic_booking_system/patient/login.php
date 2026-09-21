<?php
$pageTitle = 'Patient Login';
$basePath = '../';

require '../includes/header.php';
require '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare(
        'SELECT * FROM patients WHERE email=?'
    );

    $stmt->execute([$email]);

    $patient = $stmt->fetch();

    if (
        $patient &&
        password_verify($password, $patient['password'])
    ) {
        $_SESSION['patient_id'] = $patient['patient_id'];
        $_SESSION['patient_name'] = $patient['name'];

        header('Location: book.php');
        exit;
    }

    $error = 'Invalid email or password.';
}
?>

<div class="login-page">

    <div class="form-card login-card">

        <!-- Login Header -->
        <div class="login-title">
            <div class="login-icon">⚕</div>
            <span class="section-label">PATIENT PORTAL</span>
            <!-- <h1>Welcome Back</h1> -->
            <p>
                Sign in to your patient account to book and
                manage your clinic appointments.
            </p>
        </div>

        <!-- Success Message -->

        <?php if (isset($_GET['registered'])): ?>

        <div class="alert">
            Registration successful. Please log in.
        </div>

        <?php endif; ?>


        <!-- Error Message -->

        <?php if ($error): ?>

        <div class="alert error">
            <?= htmlspecialchars($error) ?>
        </div>

        <?php endif; ?>


        <!-- Login Form -->

        <form method="post">

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input type="email" id="email" name="email" placeholder="Enter your email address" autocomplete="email"
                    required>

            </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input type="password" id="password" name="password" placeholder="Enter your password"
                    autocomplete="current-password" required>

            </div>


            <button type="submit" class="btn login-btn">
                Login to Patient Portal
            </button>

        </form>


        <!-- Register -->

        <div class="login-footer">

            <span>New to our system?</span>

            <a href="register.php">
                Create Patient Account
            </a>

        </div>

    </div>

</div>


<?php require '../includes/footer.php'; ?>
