<?php

/**
 * Authentication Controller
 *
 * Handles user registration, login, logout, and authentication-related
 * operations for the Alzikrayat application.
 */
class AuthController extends Controller
{
    /**
     * Displays the registration form.
     *
     * @return void
     */
    public function showRegister(): void
    {
        $this->view('auth/register');
    }

    /**
     * Displays the login form.
     *
     * @return void
     */
    public function showLogin(): void
    {
        $this->view('auth/login');
    }

    /**
     * Registers a new user account.
     *
     * Validates submitted registration data, checks whether the email
     * already exists, securely hashes the password, and creates the
     * new user in the database.
     *
     * @return void
     * @throws RuntimeException If password hashing fails.
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(
                '/Php-Course/Alzikrayat/public/register'
            );
        }

        /*
         * Read and normalize submitted values.
         */
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $location = trim($_POST['location'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $occupation = trim($_POST['occupation'] ?? '');

        $errors = [];

        /*
         * Validate first name.
         *
         * The database allows a maximum of 50 characters.
         * Unicode letters and spaces are allowed.
         */
        if ($firstName === '') {
            $errors[] = 'First name is required.';
        } elseif (mb_strlen($firstName) > 50) {
            $errors[] = 'First name cannot be longer than 50 characters.';
        } elseif (!preg_match('/^[\p{L} ]+$/u', $firstName)) {
            $errors[] = 'First name must contain letters only.';
        }

        /*
         * Validate last name using the same rules.
         */
        if ($lastName === '') {
            $errors[] = 'Last name is required.';
        } elseif (mb_strlen($lastName) > 50) {
            $errors[] = 'Last name cannot be longer than 50 characters.';
        } elseif (!preg_match('/^[\p{L} ]+$/u', $lastName)) {
            $errors[] = 'Last name must contain letters only.';
        }

        /*
         * Validate the email address.
         *
         * The database allows a maximum of 100 characters.
         */
        if ($email === '') {
            $errors[] = 'Email is required.';
        } elseif (mb_strlen($email) > 100) {
            $errors[] = 'Email cannot be longer than 100 characters.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        /*
         * Validate the password.
         *
         * The password is never stored directly.
         * It is securely hashed before being sent to the model.
         */
        if ($password === '') {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }

        /*
         * Validate optional location.
         *
         * The database allows a maximum of 100 characters.
         */
        if (mb_strlen($location) > 100) {
            $errors[] = 'Location cannot be longer than 100 characters.';
        }

        /*
         * Validate optional description.
         *
         * The database uses TEXT, but limiting the submitted value
         * prevents unnecessarily large input.
         */
        if (mb_strlen($description) > 5000) {
            $errors[] =
                'Description cannot be longer than 5000 characters.';
        }

        /*
         * Validate optional occupation.
         *
         * The database allows a maximum of 100 characters.
         */
        if (mb_strlen($occupation) > 100) {
            $errors[] = 'Occupation cannot be longer than 100 characters.';
        }

        /*
         * If validation fails, return to the registration page
         * without attempting a database operation.
         */
        if (!empty($errors)) {
            $_SESSION['register_errors'] = $errors;

            $this->redirect(
                '/Php-Course/Alzikrayat/public/register'
            );
        }

        /*
         * Load the User model.
         */
        require_once __DIR__ . '/../models/User.php';

        $userModel = new User();

        /*
         * Prevent duplicate email addresses.
         */
        if ($userModel->findByEmail($email) !== null) {
            $_SESSION['register_errors'] = [
                'An account with this email already exists.'
            ];

            $this->redirect(
                '/Php-Course/Alzikrayat/public/register'
            );
        }

        /*
         * Securely hash the password.
         *
         * PASSWORD_DEFAULT allows PHP to select and update
         * the recommended password hashing algorithm.
         */
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        if ($hashedPassword === false) {
            throw new RuntimeException(
                'Unable to securely hash the password.'
            );
        }

        /*
         * Create the user account.
         */
        $userModel->create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $hashedPassword,
            'location' => $location !== '' ? $location : null,
            'description' => $description !== '' ? $description : null,
            'occupation' => $occupation !== '' ? $occupation : null
        ]);

        /*
         * Store a success message for the login page.
         */
        $_SESSION['register_success'] =
            'Registration successful. You can now log in.';

        $this->redirect(
            '/Php-Course/Alzikrayat/public/login'
        );
    }

