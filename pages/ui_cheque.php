<?php require "../php/db_conciliacion.php"; ?>
<?php require "logica_cheque.php"; ?>

<div class="container p-3">
	<form method="POST" class="border border-secondary-subtle rounded" onsubmit="grabarCheques(event)" id="cheques-form">
		<h2 class="form-header p-3 border-bottom border-secondary-subtle">Creación</h2>
		<div class="row p-4">
			<!-- Mensajes de aviso al cliente -->
			<div class="error-container" id="mensaje-cliente"></div>
			<div class="col p-3">
				<h3 class="form-header border border-secondary-subtle rounded p-3 cheque-title">Cheques</h3>
				<!-- Sección de Cheques -->
				<div class="ch-container">
					<div class="row justify-content-end">
						<div class="col-4">
							<label for="numCkInput" class="form-label">No.Cheque</label>
							<input type="text" class="form-control" id="numCkInput" name="numCheque" onkeypress="return soloNumeros(event)" onblur="validarNumCheque()" autocomplete="off" maxlength="3">
						</div>
						<div class="col-4">
							<label for="fecha-input" class="form-label">Fecha</label>
							<input type="date" name="fecha" id="fecha-input" class="form-control">
						</div>
					</div>
					<div class="col-12 pt-2">
						<label for="inputOrden" class="form-label">Páguese a la orden de</label>

						<select class="form-select" id="inputOrden" name="beneficiario">
							<option value=""></option>
							<?php while ($row = $consultaProveedores->fetch(PDO::FETCH_ASSOC)) : ?>
								<option value="<?= $row["codigo"] ?>"> <?= $row["nombre"] ?> </option>
							<?php endwhile ?>
						</select>

					</div>
					<div class="col-12 pt-2">
						<label for="inputMonto" class="form-label">La suma de</label>
						<div class="input-group">
							<input type="text" aria-label="Monto" class="form-control" id="input-monto" placeholder="$" name="monto" onkeypress="return soloDecimal(event)" onblur="mostrarMontoEnLetras()">
							<input type="text" aria-label="Monto en Letras" class="form-control w-50" id="input-monto-letras" disabled>
						</div>
					</div>
					<div class="col-12 pt-2">
						<label for="inputDetalle" class="form-label">Detalle</label>
						<input type="text" class="form-control" id="inputDetalle" name="detalle" placeholder="" autocomplete="off">
					</div>
				</div>
			</div>
			<!-- Seccción de Objetos de Gastos -->
			<div class="col p-3">
				<h3 class="form-header border border-secondary-subtle rounded p-3 og-title">Objetos de Gastos</h3>
				<div class="og-container">
					<div class="row">
						<div class="col-8">
							<label for="inputObjeto" class="form-label">Objeto</label>
							<select class="form-select" id="inputObjeto" name="objetoGasto">
								<option value=""></option>
								<?php
								$labels = array(
									"label1" => "SERVICIOS NO PERSONALES",
									"label2" => "MATERIALES DE SUMINISTRO",
									"label3" => "MAQUINARIA Y EQUIPOS"
								);
								foreach ($labels as $key => $label) :
								?>
									<optgroup label="<?= $label ?>">
										<?php
										$consultaObjetoGasto->execute();
										while ($row = $consultaObjetoGasto->fetch(PDO::FETCH_ASSOC)) :
											$codigo = $row["codigo"];
											$objetoGasto = substr($codigo, 0, 1);
											if ($objetoGasto == substr($key, -1)) :
										?>
												<option value="<?= $row["codigo"] ?>"> <?= $row["detalle"] ?> </option>
											<?php endif ?>
										<?php endwhile ?>
									</optgroup>
								<?php endforeach ?>
							</select>
						</div>
						<div class="col-4">
							<label for="inputMonto" class="form-label">Monto</label>
							<input type="text" class="form-control" id="inputMonto" placeholder="" name="montoObjeto" onkeypress="return soloDecimal(event)" readonly>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Botones -->
		<div class="d-grid gap-5 d-md-flex justify-content-md-center pb-3" id="btn-custom-container">
			<button type="submit" class="btn button-custom" id="">Grabar</button>
			<button type="reset" class="btn button-custom"  onclick="resetMensaje()">Nuevo</button>
		</div>
	</form>
</div>
