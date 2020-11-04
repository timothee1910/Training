<?php


class Application
{
    public static function process() {
        $controllerName = "ArticleController";
        $task  = "index";

        if (!empty($_GET['controller'])) {
            //Uppercase First
            $controllerName = ucfirst($_GET['controller']);
        }
        if (!empty($_GET['task'])) {
            $task = $_GET['task'];
        }
        $controllerNameField = "\Controllers\\" . $controllerName;
        $controller = new $controllerNameField();
        $controller->$task();
    }

}