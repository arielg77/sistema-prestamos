<?php

namespace App;

use App\ViewLoader;

require_once "./App/ViewLoader.php";

/**Se encarga de mapear las urls. */
class Route extends ViewLoader {
        
    /**Obtiene la plantilla del sistema. */
    public function getAppLayout() {
         return require_once "./Views/layout.php";
    }

    /**Rutea las urls. */
    public function route() {
        if(isset($_GET['views'])){
            $route=explode("/", $_GET['views']);
            $response=ViewLoader::loadView($route[0]);
        }else{
            $response="login";
        }

        return $response;
    }
}