<?php
require "../php/db_conciliacion.php";

$datos_procesados = [];
$response = array();

if (isset($_FILES['file-datos']) && $_FILES['file-datos']['error'] === UPLOAD_ERR_OK) {
	// Obtén el archivo temporal del archivo subido
	$archivoTmp = $_FILES['file-datos']['tmp_name'];

	// Verifica que el archivo temporal exista
	if (file_exists($archivoTmp)) {
		$file = fopen($archivoTmp, 'r');

		while (($line = fgets($file)) !== false) {
			// Elimina espacios en blanco adicionales
			$line = trim($line);

			// Divide la línea por espacios o tabuladores
			$data = preg_split('/\s+/', $line);

			// Almacena los datos procesados en un array
			$datos_procesados[] = [
				'codigo' => $data[0],
				'fecha' => $data[1],
				'hora' => $data[2],
				'filler1' => $data[3],
				'filler2' => $data[4],
				'filler3' => $data[5],
				'filler4' => $data[6],
			];
		}

		$statement = $conn->prepare("INSERT INTO datos (codigo, fecha, hora, filler1, filler2, filler3, filler4) VALUES (:cod, :fecha, :hora, :f1, :f2, :f3, :f4)");
		foreach ($datos_procesados as $datos) {
			try {
				$statement->execute([
					":cod" => $datos["codigo"],
					":fecha" => $datos["fecha"],
					":hora" => $datos["hora"],
					":f1" => $datos["filler1"],
					":f2" => $datos["filler2"],
					":f3" => $datos["filler3"],
					":f4" => $datos["filler4"]
				]);
				$response["success"] = true;
			} catch (PDOException $e) {
				$response["success"] = false;
				$response["message"] = "Error en la inserción: " . $e->getMessage();
			}
		}
		// Cierra el archivo después de leer
		fclose($file);
	} else {
	
	}
} else {
	exit;
}
header('Content-Type: application/json');
echo json_encode($response);
