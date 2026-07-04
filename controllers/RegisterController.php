<?php

require_once __DIR__ . "/../models/UsuarioModel.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $datos = [

        "nombre" => $_POST["nombres"],
        "apellido" => $_POST["apellidos"],
        "correo" => $_POST["correo"],
        "usuario" => $_POST["usuario"],
        "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
        "rol_id" => $_POST["rol_id"]

    ];

    if (UsuarioModel::registrar($datos)) {

        header("Location: ../views/login.php");
        exit();

    } else {

        echo "Error al registrar";

    }
}