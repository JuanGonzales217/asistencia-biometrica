<?php

require_once __DIR__ . "/../models/UsuarioModel.php";
class LoginController {

    public static function login(){

        if(isset($_POST["usuario"])){

            $usuario = $_POST["usuario"];
            $password = $_POST["password"];

            $user = UsuarioModel::login($usuario);

            if($user){

                if(password_verify($password, $user["password"])){

                    session_start();

                    $_SESSION["id"] = $user["id"];
$_SESSION["nombre"] = $user["nombre"];                    $_SESSION["rol"] = $user["rol_id"];

                    header("Location: dashboard.php");
                    exit();

                } else {

                    echo "<script>alert('Contraseña incorrecta');</script>";

                }

            } else {

                echo "<script>alert('Usuario no encontrado');</script>";

            }

        }

    }

}