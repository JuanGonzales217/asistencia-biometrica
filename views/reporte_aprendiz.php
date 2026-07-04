<?php

require_once "../models/AsistenciaModel.php";
require_once __DIR__ . "/../vendor/autoload.php";

use Dompdf\Dompdf;

$id = $_GET["id"];

$asistencias = AsistenciaModel::historialPorAprendiz($id);

$html = '

<h1 style="text-align:center;">
Reporte de Asistencia
</h1>

<table border="1" width="100%" cellpadding="8" cellspacing="0">

<tr style="background:#eaeaea;">

<th>Fecha</th>
<th>Hora</th>
<th>Estado</th>

</tr>

';

foreach($asistencias as $a){

    $html .= '

    <tr>

        <td>'.$a["fecha"].'</td>

        <td>'.$a["hora"].'</td>

        <td>'.$a["estado"].'</td>

    </tr>

    ';
}

$html .= '</table>';

$pdf = new Dompdf();

$pdf->loadHtml($html);

$pdf->setPaper('A4','portrait');

$pdf->render();

$pdf->stream(
    "reporte_asistencia.pdf",
    ["Attachment" => false]
);