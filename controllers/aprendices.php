<?php

require_once "../models/AprendizModel.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    AprendizModel::crear($_POST);

    header("Location: ../views/aprendices.php");
    exit();
}

if(isset($_GET["id"])){

    AprendizModel::eliminar($_GET["id"]);

    header("Location: ../views/aprendices.php");
    exit();
}