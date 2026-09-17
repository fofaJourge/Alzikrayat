<?php

require_once __DIR__ . '/../models/Photo.php';
require_once __DIR__ . '/../models/Comment.php';

/**
 * Photo Controller
 *
 * Handles photo-related requests in the Alzikrayat application,
 * including displaying photos, uploading photos, viewing photo
 * details, and deleting owned photos.
 */
class PhotoController extends Controller
{
    /**
     * Displays the photo gallery.
     *
     * @return void
     */
    public function index(): void
    {
        $photoModel = new Photo();

        $photos = $photoModel->getAll();

        $this->view('photos/index', [
            'photos' => $photos
        ]);
    }

    /**
     * Displays the photo upload form.
     *
     * Only authenticated users can access the upload form.
     *
     * @return void
     */
    public function showUpload(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect(
                '/Php-Course/Alzikrayat/public/login'
            );
        }

        $this->view('photos/upload');
    }

    /**
     * Handles multiple photo uploads.
     *
     * Validates uploaded files, verifies their actual MIME types,
     * generates safe random filenames, moves the files into the
     * server upload directory, and stores their metadata in the
     * database.
     *
     * Only authenticated users are allowed to upload photos.
     *
     * @return void
     */
    public function store(): void
    {
        /*
         * Only authenticated users can upload photos.
         */
        if (!isset($_SESSION['user_id'])) {
            $this->redirect(
                '/Php-Course/Alzikrayat/public/login'
            );
        }

        /*
         * This action must only be reached through POST.
         */
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo '405 - Method Not Allowed';
            return;
        }

        /*
         * Read and trim text fields.
         */
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        /*
         * Validate the required title.
         */
        if ($title === '') {
            echo 'Title is required.';
            return;
        }

        /*
         * Validate the database length limit.
         */
        if (mb_strlen($title) > 200) {
            echo 'Title cannot be longer than 200 characters.';
            return;
        }

        /*
         * Validate the description length.
         */
        if (mb_strlen($description) > 5000) {
            echo 'Description cannot be longer than 5000 characters.';
            return;
        }

        /*
         * Verify that the multiple-upload field exists
         * and has the expected array structure.
         */
        if (
            !isset($_FILES['photos']) ||
            !is_array($_FILES['photos']) ||
            !isset(
                $_FILES['photos']['name'],
                $_FILES['photos']['tmp_name'],
                $_FILES['photos']['error'],
                $_FILES['photos']['size']
            ) ||
            !is_array($_FILES['photos']['name'])
        ) {
            echo 'Please select at least one image.';
            return;
        }

     
        $fileCount = count($_FILES['photos']['name']);

if ($fileCount < 1) {
    echo 'Please select at least one image.';
    return;
}

/*
 * Limit the number of files processed in one request.
 *
 * Multiple uploads are supported, but limiting the batch size
 * helps prevent excessive server resource usage.
 */
$maximumFilesPerUpload = 20;

