<?php require "../php/db_conciliacion.php";

$selectedValue = isset($_POST['nombres']) ? $_POST['nombres'] : '';
$consultaRRHH = $conn->query("SELECT codigo_marcacion, nombre1, apellido1 FROM rrhh");
?>

<div class="container my-4">
	<div class="row border border-secondary-subtle rounded" id="asistencia-container">
		<h3 class="form-header p-3 border-bottom border-secondary-subtle">Procesar Datos</h3>
		<div class="col-md-6 m-auto text-center p-4">
			<form method="post" class="needs-validation" id="form-archivo" novalidate onsubmit="grabarArchivoAsistencia(event)">
				<div class="col-12">
					<label for="file-input" class="form-label fs-5 fw-semibold">Ingrese un archivo</label>
					<input class="form-control" type="file" id="file-input" accept=".dat,.txt,.log" name="file-datos" required>
					<div class="valid-feedback">¡Excelente!</div>
					<div class="invalid-feedback">Complete todos los campos</div>
				</div>
				<div id="btn-custom-container">
					<button type="submit" class="mt-4 px-3 py-2 btn btn-secondary btn-send-custom" onclick="mostrarModalEsperar()">
						<svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 16 16" width="16" height="16" class="svg-custom">
							<path d="M14.064 0h.186C15.216 0 16 .784 16 1.75v.186a8.752 8.752 0 0 1-2.564 6.186l-.458.459c-.314.314-.641.616-.979.904v3.207c0 .608-.315 1.172-.833 1.49l-2.774 1.707a.749.749 0 0 1-1.11-.418l-.954-3.102a1.214 1.214 0 0 1-.145-.125L3.754 9.816a1.218 1.218 0 0 1-.124-.145L.528 8.717a.749.749 0 0 1-.418-1.11l1.71-2.774A1.748 1.748 0 0 1 3.31 4h3.204c.288-.338.59-.665.904-.979l.459-.458A8.749 8.749 0 0 1 14.064 0ZM8.938 3.623h-.002l-.458.458c-.76.76-1.437 1.598-2.02 2.5l-1.5 2.317 2.143 2.143 2.317-1.5c.902-.583 1.74-1.26 2.499-2.02l.459-.458a7.25 7.25 0 0 0 2.123-5.127V1.75a.25.25 0 0 0-.25-.25h-.186a7.249 7.249 0 0 0-5.125 2.123ZM3.56 14.56c-.732.732-2.334 1.045-3.005 1.148a.234.234 0 0 1-.201-.064.234.234 0 0 1-.064-.201c.103-.671.416-2.273 1.15-3.003a1.502 1.502 0 1 1 2.12 2.12Zm6.94-3.935c-.088.06-.177.118-.266.175l-2.35 1.521.548 1.783 1.949-1.2a.25.25 0 0 0 .119-.213ZM3.678 8.116 5.2 5.766c.058-.09.117-.178.176-.266H3.309a.25.25 0 0 0-.213.119l-1.2 1.95ZM12 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z">
							</path>
						</svg>
						Envíar
					</button>
				</div>
			</form>
		</div>
		<hr>
		<div class="row p-4">
			<div class="col text-center">
				<h3 class="form-header p-3 border-bottom border-secondary-subtle rounded">Reporte</h3>
			</div>
		</div>
		<form method="post" class="w-100 pb-3" id="form-reporte" onsubmit="mostrarReporte(event)">
			<div class="row justify-content-center">
				<div class="col-md-3 py-3 text-center">
					<label for="fecha-desde-input" class="form-label">Fecha Inicio</label>
					<input type="date" name="fecha-desde" id="fecha-desde-input" class="form-control mx-auto d-block">
				</div>
				<div class="col-md-3 py-3 text-center">
					<label for="fecha-hasta-input" class="form-label">Fecha Final</label>
					<input type="date" name="fecha-hasta" id="fecha-hasta-input" class="form-control mx-auto d-block">
				</div>
				<div class="col-md-4 py-3 text-center">
					<label for="inputNombres" class="form-label">Nombre</label>
					<select class="form-select mx-auto d-block" name="nombres" id="inputNombres">
						<option value=""></option>
						<?php if ($consultaRRHH && $consultaRRHH->rowCount() > 0) : ?>
							<?php while ($row = $consultaRRHH->fetch(PDO::FETCH_ASSOC)) : ?>
								<option value="<?= htmlspecialchars($row["codigo_marcacion"]) ?>" <?= ($row["codigo_marcacion"] == $selectedValue) ? 'selected' : '' ?>>
									<?= htmlspecialchars($row["nombre1"]) ?> <?= htmlspecialchars($row["apellido1"]) ?>
								</option>
							<?php endwhile; ?>
						<?php else : ?>
							<option value="">No hay datos disponibles</option>
						<?php endif; ?>
					</select>
				</div>
			</div>

			<div class="row text-center">
				<div class="col-md-6 py-3">
					<div id="btn-custom-container-2">
						<button type="button" class="mt-4 px-3 py-2 btn btn-secondary btn-send-custom" id="consultar-container" onclick="consultarReporte(event)">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
								<path d="M9.504.43a1.516 1.516 0 0 1 2.437 1.713L10.415 5.5h2.123c1.57 0 2.346 1.909 1.22 3.004l-7.34 7.142a1.249 1.249 0 0 1-.871.354h-.302a1.25 1.25 0 0 1-1.157-1.723L5.633 10.5H3.462c-1.57 0-2.346-1.909-1.22-3.004L9.503.429Zm1.047 1.074L3.286 8.571A.25.25 0 0 0 3.462 9H6.75a.75.75 0 0 1 .694 1.034l-1.713 4.188 6.982-6.793A.25.25 0 0 0 12.538 7H9.25a.75.75 0 0 1-.683-1.06l2.008-4.418.003-.006a.036.036 0 0 0-.004-.009l-.006-.006-.008-.001c-.003 0-.006.002-.009.004Z"></path>
							</svg>
							Consultar
						</button>
					</div>
				</div>
				<div class="col-md-6 py-3">
					<div id="btn-custom-container-2">
						<button type="submit" class="mt-4 px-3 py-2 btn btn-secondary btn-send-custom" id="buscar-container">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
								<path d="M10.68 11.74a6 6 0 0 1-7.922-8.982 6 6 0 0 1 8.982 7.922l3.04 3.04a.749.749 0 0 1-.326 1.275.749.749 0 0 1-.734-.215ZM11.5 7a4.499 4.499 0 1 0-8.997 0A4.499 4.499 0 0 0 11.5 7Z">
								</path>
							</svg>
							Buscar
						</button>
					</div>
				</div>
			</div>
			<div id="consulta-reporte">
			</div>
		</form>
	</div>
