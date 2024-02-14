<?php
namespace macchiato_academy\core;

use macchiato_academy\app\exceptions\AuthenticationException;
use macchiato_academy\app\exceptions\NotFoundException;
use macchiato_academy\core\Security;

class Router
{
    private $routes;

    private function __construct()
    {
        $this->routes = [
            'GET' => [],
            'POST' => []
        ];
    }

    public static function load(string $file): Router
    {
        $router = new Router();
        require $file;
        return $router;
    }

    public function get(string $uri, string $controller, $role = 'ROLE_ANONYMOUS'): void
    {
        $this->routes['GET'][$uri] = [
            'controller' => $controller,
            'role' => $role
        ];
    }

    public function post(string $uri, string $controller, $role = 'ROLE_ANONYMOUS'): void
    {
        $this->routes['POST'][$uri] = [
            'controller' => $controller,
            'role' => $role
        ];
    }

    public function direct(string $uri, string $method): void
    {
        foreach ($this->routes[$method] as $route => $routerData) {
            $controller = $routerData['controller'];
            $minRole = $routerData['role'];

            $urlRule = $this->prepareRoute($route);

            if (preg_match($urlRule, $uri, $matches) === 1) {
                if (Security::isUserGranted($minRole) === false) {
                    if (!is_null(App::get('appUser'))) // logged but not authorized
                        throw new AuthenticationException('Access denied');
                    else // not even logged
                        $this->redirect('login');
                } else {
                    $parameters = $this->getParametersRoute($route, $matches);
    
                    list($controller, $action) = explode('@', $controller);
        
                    if($this->callAction($controller, $action, $parameters) === true)
                        return;
                }
            }
        }

        throw new NotFoundException("No se ha definido una ruta para la uri solicitada");
    }

    public function redirect(string $path)
    {
        header('location: /' . $path);
        exit();
    }

    private function prepareRoute(string $route): string
    {
        $urlRule = preg_replace('/:([^\/]+)/', '(?<\1>[^/]+)', $route);

        $urlRule = str_replace('/', '\/', $urlRule);

        return '/^' . $urlRule . '\/*$/s';
    }

    private function getParametersRoute(string $route, array $matches)
    {
        preg_match_all('/:([^\/]+)/', $route, $parameterNames);
        $parameterNames = array_flip($parameterNames[1]);

        return array_intersect_key($matches, $parameterNames);
    }

    private function callAction(string $controller, string $action, array $parameters): bool {
        try {
            $controller = App::get('config')['project']['namespace'] . '\\app\\controllers\\' . $controller;

            $objController = new $controller();

            if (!method_exists($objController, $action))
                throw new NotFoundException("Controller $controller doesn't have any action such as $action");
            call_user_func_array(array($objController, $action), $parameters);
            return true;
        } catch (\TypeError $typeError) {
            return false;
        }
    }
}
