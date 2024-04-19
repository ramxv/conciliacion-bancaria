<?php require "../php/db_conciliacion.php";

// Consulta SQL para desplegar el nombre del beneficiario
$proveedores = $conn->query("SELECT * FROM proveedores");

$numero_cheque = "";
$fecha = "";
$beneficiario = "";
$monto = "";
$descripcion = "";
$fecha_anulado = "";
$detalle_anulado = "";
$response = array();
$default_date = "0000-00-00";

// Manejo del numero de cheque del formulario de anulación
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST["numeroCheque"]) && isset($_POST['anular'])) {
		$numero_cheque = $_POST["numeroCheque"];
		$fecha_anulado = $_POST["fechaAnulado"];
		$detalle_anulado = $_POST["detalle_anulado"];

		try {
			// Actualizar la base de datos
			$stmt = $conn->prepare("UPDATE cheques SET fecha_anulado = :fecha_anulado, detalle_anulado = :detalle_anulado WHERE numero_cheque = :numero_cheque");
			$stmt->bindParam(':fecha_anulado', $fecha_anulado);
			$stmt->bindParam(':detalle_anulado', $detalle_anulado);
			$stmt->bindParam(':numero_cheque', $numero_cheque);

			if ($stmt->execute()) {
				$response['success'] = true;
				$response['mensaje'] = "El cheque se ha anulado correctamente.";
			} else {
				$response['success'] = false;
				$response['mensaje'] = "Error al anular el cheque.";
			}
		} catch (PDOException $e) {
			$response['success'] = false;
			$response['mensajeError'] = "Error al anular el cheque: " . $e->getMessage();
		}
	}
}

// Manejo del formulario principal
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['anular'])) {
	if (isset($_POST["numeroCheque"])) {
		$numero_cheque = $_POST["numeroCheque"];
		$statement = $conn->prepare("SELECT * FROM cheques WHERE numero_cheque = :numero_cheque");
		$statement->bindParam(':numero_cheque', $numero_cheque);
		$statement->execute();

		if ($statement->rowCount() > 0) {
			$ckRow = $statement->fetch(PDO::FETCH_ASSOC);
			$fecha = $ckRow["fecha"];
			$beneficiario = $ckRow["beneficiario"];
			$monto = $ckRow["monto"];
			$descripcion = $ckRow["descripcion"];
			// Obtener la fecha de circulación
			$row_circulacion = $ckRow["fecha_circulacion"];
			if (is_null($row_circulacion) || $row_circulacion == $default_date) {
				$response['successCirculacion'] = true;
				$response['mensajeNumCk'] = "✅ El cheque es válido.";
			} else {
				$response['successCirculacion'] = false;
				$response['mensajeNumCk'] = "❗El cheque está fuera de circulación o ya está anulado";
			}

			// Obtener la fecha de anulación
			$row_anulado = $ckRow["fecha_anulado"];
			if (is_null($row_anulado) || $row_anulado == $default_date) {
				$response['successAnulado'] = true;
				$response['mensajeNumCkAnulado'] = "✅ El cheque es válido.";
			} else {
				$response['successAnulado'] = false;
				$response['mensajeNumCk'] = "❗El cheque está fuera de circulación o ya está anulado";
				$fecha_anulado = $row_anulado;
			}
			
		} else {
			$response['success'] = false;
			$response['mensaje'] = "❌ El número de cheque no existe.";
		}
	}
}

function llenarCampos(){
	
}

header('Content-Type: application/json');
echo json_encode($response);