</div>

<!-- Modal -->
<div class="modal fade text-dark" id="modalExito" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">¡Envío completado!</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="mensajeErrorModal">¡El archivo se ha enviado correctamente!✅</div>
			<div class="modal-footer">
				<button type="button" class="mt-4 px-3 py-2 btn btn-primary btn-modal-custom" id="okButton">
					<svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 16 16" width="16" height="16" class="svg-custom">
						<path d="M8.834.066c.763.087 1.5.295 2.01.884.505.581.656 1.378.656 2.3 0 .467-.087 1.119-.157 1.637L11.328 5h1.422c.603 0 1.174.085 1.668.333.508.254.911.679 1.137 1.2.453.998.438 2.447.188 4.316l-.04.306c-.105.79-.195 1.473-.313 2.033-.131.63-.315 1.209-.668 1.672C13.97 15.847 12.706 16 11 16c-1.848 0-3.234-.333-4.388-.653-.165-.045-.323-.09-.475-.133-.658-.186-1.2-.34-1.725-.415A1.75 1.75 0 0 1 2.75 16h-1A1.75 1.75 0 0 1 0 14.25v-7.5C0 5.784.784 5 1.75 5h1a1.75 1.75 0 0 1 1.514.872c.258-.105.59-.268.918-.508C5.853 4.874 6.5 4.079 6.5 2.75v-.5c0-1.202.994-2.337 2.334-2.184ZM4.5 13.3c.705.088 1.39.284 2.072.478l.441.125c1.096.305 2.334.598 3.987.598 1.794 0 2.28-.223 2.528-.549.147-.193.276-.505.394-1.07.105-.502.188-1.124.295-1.93l.04-.3c.25-1.882.189-2.933-.068-3.497a.921.921 0 0 0-.442-.48c-.208-.104-.52-.174-.997-.174H11c-.686 0-1.295-.577-1.206-1.336.023-.192.05-.39.076-.586.065-.488.13-.97.13-1.328 0-.809-.144-1.15-.288-1.316-.137-.158-.402-.304-1.048-.378C8.357 1.521 8 1.793 8 2.25v.5c0 1.922-.978 3.128-1.933 3.825a5.831 5.831 0 0 1-1.567.81ZM2.75 6.5h-1a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"></path>
					</svg> OK
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade text-dark" id="modalEsperando" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Espere un momento...</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="mensajeErrorModal">El archivo se está enviando... ⌛</div>
			<div class="modal-footer">
				<button type="button" class="mt-4 px-3 py-2 btn btn-primary btn-modal-custom" id="okButtonEsperar">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" fill="white" height="16">
						<path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Zm7-3.25v2.992l2.028.812a.75.75 0 0 1-.557 1.392l-2.5-1A.751.751 0 0 1 7 8.25v-3.5a.75.75 0 0 1 1.5 0Z"></path>
					</svg> OK
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade text-dark" id="modalError" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Hubo un Error! ❌</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="mensajeErrorModal">La Fecha de Inicio no puede ser mayor a la Fecha Final</div>
			<div class="modal-footer">
				<button type="button" class="mt-4 px-3 py-2 btn btn-primary btn-modal-custom" id="okButtonError">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="white" width="16" height="16">
						<path d="M5.22 7.47a.749.749 0 0 0 0 1.06l3.75 3.75a.749.749 0 1 0 1.06-1.06L7.561 8.75h6.689a.75.75 0 0 0 0-1.5H7.561l2.469-2.47a.749.749 0 1 0-1.06-1.06L5.22 7.47ZM3 3.75a.75.75 0 0 0-1.5 0v8.5a.75.75 0 0 0 1.5 0v-8.5Z"></path>
					</svg> OK
				</button>
			</div>
		</div>
	</div>
</div>