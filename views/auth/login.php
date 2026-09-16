<?php

/**
 * Login View
 *
 * Displays the login form, registration success messages,
 * and authentication validation errors.
 *
 * @return void
 */

$errors = $_SESSION['login_errors'] ?? [];
$successMessage = $_SESSION['register_success'] ?? '';

unset($_SESSION['login_errors']);
unset($_SESSION['register_success']);
?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"

>

<title>Login - Alzikrayat</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

</head>

<body>

<?php require __DIR__ . '/../layout/navbar.php'; ?>

<main class="container py-5">

<div class="row justify-content-center">


<div class="col-12 col-md-8 col-lg-6">

    <div class="card shadow-sm">

        <div class="card-body p-4 p-md-5">

            <h1 class="text-center mb-2">
                Welcome Back
            </h1>

            <p class="text-center text-muted mb-4">
                Log in to your Alzikrayat account.
            </p>

            <?php if (!empty($errors)): ?>

                <div
                    class="alert alert-danger"
                    role="alert"
                >
                    <strong>Login failed:</strong>

                    <ul class="mb-0 mt-2">

                        <?php foreach ($errors as $error): ?>

                            <li>
                                <?= htmlspecialchars($error) ?>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            <?php endif; ?>

            <?php if ($successMessage !== ''): ?>

                <div
                    class="alert alert-success"
                    role="alert"
                >
                    <?= htmlspecialchars($successMessage) ?>
                </div>

            <?php endif; ?>

            <form
                method="POST"
                action="/Php-Course/Alzikrayat/public/login"
                id="loginForm"
                novalidate
            >

                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email Address
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        maxlength="100"
                        required
                    >

                    <div class="invalid-feedback">
                        Please enter a valid email address.
                    </div>

                </div>

                <div class="mb-4">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        required
                    >

                    <div class="invalid-feedback">
                        Please enter your password.
                    </div>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >
                    Login
                </button>

            </form>

            <p class="text-center mt-4 mb-0">
                Don't have an account?

                <a
                    href="/Php-Course/Alzikrayat/public/register"
                >
                    Register here
                </a>
            </p>

        </div>

    </div>

</div>


</div>

</main>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

<script>
    /**
     * Client-side login form validation.
     *
     * Backend validation remains responsible for security.
     */
    document
        .getElementById('loginForm')
        .addEventListener('submit', function (event) {

            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            this.classList.add('was-validated');
        });
</script>

</body>

</html>
