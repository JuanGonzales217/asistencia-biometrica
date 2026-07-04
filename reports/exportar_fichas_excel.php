<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../models/FichaModel.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

$fichas = FichaModel::listar();

$spreadsheet = new Spreadsheet();

$hoja = $spreadsheet->getActiveSheet();

$hoja->setTitle("Reporte de fichas");

/* Título principal */
$hoja->mergeCells("A1:J1");
$hoja->setCellValue("A1", "REPORTE DE FICHAS - SISTEMA DE ASISTENCIA");

$hoja->getStyle("A1")->getFont()->setBold(true);
$hoja->getStyle("A1")->getFont()->setSize(16);
$hoja->getStyle("A1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$hoja->getStyle("A1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$hoja->getStyle("A1")->getFill()->setFillType(Fill::FILL_SOLID);
$hoja->getStyle("A1")->getFill()->getStartColor()->setRGB("39A900");

$hoja->getStyle("A1")->getFont()->getColor()->setRGB("FFFFFF");

$hoja->getRowDimension(1)->setRowHeight(28);

/* Fecha de exportación */
$hoja->mergeCells("A2:J2");
$hoja->setCellValue(
    "A2",
    "Generado el: " . date("d/m/Y h:i A")
);

$hoja->getStyle("A2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$hoja->getStyle("A2")->getFont()->setItalic(true);

/* Encabezados */
$encabezados = [
    "ID",
    "Número de ficha",
    "Programa de formación",
    "Jornada",
    "Nivel de formación",
    "Instructor",
    "Fecha inicio",
    "Fecha finalización",
    "Cupo máximo",
    "Estado"
];

$columna = "A";

foreach($encabezados as $encabezado){

    $hoja->setCellValue($columna . "4", $encabezado);

    $columna++;
}

/* Diseño de encabezados */
$hoja->getStyle("A4:J4")->getFont()->setBold(true);
$hoja->getStyle("A4:J4")->getFont()->getColor()->setRGB("FFFFFF");

$hoja->getStyle("A4:J4")->getFill()->setFillType(Fill::FILL_SOLID);
$hoja->getStyle("A4:J4")->getFill()->getStartColor()->setRGB("1F6B00");

$hoja->getStyle("A4:J4")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$hoja->getStyle("A4:J4")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

$hoja->getRowDimension(4)->setRowHeight(22);

/* Datos */
$fila = 5;

foreach($fichas as $ficha){

    $hoja->setCellValue("A" . $fila, $ficha["id"]);
    $hoja->setCellValue("B" . $fila, $ficha["numero_ficha"]);
    $hoja->setCellValue("C" . $fila, $ficha["programa"]);
    $hoja->setCellValue("D" . $fila, $ficha["jornada"]);
    $hoja->setCellValue("E" . $fila, $ficha["nivel_formacion"]);
    $hoja->setCellValue("F" . $fila, $ficha["instructor"]);
    $hoja->setCellValue("G" . $fila, $ficha["fecha_inicio"]);
    $hoja->setCellValue("H" . $fila, $ficha["fecha_fin"]);
    $hoja->setCellValue("I" . $fila, $ficha["cupo_maximo"]);
    $hoja->setCellValue("J" . $fila, $ficha["estado"]);

    $fila++;
}

/* Bordes y alineación */
$ultimaFila = $fila - 1;

if($ultimaFila >= 5){

    $hoja->getStyle("A4:J" . $ultimaFila)
        ->getBorders()
        ->getAllBorders()
        ->setBorderStyle(Border::BORDER_THIN);

    $hoja->getStyle("A5:J" . $ultimaFila)
        ->getAlignment()
        ->setVertical(Alignment::VERTICAL_CENTER);

    $hoja->getStyle("A5:A" . $ultimaFila)
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $hoja->getStyle("D5:D" . $ultimaFila)
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $hoja->getStyle("G5:J" . $ultimaFila)
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

/* Tamaño automático de columnas */
foreach(range("A", "J") as $columna){
    $hoja->getColumnDimension($columna)->setAutoSize(true);
}

/* Congelar encabezados */
$hoja->freezePane("A5");

/* Descargar */
$nombreArchivo = "reporte_fichas_" . date("Y-m-d_H-i-s") . ".xlsx";

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"" . $nombreArchivo . "\"");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");

exit();