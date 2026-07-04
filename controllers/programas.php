<?php

require_once "../models/ProgramaModel.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    ProgramaModel::crear($_POST["nombre"]);

    header("Location: ../views/programas.php");
    exit();
}