<?php

namespace Controllers;

if ($ajaxRequest) {
    require_once "../Models/UserModel.php";
} else {
    require_once "./Models/UserModel.php";
}



use Models\MainModel;
use Models\UserModel;

class UserController extends UserModel {

    public function create() { 
        $dni = parent::clearString($_POST['usuario_dni_reg']);
        $nombre = parent::clearString($_POST['usuario_nombre_reg']);
        $apellido = parent::clearString($_POST['usuario_apellido_reg']);
        $telefono = parent::clearString($_POST['usuario_telefono_reg']);
        $direccion = parent::clearString($_POST['usuario_direccion_reg']);
        $usuario = parent::clearString($_POST['usuario_usuario_reg']);
        $email = parent::clearString($_POST['usuario_email_reg']);
        $clave1 = parent::clearString($_POST['usuario_clave_1_reg']);
        $clave2 = parent::clearString($_POST['usuario_clave_2_reg']);
        $privilegio = parent::clearString($_POST['usuario_privilegio_reg']);

        //Comprobar campos vacíos
        if ($dni == "" || $nombre == "" || $apellido == "" || $usuario == "" || $clave1 == "" || $clave2 == "") {
            $alert = [
                "Alert" => "simple",
                "Title" => "Ocurrió un error inesperado",
                "Text" => "No has llenado todos los campos requeridos",
                "Type" => "error"
            ];

            echo json_encode($alert);

            exit();
        }

        //Verificar integridad de lo datos
        if (parent::verifyData("[0-9-]{10,20}", $dni)) {
            $alert = [
                "Alert" => "simple",
                "Title" => "Ocurrió un error inesperado",
                "Text" => "El DNI no coincide con el formato solicitado",
                "Type" => "error"
            ];

            echo json_encode($alert);

            exit();
        }

        if (parent::verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}", $nombre)) {
            $alert = [
                "Alert" => "simple",
                "Title" => "Ocurrió un error inesperado",
                "Text" => "El nombre no coincide con el formato solicitado",
                "Type" => "error"
            ];

            echo json_encode($alert);

            exit();
        }

        if (parent::verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}", $apellido)) {
            $alert = [
                "Alert" => "simple",
                "Title" => "Ocurrió un error inesperado",
                "Text" => "El apellido no coincide con el formato solicitado",
                "Type" => "error"
            ];

            echo json_encode($alert);

            exit();
        }

        if ($telefono != "") {
            if (parent::verifyData("[0-9()+]{8,20}", $telefono)) {
                $alert = [
                    "Alert" => "simple",
                    "Title" => "Ocurrió un error inesperado",
                    "Text" => "El teléfono no coincide con el formato solicitado",
                    "Type" => "error"
                ];
    
                echo json_encode($alert);
    
                exit();
            }
        }

        if ($direccion != "") {
            if (parent::verifyData("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}", $direccion)) {
                $alert = [
                    "Alert" => "simple",
                    "Title" => "Ocurrió un error inesperado",
                    "Text" => "La dirección no coincide con el formato solicitado",
                    "Type" => "error"
                ];
    
                echo json_encode($alert);
    
                exit();
            }
        }

        if (parent::verifyData("[a-zA-Z0-9]{1,35}", $usuario)) {
            $alert = [
                "Alert" => "simple",
                "Title" => "Ocurrió un error inesperado",
                "Text" => "El nombre de usuario no coincide con el formato solicitado",
                "Type" => "error"
            ];

            echo json_encode($alert);

            exit();
        }

        if (parent::verifyData("[a-zA-Z0-9$@.-]{7,100}", $clave1) || parent::verifyData("[a-zA-Z0-9$@.-]{7,100}", $clave2)) {
            $alert = [
                "Alert" => "simple",
                "Title" => "Ocurrió un error inesperado",
                "Text" => "Las contraseñas de usuario no coinciden con el formato solicitado",
                "Type" => "error"
            ];

            echo json_encode($alert);

            exit();
        }

        //Verifica que el DNI sea único en la base de datos
        $checkDni =parent::executeSimpleQuery("SELECT usuario_dni FROM usuario 
                                                    WHERE usuario_dni = '$dni'");
        
        if ($checkDni->rowCount() > 0) {
            $alert = [
                "Alert" => "simple",
                "Title" => "Ocurrió un error inesperado",
                "Text" => "El DNI ingresado ya se encuentra registrado en el sistema",
                "Type" => "error"
            ];

            echo json_encode($alert);

            exit();
        }

         //Verifica que el usuario sea único en la base de datos
         $checkUser = parent::executeSimpleQuery("SELECT usuario_usuario FROM usuario 
         WHERE usuario_usuario = '$usuario'");

        if ($checkUser->rowCount() > 0) {
            $alert = [
            "Alert" => "simple",
            "Title" => "Ocurrió un error inesperado",
            "Text" => "El nombre de usuario ingresado ya se encuentra registrado en el sistema",
            "Type" => "error"
            ];

            echo json_encode($alert);

            exit();
        }

        //Comprueba la validez del email y que no exista en la abse de datos
        if ($email != "") {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $checkEmail = parent::executeSimpleQuery("SELECT usuario_email FROM usuario 
                                                            WHERE usuario_email = '$email'");
            
                if ($checkEmail->rowCount() > 0) {
                    $alert = [
                    "Alert" => "simple",
                    "Title" => "Ocurrió un error inesperado",
                    "Text" => "El email incgresado ya se encuentra registrado en el sistema",
                    "Type" => "error"
                    ];
        
                    echo json_encode($alert);
        
                    exit();
                }

            } else {           
                $alert = [
                    "Alert" => "simple",
                    "Title" => "Ocurrió un error inesperado",
                    "Text" => "Ha ingresado un email incorrecto",
                    "Type" => "error"
                    ];
        
                    echo json_encode($alert);
        
                    exit();
            }
        }

        //Comprobar que las contraseñas y la comprobación de contraseñas son iguales
        if ($clave1 != $clave2) {
            $alert = [
                "Alert" => "simple",
                "Title" => "Ocurrió un error inesperado",
                "Text" => "Las contraseñas no coinciden",
                "Type" => "error"
                ];
    
                echo json_encode($alert);
    
                exit();
        } else {
            $password = parent::encryption($clave1);
        }

        //Comprobar privilegios
        if ($privilegio < 1 || $privilegio > 3) {
            $alert = [
                "Alert" => "simple",
                "Title" => "Ocurrió un error inesperado",
                "Text" => "El privilegio seleccionado no es válido",
                "Type" => "error"
            ];
    
                echo json_encode($alert);
    
                exit();
        }

        $userData = [
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion,
            "Email" => $email,
            "Usuario" => $usuario,
            "Clave" => $password,
            "Estado" => "Activa",
            "Privilegio" => $privilegio
        ];

        $insertUser = parent::createUser($userData);

        if ($insertUser->rowCount() == 1) {
            $alert = [
                "Alert" => "clean",
                "Title" => "Usuario registrado",
                "Text" => "El usuario se ha registrado con éxito",
                "Type" => "success"
            ];
        } else {
            $alert = [
                "Alert" => "simple",
                "Title" => "Ocurrió un error inesperado",
                "Text" => "No hemos podido registrar el usuario",
                "Type" => "error"
            ];
        }

        echo json_encode($alert);
    }
}