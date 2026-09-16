<?php

/**
 * Photo Gallery View
 *
 * Displays all photos retrieved by PhotoController.
 * Logged-in users can access the photo upload page.
 * When no photos exist, an appropriate empty-state message
 * is displayed instead.
 *
 * @param array<int, array<string, mixed>> $photos
 *        Photos retrieved from the database.
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

<title>Gallery - Alzikrayat</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<style>

    .gallery-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.2s ease;
    }

    .gallery-card:hover {
        transform: translateY(-4px);
    }

    .gallery-image {
        width: 100%;
        height: 240px;
        object-fit: cover;
    }

    .empty-gallery {
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gallery-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {

        .gallery-image {
            height: 200px;
        }

        .gallery-header {
            flex-direction: column;
            text-align: center;
        }

    }

    @media (max-width: 768px) and (orientation: landscape) {

        .gallery-image {
            height: 180px;
        }

    }

</style>


</head>

<body>


<?php require __DIR__ . '/../layout/navbar.php'; ?>

<main class="container py-5">

    <div class="gallery-header mb-5">

        <div>

            <h1 class="fw-bold mb-2">
                Photo Gallery
            </h1>

            <p class="text-muted mb-0">
                Explore memorable moments shared by the Alzikrayat community.
            </p>

        </div>

        <?php if (isset($_SESSION['user_id'])): ?>

            <a
                href="/Php-Course/Alzikrayat/public/photo/upload"
                class="btn btn-primary btn-lg"
            >
                📤 Upload Photos
            </a>

        <?php endif; ?>

    </div>

    <?php if (empty($photos)): ?>

        <div class="card shadow-sm empty-gallery">

            <div class="card-body text-center">

                <h2 class="h4 mb-3">
                    No Photos Yet
                </h2>

                <p class="text-muted mb-4">
                    There are no photos in the gallery yet.
                    Be the first to share a memory!
                </p>

                <?php if (isset($_SESSION['user_id'])): ?>

                    <a
                        href="/Php-Course/Alzikrayat/public/photo/upload"
                        class="btn btn-primary"
                    >
                        📤 Share a Photo
                    </a>

                <?php else: ?>

                    <a
                        href="/Php-Course/Alzikrayat/public/login"
                        class="btn btn-primary"
                    >
                        Login to Share a Photo
                    </a>

                <?php endif; ?>

            </div>

        </div>

    <?php else: ?>

        <div class="row g-4">

            <?php foreach ($photos as $photo): ?>

                <div class="col-12 col-md-6 col-lg-4">

                    <div class="card gallery-card shadow-sm h-100">

                        <img
                            src="/Php-Course/Alzikrayat/public/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>"
                            class="gallery-image"
                            alt="<?= htmlspecialchars($photo['title']) ?>"
                        >

                        <div class="card-body">

                            <h2 class="h5">
                                <?= htmlspecialchars($photo['title']) ?>
                            </h2>

                            <p class="text-muted small mb-2">
                                By
                                <?= htmlspecialchars($photo['first_name']) ?>
                                <?= htmlspecialchars($photo['last_name']) ?>
                            </p>

                            <?php if (!empty($photo['description'])): ?>

                                <p class="card-text">
                                    <?= htmlspecialchars($photo['description']) ?>
                                </p>

                            <?php endif; ?>

                            <a
                                href="/Php-Course/Alzikrayat/public/photo/<?= (int) $photo['id'] ?>"
                                class="btn btn-outline-primary"
                            >
                                View Photo
                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
