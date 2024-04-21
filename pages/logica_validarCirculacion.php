<?php require "../php/db_conciliacion.php";

// Consulta SQL para obtener datos de proveedores
$proveedores = $conn->query("SELECT * FROM proveedores");

$numero_cheque = "";
$fecha = "";
$beneficiario = "";
$monto = "";
$descripcion = "";
$fecha_circulacion = "";

$response = array();
$default_date = "0000-00-00";

// Manejo del formulario principal

if (isset($_POST["numeroCheque"])) {
	$numero_cheque = $_POST["numeroCheque"];
	$statement = $conn->prepare("SELECT * FROM cheques WHERE numero_cheque = :numero_cheque");
	$statement->bindParam(':numero_cheque', $numero_cheque);
	$statement->execute();

	try {
		$statement->execute();

		// Si se encuentran resultados, procesarlos
		if ($statement->rowCount() > 0) {
			$ckRow = $statement->fetch(PDO::FETCH_ASSOC);
			$fecha = $ckRow["fecha"];
			$beneficiario = $ckRow["beneficiario"];
			$monto = $ckRow["monto"];
			$descripcion = $ckRow["descripcion"];

			// Validar la fecha de circulación
			$row_circulacion = $ckRow["fecha_circulacion"];
			if (is_null($row_circulacion) || $row_circulacion == $default_date) {
				$response['successCirculacion'] = true;
				$response['mensajeNumCk'] = "✅ El cheque es válido.";
			} else {
				$response['successCirculacion'] = false;
				$response['mensajeNumCk'] = "❗El cheque está fuera de circulación o ya está anulado";
			}

			// Validar la fecha de anulación
			$row_anulado = $ckRow["fecha_anulado"];
			if (is_null($row_anulado) || $row_anulado == $default_date) {
				$response['successAnulado'] = true;
				$response['mensajeNumCk'] = "✅ El cheque es válido.";
			} else {
				$response['successAnulado'] = false;
				$response['mensajeNumCk'] = "❗El cheque está fuera de circulación o ya está anulado";
				$fecha_anulado = $row_anulado;
			}

			// Incluir los datos de los campos de entrada en la respuesta JSON
			$response['fecha'] = $fecha;
			$response['beneficiario'] = $beneficiario;
			$response['monto'] = $monto;
			$response['descripcion'] = $descripcion;
			$response['fecha_circulacion'] = $fecha_circulacion;
		} else {
			// Si no se encuentra el número de cheque, enviar un mensaje de error
			$response['success'] = false;
			$response['mensaje'] = "❌ El número de cheque no existe.";
		}
	} catch (PDOException $e) {
		// Manejo de errores de la consulta
		$response['success'] = false;
		$response['mensaje'] = "❌ Error al buscar el número de cheque: " . $e->getMessage();
	}
}

// Establecer la cabecera de contenido JSON
header('Content-Type: application/json');

// Enviar la respuesta en formato JSON
echo json_encode($response);
