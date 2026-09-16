<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Photo.php';
require_once __DIR__ . '/../models/Comment.php';

/**
 * Home Controller
 *
 * Handles requests related to the application's home page.
 */
class HomeController extends Controller
{
    /**
     * Displays the application's home page.
     *
     * Retrieves current application statistics from the database
     * and passes them to the homepage view.
     *
     * @return void
     */
    public function index(): void
    {
        $userModel = new User();
        $photoModel = new Photo();
        $commentModel = new Comment();

        $statistics = [
            'photos' => $photoModel->countAll(),
            'users' => $userModel->countAll(),
            'comments' => $commentModel->countAll()
        ];

        $this->view('home/index', [
            'statistics' => $statistics
        ]);
    }
}