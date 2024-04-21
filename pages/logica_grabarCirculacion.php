<?php require "../php/db_conciliacion.php";
// Inicializa el array de respuesta
$response = array();
// Recibe los datos del formulario si la solicitud es POST
// Manejo del formulario de anulación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["numeroCheque"])) {
	$default_date = "0000-00-00";

	$numero_cheque = $_POST["numeroCheque"];
	$fecha_circulacion = $_POST["fecha_circulacion"];

	try {
		// Actualizar la base de datos
		$stmt = $conn->prepare("UPDATE cheques SET  fecha_circulacion = :fecha_circulacion WHERE numero_cheque = :numero_cheque");
		$stmt->bindParam(':fecha_circulacion', $fecha_circulacion);
		$stmt->bindParam(':numero_cheque', $numero_cheque);

		if ($stmt->execute()) {
			$response['success'] = true;
			$response['mensaje'] = "✅ El cheque ha salido de circulación correctamente.";
		} else {
			$response['success'] = false;
			$response['mensaje'] = "❌ Error al sacar de circulación el cheque.";
		}
	} catch (PDOException $e) {
		$response['success'] = false;
		$response['mensajeError'] = "❌ Error al sacar de circulación el cheque: " . $e->getMessage();
	}
}
// Establecer la cabecera de contenido JSON
header('Content-Type: application/json');
// Devuelve la respuesta como JSON
echo json_encode($response);
