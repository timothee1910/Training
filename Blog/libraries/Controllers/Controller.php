<?php

/**
 * Classe abstraite qui aura pour but de creer les models dont on a besoin
 */
namespace Controllers;


abstract class Controller
{
    protected $model;
    protected $modelName;

    public function __construct() {
        $this->model = new $this->modelName();
    }
}