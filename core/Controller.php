<?php

/**
 * Base Controller Class
 *
 * Provides common functionality for application controllers,
 * including rendering PHP view files.
 *
 * @return void
 */
class Controller
{
    /**
     * Renders a view file and optionally provides data to it.
     *
     * @param string $view The path of the view relative to the views directory.
     * @param array $data Data that will be made available to the view.
     * @return void
     * @throws RuntimeException If the requested view does not exist.
     */
    protected function view(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new RuntimeException("View not found: " . $view);
        }

        extract($data);

        require $viewPath;
    }

    /**
     * Redirects the browser to another application path.
     *
     * @param string $path The destination path.
     * @return never
     */
    protected function redirect(string $path): never
    {
        header('Location: ' . $path);
        exit;
    }
}