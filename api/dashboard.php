<?php

session_start();

header("Content-Type: application/json");

require_once "../config/conexion.php";

$conexion = Conexion::conectar();

/*
|--------------------------------------------------------------------------
| TOTAL APRENDICES
|--------------------------------------------------------------------------
*/

$sql = $conexion->prepare("
SELECT COUNT(*) total
FROM aprendices
");

$sql->execute();

$totalAprendices = $sql->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| PRESENTES HOY
|--------------------------------------------------------------------------
*/

$sql = $conexion->prepare("
SELECT COUNT(*) total
FROM asistencia
WHERE fecha = CURDATE()
AND estado='Puntual'
");

$sql->execute();

$presentes = $sql->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| TARDANZAS
|--------------------------------------------------------------------------
*/

$sql = $conexion->prepare("
SELECT COUNT(*) total
FROM asistencia
WHERE fecha = CURDATE()
AND estado='Tarde'
");

$sql->execute();

$tardes = $sql->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| AUSENTES
|--------------------------------------------------------------------------
*/

$total = $totalAprendices["total"];

$presentesHoy = $presentes["total"];

$tardeHoy = $tardes["total"];

$ausentes = $total - ($presentesHoy + $tardeHoy);

if($ausentes < 0){

    $ausentes = 0;

}


/*
|--------------------------------------------------------------------------
| ULTIMAS ACTIVIDADES
|--------------------------------------------------------------------------
*/

$sql = $conexion->prepare("
SELECT

a.nombres,
a.apellidos,

s.estado,
s.hora,
s.fecha

FROM asistencia s

INNER JOIN aprendices a

ON a.id=s.aprendiz_id

ORDER BY s.id DESC

LIMIT 10

");

$sql->execute();

$actividad = $sql->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| GRAFICO ULTIMOS 7 DIAS
|--------------------------------------------------------------------------
*/

$sql = $conexion->prepare("
SELECT

fecha,

COUNT(*) total

FROM asistencia

WHERE fecha>=DATE_SUB(CURDATE(),INTERVAL 6 DAY)

GROUP BY fecha

ORDER BY fecha

");

$sql->execute();

$grafico = $sql->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| RESPUESTA JSON
|--------------------------------------------------------------------------
*/

echo json_encode([

    "aprendices"=>$totalAprendices["total"],

    "presentes"=>$presentes["total"],

    "tardes"=>$tardes["total"],

    "ausentes"=>$ausentes,

    "actividad"=>$actividad,

    "grafico"=>$grafico,

    "hora"=>date("H:i:s"),

    "fecha"=>date("d/m/Y")

]);
