<?php

/**
 * Photo Details View
 *
 * Displays complete information about a selected photo,
 * including its comments and the comment form for
 * authenticated users.
 *
 * @var array<string, mixed> $photo
 * @var array<int, array<string, mixed>> $comments
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

    <title>
        <?= htmlspecialchars($photo['title']) ?> - Alzikrayat
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        .photo-details-image {
            width: 100%;
            max-height: 75vh;
            object-fit: contain;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .photo-description {
            white-space: pre-wrap;
        }

        .comment-item {
            border-left: 4px solid #dee2e6;
            padding-left: 1rem;
        }

        .comment-text {
            white-space: pre-wrap;
            word-break: break-word;
        }
    </style>

</head>

<body>

    <?php require __DIR__ . '/../layout/navbar.php'; ?>

    <main class="container py-5">

        <div class="row justify-content-center">

            <div class="col-12 col-xl-10">

                <a
                    href="/Php-Course/Alzikrayat/public/photos"
                    class="btn btn-outline-secondary mb-4"
                >
                    &larr; Back to Gallery
                </a>

                <div class="card shadow-sm overflow-hidden">

                    <div class="card-body p-3 p-md-4">

                        <!-- Photo -->

                        <img
                            src="/Php-Course/Alzikrayat/public/images/uploads/<?= htmlspecialchars($photo['file_name']) ?>"
                            alt="<?= htmlspecialchars($photo['title']) ?>"
                            class="photo-details-image"
                        >

                        <!-- Photo Information -->

                        <div class="mt-4">

                            <div
                                class="d-flex flex-wrap justify-content-between align-items-start gap-3"
                            >

                                <div>

                                    <h1 class="fw-bold mb-2">
                                        <?= htmlspecialchars($photo['title']) ?>
                                    </h1>

                                    <p class="text-muted mb-3">

                                        <strong>Author:</strong>

                                        <?= htmlspecialchars(
                                            $photo['first_name']
                                            . ' '
                                            . $photo['last_name']
                                        ) ?>

                                        <br>

                                        <strong>Date:</strong>

                                        <?= htmlspecialchars(
                                            $photo['date_time']
                                        ) ?>

                                    </p>

                                </div>

                                <?php if (
                                    isset($_SESSION['user_id']) &&
                                    (int) $_SESSION['user_id'] ===
                                    (int) $photo['user_id']
                                ): ?>

                                    <div>

                                        <a
                                            href="/Php-Course/Alzikrayat/public/photo/<?= (int) $photo['id'] ?>/delete"
                                            class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this photo? This action cannot be undone.');"
                                        >
                                            Delete Photo
                                        </a>

                                    </div>

                                <?php endif; ?>

                            </div>

                            <?php if (!empty($photo['description'])): ?>

                                <h5 class="fw-bold">
                                    Description
                                </h5>

                                <p class="photo-description">
                                    <?= htmlspecialchars(
                                        $photo['description']
                                    ) ?>
                                </p>

                            <?php else: ?>

                                <p class="text-muted">
                                    No description was provided.
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>


                <!-- Comments Section -->

                <div class="card shadow-sm mt-4">

                    <div class="card-body p-3 p-md-4">

                        <h2 class="h4 fw-bold mb-4">
                            Comments
                        </h2>


                        <!-- Existing Comments -->

                        <?php if (empty($comments)): ?>

                            <p class="text-muted mb-4">
                                No comments yet. Be the first to comment!
                            </p>

                        <?php else: ?>

                            <div class="mb-4">

                                <?php foreach ($comments as $comment): ?>

                                    <div class="comment-item mb-4">

                                        <div
                                            class="d-flex flex-wrap justify-content-between gap-2"
                                        >

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $comment['first_name']
                                                    . ' '
                                                    . $comment['last_name']
                                                ) ?>
                                            </strong>

                                            <small class="text-muted">
                                                <?= htmlspecialchars(
                                                    $comment['date_time']
                                                ) ?>
                                            </small>

                                        </div>

                                        <p class="comment-text mt-2 mb-0">
                                            <?= htmlspecialchars(
                                                $comment['comment']
                                            ) ?>
                                        </p>

                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>


                        <!-- Add Comment -->

                        <?php if (isset($_SESSION['user_id'])): ?>

                            <hr class="my-4">

                            <h3 class="h5 fw-bold mb-3">
                                Add a Comment
                            </h3>

                            <?php if (
                                isset($_SESSION['comment_error'])
                            ): ?>

                                <div
                                    class="alert alert-danger"
                                    role="alert"
                                >
                                    <?= htmlspecialchars(
                                        $_SESSION['comment_error']
                                    ) ?>
                                </div>

                                <?php unset(
                                    $_SESSION['comment_error']
                                ); ?>

                            <?php endif; ?>

                            <form
                                action="/Php-Course/Alzikrayat/public/comment/store"
                                method="POST"
                                novalidate
                            >

                                <input
                                    type="hidden"
                                    name="photo_id"
                                    value="<?= (int) $photo['id'] ?>"
                                >

                                <div class="mb-3">

                                    <label
                                        for="comment"
                                        class="form-label fw-semibold"
                                    >
                                        Your Comment
                                    </label>

                                    <textarea
                                        id="comment"
                                        name="comment"
                                        class="form-control"
                                        rows="4"
                                        maxlength="5000"
                                        required
                                    ></textarea>

                                    <div class="form-text">
                                        Maximum 5000 characters.
                                    </div>

                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    Post Comment
                                </button>

                            </form>

                        <?php else: ?>

                            <hr class="my-4">

                            <p class="text-muted mb-0">
                                Please
                                <a
                                    href="/Php-Course/Alzikrayat/public/login"
                                >
                                    log in
                                </a>
                                to leave a comment.
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </main>

    <script>
        /*
         * Client-side HTML5 validation for the comment form.
         */
        document.addEventListener('DOMContentLoaded', function () {

            const commentForm = document.querySelector(
                'form[action="/Php-Course/Alzikrayat/public/comment/store"]'
            );

            if (!commentForm) {
                return;
            }

            commentForm.addEventListener('submit', function (event) {

                if (!commentForm.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                commentForm.classList.add('was-validated');

            });

        });
    </script>

</body>

</html>