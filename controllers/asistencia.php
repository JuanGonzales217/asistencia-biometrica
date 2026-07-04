<?php

require_once "../models/AsistenciaModel.php";

if(isset($_GET["aprendiz"])){

    $resultado = AsistenciaModel::registrar(
        $_GET["aprendiz"]
    );

    if($resultado == "EXISTE"){

        echo "
        <script>
            alert('El aprendiz ya registró asistencia hoy');
            window.location='../views/asistencia.php';
        </script>
        ";

        exit();
    }

    header("Location: ../views/asistencia.php");
    exit();
}