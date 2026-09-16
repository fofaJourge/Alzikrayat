<?php

/**
 * Home Page View
 *
 * Displays the main landing page of the Alzikrayat application.
 * The page includes the shared navigation bar, a welcome section,
 * dynamic application statistics, and navigation to the gallery.
 *
 * @var array<string, int> $statistics
 *
 * @return void
 */
?>

<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Home - Alzikrayat</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<style>

    /**
     * Main hero section styling.
     */
    .hero-section {
        min-height: 420px;
        display: flex;
        align-items: center;
        background: linear-gradient(
            135deg,
            #f8f9fa,
            #e9ecef
        );
    }

    /**
     * Decorative image cards used on the homepage.
     */
    .photo-card {
        height: 220px;
        overflow: hidden;
        border-radius: 16px;
    }

    .photo-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /**
     * Statistics cards.
     */
    .stat-card {
        border: none;
        border-radius: 16px;
    }

    /**
     * Responsive adjustment for smaller screens.
     */
    @media (max-width: 768px) {

        .hero-section {
            min-height: 350px;
            text-align: center;
        }

        .photo-card {
            height: 180px;
        }

    }

    /**
     * Responsive adjustment for landscape orientation
     * on smaller devices.
     */
    @media (max-width: 768px) and (orientation: landscape) {

        .hero-section {
            min-height: 300px;
        }

        .photo-card {
            height: 160px;
        }

    }

</style>


</head>

<body>


<?php require __DIR__ . '/../layout/navbar.php'; ?>

<main>

    <!-- Hero Section -->

    <section class="hero-section">

        <div class="container">

            <div class="row align-items-center g-5">

                <div class="col-12 col-lg-7">

                    <span class="badge text-bg-primary mb-3">
                        Welcome to Alzikrayat
                    </span>

                    <h1 class="display-4 fw-bold mb-3">
                        Share Your Memories.
                        Preserve Your Moments.
                    </h1>

                    <p class="lead text-muted mb-4">
                        Alzikrayat is a photo sharing platform
                        where users can upload, discover, and
                        comment on memorable moments.
                    </p>

                    <div class="d-flex flex-wrap gap-2">

                        <a
                            href="/Php-Course/Alzikrayat/public/photos"
                            class="btn btn-primary btn-lg"
                        >
                            Explore Gallery
                        </a>

                        <?php if (isset($_SESSION['user_id'])): ?>

                            <a
                                href="/Php-Course/Alzikrayat/public/photo/upload"
                                class="btn btn-outline-dark btn-lg"
                            >
                                Share a Photo
                            </a>

                        <?php else: ?>

                            <a
                                href="/Php-Course/Alzikrayat/public/register"
                                class="btn btn-outline-dark btn-lg"
                            >
                                Create Account
                            </a>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="col-12 col-lg-5">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="photo-card shadow-sm">

                                <img
                                    src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=600&q=80"
                                    alt="Beautiful outdoor landscape"
                                >

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="photo-card shadow-sm">

                                <img
                                    src="https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=600&q=80"
                                    alt="Mountain landscape"
                                >

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="photo-card shadow-sm">

                                <img
                                    src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=600&q=80"
                                    alt="Nature scenery"
                                >

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="photo-card shadow-sm">

                                <img
                                    src="https://images.unsplash.com/photo-1500534623283-312aade485b7?auto=format&fit=crop&w=600&q=80"
                                    alt="Scenic destination"
                                >

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- Statistics Section -->

    <section class="py-5">

        <div class="container">

            <div class="text-center mb-5">

                <h2 class="fw-bold">
                    Alzikrayat at a Glance
                </h2>

                <p class="text-muted">
                    A place for people to share and preserve
                    their favorite memories.
                </p>

            </div>

            <div class="row g-4">

                <div class="col-12 col-md-4">

                    <div class="card stat-card shadow-sm h-100">

                        <div class="card-body text-center p-4">

                            <h3 class="display-6 fw-bold">
                                <?= (int) $statistics['photos'] ?>
                            </h3>

                            <h5>
                                Photos Shared
                            </h5>

                            <p class="text-muted mb-0">
                                Memories shared by our community.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-12 col-md-4">

                    <div class="card stat-card shadow-sm h-100">

                        <div class="card-body text-center p-4">

                            <h3 class="display-6 fw-bold">
                                <?= (int) $statistics['users'] ?>
                            </h3>

                            <h5>
                                Members
                            </h5>

                            <p class="text-muted mb-0">
                                People connected through memories.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-12 col-md-4">

                    <div class="card stat-card shadow-sm h-100">

                        <div class="card-body text-center p-4">

                            <h3 class="display-6 fw-bold">
                                <?= (int) $statistics['comments'] ?>
                            </h3>

                            <h5>
                                Comments
                            </h5>

                            <p class="text-muted mb-0">
                                Conversations around shared photos.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- About Us Section -->

    <section class="py-5 bg-light">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-12 col-lg-9 text-center">

                    <h2 class="fw-bold mb-3">
                        About Alzikrayat
                    </h2>

                    <p class="lead text-muted">
                        Alzikrayat is designed to provide a simple
                        and friendly environment for sharing photos
                        and preserving memorable moments.
                    </p>

                    <p class="text-muted mb-0">
                        Users can create accounts, upload photos,
                        explore the gallery, view photo details,
                        and interact with other users through comments.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- Call to Action -->

    <section class="py-5">

        <div class="container">

            <div class="text-center">

                <h2 class="fw-bold mb-3">
                    Ready to Share Your Memories?
                </h2>

                <p class="text-muted mb-4">
                    Join Alzikrayat and start building your
                    collection of memorable moments.
                </p>

                <?php if (isset($_SESSION['user_id'])): ?>

                    <a
                        href="/Php-Course/Alzikrayat/public/photos"
                        class="btn btn-primary btn-lg"
                    >
                        Go to Gallery
                    </a>

                <?php else: ?>

                    <a
                        href="/Php-Course/Alzikrayat/public/register"
                        class="btn btn-primary btn-lg"
                    >
                        Get Started
                    </a>

                <?php endif; ?>

            </div>

        </div>

    </section>

</main>

<!-- Bootstrap JavaScript bundle.
     Required for the responsive navbar collapse. -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
