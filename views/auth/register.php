<?php

/**
 * Registration View
 *
 * Displays the user registration form and any validation
 * or success messages stored in the current session.
 *
 * @return void
 */

$errors = $_SESSION['register_errors'] ?? [];
$successMessage = $_SESSION['register_success'] ?? '';

unset($_SESSION['register_errors']);
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

<title>Register - Alzikrayat</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>


</head>

<body>

<?php require __DIR__ . '/../layout/navbar.php'; ?>

<main class="container py-5">


<div class="row justify-content-center">

    <div class="col-12 col-md-10 col-lg-8">

        <div class="card shadow-sm">

            <div class="card-body p-4 p-md-5">

                <h1 class="text-center mb-2">
                    Create Your Account
                </h1>

                <p class="text-center text-muted mb-4">
                    Join Alzikrayat and start sharing your memories.
                </p>

                <?php if (!empty($errors)): ?>

                    <div
                        class="alert alert-danger"
                        role="alert"
                    >

                        <strong>
                            Please fix the following:
                        </strong>

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
                    action="/Php-Course/Alzikrayat/public/register"
                    id="registerForm"
                    novalidate
                >

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label
                                for="first_name"
                                class="form-label"
                            >
                                First Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="first_name"
                                name="first_name"
                                maxlength="50"
                                pattern="[\p{L} ]+"
                                required
                            >

                            <div class="invalid-feedback">
                                Please enter your first name using letters only.
                            </div>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label
                                for="last_name"
                                class="form-label"
                            >
                                Last Name
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="last_name"
                                name="last_name"
                                maxlength="50"
                                pattern="[\p{L} ]+"
                                required
                            >

                            <div class="invalid-feedback">
                                Please enter your last name using letters only.
                            </div>

                        </div>

                    </div>

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

                    <div class="mb-3">

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
                            minlength="8"
                            required
                        >

                        <div class="form-text">
                            Password must contain at least 8 characters.
                        </div>

                        <div class="invalid-feedback">
                            Please enter a password with at least 8 characters.
                        </div>

                    </div>

                    <div class="mb-3">

                        <label
                            for="location"
                            class="form-label"
                        >
                            Location
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="location"
                            name="location"
                            maxlength="100"
                        >

                    </div>

                    <div class="mb-3">

                        <label
                            for="occupation"
                            class="form-label"
                        >
                            Occupation
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="occupation"
                            name="occupation"
                            maxlength="100"
                        >

                    </div>

                    <div class="mb-4">

                        <label
                            for="description"
                            class="form-label"
                        >
                            Description
                        </label>

                        <textarea
                            class="form-control"
                            id="description"
                            name="description"
                            rows="4"
                            maxlength="5000"
                        ></textarea>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        Create Account
                    </button>

                </form>

                <p class="text-center mt-4 mb-0">

                    Already have an account?

                    <a
                        href="/Php-Course/Alzikrayat/public/login"
                    >
                        Login here
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
 * Client-side registration form validation.
 *
 * Uses the browser's HTML5 validation API and Bootstrap's
 * validation styling without replacing backend validation.
 */
document
    .getElementById('registerForm')
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
