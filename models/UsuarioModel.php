<?php

require_once __DIR__ . "/../config/conexion.php";
class UsuarioModel {

    public static function login($usuario){

        $sql = Conexion::conectar()->prepare(
            "SELECT * FROM usuarios WHERE usuario = :usuario AND estado = 'Activo'"
        );

        $sql->bindParam(":usuario", $usuario);

        $sql->execute();

        return $sql->fetch(PDO::FETCH_ASSOC);
    }

public static function registrar($datos)
{

    $sql = Conexion::conectar()->prepare("
        INSERT INTO usuarios
        (
            nombre,
            apellido,
            correo,
            usuario,
            password,
            rol_id
        )
        VALUES
        (
            :nombre,
            :apellido,
            :correo,
            :usuario,
            :password,
            :rol_id
        )
    ");

    $sql->bindParam(":nombre", $datos["nombre"]);
    $sql->bindParam(":apellido", $datos["apellido"]);
    $sql->bindParam(":correo", $datos["correo"]);
    $sql->bindParam(":usuario", $datos["usuario"]);
    $sql->bindParam(":password", $datos["password"]);
    $sql->bindParam(":rol_id", $datos["rol_id"]);

    return $sql->execute();
}

}