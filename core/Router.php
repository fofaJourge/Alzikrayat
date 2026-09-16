<?php

/**
 * Manual MVC Router
 *
 * Registers application routes and dispatches incoming HTTP requests
 * to the appropriate controller action.
 *
 * The router supports dynamic route parameters using syntax such as:
 *
 * /photo/{id}
 *
 * which is internally converted into a regular expression.
 *
 * @return void
 */
class Router
{
    /**
     * Registered application routes.
     *
     * @var array<int, array{
     *     method: string,
     *     path: string,
     *     action: array{0: string, 1: string}
     * }>
     */
    private array $routes = [];

    /**
     * Registers a GET route.
     *
     * @param string $path The route path.
     * @param array{0: string, 1: string} $action Controller class and method.
     * @return void
     */
    public function get(string $path, array $action): void
    {
        $this->addRoute('GET', $path, $action);
    }

    /**
     * Registers a POST route.
     *
     * @param string $path The route path.
     * @param array{0: string, 1: string} $action Controller class and method.
     * @return void
     */
    public function post(string $path, array $action): void
    {
        $this->addRoute('POST', $path, $action);
    }

    /**
     * Stores a route in the route collection.
     *
     * @param string $method HTTP request method.
     * @param string $path Route path.
     * @param array{0: string, 1: string} $action Controller class and method.
     * @return void
     */
    private function addRoute(string $method, string $path, array $action): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'action' => $action
        ];
    }

    /**
     * Dispatches the current request to the matching controller action.
     *
     * Dynamic route parameters such as {id} are converted to regular
     * expression capture groups and passed to the controller method.
     *
     * @param string $method HTTP request method.
     * @param string $uri Requested URI path.
     * @return void
     * @throws RuntimeException If no matching route exists.
     */
    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);

        $uri = parse_url($uri, PHP_URL_PATH);

        if ($uri === false || $uri === null) {
            $uri = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = preg_replace(
                '/\{([a-zA-Z][a-zA-Z0-9_]*)\}/',
                '([^/]+)',
                $route['path']
            );

            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                [$controllerName, $actionName] = $route['action'];

                $controllerFile =
                    __DIR__ . '/../controllers/' . $controllerName . '.php';

                if (!file_exists($controllerFile)) {
                    throw new RuntimeException(
                        "Controller not found: " . $controllerName
                    );
                }

                require_once $controllerFile;

                if (!class_exists($controllerName)) {
                    throw new RuntimeException(
                        "Controller class not found: " . $controllerName
                    );
                }

                $controller = new $controllerName();

                if (!method_exists($controller, $actionName)) {
                    throw new RuntimeException(
                        "Controller action not found: "
                        . $controllerName
                        . '::'
                        . $actionName
                    );
                }

                call_user_func_array(
                    [$controller, $actionName],
                    $matches
                );

                return;
            }
        }

        http_response_code(404);
        echo '404 - Page Not Found';
    }
}