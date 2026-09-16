<?php

/**
 * Dynamic Navigation Bar
 *
 * Displays different navigation options depending on whether
 * a user is authenticated.
 *
 * Logged-in users see their first name, the last-login timestamp
 * when available, and a prominent logout button.
 *
 * Logged-out users see a login option.
 *
 * @return void
 */

$isLoggedIn = isset($_SESSION['user_id']);

$firstName = $_SESSION['first_name'] ?? '';

$lastLogin = $_COOKIE['last_login'] ?? '';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">


<div class="container">

    <a
        class="navbar-brand fw-bold"
        href="/Php-Course/Alzikrayat/public/"
    >
        Alzikrayat
    </a>

    <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#mainNavbar"
        aria-controls="mainNavbar"
        aria-expanded="false"
        aria-label="Toggle navigation"
    >
        <span class="navbar-toggler-icon"></span>
    </button>

    <div
        class="collapse navbar-collapse"
        id="mainNavbar"
    >

        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            <li class="nav-item">

                <a
                    class="nav-link"
                    href="/Php-Course/Alzikrayat/public/"
                >
                    Home
                </a>

            </li>

            <li class="nav-item">

                <a
                    class="nav-link"
                    href="/Php-Course/Alzikrayat/public/photos"
                >
                    Gallery
                </a>

            </li>

        </ul>

        <div class="d-flex align-items-center gap-2">

            <?php if ($isLoggedIn): ?>

                <span class="text-white">
                    Hi <?= htmlspecialchars($firstName) ?>
                </span>

                <?php if ($lastLogin !== ''): ?>

                    <span class="text-light small">
                        Last login from this computer was
                        <?= htmlspecialchars($lastLogin) ?>
                    </span>

                <?php endif; ?>

                <a
                    href="/Php-Course/Alzikrayat/public/logout"
                    class="btn btn-danger"
                >
                    Logout
                </a>

            <?php else: ?>

                <span class="text-white me-2">
                    Please Login
                </span>

                <a
                    href="/Php-Course/Alzikrayat/public/login"
                    class="btn btn-outline-light"
                >
                    Login
                </a>

                <a
                    href="/Php-Course/Alzikrayat/public/register"
                    class="btn btn-primary"
                >
                    Register
                </a>

            <?php endif; ?>

        </div>

    </div>

</div>


</nav>
