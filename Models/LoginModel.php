<?php

namespace Models;

require_once "MainModel.php";

/**
 * Modelo para iniciar sesión.
 */
class LoginModel extends MainModel {

    /**
     * Inicia sesion con las credenciales proporcionadas.
     * 
     * @param array $data Datos de inicio de sesión.
     */
    protected static function loginUser($data) {
        $sql = parent::getDBConnection()->prepare("SELECT * FROM usuario 
                                                    WHERE usuario_usuario = :Usuario 
                                                    AND usuario_clave = :Clave 
                                                    AND usuario_estado = 'Activa'");
        
        $sql->bindParam(":Usuario", $data['Usuario']);
        $sql->bindParam(":Clave", $data['Clave']);
        $sql->execute();

        return $sql;
    }
}