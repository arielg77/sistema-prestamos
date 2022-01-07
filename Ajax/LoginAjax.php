<?php

use Controllers\UserController;

$ajaxRequest = true;

require_once "../Config/APP.php";

if (true) {
   

} else {
    session_start(['name' => 'SPM']);
    session_unset();
    session_destroy();
    header("Location: ".SERVERURL."login/");
}