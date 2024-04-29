<?php
// Conexión a la base de datos
require "../php/db_conciliacion.php";

// Obtener meses
$consultaMeses = $conn->query("SELECT * FROM meses");

// Variables de mensajes
$response = array(
	// Inicializar $response con todas las claves necesarias y valores predeterminados (por ejemplo, 0)
	'mas_depositos' => 0,
	'mas_cheques_anulados' => 0,
	'mas_notas_credito' => 0,
	'mas_ajustes_libro' => 0,
	'menos_cheques_girados' => 0,
	'menos_notas_debito' => 0,
	'menos_ajustes_libro' => 0,
	'mas_depositos_transito' => 0,
	'menos_cheques_circulacion' => 0,
	'mas_ajustes_banco' => 0,
);

// Variables de fechas para mostrar en SALDO
$anio = "";
$mes_anterior = "";
$mes_libro = "";
$dia = "";
$dia_actual = "";
$mes_actual = "";
$anio_anterior = "";

// Variables de los inputs de la Primera Sección
$saldo_anterior = "";
$mas_depositos = "";
$mas_cheques_anulados = "";
$mas_notas_credito = "";
$mas_ajustes_libro = "";
$sub_primera = "";
$subtotal_primera = "";

// Variables de los inputs de la Segunda Sección
$menos_cheques_girados = "";
$menos_notas_debito = "";
$menos_ajustes_libro = "";
$sub_segunda = "";
$saldo_libros = "";

// Variables de los inputs de la Tercera Sección
$saldo_blanco = "";
$mas_depositos_transito = "";
$menos_cheques_circulacion = "";
$mas_ajustes_banco = "";
$sub_tercero = "";
$saldo_conciliado = "";

// Variables para cálculo de cks
$total_monto_anulado = 0;
$total_monto_circulacion = 0;
$total_monto_girado = 0;

// * Función para obtener el mes anterior con respecto al mes ingresado por el usuario
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
	$statement_mes = $conn->prepare("SELECT * FROM meses WHERE mes = :mes");
	$statement_mes->bindParam(":mes", $mes_anterior);
	$statement_mes->execute();

	if ($statement_mes->rowCount() > 0) {
		$row_mes = $statement_mes->fetch(PDO::FETCH_ASSOC);
		$mes_libro = $row_mes["nombre_mes"];
		$dia = $row_mes["dia"];

		// Retornar el día y el nombre del mes en un array asociativo
		return array("dia" => $dia, "mes_libro" => $mes_libro, "anio_anterior" => $anio_anterior);
	} else {
		return null;
	}
}

// * Función para obtener la fecha ingresada por el usuario
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

		// Retornar el día y el nombre del mes en un array asociativo
		return array("dia_actual" => $dia_actual, "mes_actual" => $mes_actual, "anio" => $anio);
	} else {
		return null;
	}
}

// * Función para calcular las transacciones en la fecha ingresada por el usuario
function calcularTransacciones($conn, $mes_seleccionado, $anio)
{
	$query_transacciones = $conn->prepare("SELECT transaccion, SUM(monto) AS total_monto FROM otros WHERE EXTRACT(YEAR FROM fecha) = :anio AND EXTRACT(MONTH FROM fecha) = :mes GROUP BY transaccion");
	$query_transacciones->bindParam(":anio", $anio);
	$query_transacciones->bindParam(":mes", $mes_seleccionado);
	$query_transacciones->execute();

	if ($query_transacciones->rowCount() > 0) {
		$resultados = array();
		while ($row_transacciones = $query_transacciones->fetch(PDO::FETCH_ASSOC)) {
			$resultados[] = array(
				"numero_transaccion" => $row_transacciones["transaccion"],
				"monto_transaccion" => $row_transacciones["total_monto"]
			);
		}
		return $resultados;
	} else {
		return null;
	}
}

