<?php

/**
 * Photo Upload View
 *
 * Displays the photo upload form for authenticated users.
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

<title>Upload Photos - Alzikrayat</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>


</head>

<body>


<?php require __DIR__ . '/../layout/navbar.php'; ?>

<main class="container py-5">

    <div class="row justify-content-center">

        <div class="col-12 col-lg-8">

            <div class="card shadow-sm">

                <div class="card-body p-4 p-md-5">

                    <h1 class="fw-bold mb-2">
                        Share Your Photos
                    </h1>

                    <p class="text-muted mb-4">
                        Upload one or more photos and add information
                        about your memories.
                    </p>

                    <form
                        method="POST"
                        action="/Php-Course/Alzikrayat/public/photo/store"
                        enctype="multipart/form-data"
                        id="uploadForm"
                        novalidate
                    >

                        <div class="mb-3">

                            <label
                                for="photos"
                                class="form-label fw-semibold"
                            >
                                Select Photos
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                id="photos"
                                name="photos[]"
                                accept="image/*"
                                multiple
                                required
                            >

                            <div class="form-text">
                                You can select multiple image files.
                                Supported formats: JPG, PNG, GIF, and WebP.
                            </div>

                            <div class="invalid-feedback">
                                Please select at least one photo.
                            </div>

                        </div>

                        <div class="mb-3">

                            <label
                                for="title"
                                class="form-label fw-semibold"
                            >
                                Title
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="title"
                                name="title"
                                maxlength="200"
                                required
                            >

                            <div class="invalid-feedback">
                                Please enter a title.
                            </div>

                        </div>

                        <div class="mb-4">

                            <label
                                for="description"
                                class="form-label fw-semibold"
                            >
                                Description
                            </label>

                            <textarea
                                class="form-control"
                                id="description"
                                name="description"
                                rows="5"
                                maxlength="5000"
                            ></textarea>

                            <div class="form-text">
                                Description is optional.
                            </div>

                        </div>

                        <div class="d-flex flex-wrap gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                Upload Photos
                            </button>

                            <a
                                href="/Php-Course/Alzikrayat/public/photos"
                                class="btn btn-outline-secondary"
                            >
                                Back to Gallery
                            </a>

                        </div>

                    </form>

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
     * Client-side upload form validation.
     *
     * Backend validation remains responsible for security.
     */
    document
        .getElementById('uploadForm')
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
