<?php require "../php/db_conciliacion.php";

$queryTransaccionesLibros = $conn->query("SELECT * FROM transacciones LIMIT 5");
$queryTransaccionesBanco = $conn->query("SELECT * FROM transacciones LIMIT 2 OFFSET 5");
$queryTransaccionesTransferencia = $conn->query("SELECT * FROM transacciones LIMIT 2 OFFSET 7");

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $transaccion = $_POST["transaccion"];
  $fecha_transaccion = $_POST["fecha_transaccion"];
  $monto_transaccion = $_POST["monto_transaccion"];
  if (empty($transaccion) || empty($fecha_transaccion) || empty($monto_transaccion)) {
		$response['success'] = false;
		$response['mensaje'] = "⚠️ Llene todos los campos"; 
  } else {
    $correcto = "✅ Se registro exitosamente la transacción";
    $statement = $conn->prepare("INSERT INTO otros (transaccion, fecha, monto) VALUES (:transaccion, :fecha_transaccion, :monto_transaccion)");
    $statement->bindParam(':transaccion', $transaccion);
    $statement->bindParam(':fecha_transaccion', $fecha_transaccion);
    $statement->bindParam(':monto_transaccion', $monto_transaccion);
    try {
      $statement->execute();
      $response['success'] = true;
			$response['mensaje'] = "✅ Se registro exitosamente la transacción";
    } catch (PDOException $e) {
      $response['success'] = false;
			$response['mensaje'] = "❌ Error al registrar transacción: " . $e->getMessage();
    }
  }
}

// Establecer la cabecera de contenido JSON
header('Content-Type: application/json');
// Devuelve la respuesta como JSON
echo json_encode($response);