// * Función para calcular los cheques en la fecha ingresada por el usuario
function calcularCks($conn, $mes_seleccionado, $anio)
{
	$total_monto_anulado = 0;
	$total_monto_circulacion = 0;
	$total_monto_girado = 0;

	// Calcular cheques anulados
	$statement_cks_anulado = $conn->prepare("SELECT fecha_anulado, SUM(monto) as total_monto_cks_anulado FROM cheques WHERE EXTRACT(YEAR FROM fecha) = :anio AND EXTRACT(MONTH FROM fecha) = :mes GROUP BY fecha_anulado");
	$statement_cks_anulado->bindParam(":anio", $anio);
	$statement_cks_anulado->bindParam(":mes", $mes_seleccionado);
	$statement_cks_anulado->execute();

	if ($statement_cks_anulado->rowCount() > 0) {
		while ($row_cks = $statement_cks_anulado->fetch(PDO::FETCH_ASSOC)) {
			if ($row_cks["fecha_anulado"] === "0000-00-00" || $row_cks["fecha_anulado"] === null) {
				$total_monto_anulado = $row_cks["total_monto_cks_anulado"];
			}
		}
	}

	// Calcular cheques fuera de circulación
	$statement_cks_circulacion = $conn->prepare("SELECT fecha_circulacion, SUM(monto) as total_monto_cks_circulacion FROM cheques WHERE EXTRACT(YEAR FROM fecha) = :anio AND EXTRACT(MONTH FROM fecha) = :mes GROUP BY fecha_circulacion");
	$statement_cks_circulacion->bindParam(":anio", $anio);
	$statement_cks_circulacion->bindParam(":mes", $mes_seleccionado);
	$statement_cks_circulacion->execute();

	if ($statement_cks_circulacion->rowCount() > 0) {
		while ($row_cks = $statement_cks_circulacion->fetch(PDO::FETCH_ASSOC)) {
			if ($row_cks["fecha_circulacion"] === "0000-00-00" || $row_cks["fecha_circulacion"] === null) {
				$total_monto_circulacion = $row_cks["total_monto_cks_circulacion"];
			}
		}
	}

	// Calcular cheques girados
	$statement_cks_girados = $conn->prepare("SELECT fecha_anulado, fecha_circulacion, SUM(monto) as total_monto_cks_girado FROM cheques WHERE EXTRACT(YEAR FROM fecha) = :anio AND EXTRACT(MONTH FROM fecha) = :mes GROUP BY fecha_anulado, fecha_circulacion LIMIT 100");
	$statement_cks_girados->bindParam(":anio", $anio);
	$statement_cks_girados->bindParam(":mes", $mes_seleccionado);
	$statement_cks_girados->execute();

	if ($statement_cks_girados->rowCount() > 0) {
		while ($row_cks = $statement_cks_girados->fetch(PDO::FETCH_ASSOC)) {
			if (($row_cks["fecha_anulado"] === "0000-00-00" || $row_cks["fecha_anulado"] === null) && ($row_cks["fecha_circulacion"] === "0000-00-00" || $row_cks["fecha_circulacion"] === null)) {
				$total_monto_girado = $row_cks["total_monto_cks_girado"];
			}
		}
	}

	// Devolver resultados
	return array(
		"monto_cks_anulado" => $total_monto_anulado,
		"monto_cks_circulacion" => $total_monto_circulacion,
		"monto_cks_girado" => $total_monto_girado
	);
}

