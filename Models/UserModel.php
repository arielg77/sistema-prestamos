<?php
namespace Models;

require_once "MainModel.php";



class UserModel extends MainModel {

    /**
     * Inserta un nuevo usuario en la base de datos.
     * 
     * @param array $data Datos del usuario.
     */
    protected static function createUser($data) {
        $sql = MainModel::getDBConnection()->prepare("INSERT INTO usuario(usuario_dni, usuario_nombre, usuario_apellido, usuario_telefono, usuario_direccion, usuario_email, usuario_usuario, usuario_clave, usuario_estado, usuario_privilegio) 
                                                        VALUES(:DNI, :Nombre, :Apellido, :Telefono, :Direccion, :Email, :Usuario, :Clave, :Estado, :Privilegio)");

        $sql->bindParam(":DNI", $data['DNI']);
        $sql->bindParam(":Nombre", $data['Nombre']);
        $sql->bindParam(":Apellido", $data['Apellido']);
        $sql->bindParam(":Telefono", $data['Telefono']);
        $sql->bindParam(":Direccion", $data['Direccion']);
        $sql->bindParam(":Email", $data['Email']);
        $sql->bindParam(":Usuario", $data['Usuario']);
        $sql->bindParam(":Clave", $data['Clave']);
        $sql->bindParam(":Estado", $data['Estado']);
        $sql->bindParam(":Privilegio", $data['Privilegio']);

        $sql->execute();

        return $sql;
    }
}