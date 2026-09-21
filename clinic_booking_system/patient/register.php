<?php
$pageTitle = 'Patient Registration';
$basePath = '../';

require '../includes/header.php';
require '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (
        $name === '' ||
        $email === '' ||
        $phone === '' ||
        strlen($password) < 6
    ) {
        $error = 'Please complete all fields. Password must be at least 6 characters.';
    } else {

        try {

            $stmt = $pdo->prepare(
                'INSERT INTO patients(name,email,password,phone) VALUES(?,?,?,?)'
            );

            $stmt->execute([
                $name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $phone
            ]);

            header('Location: login.php?registered=1');
            exit;

        } catch (PDOException $e) {

            $error = 'This email may already be registered.';
        }
    }
}
?>

<div class="register-page">

    <div class="form-card register-card">

        <div class="register-title">

            <div class="register-icon">⚕</div>

            <span class="section-label">PATIENT PORTAL</span>

            <h1>Create Your Account</h1>

            <p>
                Register to book and manage your clinic
                appointments online.
            </p>

        </div>


        <?php if ($error): ?>

            <div class="alert error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <form method="post">

            <div class="form-row">

                <div class="form-group">

                    <label for="name">Full Name</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Full name"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                        autocomplete="name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="phone">Phone Number</label>

                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        placeholder="Phone number"
                        value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                        autocomplete="tel"
                        required
                    >

                </div>

            </div>


            <div class="form-row">

                <div class="form-group">

                    <label for="email">Email Address</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Email address"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        autocomplete="email"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">Password</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimum 6 characters"
                        minlength="6"
                        autocomplete="new-password"
                        required
                    >

                </div>

            </div>


            <button
                type="submit"
                class="btn register-btn"
            >
                Create Patient Account
            </button>

        </form>


        <div class="register-footer">

            <span>Already have an account?</span>

            <a href="login.php">Patient Login</a>

        </div>

    </div>

</div>


<?php require '../includes/footer.php'; ?>


 