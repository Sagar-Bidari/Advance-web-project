<?php
$pageTitle='Clinic Login';
$basePath='../';

require '../includes/header.php';
require '../config/database.php';

$error = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM staff WHERE username = ?');
    $stmt->execute([$u]);
    $staff = $stmt->fetch();

    if ($staff && password_verify($p, $staff['password'])) {
        session_regenerate_id(true);
        $_SESSION['clinic_admin'] = true;
        $_SESSION['staff_id'] = $staff['staff_id'];
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid username or password.';
}
?>

<div class="clinic-login">

    <div class="clinic-login-left">

        <div class="clinic-icon">⚕</div>

        <div>
            <small>CLINIC MANAGEMENT</small>
            <h1>Welcome Back</h1>
            <p>Manage appointments and clinic schedules easily.</p>
        </div>

    </div>


    <div class="clinic-login-right">

        <h2>Staff Login</h2>

        <p class="muted">
            Sign in to access your clinic dashboard.
        </p>


        <?php if(!empty($error)): ?>

            <div class="alert error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <form method="post">

            <div class="form-group">

                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    placeholder="Enter username"
                    required
                >

            </div>


            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter password"
                    required
                >

            </div>


            <button class="btn clinic-btn">
                Login to Dashboard →
            </button>

        </form>


        <a class="back-link" href="../index.php">
            ← Back to Website
        </a>

    </div>

</div>


<?php require '../includes/footer.php'; ?>
