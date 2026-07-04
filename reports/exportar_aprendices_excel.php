<?php

session_start();

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../models/AprendizModel.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;

$aprendices = AprendizModel::listar();

$totalAprendices = count($aprendices);
$totalActivos = 0;
$totalSuspendidos = 0;
$totalConHuella = 0;
$totalSinHuella = 0;

foreach ($aprendices as $aprendiz) {

    if ($aprendiz["estado"] === "Activo") {
        $totalActivos++;
    } else {
        $totalSuspendidos++;
    }

    if ($aprendiz["huella_id"] !== "SIN_HUELLA" && !empty($aprendiz["huella_id"])) {
        $totalConHuella++;
    } else {
        $totalSinHuella++;
    }
}

$spreadsheet = new Spreadsheet();

/* ================= HOJA 1: RESUMEN ================= */

$hojaResumen = $spreadsheet->getActiveSheet();
$hojaResumen->setTitle("Resumen");

$hojaResumen->mergeCells("A1:H1");
$hojaResumen->setCellValue("A1", "BIOASIST SENA");
$hojaResumen->mergeCells("A2:H2");
$hojaResumen->setCellValue("A2", "Sistema Inteligente de Control de Asistencia");
$hojaResumen->mergeCells("A4:H4");
$hojaResumen->setCellValue("A4", "REPORTE GENERAL DE APRENDICES");

$hojaResumen->setCellValue("A6", "Fecha de generación:");
$hojaResumen->setCellValue("B6", date("d/m/Y"));

$hojaResumen->setCellValue("A7", "Hora de generación:");
$hojaResumen->setCellValue("B7", date("h:i:s a"));

$hojaResumen->setCellValue("A8", "Generado por:");
$hojaResumen->setCellValue("B8", $_SESSION["nombre"] ?? "Administrador");

$hojaResumen->setCellValue("A10", "Indicador");
$hojaResumen->setCellValue("B10", "Cantidad");

$hojaResumen->setCellValue("A11", "Total de aprendices");
$hojaResumen->setCellValue("B11", $totalAprendices);

$hojaResumen->setCellValue("A12", "Aprendices activos");
$hojaResumen->setCellValue("B12", $totalActivos);

$hojaResumen->setCellValue("A13", "Aprendices suspendidos");
$hojaResumen->setCellValue("B13", $totalSuspendidos);

$hojaResumen->setCellValue("A14", "Con huella registrada");
$hojaResumen->setCellValue("B14", $totalConHuella);

$hojaResumen->setCellValue("A15", "Sin huella registrada");
$hojaResumen->setCellValue("B15", $totalSinHuella);

/* Estilos hoja resumen */

$verdeSena = "39A900";
$verdeOscuro = "1F6B00";
$verdeClaro = "EAF7E0";

$hojaResumen->getStyle("A1:H1")->getFont()->setBold(true)->setSize(18)->getColor()->setRGB("FFFFFF");
$hojaResumen->getStyle("A2:H2")->getFont()->setItalic(true)->setSize(11)->getColor()->setRGB("FFFFFF");
$hojaResumen->getStyle("A4:H4")->getFont()->setBold(true)->setSize(14)->getColor()->setRGB("FFFFFF");

$hojaResumen->getStyle("A1:H2")->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setRGB($verdeOscuro);

$hojaResumen->getStyle("A4:H4")->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setRGB($verdeSena);

$hojaResumen->getStyle("A1:H4")->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

$hojaResumen->getStyle("A10:B10")->getFont()
    ->setBold(true)
    ->getColor()->setRGB("FFFFFF");

$hojaResumen->getStyle("A10:B10")->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setRGB($verdeSena);

$hojaResumen->getStyle("A10:B15")->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN)
    ->getColor()
    ->setRGB("D9E8D2");

$hojaResumen->getStyle("A11:B15")->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setRGB("F8FCF6");

