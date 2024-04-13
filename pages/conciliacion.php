<?php require "../php/db_conciliacion.php"; ?>

<?php

$consultaMeses = $conn->query("SELECT * FROM meses");

// Variables de mensajes
$error = null;
$warning = null;
$correcto = null;

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["meses"]) && isset($_POST["anio"])) {

	if (empty($_POST["meses"]) || empty($_POST["anio"])) {
		$warning = "⚠️ Completa todos los campos antes de continuar";
	} else {
		$correcto = "✅ Los campos han sido completados correctamente";
		$anio = $_POST["anio"];
		$mes_seleccionado = (int)$_POST["meses"];
		$mesAnteriorInfo = obtenerMesAnterior($conn, $mes_seleccionado, $anio);
		$mesActualInfo = obtenerFechaActual($conn, $mes_seleccionado, $anio);

		// Verificamos si se obtuvo la información de ambas funciones
		if ($mesAnteriorInfo !== null && $mesActualInfo !== null) {
			// Extraemos los valores de la función del mes anterior
			$dia = $mesAnteriorInfo["dia"];
			$mes_libro = $mesAnteriorInfo["mes_libro"];
			$anio_anterior = $mesAnteriorInfo["anio_anterior"];
			//Extraemos los valores de la función para la fecha actual
			$dia_actual = $mesActualInfo["dia_actual"];
			$mes_actual = $mesActualInfo["mes_actual"];
			$anio = $mesActualInfo["anio"];
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
		}
	}
}
?>

<?php require "../includes/header.php" ?>

