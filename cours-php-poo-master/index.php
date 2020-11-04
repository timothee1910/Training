<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

//require_once ('libraries/Controllers/ArticleController.php');
require_once ('libraries/models/autoload.php');
Application::process();
//$controller = new \Controllers\ArticleController();
//$controller->index();