$hojaResumen->getColumnDimension("A")->setWidth(30);
$hojaResumen->getColumnDimension("B")->setWidth(18);
$hojaResumen->getColumnDimension("C")->setWidth(15);
$hojaResumen->getColumnDimension("D")->setWidth(15);
$hojaResumen->getColumnDimension("E")->setWidth(15);
$hojaResumen->getColumnDimension("F")->setWidth(15);
$hojaResumen->getColumnDimension("G")->setWidth(15);
$hojaResumen->getColumnDimension("H")->setWidth(15);

/* ================= HOJA 2: LISTADO ================= */

$hojaListado = $spreadsheet->createSheet();
$hojaListado->setTitle("Listado de aprendices");

$hojaListado->mergeCells("A1:H1");
$hojaListado->setCellValue("A1", "BIOASIST SENA - LISTADO DE APRENDICES");

$hojaListado->setCellValue("A3", "ID");
$hojaListado->setCellValue("B3", "DOCUMENTO");
$hojaListado->setCellValue("C3", "NOMBRES");
$hojaListado->setCellValue("D3", "APELLIDOS");
$hojaListado->setCellValue("E3", "CORREO");
$hojaListado->setCellValue("F3", "TELÉFONO");
$hojaListado->setCellValue("G3", "FICHA");
$hojaListado->setCellValue("H3", "HUELLA");
$hojaListado->setCellValue("I3", "ESTADO");

$fila = 4;

foreach ($aprendices as $aprendiz) {

    $hojaListado->setCellValue("A" . $fila, $aprendiz["id"]);
    $hojaListado->setCellValue("B" . $fila, $aprendiz["documento"]);
    $hojaListado->setCellValue("C" . $fila, $aprendiz["nombres"]);
    $hojaListado->setCellValue("D" . $fila, $aprendiz["apellidos"]);
    $hojaListado->setCellValue("E" . $fila, $aprendiz["correo"]);
    $hojaListado->setCellValue("F" . $fila, $aprendiz["telefono"]);
    $hojaListado->setCellValue("G" . $fila, $aprendiz["numero_ficha"] ?? "Sin ficha");
    $hojaListado->setCellValue(
        "H" . $fila,
        $aprendiz["huella_id"] === "SIN_HUELLA" ? "Sin registrar" : $aprendiz["huella_id"]
    );
    $hojaListado->setCellValue("I" . $fila, $aprendiz["estado"]);

    if ($fila % 2 === 0) {
        $hojaListado->getStyle("A{$fila}:I{$fila}")->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB("F7FBF5");
    }

    $fila++;
}

$hojaListado->getStyle("A1:I1")->getFont()
    ->setBold(true)
    ->setSize(15)
    ->getColor()
    ->setRGB("FFFFFF");

$hojaListado->getStyle("A1:I1")->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setRGB($verdeOscuro);

$hojaListado->getStyle("A1:I1")->getAlignment()
    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

$hojaListado->getStyle("A3:I3")->getFont()
    ->setBold(true)
    ->getColor()
    ->setRGB("FFFFFF");

$hojaListado->getStyle("A3:I3")->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setRGB($verdeSena);

$hojaListado->getStyle("A3:I" . ($fila - 1))->getBorders()
    ->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN)
    ->getColor()
    ->setRGB("E1E9DE");

$hojaListado->getStyle("A3:I" . ($fila - 1))->getAlignment()
    ->setVertical(Alignment::VERTICAL_CENTER);

$hojaListado->setAutoFilter("A3:I" . ($fila - 1));
$hojaListado->freezePane("A4");

$anchos = [
    "A" => 8,
    "B" => 16,
    "C" => 22,
    "D" => 24,
    "E" => 35,
    "F" => 16,
    "G" => 16,
    "H" => 18,
    "I" => 15
];

foreach ($anchos as $columna => $ancho) {
    $hojaListado->getColumnDimension($columna)->setWidth($ancho);
}

/* Descargar archivo */

$nombreArchivo = "Reporte_Aprendices_" . date("Y-m-d_H-i-s") . ".xlsx";

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"" . $nombreArchivo . "\"");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;