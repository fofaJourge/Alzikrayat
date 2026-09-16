<?php

require_once __DIR__ . '/../models/Photo.php';
require_once __DIR__ . '/../models/Comment.php';

/**
 * Comment Controller
 *
 * Handles requests related to creating and displaying
 * comments in the Alzikrayat application.
 *
 * The controller validates incoming requests, checks
 * authentication, and communicates with the Comment model.
 */
class CommentController extends Controller
{
    /**
     * Stores a new comment for a photo.
     *
     * Only authenticated users are allowed to create comments.
     * The comment is validated before being saved to the database.
     *
     * @return void
     */
    public function store(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/Php-Course/Alzikrayat/public/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo '405 - Method Not Allowed';
            return;
        }

        $photoId = filter_input(
            INPUT_POST,
            'photo_id',
            FILTER_VALIDATE_INT
        );

        if ($photoId === false || $photoId === null || $photoId <= 0) {
            http_response_code(400);
            echo '400 - Invalid photo ID';
            return;
        }

        $commentText = trim($_POST['comment'] ?? '');

        if ($commentText === '') {
            $_SESSION['comment_error'] = 'Comment cannot be empty.';

            $this->redirect(
                '/Php-Course/Alzikrayat/public/photo/' . $photoId
            );
        }

        if (mb_strlen($commentText) > 5000) {
            $_SESSION['comment_error'] =
                'Comment cannot be longer than 5000 characters.';

            $this->redirect(
                '/Php-Course/Alzikrayat/public/photo/' . $photoId
            );
        }

        $photoModel = new Photo();

        $photo = $photoModel->findById($photoId);

        if ($photo === null) {
            http_response_code(404);
            echo '404 - Photo Not Found';
            return;
        }

        $commentModel = new Comment();

        $commentModel->create([
            'photo_id' => $photoId,
            'user_id' => (int) $_SESSION['user_id'],
            'comment' => $commentText
        ]);

        $this->redirect(
            '/Php-Course/Alzikrayat/public/photo/' . $photoId
        );
    }
}