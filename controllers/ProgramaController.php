<?php

require_once __DIR__ . "/../models/ProgramaModel.php";

class ProgramaController {

    public static function crear(){

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $data = [
                "codigo" => trim($_POST["codigo"]),
                "nombre" => trim($_POST["nombre"]),
                "nivel" => trim($_POST["nivel"]),
                "duracion" => trim($_POST["duracion"]),
                "descripcion" => trim($_POST["descripcion"])
            ];

            $resultado = ProgramaModel::crear($data);

            if($resultado){
                header("Location: ../views/programas.php?mensaje=creado");
            }else{
                header("Location: ../views/programas.php?mensaje=error");
            }

            exit();
        }
    }


    public static function actualizar(){

        if($_SERVER["REQUEST_METHOD"] === "POST"){

            $data = [
                "id" => $_POST["id"],
                "codigo" => trim($_POST["codigo"]),
                "nombre" => trim($_POST["nombre"]),
                "nivel" => trim($_POST["nivel"]),
                "duracion" => trim($_POST["duracion"]),
                "descripcion" => trim($_POST["descripcion"])
            ];

            $resultado = ProgramaModel::actualizar($data);

            if($resultado){
                header("Location: ../views/programas.php?mensaje=actualizado");
            }else{
                header("Location: ../views/programas.php?mensaje=error");
            }

            exit();
        }
    }


    public static function cambiarEstado(){

        if(isset($_GET["id"]) && isset($_GET["estado"])){

            $id = $_GET["id"];
            $estado = $_GET["estado"];

            $estadosPermitidos = ["Activo", "Suspendido", "Finalizado"];

            if(!in_array($estado, $estadosPermitidos)){
                header("Location: ../views/programas.php?mensaje=estado_invalido");
                exit();
            }

            $resultado = ProgramaModel::cambiarEstado($id, $estado);

            if($resultado){
                header("Location: ../views/programas.php?mensaje=estado_actualizado");
            }else{
                header("Location: ../views/programas.php?mensaje=error");
            }

            exit();
        }
    }


    public static function eliminar(){

        if(isset($_GET["id"])){

            $resultado = ProgramaModel::eliminar($_GET["id"]);

            if($resultado === "TIENE_FICHAS"){
                header("Location: ../views/programas.php?mensaje=tiene_fichas");
            }elseif($resultado === "OK"){
                header("Location: ../views/programas.php?mensaje=eliminado");
            }else{
                header("Location: ../views/programas.php?mensaje=error");
            }

            exit();
        }
    }
}


/* Detectar qué acción se solicitó */

if(isset($_GET["accion"])){

    if($_GET["accion"] === "eliminar"){
        ProgramaController::eliminar();
    }

    if($_GET["accion"] === "estado"){
        ProgramaController::cambiarEstado();
    }
}

if(isset($_POST["accion"])){

    if($_POST["accion"] === "crear"){
        ProgramaController::crear();
    }

    if($_POST["accion"] === "actualizar"){
        ProgramaController::actualizar();
    }
}