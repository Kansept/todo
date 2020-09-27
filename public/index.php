<?php

require_once(__DIR__ . '/../vendor/autoload.php');

session_start();

/**
 * Путь к корневой директории сайта
 */
define('ROOT_DIR', realpath(__DIR__ . '/../'));

HttpKernel($_SERVER['REQUEST_URI']);

function HttpKernel($uri)
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = array_values(array_filter(explode('/', $uri)));

    if (empty($uri)) {
        $uri = ['/'];
    }

    $config = require ROOT_DIR . '/config/config.php';
    $routes = require ROOT_DIR . '/config/rout.php';

    if (!isset($routes[$uri[0]])) {
        http_response_code(404);
        exit();
    }

    $class = $routes[$uri[0]];
    $method = $uri[1] ?? 'index';

    if (!class_exists($class)) {
        throw new \Exception("Class {$class} not exist", 1);
    }
    if (!method_exists($class, 'action' . $method)) {
        throw new \Exception("Method {$method} not exist in class {$class}", 1);
    }
    $controller = new $class($config);
    $controller->init();

    echo call_user_func_array(array($controller, 'action' . $method), []);

    return;
}
