<?php

require_once "../models/AsistenciaModel.php";

header("Content-Type: application/json");

echo json_encode(
    AsistenciaModel::actividadTiempoReal()
);