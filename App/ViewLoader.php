<?php

namespace App;

/**
 * Se encarga de cargar las distintas vistas que componen el sistema.
 */
class ViewLoader{

    /**Obtiene la vista especificada */
    protected static function loadView($view){
        
        $white_list=["home", 
                    "client-list", 
                    "client-new", 
                    "client-search",
                    "client-update",
                    "company",
                    "item-list",
                    "item-new",
                    "item-search",
                    "item-update",
                    "reservation-list", 
                    "reservation-new",
                    "reservation-pending",
                    "reservation-reservation",
                    "reservation-search",
                    "reservation-update",
                    "user-list",
                    "user-new",
                    "user-search",
                    "user-update"];

        if(in_array($view, $white_list)){
            if(is_file("./Views/Contents/".$view."-view.php")){
                $content="./Views/Contents/".$view."-view.php";
            }else{
                $content="404";
            }
        }elseif($view=="login" || $view=="index"){
            $content="login";
        }else{
            $content="404";
        }

        return $content;
    }
}