<div class="container-fluid w-75 mt-5 p-5">

	<form action="" class="border border-secondary-subtle rounded" method="post">
		<h3 class="form-header p-3 border-bottom border-secondary-subtle ">Conciliación Bancaria</h3>
		<?php if ($warning): ?>
				<div class="p-3">
					<div class="warning-container alert alert-warning" role="alert"><?= $warning ?></div>
				</div>
      <?php elseif ($correcto): ?>
				<div class="p-3">
					<div class="correcto-container alert alert-success" role="alert"><?= $correcto ?></div>
				</div>
    <?php endif ?>

		<!-- ----------------------------------------------------------- CABECERA ----------------------------------------------------------- -->
		<div class="row p-3 justify-content-end align-items-end d-flex">
			<div class="col-2 me-3">
				<label for="inputMeses" class="form-label"><strong>Meses</strong></label>
				<select class="form-select" name="meses" id="inputMeses">
					<option value=""></option>
					<?php
					while ($row = $consultaMeses->fetch(PDO::FETCH_ASSOC)) {
						$selected = ($_POST['meses'] == $row["mes"]) ? 'selected' : '';
						echo '<option value="' . $row["mes"] . '" ' . $selected . '>' . $row["nombre_mes"] . '</option>';
					}
					?>
				</select>
			</div>

			<div class="col-2 me-3">
				<label for="inputAnio" class="form-label"><strong>Año</strong></label>
				<select class="form-select" name="anio" id="inputAnio">
					<option value=""></option>
					<?php
					$anio_actual = date("Y");
					for ($i = 0; $i < 5; $i++) {
						$selected = ($_POST['anio'] == $anio_actual - $i) ? 'selected' : '';
						echo '<option value="' . ($anio_actual - $i) . '" ' . $selected . '>' . ($anio_actual - $i) . '</option>';
					}
					?>
				</select>
			</div>

			<div class="col-3" id="btn-custom-container">
				<button type="submit" class="btn button-custom" id="btn-custom">Realizar Conciliación</button>
			</div>
		</div>
	<!-- ----------------------------------------------------------- PRIMERA SECCIÓN (MÁS) ----------------------------------------------------------- -->
		<!-- Mostrar el nombre del mes anterior seleccionado -->
		<div class="row justify-content-between pe-5 ps-2">
			<label for="inputSaldoLibro" class="col-sm-2 col-form-label w-auto text-uppercase"><strong>SALDO SEGÚN LIBRO AL <?= $dia ?> de <?= $mes_libro ?> de <?= $anio_anterior ?></strong></label>
			<div class="col-3">
				<input type="saldoLibro" class="form-control" name="saldo_anterior" id="inputSaldoLibro" value="<?= $saldo_anterior ?>" disabled>
			</div>
		</div>

		<div class="row ps-4 mb-2">
			<label for="inputDeposito" class="col-md-3 col-form-label"><strong>Más: Deposito</strong></label>
			<div class="col-3 offset-2">
				<input type="deposito" class="form-control" name="mas_depositos" id="inputDeposito" value="<?= $mas_depositos ?>" disabled>
			</div>
		</div>

		<div class="row mb-2 ps-4">
			<label for="inputChequesAnulados" class="col-md-3 col-form-label"><strong>Cheques Anulados</strong></label>
			<div class="col-3 offset-2">
				<input type="chequesAnulados" class="form-control" name="mas_cheques_anulados" id="inputChequesAnulados" value="<?= $mas_cheques_anulados ?>" disabled>
			</div>
		</div>

		<div class="row ps-4 mb-2">
			<label for="inputNotasCredito" class="col-md-3 col-form-label"><strong>Notas de Crédito</strong></label>
			<div class="col-3 offset-2">
				<input type="notasCredito" class="form-control" name="mas_notas_credito" id="inputNotasCredito" value="<?= $mas_notas_credito ?>" disabled>
			</div>
		</div>

		<div class="row ps-4 mb-2">
			<label for="inputAjustesLibro" class="col-md-3 col-form-label"><strong>Ajustes</strong></label>
			<div class="col-3 offset-2">
				<input type="ajustesLibro" class="form-control" name="mas_ajustes_libro" id="inputAjustesLibro" value="<?= $mas_ajustes_libro ?>" disabled>
			</div>
		</div>

		<!-- SUB1 -->
		<div class="row mb-2 justify-content-end pe-5 ps-2">
			<label for="inputSubtotal" class="col-sm-2 col-form-label"><strong>Subtotal</strong></label>
			<div class="col-3">
				<input type="subtotal" class="form-control" name="sub_primera" id="inputSubtotal" value="<?= $sub_primera ?>" disabled>
			</div>
		</div>

		<!-- (SUBTOTAL1) -->
		<div class="row justify-content-between pe-5 ps-2">
			<label for="inputSubtotalFinal" class="col-sm-2 col-form-label"><strong>SUBTOTAL</strong></label>
			<div class="col-3">
				<input type="subtotalFinal" class="form-control" name="subtotal_primera" id="inputSubtotalFinal" value="<?= $subtotal_primera ?>" disabled>
			</div>
		</div>

		<!-- ----------------------------------------------------------- SEGUNDA SECCIÓN (MENOS) ----------------------------------------------------------- -->
		<div class="row ps-4 mb-2">
			<label for="inputCkGirados" class="col-md-3 col-form-label"><strong>Menos: Cheques girados en el mes</strong></label>
			<div class="col-3 offset-2">
				<input type="ckGirados" class="form-control" name="menos_cheques_girados" id="inputCkGirados" value="<?= $menos_cheques_girados ?>" disabled>
			</div>
		</div>

		<div class="row ps-4 mb-2">
			<label for="inputNotasDebito" class="col-md-3 col-form-label"><strong>Notas de Débitos</strong></label>
			<div class="col-3 offset-2">
				<input type="notasDebito" class="form-control" name="menos_notas_debito" id="inputNotasDebito" value="<?= $menos_notas_debito ?>" disabled>
			</div>
		</div>

		<div class="row ps-4 mb-2">
			<label for="inputAjusteCkGirados" class="col-md-3 col-form-label"><strong>Ajustes</strong></label>
			<div class="col-3 offset-2">
				<input type="ajusteCkGirados" class="form-control" name="menos_ajustes_libro" id="inputAjusteCkGirados" value="<?= $menos_ajustes_libro ?>" disabled>
			</div>
		</div>

		<!-- SUB2 -->
		<div class="row mb-2 justify-content-end pe-5 ps-2">
			<label for="inputSubtotalMenos" class="col-sm-2 col-form-label"><strong>Subtotal</strong></label>
			<div class="col-3">
				<input type="subtotalMenos" class="form-control" name="sub_segunda" id="inputSubtotalMenos" value="<?= $sub_segunda ?>" disabled>
			</div>
		</div>

		<!-- SALDOLIBROS -->
		<div class="row justify-content-between pe-5 ps-2">
			<label for="inputSaldoConsiliado" class="col col-form-label text-uppercase"><strong>SALDO CONCILIADO SEGÚN LIBROS AL <?= $dia_actual ?> de <?= $mes_actual ?> de <?= $anio ?></strong></label>
			<div class="col-3">
				<input type="saldoConsiliado" class="form-control" name="saldo_libros" id="inputSaldoConsiliado" value="<?= $saldo_libros ?>" disabled>
			</div>
		</div>

		<hr>

		<!-- ----------------------------------------------------------- TERCERA SECCIÓN (BANCO) ----------------------------------------------------------- -->
		<div class="row justify-content-between pe-5 ps-2">
			<label for="inputSaldoBanco" class="col-sm-2 col-form-label w-auto text-uppercase"><strong>SALDO EN BANCO AL <?= $dia_actual ?> de <?= $mes_actual ?> de <?= $anio ?></strong></label>
			<div class="col-3">
				<input type="saldoBanco" class="form-control" name="saldo_blanco" id="inputSaldoBanco">
			</div>
		</div>

		<div class="row ps-4 mb-2">
			<label for="inputDepositoTransito" class="col-md-3 col-form-label"><strong>Más: Depósitos en Tránsitos</strong></label>
			<div class="col-3 offset-2">
				<input type="depositoTransito" class="form-control" name="mas_depositos_transito" id="inputDepositoTransito" value="<?= $mas_depositos_transito ?>" disabled>
			</div>
		</div>

		<div class="row ps-4 mb-2">
			<label for="inputChequesCirculacion" class="col-md-3 col-form-label"><strong>Menos: Cheques en Circulación</strong></label>
			<div class="col-3 offset-2">
				<input type="chequesCirculacion" class="form-control" name="menos_cheques_circulacion" id="inputChequesCirculacion" value="<?= $menos_cheques_circulacion ?>" disabled>
			</div>
		</div>

		<div class="row ps-4 mb-2">
			<label for="inputAjusteBanco" class="col-md-3 col-form-label"><strong>Más: Ajustes</strong></label>
			<div class="col-3 offset-2">
				<input type="ajusteBanco" class="form-control" name="mas_ajustes_banco" id="inputAjusteBanco" value="<?= $mas_ajustes_banco ?>" disabled>
			</div>
		</div>

		<!-- SUB3 -->
		<div class="row mb-2 justify-content-end pe-5 ps-2">
			<label for="inputSubtotalMenos" class="col-sm-2 col-form-label"><strong>Subtotal</strong></label>
			<div class="col-3">
				<input type="subtotalMenos" class="form-control" name="sub_tercero" id="inputSubtotalMenos" value="<?= $sub_tercero ?>" disabled>
			</div>
		</div>

		<!-- SALDO_CONCILIADO -->
		<div class="row justify-content-between pe-5 ps-2">
			<label for="inputSaldoConsiliado" class="col col-form-label text-uppercase"><strong>SALDO CONCILIADO IGUAL A BANCO AL <?= $dia_actual ?> de <?= $mes_actual ?> de <?= $anio ?></strong></label>
			<div class="col-3">
				<input type="saldoConsiliado" class="form-control" name="saldo_conciliado" id="inputSaldoConsiliado" value="<?= $saldo_conciliado ?>" disabled>
			</div>
		</div>

		<hr>

		<!-- ----------------------------------------------------------- BOTONES ----------------------------------------------------------- -->
		<div class="d-grid gap-5 d-md-flex justify-content-md-center pb-3" id="btn-custom-container">
			<button type="submit" class="btn button-custom" id="btn-custom">Grabar</button>
			<button type="submit" class="btn button-custom" id="btn-custom">Nuevo</button>
		</div>

	</form>
</div>
<?php require "../includes/footer.php" ?>
