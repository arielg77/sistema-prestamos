<?php

namespace Controllers;


if ($ajaxRequest) {
    require_once "../Models/LoginModel.php";
} else {
    require_once "./Models/LoginModel.php";
}

use Models\LoginModel;

/**
 * Controlador para login
 */
class LoginController extends LoginModel {

    /**
     * Inicia sesión.
     */
    public function login() {
        $user = parent::clearString($_POST['usuario']);
        $password = parent::clearString($_POST['clave']);

        //Comprobar campos vacíos
        if ($user == "" || $password == "") {
            echo '
                <script>
                    Swal.fire({
                        title: "Ocurrió un error inesperado",
                        text: "No has llenado todos los campos que son requeridos",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';

                exit();
        }

        //Verificar integridad de los datos
        if (parent::verifyData("[a-zA-Z0-9]{1,35}", $user)) {
            echo '
                <script>
                    Swal.fire({
                        title: "Ocurrió un error inesperado",
                        text: "El nombre de usuario no coincide con el formato requerido",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';

                exit();
        }

        if (parent::verifyData("[a-zA-Z0-9$@.-]{7,100}", $password)) {
            echo '
                <script>
                    Swal.fire({
                        title: "Ocurrió un error inesperado",
                        text: "La contraseña no coincide con el formato requerido",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';

                exit();
        }

        $password = parent::encryption($password);        

        $loginData = [
            "Usuario" => $user,
            "Clave" => $password
        ];

        $login = parent::loginUser($loginData);

        if ($login->rowCount() == 1) {
            $row = $login->fetch();

            session_start(['name' => 'SPM']);
            
            $_SESSION['id_spm'] = $row['usuario_id'];
            $_SESSION['nombre_spm'] = $row['usuario_nombre'];
            $_SESSION['apellido_spm'] = $row['usuario_apellido'];
            $_SESSION['usuario_spm'] = $row['usuario_usuario'];
            $_SESSION['privilegio_spm'] = $row['usuario_privilegio'];
            $_SESSION['token_spm'] = md5(uniqid(mt_rand(), true));

            return header("Location: ".SERVERURL."home/");
        } else {
            echo '
                <script>
                    Swal.fire({
                        title: "Ocurrió un error inesperado",
                        text: "El usuario o contraseña son incorrectos",
                        type: "error",
                        confirmButtonText: "Aceptar"
                    });
                </script>
                ';
        }
    }

    public function forceLogout() {
        session_unset();
        session_destroy();

        if (headers_sent()) {
            return "<script>
                        window.location.href='".SERVERURL."login/';
                    </script>";
        } else {
            return header("Location: ".SERVERURL."login/");
        }
    }

}