<?php

    use App\Route;

    require_once "./config/APP.php";
    require_once "./App/Route.php";

    $layout = new Route();
    $layout->getAppLayout();