if ($fileCount > $maximumFilesPerUpload) {
    echo 'You can upload a maximum of 20 images at once.';
    return;
}


        /*
         * Define the physical directory where uploaded images
         * are stored.
         */
        $uploadDirectory =
            __DIR__ . '/../public/images/uploads/';

        /*
         * Create the upload directory if it does not exist.
         */
        if (!is_dir($uploadDirectory)) {
            if (!mkdir($uploadDirectory, 0755, true)) {
                echo 'Unable to create upload directory.';
                return;
            }
        }

        /*
         * Verify that the upload directory is writable.
         */
        if (!is_writable($uploadDirectory)) {
            echo 'Upload directory is not writable.';
            return;
        }

        /*
         * Only these image MIME types are accepted.
         *
         * The MIME type is detected from the actual uploaded file,
         * not from the filename supplied by the browser.
         */
        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        ];

        /*
         * Maximum permitted size for each image: 5 MB.
         */
        $maximumFileSize = 5 * 1024 * 1024;

        /*
         * Create the model once and reuse it for all uploaded files.
         */
        $photoModel = new Photo();

        /*
         * Process every selected image independently.
         */
        for ($index = 0; $index < $fileCount; $index++) {
            /*
             * Make sure all expected indexes exist.
             */
            if (
                !isset(
                    $_FILES['photos']['name'][$index],
                    $_FILES['photos']['tmp_name'][$index],
                    $_FILES['photos']['error'][$index],
                    $_FILES['photos']['size'][$index]
                )
            ) {
                echo 'Invalid uploaded file data.';
                return;
            }

            $temporaryPath =
                $_FILES['photos']['tmp_name'][$index];

            $fileError =
                $_FILES['photos']['error'][$index];

            $fileSize =
                $_FILES['photos']['size'][$index];

            /*
             * Reject uploads that PHP could not process correctly.
             */
            if ($fileError !== UPLOAD_ERR_OK) {
                echo 'One of the uploaded files could not be processed.';
                return;
            }

            /*
             * Confirm that the temporary path represents
             * an actual HTTP-uploaded file.
             */
            if (!is_uploaded_file($temporaryPath)) {
                echo 'Invalid uploaded file.';
                return;
            }

            /*
             * Validate the file size.
             */
            if (
                !is_int($fileSize) ||
                $fileSize <= 0 ||
                $fileSize > $maximumFileSize
            ) {
                echo 'Each image must be larger than 0 bytes and 5MB or smaller.';
                return;
            }

            /*
             * Detect the actual MIME type from the file contents.
             */
            $fileInfo = new finfo(FILEINFO_MIME_TYPE);

            $mimeType = $fileInfo->file($temporaryPath);

            if (
                $mimeType === false ||
                !isset($allowedMimeTypes[$mimeType])
            ) {
                echo 'Only JPEG, PNG, GIF, and WebP images are allowed.';
                return;
            }

            /*
             * Verify that the uploaded file is actually an image.
             */
            $imageInformation =
                @getimagesize($temporaryPath);

            if ($imageInformation === false) {
                echo 'The uploaded file is not a valid image.';
                return;
            }

            /*
             * Use only a server-generated extension based on
             * the detected MIME type.
             */
            $extension = $allowedMimeTypes[$mimeType];

            /*
             * Generate a cryptographically secure random filename.
             *
             * The original client-provided filename is deliberately
             * not used for the stored filename.
             */
            try {
                $randomName = bin2hex(
                    random_bytes(16)
                );
            } catch (Throwable $exception) {
                echo 'Unable to generate a secure file name.';
                return;
            }

            $storedFileName =
                $randomName . '.' . $extension;

            $destinationPath =
                $uploadDirectory . $storedFileName;

            /*
             * Move the verified upload into the application's
             * controlled upload directory.
             */
            if (!move_uploaded_file(
                $temporaryPath,
                $destinationPath
            )) {
                echo 'Failed to store the uploaded image.';
                return;
            }

            /*
             * Store the file metadata in the database.
             *
             * If the database operation fails, remove the physical
             * file so an orphaned upload is not left behind.
             */
            try {
                $photoModel->create([
                    'user_id' => (int) $_SESSION['user_id'],
                    'file_name' => $storedFileName,
                    'title' => $title,
                    'description' =>
                        $description !== ''
                            ? $description
                            : null
                ]);
            } catch (Throwable $exception) {
                if (file_exists($destinationPath)) {
                    unlink($destinationPath);
                }

                echo 'Failed to save photo information.';
                return;
            }
        }

        /*
         * All uploaded files were processed successfully.
         */
        $this->redirect(
            '/Php-Course/Alzikrayat/public/photos'
        );
    }

    /**
     * Displays a single photo and all comments belonging to it.
     *
     * @param string $id The photo ID from the URL.
     * @return void
     */
    public function show(string $id): void
    {
        /*
         * Convert and validate the dynamic route parameter.
         */
        $photoId = filter_var(
            $id,
            FILTER_VALIDATE_INT
        );

        if (
            $photoId === false ||
            $photoId === null ||
            $photoId <= 0
        ) {
            http_response_code(400);
            echo '400 - Invalid Photo ID';
            return;
        }

        /*
         * Retrieve the requested photo.
         */
        $photoModel = new Photo();

        $photo = $photoModel->findById($photoId);

        if ($photo === null) {
            http_response_code(404);
            echo '404 - Photo Not Found';
            return;
        }

        /*
         * Retrieve all comments belonging to this photo.
         */
        $commentModel = new Comment();

        $comments = $commentModel->getByPhotoId($photoId);

        /*
         * Render the complete photo details page.
         */
        $this->view('photos/show', [
            'photo' => $photo,
            'comments' => $comments
        ]);
    }

    /**
     * Deletes a photo belonging to the currently logged-in user.
     *
     * The server verifies ownership before deleting the database
     * record. The corresponding physical image file is then removed.
     *
     * @param string $id The photo ID from the URL.
     * @return void
     */
    public function delete(string $id): void
    {
        /*
         * Only authenticated users can delete photos.
         */
        if (!isset($_SESSION['user_id'])) {
            $this->redirect(
                '/Php-Course/Alzikrayat/public/login'
            );
        }

        /*
         * Validate the photo ID from the route.
         */
        $photoId = filter_var(
            $id,
            FILTER_VALIDATE_INT
        );

        if (
            $photoId === false ||
            $photoId === null ||
            $photoId <= 0
        ) {
            http_response_code(400);
            echo '400 - Invalid Photo ID';
            return;
        }

        $userId = (int) $_SESSION['user_id'];

        $photoModel = new Photo();

        /*
         * Retrieve the photo only if it belongs to the
         * currently authenticated user.
         *
         * This is the important server-side ownership check.
         */
        $photo = $photoModel->findOwnedPhoto(
            $photoId,
            $userId
        );

        if ($photo === null) {
            http_response_code(403);
            echo '403 - You are not allowed to delete this photo.';
            return;
        }

        /*
         * Keep the stored filename before deleting the database row.
         */
        $fileName = $photo['file_name'];

        /*
         * Delete only the record belonging to this user.
         */
        $deleted = $photoModel->deleteOwnedPhoto(
            $photoId,
            $userId
        );

        if (!$deleted) {
            http_response_code(500);
            echo 'Failed to delete photo.';
            return;
        }

        /*
         * Build the physical path using the trusted filename
         * retrieved from the database.
         */
        $filePath =
            __DIR__ .
            '/../public/images/uploads/' .
            $fileName;

        /*
         * Remove the physical image when it exists.
         */
        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                /*
                 * The database record has already been removed.
                 * The application remains functionally correct,
                 * but the physical file could not be cleaned up.
                 */
                echo 'Photo deleted, but the physical image file could not be removed.';
                return;
            }
        }

        /*
         * Return to the gallery after successful deletion.
         */
        $this->redirect(
            '/Php-Course/Alzikrayat/public/photos'
        );
    }
}