// * Función para cargar páginas desde el inico
function cargarPagina(page) {
	fetch(page)
		.then(response => response.text())
		.then(data => {
			document.getElementById('contenido').innerHTML = data;
		})
		.catch(error => console.error('Error al cargar la página:', error));
}

// ! ======================================== 			Sección de Grabar Cheque 						==============================================

// * Función para grabar cheques
function grabarCheques(event) {

	event.preventDefault();

	let form_data = new FormData(document.getElementById("cheques-form"));

	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			try {
				console.log(xhr.responseText);
				var response = JSON.parse(xhr.responseText);
				console.log(response);
				if (response.success) {
					console.log('Respuesta del servidor:', response.mensaje);
					$('.error-container').html('<div class="alert alert-success" role="alert">' + response.mensaje + '</div>');
				} else {
					console.error('Error en el servidor:', response.error);
					$('.error-container').html('<div class="alert alert-danger" role="alert">' + response.error + '</div>');
				}
			} catch (e) {
				console.error('Error al analizar la respuesta del servidor:', e);
			}
		}
	};
	xhr.open('POST', 'logica_cheque.php', true);
	xhr.send(form_data);
}

// * Función para validar el número del cheque
function validarNumCheque() {
	let numCheque = document.getElementById("numCkInput").value.trim();
	console.log(numCheque);
	if (numCheque !== "") {
		console.log(numCheque);
		$.ajax({
			type: 'POST',
			url: 'logica_cheque.php',
			dataType: 'json', // Especifica que esperamos una respuesta JSON
			data: { numCheque: numCheque },
			success: function (response) {
				try {
					console.log(response);
					if (response.successNumCk) {
						// El número de cheque es válido
						$('.error-container').html('<div class="alert alert-success" role="alert">' + response.mensajeNumCk + '</div>');
						enableFields();
					} else {
						$('.error-container').html('<div class="alert alert-danger" role="alert"> ' + response.mensajeNumCk + ' </div>');
						disableFields();
					}
				} catch (error) {
					console.error('Error al analizar la respuesta JSON:', error);
					$('.error-container').html('<div class="alert alert-danger" role="alert">Error al analizar la respuesta del servidor</div>');
					disableFields();
				}
			},
			error: function (xhr, status, error) {
				console.error(error);
				$('.error-container').html('<div class="alert alert-danger" role="alert">Error al conectar con el servidor</div>');
				disableFields();
			}
		});
	} else {
		// Error: número de cheque no recibido
		$('.error-container').html('<div class="alert alert-danger" role="alert">Error: número de cheque no recibido.</div>');
		disableFields();
	}
}

// ! ======================================== 			Sección Anulación de Cheque 				==============================================
// * Función para grabar Anulación
function grabarAnulacion(event) {
	event.preventDefault();
	let fecha_anulacion = document.getElementById("fecha-anulada").value;
	let detalle_anulacion = document.getElementById("detalle-anulacion").value;
	// Crear un objeto FormData con los campos necesarios para la actualización
	let formData = new FormData(document.getElementById("anulacion-form"));

	if (fecha_anulacion === '' || detalle_anulacion === '') {
		$('#mensaje-cliente').html('<div class="alert alert-warning" role="alert"> ⚠️ Llene todos los campos.</div>');
	}

	// Realizar la solicitud AJAX
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			try {
				// Registrar la respuesta completa del servidor
				console.log('Respuesta completa del servidor:', xhr.responseText);

				// Analizar la respuesta JSON del servidor
				var response = JSON.parse(xhr.responseText);

				if (response.success) {
					// Mostrar mensaje de éxito si se completó la actualización
					console.log('Cheque anulado correctamente:', response.mensaje);
					$('#mensaje-cliente').html('<div class="alert alert-success" role="alert">' + response.mensaje + '</div>');
				} else {
					// Mostrar mensaje de error si hubo problemas en la actualización
					console.error('Error en el servidor:', response.mensaje || 'Error desconocido');
					$('#mensaje-cliente').html('<div class="alert alert-danger" role="alert">' + (response.mensaje || 'Error desconocido') + '</div>');
				}
			} catch (e) {
				console.error('Error al analizar la respuesta del servidor:', e);
				$('#mensaje-cliente').html('<div class="alert alert-danger" role="alert">Error al analizar la respuesta del servidor</div>');
			}
		} else {
			// Manejo de errores HTTP
			console.error('Error HTTP:', xhr.status, xhr.statusText);
			$('#mensaje-cliente').html('<div class="alert alert-danger" role="alert">Error en la solicitud HTTP</div>');
		}
	};
	// Configurar la solicitud AJAX como POST
	xhr.open('POST', 'logica_grabarAnulacion.php', true);
	// Enviar los datos de los campos para la actualización
	xhr.send(formData);
}

// * Función para validar número de Cheque
function validarCkAnulacion() {
	let numeroCheque = document.getElementById("num-cheque-input").value.trim();
	if (numeroCheque !== "") {
		$.ajax({
			type: 'POST',
			url: 'logica_validarAnulacion.php',
			dataType: 'json',
			data: { numeroCheque: numeroCheque },
			success: function (response) {
				try {
					if (response.successCirculacion && response.successAnulado) {
						// El número de cheque es válido
						$('#mensaje-cliente').html('<div class="alert alert-success" role="alert">' + response.mensajeNumCk + '</div>');
						llenarCampos(response);
						disableFields();
					} else {
						$('#mensaje-cliente').html('<div class="alert alert-danger" role="alert">' + response.mensajeNumCk + '</div>');
					}
				} catch (error) {
					console.error('Error al analizar la respuesta JSON:', error);
					$('#mensaje-cliente').html('<div class="alert alert-danger" role="alert">Error al analizar la respuesta del servidor</div>');
				}
			},
			error: function (xhr, status, error) {
				console.error('Error al conectar con el servidor:', error);
				$('#mensaje-cliente').html('<div class="alert alert-danger" role="alert">Error al conectar con el servidor</div>');
			}
		});
	} else {
		// Error: número de cheque no recibido
		$('#mensaje-cliente').html('<div class="alert alert-danger" role="alert">Error: número de cheque no recibido.</div>');
	}
}



// ! ======================================== 			Sección Sacar de Circulación 				==============================================



// ! ======================================== 			Sección Otras Transacciones 				==============================================



// ! ======================================== 			Sección Conciliación 								==============================================



// ! ======================================== 			Sección Funciones		==============================================

function resetMensaje() {
	const mensajeCliente = document.getElementById('mensaje-cliente');
	mensajeCliente.innerHTML = '';
}

// * Esta funcón llena los campos del formulario con los datos recibidos
function llenarCampos(response) {
	$('#fecha-input').val(response.fecha);
	$('#inputOrden').val(response.beneficiario);
	$('#inputMonto').val(response.monto);
	$('#inputDetalle').val(response.descripcion);
	$('#fecha-anulada').val(response.fecha_anulado);
}

function vaciarCampos() {
	// Vacía los campos del formulario
	$('#fecha-input').val('');
	$('#inputOrden').val('');
	$('#inputMonto').val('');
	$('#inputDetalle').val('');
	$('#fecha-anulada').val('');
}
