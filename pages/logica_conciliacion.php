<?php require "../php/db_conciliacion.php"; ?>

<?php

$consultaMeses = $conn->query("SELECT * FROM meses");

// Variables de mensajes
$response = array();

// Variables de fechas para mostrar en SALDO
$anio = "";
$mes_anterior = "";
$mes_libro = "";
$dia = "";
$dia_actual = "";
$mes_actual = "";
$anio_anterior="";
// Variables de los inputs de la Primera Sección
$saldo_anterior = "";
$mas_depositos = "";
$mas_cheques_anulados = "";
$mas_notas_credito = "";
$mas_ajustes_libro = "";
$sub_primera = "";
$subtotal_primera = "";
// Variables de los inputs de la Segunda Sección
$menos_cheques_girados="";
$menos_notas_debito="";
$menos_ajustes_libro="";
$sub_segunda="";
$saldo_libros="";
// Variables de los inputs de la Tercera Sección
$saldo_blanco = "";
$mas_depositos_transito = "";
$menos_cheques_circulacion = "";
$mas_ajustes_banco = "";
$sub_tercero = "";
$saldo_conciliado = "";

function obtenerMesAnterior($conn, $mes_seleccionado, $anio_anterior)
{
	if ($mes_seleccionado == 1) {
		$mes_anterior = 12;
		$anio_anterior--;
	} else {
		$mes_anterior = $mes_seleccionado - 1;
	}

	// Formateamos el mes como "03" si es necesario
	$mes_anterior = sprintf("%02d", $mes_anterior);
	$statement_mes = $conn->prepare("SELECT * FROM meses WHERE mes = :mes");
	$statement_mes->bindParam(":mes", $mes_anterior);
	$statement_mes->execute();

	if ($statement_mes->rowCount() > 0) {
		$row_mes = $statement_mes->fetch(PDO::FETCH_ASSOC);
		$mes_libro = $row_mes["nombre_mes"];
		$dia = $row_mes["dia"];

		// Retornamos el día y el nombre del mes en un array asociativo
		return array("dia" => $dia, "mes_libro" => $mes_libro, "anio_anterior" => $anio_anterior);
	} else {
		return null;
	}
}

function obtenerFechaActual($conn, $mes_seleccionado, $anio)
{
	$mes_seleccionado = sprintf("%02d", $mes_seleccionado);
	$statement_mes = $conn->prepare("SELECT * FROM meses WHERE mes = :mes");
	$statement_mes->bindParam(":mes", $mes_seleccionado);
	$statement_mes->execute();

	if ($statement_mes->rowCount() > 0) {
		$row_mes = $statement_mes->fetch(PDO::FETCH_ASSOC);
		$mes_actual = $row_mes["nombre_mes"];
		$dia_actual = $row_mes["dia"];

		// Retornamos el día y el nombre del mes en un array asociativo
		return array("dia_actual" => $dia_actual, "mes_actual" => $mes_actual, "anio" => $anio);
	} else {
		return null;
	}
}

if (isset($_POST["meses"]) && isset($_POST["anio"])) {

	if (empty($_POST["meses"]) || empty($_POST["anio"])) {
		$response['success'] = false;
		$response['mensaje'] = "⚠️ Completa todos los campos antes de continuar";
	} else {
		$response['success'] = true;
		$response['mensaje'] = "✅ Los campos han sido completados correctamente";
		$anio = $_POST["anio"];
		$mes_seleccionado = (int)$_POST["meses"];
		$mesAnteriorInfo = obtenerMesAnterior($conn, $mes_seleccionado, $anio);
		$mesActualInfo = obtenerFechaActual($conn, $mes_seleccionado, $anio);

		// * Verificamos si se obtuvo la información de ambas funciones
		if ($mesAnteriorInfo !== null && $mesActualInfo !== null) {
			// * Extraemos los valores de la función del mes anterior
			$dia = $mesAnteriorInfo["dia"];
			$mes_libro = $mesAnteriorInfo["mes_libro"];
			$anio_anterior = $mesAnteriorInfo["anio_anterior"];
			// * Extraemos los valores de la función para la fecha actual
			$dia_actual = $mesActualInfo["dia_actual"];
			$mes_actual = $mesActualInfo["mes_actual"];
			$anio = $mesActualInfo["anio"];

			$response['dia'] = $dia;
			$response['mes_libro'] = $mes_libro;
			$response['anio_anterior'] = $anio_anterior;
			$response['dia_actual'] = $dia_actual;
			$response['mes_actual'] = $mes_actual;
			$response['anio'] = $anio;
		}

		$statement = $conn->prepare("SELECT * FROM conciliacion WHERE mes = :mes AND agno = :agno");
		$statement->bindParam(':mes', $_POST["meses"]);
		$statement->bindParam(':agno',$_POST["anio"]);
		$statement->execute();
		if ($statement->rowCount() > 0) {
			$row_conciliacion = $statement->fetch(PDO::FETCH_ASSOC);
			$saldo_anterior = $row_conciliacion["saldo_anterior"];
			$mas_depositos = $row_conciliacion["masdepositos"];
			$mas_cheques_anulados = $row_conciliacion["maschequesanulados"];
			$mas_notas_credito = $row_conciliacion["masnotascredito"];
			$mas_ajustes_libro = $row_conciliacion["masajusteslibro"];
			$sub_primera = $row_conciliacion["sub1"];
			$subtotal_primera = $row_conciliacion["subtotal1"];
			$menos_cheques_girados = $row_conciliacion["menoschequesgirados"];
			$menos_notas_debito = $row_conciliacion["menosnotasdebito"];
			$menos_ajustes_libro = $row_conciliacion["menosajusteslibro"];
			$sub_segunda = $row_conciliacion["sub2"];
			$saldo_libros = $row_conciliacion["saldolibros"];
			$saldo_banco = $row_conciliacion["saldobanco"];
			$mas_depositos_transito = $row_conciliacion["masdepositostransito"];
			$menos_cheques_circulacion = $row_conciliacion["menoschequescirculacion"];
			$mas_ajustes_banco = $row_conciliacion["masajustesbanco"];
			$sub_tercero = $row_conciliacion["sub3"];
			$saldo_conciliado = $row_conciliacion["saldo_conciliado"];

			// * Asignamos todas las variables de $row_conciliacion al arreglo $response
			$response['saldo_anterior'] = $saldo_anterior;
			$response['mas_depositos'] = $mas_depositos;
			$response['mas_cheques_anulados'] = $mas_cheques_anulados;
			$response['mas_notas_credito'] = $mas_notas_credito;
			$response['mas_ajustes_libro'] = $mas_ajustes_libro;
			$response['sub_primera'] = $sub_primera;
			$response['subtotal_primera'] = $subtotal_primera;
			$response['menos_cheques_girados'] = $menos_cheques_girados;
			$response['menos_notas_debito'] = $menos_notas_debito;
			$response['menos_ajustes_libro'] = $menos_ajustes_libro;
			$response['sub_segunda'] = $sub_segunda;
			$response['saldo_libros'] = $saldo_libros;
			$response['saldo_banco'] = $saldo_banco;
			$response['mas_depositos_transito'] = $mas_depositos_transito;
			$response['menos_cheques_circulacion'] = $menos_cheques_circulacion;
			$response['mas_ajustes_banco'] = $mas_ajustes_banco;
			$response['sub_tercero'] = $sub_tercero;
			$response['saldo_conciliado'] = $saldo_conciliado;
		}
	}
}
// Establecer la cabecera de contenido JSON
header('Content-Type: application/json');
// Devuelve la respuesta como JSON
echo json_encode($response);
