<?php

require_once __DIR__ . "/../models/AprendizModel.php";
require_once __DIR__ . "/../models/FichaModel.php";

class AprendizController {

    public static function crear(){

        if($_SERVER["REQUEST_METHOD"] == "POST"){

        $ficha = FichaModel::obtenerPorId($_POST["ficha_id"]);

if(!$ficha || $ficha["estado"] !== "Activa"){
    header("Location: ../views/crear_aprendiz.php?mensaje=ficha_no_disponible");
    exit();
}

            $data = [
                "documento" => $_POST["documento"],
                "nombres" => $_POST["nombres"],
                "apellidos" => $_POST["apellidos"],
                "correo" => $_POST["correo"],
                "telefono" => $_POST["telefono"],
                "ficha_id" => $_POST["ficha_id"],
"huella_id" => !empty($_POST["huella_id"])
    ? trim($_POST["huella_id"])
    : "SIN_HUELLA"            ];

header("Location: ../views/aprendices.php?mensaje=creado");
exit();
            ;
        }
    }

    

  public static function eliminar(){

    if(isset($_GET["id"])){

        $resultado = AprendizModel::eliminar($_GET["id"]);

        if($resultado == "OK"){

            header("Location: ../views/aprendices.php?mensaje=eliminado");

        }elseif($resultado == "TIENE_ASISTENCIAS"){

            header("Location: ../views/aprendices.php?error=tiene_asistencias");

        }else{

            header("Location: ../views/aprendices.php?error=eliminar");

        }

        exit();
    }
}

    public static function actualizar(){

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $data = [
            "id" => $_POST["id"],
            "documento" => trim($_POST["documento"]),
            "nombres" => trim($_POST["nombres"]),
            "apellidos" => trim($_POST["apellidos"]),
            "correo" => trim($_POST["correo"]),
            "telefono" => trim($_POST["telefono"]),
            "ficha_id" => $_POST["ficha_id"]
        ];

        AprendizModel::actualizar($data);

        header("Location: ../views/aprendices.php?mensaje=actualizado");
        exit();
    }
}
}

if(isset($_GET["accion"])){

    if($_GET["accion"] === "eliminar" && isset($_GET["id"])){

        $resultado = AprendizModel::eliminar($_GET["id"]);

        if($resultado === "TIENE_ASISTENCIAS"){

            header("Location: ../views/aprendices.php?mensaje=tiene_asistencias");
            exit();
        }

        if($resultado === "OK"){

            header("Location: ../views/aprendices.php?mensaje=eliminado");
            exit();
        }

        header("Location: ../views/aprendices.php?mensaje=error_eliminar");
        exit();
    }

    if($_GET["accion"] === "suspender" && isset($_GET["id"])){

        AprendizModel::cambiarEstado($_GET["id"], "Inactivo");

        header("Location: ../views/aprendices.php?mensaje=suspendido");
        exit();
    }

    if($_GET["accion"] === "activar" && isset($_GET["id"])){

        AprendizModel::cambiarEstado($_GET["id"], "Activo");

        header("Location: ../views/aprendices.php?mensaje=activado");
        exit();
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST["accion"]) && $_POST["accion"] === "actualizar"){
        AprendizController::actualizar();
    }else{
        AprendizController::crear();
    }
}