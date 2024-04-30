<?php require "../php/db_conciliacion.php";

// Inicializa el array de respuesta
$response = array();
// Recibe los datos del formulario si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['numeroCheque'])) {
	$numero_cheque = $_POST["numeroCheque"];
	$fecha_anulado = $_POST["fechaAnulado"];
	$detalle_anulado = $_POST["detalle_anulado"];

	if (empty($fecha_anulado) || empty($detalle_anulado)) {
		// Devuelve un mensaje de error si algún campo está vacío
		$response['success'] = false;
		$response['mensaje'] = "⚠️ Llene todos los campos.";
	} else {
		try {
			// Actualizar la base de datos
			$stmt = $conn->prepare("UPDATE cheques SET fecha_anulado = :fecha_anulado, detalle_anulado = :detalle_anulado WHERE numero_cheque = :numero_cheque");
			$stmt->bindParam(':fecha_anulado', $fecha_anulado);
			$stmt->bindParam(':detalle_anulado', $detalle_anulado);
			$stmt->bindParam(':numero_cheque', $numero_cheque);

			if ($stmt->execute()) {
				$response['success'] = true;
				$response['mensaje'] = "✅ El cheque se ha anulado correctamente.";
			} else {
				$response['success'] = false;
				$response['mensaje'] = "❌ Error al anular el cheque.";
			}
		} catch (PDOException $e) {
			$response['success'] = false;
			$response['mensajeError'] = "❌ Error al anular el cheque: " . $e->getMessage();
		}
	}
}
// Establecer la cabecera de contenido JSON
header('Content-Type: application/json');
// Devuelve la respuesta como JSON
echo json_encode($response);
