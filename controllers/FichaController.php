<?php

require_once __DIR__ . "/../models/FichaModel.php";

class FichaController {

    public static function crear(){

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $data = [
                "numero_ficha"     => trim($_POST["numero_ficha"]),
                "programa"         => trim($_POST["programa"]),
                "jornada"          => $_POST["jornada"],
                "nivel_formacion"  => $_POST["nivel_formacion"],
                "fecha_inicio"     => $_POST["fecha_inicio"],
                "fecha_fin"        => $_POST["fecha_fin"],
                "instructor"       => trim($_POST["instructor"]),
                "cupo_maximo"      => $_POST["cupo_maximo"]
            ];

            $resultado = FichaModel::crear($data);

            if($resultado){
                header("Location: ../views/fichas.php?mensaje=creada");
            } else {
                header("Location: ../views/crear_ficha.php?mensaje=error");
            }

            exit();
        }
    }


    public static function actualizar(){

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $data = [
                "id"               => $_POST["id"],
                "numero_ficha"     => trim($_POST["numero_ficha"]),
                "programa"         => trim($_POST["programa"]),
                "jornada"          => $_POST["jornada"],
                "nivel_formacion"  => $_POST["nivel_formacion"],
                "fecha_inicio"     => $_POST["fecha_inicio"],
                "fecha_fin"        => $_POST["fecha_fin"],
                "instructor"       => trim($_POST["instructor"]),
                "cupo_maximo"      => $_POST["cupo_maximo"]
            ];

            $resultado = FichaModel::actualizar($data);

            if($resultado){
                header("Location: ../views/fichas.php?mensaje=actualizada");
            } else {
                header("Location: ../views/editar_ficha.php?id=" . $data["id"] . "&mensaje=error");
            }

            exit();
        }
    }


    public static function cambiarEstado(){

        if(isset($_GET["id"]) && isset($_GET["estado"])){

            $id = $_GET["id"];
            $estado = $_GET["estado"];

            $estadosPermitidos = ["Activa", "Suspendida", "Finalizada"];

            if(!in_array($estado, $estadosPermitidos)){
                header("Location: ../views/fichas.php?mensaje=estado_invalido");
                exit();
            }

            FichaModel::cambiarEstado($id, $estado);

            header("Location: ../views/fichas.php?mensaje=estado_actualizado");
            exit();
        }
    }


    public static function eliminar(){

        if(isset($_GET["id"])){

            $resultado = FichaModel::eliminar($_GET["id"]);

            if($resultado === "TIENE_APRENDICES"){
                header("Location: ../views/fichas.php?mensaje=tiene_aprendices");
            } elseif($resultado === "OK"){
                header("Location: ../views/fichas.php?mensaje=eliminada");
            } else {
                header("Location: ../views/fichas.php?mensaje=error_eliminar");
            }

            exit();
        }
    }

}


/* Acciones que llegan desde formularios o enlaces */

if(isset($_POST["accion"])){

    if($_POST["accion"] === "crear"){
        FichaController::crear();
    }

    if($_POST["accion"] === "actualizar"){
        FichaController::actualizar();
    }
}


if(isset($_GET["accion"])){

    if($_GET["accion"] === "estado"){
        FichaController::cambiarEstado();
    }

    if($_GET["accion"] === "eliminar"){
        FichaController::eliminar();
    }
}