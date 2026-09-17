<?php

/**
 * Alzikrayat Front Controller
 *
 * Initializes the application, starts the user session when necessary,
 * registers application routes, and dispatches the incoming request
 * to the appropriate controller action.
 *
 * @return void
 */

/*
 * Add basic HTTP security headers.
 *
 * These headers instruct the browser to apply safer defaults when
 * displaying and communicating with the application.
 */
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

/*
 * Start a PHP session only when one is not already active.
 *
 * This prevents the "session_start(): Ignoring session_start()
 * because a session is already active" notice.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Model.php';

$router = new Router();

/*
 * Authentication routes.
 */
$router->get('/', ['HomeController', 'index']);
$router->get('/register', ['AuthController', 'showRegister']);
$router->post('/register', ['AuthController', 'register']);
$router->get('/login', ['AuthController', 'showLogin']);
$router->post('/login', ['AuthController', 'login']);
$router->get('/logout', ['AuthController', 'logout']);

/*
 * Photo routes.
 */
$router->get('/photos', ['PhotoController', 'index']);
$router->get('/photo/upload', ['PhotoController', 'showUpload']);
$router->post('/photo/store', ['PhotoController', 'store']);

/*
 * Comment route.
 */
$router->post('/comment/store', ['CommentController', 'store']);

/*
 * Dynamic photo routes.
 */
$router->get('/photo/{id}', ['PhotoController', 'show']);
$router->get('/photo/{id}/delete', ['PhotoController', 'delete']);

/*
 * Determine the application-relative request path.
 *
 * The project is currently accessed through:
 *
 * /Php-Course/Alzikrayat/public/
 */
$scriptDirectory = dirname($_SERVER['SCRIPT_NAME']);

$requestPath = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

if ($requestPath === false || $requestPath === null) {
    $requestPath = '/';
}

if (str_starts_with($requestPath, $scriptDirectory)) {
    $requestPath = substr(
        $requestPath,
        strlen($scriptDirectory)
    );
}

if ($requestPath === '' || $requestPath === false) {
    $requestPath = '/';
}

if ($requestPath[0] !== '/') {
    $requestPath = '/' . $requestPath;
}

/*
 * Dispatch the request through the manual MVC router.
 */
$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $requestPath
);