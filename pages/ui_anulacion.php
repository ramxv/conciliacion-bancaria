<?php require "../php/db_conciliacion.php"; ?>
<?php require "logica_validarAnulacion.php"; ?>
<?php require "logica_grabarAnulacion.php"; ?>

<div class="container">
	<form id="anulacion-form" class="border border-secondary-subtle rounded" method="POST" onsubmit="grabarAnulacion(event)">
		<h2 class="form-header p-3 border-bottom border-secondary-subtle">Anulación de Cheque</h2>
		<div class="row p-4">
			<!-- Mensaje de error al usuario -->
			<div class="error-container" id="mensaje-cliente"></div>
			<!-- Contenedor de Anulación Cheque -->
			<div class="col p-3">
				<div class="anul-container">
					<!-- Sección buscar cheque -->
					<div class="row justify-content-end align-items-end">
						<div class="col-4">
							<label for="num-cheque-input" class="form-label">No.Cheque</label>
							<input type="text" class="form-control" autocomplete="off" name="numeroCheque" id="num-cheque-input" value="<?= $numero_cheque ?>" onkeypress="return soloNumeros(event)">
						</div>
						<div class="col-4" id="btn-custom-container">
							<button type="button" class="btn button-custom" onclick="validarCkAnulacion()" id="btn-buscar" name="buscar">Buscar</button>
						</div>
					</div>
					<!-- Sección insertar datos desde la BD -->
					<div class="col-4">
						<label for="fecha-input" class="form-label">Fecha</label>
						<input type="date" name="date" id="fecha-input" class="form-control" value="<?= $fecha ?>" disabled>
					</div>
					<div class="col-12 pt-2">
						<label for="inputOrden" class="form-label">Páguese a la orden de</label>
						<select class="form-select" id="inputOrden" disabled>
							<option value=""></option>
							<?php if ($proveedores->rowCount() > 0) : ?>
								<?php while ($consultaProveedores = $proveedores->fetch(PDO::FETCH_ASSOC)) :
									$codigo = $consultaProveedores["codigo"];
									$nombre = $consultaProveedores["nombre"]; ?>
									<option value="<?= $codigo ?>" <?= ($codigo == $beneficiario) ? 'selected' : '' ?>><?= $nombre ?></option>
								<?php endwhile; ?>
							<?php endif; ?>
						</select>
					</div>
					<div class="col-4 pt-2">
						<label for="inputMonto" class="form-label">La suma de</label>
						<div class="input-group">
							<input type="text" aria-label="First name" class="form-control" id="inputMonto" placeholder="$" value="<?= $monto ?>" disabled>
						</div>
					</div>
					<div class="col-12 pt-2">
						<label for="inputDetalle" class="form-label">Detalle</label>
						<input type="text" class="form-control" id="inputDetalle" placeholder="" value="<?= $descripcion ?>" disabled>
					</div>
				</div>
			</div>
			<div class="col p-3">
				<div class="col-4">
					<label for="fecha-anulada" class="form-label">Fecha de Anulación</label>
					<input type="date" name="fechaAnulado" id="fecha-anulada" class="form-control">
				</div>
				<div class="col-12 pt-2">
					<label for="detalle-anulacion" class="form-label">Detalle de la Anulación</label>
					<textarea class="form-control" placeholder="" id="detalle-anulacion" name="detalle_anulado" style="height: 100px"></textarea>
				</div>
				<div class="d-grid gap-2 d-md-flex justify-content-md-center pt-5" id="btn-custom-container">
					<button type="submit" class="btn button-custom" id="btn-anular" name="anular">Anular</button>
					<button type="reset" class="btn button-custom"  onclick="resetMensaje()">Limpiar</button>
				</div>
			</div>
		</div>
	</form>
</div>