    /**
     * Authenticates an existing user.
     *
     * Finds the user by email, verifies the submitted password against
     * the stored password hash, creates an authenticated session,
     * and records the successful login timestamp in a seven-day cookie.
     *
     * @return void
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(
                '/Php-Course/Alzikrayat/public/login'
            );
        }

        /*
         * Normalize the email in the same way as registration.
         */
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $errors = [];

        /*
         * Validate the submitted email.
         */
        if ($email === '') {
            $errors[] = 'Email is required.';
        } elseif (mb_strlen($email) > 100) {
            $errors[] = 'Email cannot be longer than 100 characters.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        /*
         * Validate the submitted password.
         */
        if ($password === '') {
            $errors[] = 'Password is required.';
        }

        /*
         * Do not query the database when basic validation fails.
         */
        if (!empty($errors)) {
            $_SESSION['login_errors'] = $errors;

            $this->redirect(
                '/Php-Course/Alzikrayat/public/login'
            );
        }

        /*
         * Load the User model.
         */
        require_once __DIR__ . '/../models/User.php';

        $userModel = new User();

        /*
         * Find the account using the submitted email.
         */
        $user = $userModel->findByEmail($email);

        /*
         * Reject the login if the account does not exist or
         * if the password does not match the stored hash.
         *
         * A generic message avoids revealing whether an email
         * address is registered.
         */
        if (
            $user === null
            || !password_verify($password, $user['password'])
        ) {
            $_SESSION['login_errors'] = [
                'Invalid email or password.'
            ];

            $this->redirect(
                '/Php-Course/Alzikrayat/public/login'
            );
        }

        /*
         * Regenerate the session ID after successful authentication
         * to protect against session fixation.
         */
        session_regenerate_id(true);

        /*
         * Store only the information needed by the application
         * in the authenticated session.
         */
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['first_name'] = $user['first_name'];

        /*
         * Record the exact successful login timestamp.
         *
         * The cookie lasts for seven days and is available only
         * to this browser.
         */
        $lastLoginTimestamp = date('Y-m-d H:i:s');

        setcookie(
            'last_login',
            $lastLoginTimestamp,
            [
                'expires' => time() + (7 * 24 * 60 * 60),
                'path' => '/',
                'httponly' => true,
                'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
                'samesite' => 'Lax'
            ]
        );

        /*
         * Redirect the authenticated user to the homepage.
         */
        $this->redirect(
            '/Php-Course/Alzikrayat/public/'
        );
    }

    /**
     * Logs the current user out of the application.
     *
     * Clears all session data, removes the session cookie,
     * destroys the server-side session, and redirects the user
     * to the login page.
     *
     * @return void
     */
    public function logout(): void
    {
        /*
         * Remove all session variables.
         */
        $_SESSION = [];

        /*
         * Remove the browser's PHP session cookie when one exists.
         */
        if (ini_get('session.use_cookies')) {
            $cookieParameters = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                [
                    'expires' => time() - 42000,
                    'path' => $cookieParameters['path'],
                    'domain' => $cookieParameters['domain'],
                    'secure' => $cookieParameters['secure'],
                    'httponly' => $cookieParameters['httponly'],
                    'samesite' => $cookieParameters['samesite'] ?? 'Lax'
                ]
            );
        }

        /*
         * Destroy the server-side session.
         */
        session_destroy();

        /*
         * Redirect the user to the login page.
         */
        $this->redirect(
            '/Php-Course/Alzikrayat/public/login'
        );
    }
}