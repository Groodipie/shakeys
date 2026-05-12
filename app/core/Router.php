<?php
class Router {
    private array $routes = [];

    public function get(string $pattern, string $handler): void    { $this->add('GET',  $pattern, $handler); }
    public function post(string $pattern, string $handler): void   { $this->add('POST', $pattern, $handler); }
    public function any(string $pattern, string $handler): void    { $this->add('ANY',  $pattern, $handler); }

    private function add(string $method, string $pattern, string $handler): void {
        $regex = '#^' . preg_replace_callback(
            '#\{([a-zA-Z_][a-zA-Z0-9_]*)(?::([^}]+))?\}#',
            fn($m) => '(?P<' . $m[1] . '>' . ($m[2] ?? '[^/]+') . ')',
            $pattern
        ) . '$#';

        $this->routes[] = ['method' => $method, 'regex' => $regex, 'handler' => $handler];
    }

    public function dispatch(string $method, string $path, PDO $pdo): void {
        $path = '/' . trim($path, '/');
        if ($path === '/') $path = '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== 'ANY' && $route['method'] !== $method) continue;
            if (!preg_match($route['regex'], $path, $matches)) continue;

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            [$class, $action] = explode('@', $route['handler']);

            $controller = new $class($pdo);
            call_user_func_array([$controller, $action], array_values($params));
            return;
        }

        http_response_code(404);
        echo 'Not Found';
    }
}
