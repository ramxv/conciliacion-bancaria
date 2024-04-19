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
						$('.error-container').html('<div class="alert alert-success" role="alert">'+ response.mensajeNumCk +'</div>');
						enableFields();
					} else {
						$('.error-container').html('<div class="alert alert-danger" role="alert"> '+ response.mensajeNumCk +' </div>');
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
	let form_data = new FormData(document.getElementById("anulacion-form"));

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
	xhr.open('POST', 'logica_anulacion.php', true);
	xhr.send(form_data);
}

// * Función para validar número de Cheque
function validarCkAnulacion() {
	let numeroCheque = document.getElementById("num-cheque-input").value.trim();
	console.log(numeroCheque);
	if (numeroCheque !== "") {
		console.log(numeroCheque);
		$.ajax({
			type: 'POST',
			url: 'logica_anulacion.php',
			dataType: 'json', // Especifica que esperamos una respuesta JSON
			data: { numeroCheque: numeroCheque },
			success: function (response) {
				try {
					console.log(response);
					if (response.successCirculacion && response.successAnulado) {
						// El número de cheque es válido
						$('.error-container').html('<div class="alert alert-success" role="alert">'+ response.mensajeNumCk +'</div>');
						disableFields();
					} else {
						$('.error-container').html('<div class="alert alert-danger" role="alert"> '+ response.mensajeNumCk +' </div>');
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

// ! ======================================== 			Sección Sacar de Circulación 				==============================================



// ! ======================================== 			Sección Otras Transacciones 				==============================================



// ! ======================================== 			Sección Conciliación 								==============================================



// ! ======================================== 			Sección Resetear Mensaje Cliente 		==============================================

function resetMensaje() {
	const mensajeCliente = document.getElementById('mensaje-cliente');
	mensajeCliente.innerHTML = '';
}
