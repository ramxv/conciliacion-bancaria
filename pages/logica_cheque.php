<?php
require "../php/db_conciliacion.php";

$consultaProveedores = $conn->query("SELECT * FROM proveedores");
$consultaObjetoGasto = $conn->query("SELECT * FROM objeto_gasto");

$response = array();


if (isset($_POST["numCheque"])) {
	$numCheque = $_POST["numCheque"];

	$statement = $conn->prepare("SELECT * FROM cheques WHERE numero_cheque = :numCheque");
	$statement->bindParam(":numCheque", $numCheque);
	$statement->execute();

	if ($statement->rowCount() > 0) {
		$response["successNumCk"] = false;
		$response["mensajeNumCk"] = "❌ El número de cheque ya esta registrado";
	} else {
		$response["successNumCk"] = true;
		$response["mensajeNumCk"] = "✅ El número de cheque es válido";
		if (
			empty($_POST["fecha"]) || empty($_POST["beneficiario"]) ||
			empty($_POST["monto"]) || empty($_POST["detalle"]) || empty($_POST["objetoGasto"]) || empty($_POST["montoObjeto"])
		) {
			$response["success"] = false;
			$response["error"] = "❗Llene todos los campos";
		} else {
			$statement = $conn->prepare("INSERT INTO cheques (numero_cheque,fecha,beneficiario,monto,descripcion,codigo_objeto1,monto_objeto1) VALUES (:numCheque, :fecha, :beneficiario, :monto, :detalle, :objetoGasto, :montoObjeto)");
			$result = $statement->execute([
				":numCheque" => $_POST["numCheque"],
				":fecha" => $_POST["fecha"],
				":beneficiario" => $_POST["beneficiario"],
				":monto" => $_POST["monto"],
				":detalle" => $_POST["detalle"],
				":objetoGasto" => $_POST["objetoGasto"],
				":montoObjeto" => $_POST["montoObjeto"]
			]);

			if ($result) {
				$response["success"] = true;
				$response["mensaje"] = "✅ Se ha registrado correctamente el cheque";
			} else {
				$response["success"] = false;
				$response["error"] = "❌ Error al registrar el cheque en la base de datos";
			}
		}
	}
} else {
	$response["success"] = false;
	$response["error"] = "❗Numero de cheque no recibido";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
