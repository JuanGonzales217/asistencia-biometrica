<?php

require_once "../models/AsistenciaModel.php";

if(isset($_GET["fecha"]) && !empty($_GET["fecha"])){

    $historial = AsistenciaModel::historialPorFecha(
        $_GET["fecha"]
    );

}else{

    $historial = AsistenciaModel::historial();

}
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<title>Historial de Asistencia</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="dashboard.php">Inicio</a>
        </li>

        <li class="breadcrumb-item active">
            Aprendices
        </li>
    </ol>
</nav>

    <h2>Historial de Asistencia</h2>

    <form method="GET">

    <div class="row mb-3">

        <div class="col-md-4">

            <input
                type="date"
                name="fecha"
                class="form-control"
                required
            >

        </div>

        <div class="col-md-2">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Buscar
            </button>

        </div>

        <div class="col-md-2">

            <a
                href="historial.php"
                class="btn btn-secondary"
            >
                Mostrar Todo
            </a>

        </div>

    </div>

</form>

    <hr>

    <table class="table table-bordered table-striped">

        <thead>

            <tr>
                <th>Documento</th>
                <th>Aprendiz</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
            </tr>

        </thead>

        <tbody>

        <?php foreach($historial as $fila): ?>

            <tr>

                <td><?= $fila["documento"] ?></td>

                <td>
                    <?= $fila["nombres"] ?>
                    <?= $fila["apellidos"] ?>
                </td>

                <td><?= $fila["fecha"] ?></td>

                <td><?= $fila["hora"] ?></td>

                <td>

                    <?php if($fila["estado"] == "Puntual"): ?>

                        <span class="badge bg-success">
                            Puntual
                        </span>

                    <?php else: ?>

                        <span class="badge bg-danger">
                            Tarde
                        </span>

                    <?php endif; ?>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

</body>
</html>