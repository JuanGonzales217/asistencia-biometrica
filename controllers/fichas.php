<?php

require_once "../models/FichaModel.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    FichaModel::crear($_POST);

    header("Location: ../views/fichas.php");
    exit();
}