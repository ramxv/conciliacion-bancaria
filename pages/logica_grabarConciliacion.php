<?php require "../php/db_conciliacion.php";

function obtenerMesAnterior($conn, $mes_seleccionado, $anio_anterior)
{
	if ($mes_seleccionado == 1) {
		$mes_anterior = 12;
		$anio_anterior--;
	} else {
		$mes_anterior = $mes_seleccionado - 1;
	}

	// Formatear el mes como "0N" si es necesario
	$mes_anterior = sprintf("%02d", $mes_anterior);
	$statement_mes = $conn->prepare("SELECT mes, dia FROM meses WHERE mes = :mes");
	$statement_mes->bindParam(":mes", $mes_anterior);
	$statement_mes->execute();

	if ($statement_mes->rowCount() > 0) {
		$row_mes = $statement_mes->fetch(PDO::FETCH_ASSOC);
		$mes_ant = $row_mes["mes"];
		$dia_ant = $row_mes["dia"];

		// Retornar el día y el nombre del mes en un array asociativo
		return array("dia_ant" => $dia_ant, "mes_ant" => $mes_ant, "anio_anterior" => $anio_anterior);
	} else {
		return null;
	}
}

// * Función para obtener la fecha ingresada por el usuario
function obtenerFechaActual($conn, $mes_seleccionado, $anio)
{
	$mes_seleccionado = sprintf("%02d", $mes_seleccionado);
	$statement_mes = $conn->prepare("SELECT mes, dia FROM meses WHERE mes = :mes");
	$statement_mes->bindParam(":mes", $mes_seleccionado);
	$statement_mes->execute();

	if ($statement_mes->rowCount() > 0) {
		$row_mes = $statement_mes->fetch(PDO::FETCH_ASSOC);
		$mes_actual = $row_mes["mes"];
		$dia_actual = $row_mes["dia"];

		// Retornar el día y el nombre del mes en un array asociativo
		return array("dia_actual" => $dia_actual, "mes_actual" => $mes_actual, "anio" => $anio);
	} else {
		return null;
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Obtén los datos del formulario
	$mes_actual = $_POST['meses'];
	$anio_actual = $_POST['anio'];
	$saldo_ant = $_POST["saldo_anterior"];
	$mas_depositos = $_POST["mas_depositos"];
	$mas_cheques_anulados = $_POST["mas_cheques_anulados"];
	$mas_notas_credito = $_POST["mas_notas_credito"];
	$mas_ajustes_libro = $_POST["mas_ajustes_libro"];
	$sub_primera = $_POST["sub_primera"];
	$subtotal_primera = $_POST["subtotal_primera"];
	$menos_cheques_girados = $_POST["menos_cheques_girados"];
	$menos_notas_debito = $_POST["menos_notas_debito"];
	$menos_ajustes_libro = $_POST["menos_ajustes_libro"];
	$sub_segunda = $_POST["sub_segunda"];
	$saldo_libros = $_POST["saldo_libros"];
	$saldo_banco = $_POST["saldo_banco"];
	$mas_depositos_transito = $_POST["mas_depositos_transito"];
	$menos_cheques_circulacion = $_POST["menos_cheques_circulacion"];
	$mas_ajustes_banco = $_POST["mas_ajustes_banco"];
	$sub_tercero = $_POST["sub_tercero"];

	$sub_tercero_format = floatval(str_replace(array('(',')'), '', $sub_tercero));

	$saldo_conciliado = $_POST["saldo_conciliado"];

	$fecha_anterior = obtenerMesAnterior($conn, $mes_actual, $anio_actual);
	$fecha_actual = obtenerFechaActual($conn, $mes_actual, $anio_actual);

	$dia_actual = $fecha_actual["dia_actual"];
	$dia_anterior = $fecha_anterior["dia_ant"];
	$mes_anterior = $fecha_anterior["mes_ant"];
	$anio_anterior = $fecha_anterior["anio_anterior"];

	if ($saldo_libros === $saldo_conciliado) {
		// Respuesta de éxito
		$response = [
			"success" => true,
			"mensaje" => "✅ Se ha registrado correctamente la conciliación",
		];
		try {
			// Tu código de consulta SQL
			$statement_grabar = $conn->prepare("INSERT INTO conciliacion (
				dia,
				mes,
				agno,
				dia_anterior,
				mes_anterior,
				agno_anterior,
				saldo_anterior,
				masdepositos,
				maschequesanulados,
				masnotascredito,
				masajusteslibro,
				sub1,
				subtotal1,
				menoschequesgirados,
				menosnotasdebito,
				menosajusteslibro,
				sub2,
				saldolibros,
				saldobanco,
				masdepositostransito,
				menoschequescirculacion,
				masajustesbanco,
				sub3,
				saldo_conciliado) 
				VALUES (
				:dia,
				:mes,
				:agno,
				:d_anterior,
				:m_anterior,
				:a_anterior,
				:saldo_ant,
				:masdepositos,
				:maschequesanulados,
				:masnotascredito,
				:masajusteslibro,
				:sub1,
				:subtotal1,
				:menoschequesgirados,
				:menosnotasdebito,
				:menosajusteslibro,
				:sub2,
				:saldolibros,
				:saldobanco,
				:masdepositostransito,
				:menoschequescirculacion,
				:masajustesbanco,
				:sub3,
				:saldo_conciliado)");
			$statement_grabar->execute([':dia' => $dia_actual,
			':mes' => $mes_actual,
			':agno' => $anio_actual,
			':d_anterior' => $dia_anterior,
			':m_anterior' => $mes_anterior,
			':a_anterior' => $anio_anterior,
			':saldo_ant' => $saldo_ant,
			':masdepositos' => $mas_depositos,
			':maschequesanulados' => $mas_cheques_anulados,
			':masnotascredito' => $mas_notas_credito,
			':masajusteslibro' => $mas_ajustes_libro,
			':sub1' => $sub_primera,
			':subtotal1' => $subtotal_primera,
			':menoschequesgirados' => $menos_cheques_girados,
			':menosnotasdebito' => $menos_notas_debito,
			':menosajusteslibro' => $menos_ajustes_libro,
			':sub2' => $sub_segunda,
			':saldolibros' => $saldo_libros,
			':saldobanco' => $saldo_banco,
			':masdepositostransito' => $mas_depositos_transito,
			':menoschequescirculacion' => $menos_cheques_circulacion,
			':masajustesbanco' => $mas_ajustes_banco,
			':sub3' => $sub_tercero_format,
			':saldo_conciliado' => $saldo_conciliado]);
		} catch (Exception $e) {
			// Maneja errores de la base de datos
			$response = [
					"success" => false,
					"error" => "❌ Error al registrar la conciliación: " . $e->getMessage(),
			];
		}	
	} else {
		$response = [
			"success" => false,
			"error" => "❌ La conciliación no puede realizarse porque no hay balance",
		];
	}
}

header("Content-Type: application/json");
echo json_encode($response);