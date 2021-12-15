<?php

namespace Core;

class Router
{
    static function start()
    {
        $controller_name = 'index';
        $action_name = 'index';

        if (isset($_GET['route'])) {
            $routes = explode('/', $_GET['route']);

            if (!empty($routes[0])) {
                $controller_name = $routes[0];
            }

            if (!empty($routes[1])) {
                $action_name = $routes[1];
            }
        }

        $controller_name = 'Controller' . ucfirst($controller_name);
        $action_name = 'action' . ucfirst($action_name);
        $controller_path = 'src/controller/' . $controller_name . '.php';
        $controller_name = '\Controller\\' . $controller_name;

        if (file_exists($controller_path)) {
            $controller = new $controller_name(new View());
        } else {
            header(sprintf("Location: %s", 'index.php?route=error/not_found'));
        }


        if (method_exists($controller, $action_name)) {
            $controller->$action_name();
        } else {
            header(sprintf("Location: %s", 'index.php?route=error/not_found'));
        }
    }

}
