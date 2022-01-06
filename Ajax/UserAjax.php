<?php

use Controllers\UserController;

$ajaxRequest = true;

require_once "../Config/APP.php";

if (isset($_POST['usuario_dni_reg'])) {
    require_once "../Controllers/UserController.php";
    $user = new UserController();

    if (isset($_POST['usuario_dni_reg']) && isset($_POST['usuario_nombre_reg'])) {
        echo $user->create();
    }

} else {
    session_start(['name' => 'SPM']);
    session_unset();
    session_destroy();
    header("Location: ".SERVERURL."login/");
}