// Comprobación de los valores de entrada
if (isset($_POST["meses"]) && isset($_POST["anio"])) {
	// Inicialización de variables
	$anio = $_POST["anio"];
	$mes_seleccionado = (int)$_POST["meses"];

	// Obtener información de los meses
	$mesAnteriorInfo = obtenerMesAnterior($conn, $mes_seleccionado, $anio);
	$mesActualInfo = obtenerFechaActual($conn, $mes_seleccionado, $anio);

	// Verificar si se obtuvo la información de ambas funciones
	if ($mesAnteriorInfo !== null && $mesActualInfo !== null) {
		// Extraer valores del mes anterior
		$dia = $mesAnteriorInfo["dia"];
		$mes_libro = $mesAnteriorInfo["mes_libro"];
		$anio_anterior = $mesAnteriorInfo["anio_anterior"];

		// Extraer valores de la fecha actual
		$dia_actual = $mesActualInfo["dia_actual"];
		$mes_actual = $mesActualInfo["mes_actual"];
		$anio = $mesActualInfo["anio"];

		// Asignar información a $response
		$response['dia'] = $dia;
		$response['mes_libro'] = $mes_libro;
		$response['anio_anterior'] = $anio_anterior;
		$response['dia_actual'] = $dia_actual;
		$response['mes_actual'] = $mes_actual;
		$response['anio'] = $anio;
	}

	// Preparar consulta
	$statement = $conn->prepare("SELECT * FROM conciliacion WHERE mes = :mes AND agno = :agno");
	$statement->bindParam(':mes', $_POST["meses"]);
	$statement->bindParam(':agno', $_POST["anio"]);
	$statement->execute();

	// Verificar resultado de la consulta
	if ($statement->rowCount() > 0) {
		$response['success'] = true;
		$response['mensaje'] = "⚠️ Este mes ya fue conciliado. Ingrese otra fecha";

		// Extraer datos de conciliación
		$row_conciliacion = $statement->fetch(PDO::FETCH_ASSOC);

		// Extraer valores y asignarlos a variables
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

		// Asignar valores a $response
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
	} else {

		if ($mes_seleccionado == 1) {
			$mes_anterior = 12;
			$anio_anterior--;
		} else {
			$mes_anterior = $mes_seleccionado - 1;
		}

		// Formatear el mes como "0N" si es necesario
		$mes_anterior = sprintf("%02d", $mes_anterior);
		$query_saldo_conciliado = $conn->prepare("SELECT saldo_conciliado FROM conciliacion WHERE mes = :mes_anterior");
		$query_saldo_conciliado->bindParam(":mes_anterior", $mes_anterior);
		$query_saldo_conciliado->execute();

		if ($query_saldo_conciliado->rowCount() > 0) {
			// Información sobre conciliación válida
			$response['successConciliacion'] = true;
			$response['mensajeConciliacion'] = "✅ La fecha ingresada se puede conciliar";

			$row_saldo_conciliacion = $query_saldo_conciliado->fetch(PDO::FETCH_ASSOC);
			$response["saldo_anterior"] = $row_saldo_conciliacion["saldo_conciliado"];

			// Calcular transacciones y cheques
			$transacciones = calcularTransacciones($conn, $mes_seleccionado, $anio);
			$cheques = calcularCks($conn, $mes_seleccionado, $anio);

			if ($transacciones !== null) {
				foreach ($transacciones as $transaccion) {
					$transaccion_codigo = $transaccion["numero_transaccion"];
					$monto_transaccion = $transaccion["monto_transaccion"];

					// Asignar el valor de la transacción al array $response, incluyendo 0
					switch ($transaccion_codigo) {
						case "1":
							$response['mas_depositos'] = $monto_transaccion;
							break;
						case "2":
							$response['mas_notas_credito'] = $monto_transaccion;
							break;
						case "3":
							$response['mas_ajustes_libro'] = $monto_transaccion;
							break;
						case "4":
							$response['menos_notas_debito'] = $monto_transaccion;
							break;
						case "5":
							$response['menos_ajustes_libro'] = $monto_transaccion;
							break;
						case "6":
							$response['mas_depositos_transito'] = $monto_transaccion;
							break;
						case "7":
							$response['mas_ajustes_banco'] = $monto_transaccion;
							break;
							// Puedes agregar más casos según sea necesario
					}
				}
			}

			// Asignar valores de cheques a `response`
			$response['mas_cheques_anulados'] = $cheques["monto_cks_anulado"];
			$response['menos_cheques_girados'] = $cheques["monto_cks_girado"];
			$response['menos_cheques_circulacion'] = $cheques["monto_cks_circulacion"];
		} else {
			$response['successConciliacion'] = false;
			$response['mensajeConciliacion'] = "❌ No se puede realizar la conciliacion";
		}
	}
} else {
	$response['success'] = false;
	$response['mensaje'] = "⚠️ Completa todos los campos antes de continuar";
}

// Establecer la cabecera de contenido JSON
header('Content-Type: application/json');
// Devolver la respuesta como JSON
echo json_encode($response);
