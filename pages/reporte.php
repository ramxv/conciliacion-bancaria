<?php
require_once "../tcpdf/tcpdf.php";
require "../php/db_conciliacion.php";

// Obtener los parámetros de la URL
$fecha_desde = $_GET['fecha-desde'];
$fecha_hasta = $_GET['fecha-hasta'];
$codigo_marcacion = $_GET['codigo-marcacion'];

// Cambiar idioma de MySQL al español
$conn->exec("SET lc_time_names = 'es_ES'");

$data_reporte = $conn->prepare("SELECT fecha, CONCAT(UCASE(LEFT(DATE_FORMAT(fecha, '%a'), 1)), LCASE(SUBSTRING(DATE_FORMAT(fecha, '%a'), 2)), '.') as dia, MIN(hora) as min_hora, MAX(hora) as max_hora FROM datos WHERE codigo = :codigo_marcacion AND fecha BETWEEN :fecha_desde AND :fecha_hasta GROUP BY fecha");
$data_reporte->bindParam(":codigo_marcacion", $codigo_marcacion);
$data_reporte->bindParam(":fecha_desde", $fecha_desde);
$data_reporte->bindParam(":fecha_hasta", $fecha_hasta);
$data_reporte->execute();
$datos = $data_reporte->fetchAll(PDO::FETCH_ASSOC);

$nombre_reporte = $conn->prepare("SELECT nombre1, apellido1 FROM rrhh WHERE codigo_marcacion = :codigo_marcacion");
$nombre_reporte->bindParam(':codigo_marcacion', $codigo_marcacion);
$nombre_reporte->execute();

$resultado = $nombre_reporte->fetch(PDO::FETCH_ASSOC);

global $nombre, $apellido;

$nombre = $resultado['nombre1'];
$apellido = $resultado['apellido1'];

// Crear una nueva instancia de TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Industrias Horizon');
$pdf->SetTitle('Reporte de Asistencia');
$pdf->SetSubject('Reporte de asistencia');
$pdf->SetKeywords('TCPDF, PDF, ejemplo');

// Eliminar encabezado y pie de página predeterminados
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage();

// Ancho de las celdas
$w = array(30, 30, 45, 30, 45);

$encabezado = array('Fecha', ' ', 'Entrada', ' ', 'Salida');

// Límites de la página
$line_limit = 38; // Número máximo de líneas por página
$current_line = 0; // Contador de líneas actuales


// Función para agregar el encabezado de la tabla
function addTableHeader($pdf, $encabezado, $w)
{
	global $nombre, $apellido;

	// Añadir logo de la empresa
	$favicon = '../assets/images/reporte-pdf/navbar-logo.jpg';
	$pdf->Image($favicon, 10, 10, 10, 10, '', '', '', false, 300, '', false, false, 0, false, false, false);

	// Añadir Nombre de la empresa
	$pdf->SetAlpha(0.5);
	$pdf->SetFont('helvetica', 'B', 9);
	$titulo_empresa = 'Industrias Horizon';
	$pdf->Cell(0, 0, $titulo_empresa, 0, 1, 'C',);
	$pdf->Ln(1);
	$pdf->SetAlpha(1);

	// Añadir un título al PDF
	$pdf->SetFont('helvetica', 'B', 18);
	$title = 'Reporte de Asistencia';
	$pdf->Cell(0, 15, $title, 0, 1, 'C');
	$pdf->Ln(4);

	// Nombre del empleado
	$pdf->SetFont('helvetica', 'B', 9);
	$nombre_completo = 'Reporte de Asistencia de ' . $nombre . ' ' . $apellido;
	$pdf->Cell(0, 0, $nombre_completo, 0, 1, 'L',);
	$pdf->Ln(1);

	// Configurar fuente, colores y estilos para el encabezado de la tabla
	$pdf->SetFont('helvetica', 'B', 12);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(1, 32, 127);

	$border_style = array(
		'width' => 0.4,
		'cap' => 'butt',
		'join' => 'miter',
		'dash' => '3,3',
		'phase' => 0,
		'color' => array(1, 32, 127)
	);
	$pdf->SetLineStyle($border_style);

	for ($i = 0; $i < count($encabezado); ++$i) {
		$pdf->MultiCell($w[$i], 7, $encabezado[$i], 'TB', 'C', 1, 0, '', '', true, 0, false, true, 8, 'M', true);
	}

	$pdf->Ln();
}

// Añadir encabezado de la tabla al inicio
addTableHeader($pdf, $encabezado, $w);

// Agregar datos con paginación
$pdf->SetFont('helvetica', '', 12);

foreach ($datos as $row) {
	if ($current_line >= $line_limit) {
		$pdf->AddPage();
		addTableHeader($pdf, $encabezado, $w);
		$pdf->SetFont('helvetica', '', 12); // Restablecer la fuente a normal después del encabezado
		$current_line = 0;
	}
	$pdf->Cell($w[0], 6, date('d-m-Y', strtotime($row['fecha'])), 0, 0, 'C');
	$pdf->Cell($w[1], 6, $row['dia'], 0, 0, 'C');
	$pdf->Cell($w[2], 6, $row['min_hora'], 0, 0, 'C');
	$pdf->Cell($w[3], 6, '', 0, 0, 'C');
	$pdf->Cell($w[4], 6, $row['max_hora'], 0, 0, 'C');
	$pdf->Ln();
	$current_line++;
}

$pdf->Cell(array_sum($w), 0, '', 'T');

$pdf->Output('reporte.pdf', 'I');
