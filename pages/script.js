// FUNCIÓN PARA CARGAR PÁGINAS DESDE EL INICIO
function cargarCheques() {
	fetch('cheques.php')
		.then(response => response.text())
		.then(data => {
			document.getElementById('contenido').innerHTML = data;
		})
		.catch(error => console.error('Error al cargar la página:', error));
}

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
					$('.error-container').html('<div class="alert alert-success" role="alert">El cheque se registró correctamente.</div>');
				} else {
					console.error('Error en el servidor:', response.error);
					$('.error-container').html('<div class="alert alert-danger" role="alert">' + response.mensaje + '</div>');
				}
			} catch (e) {
				console.error('Error al analizar la respuesta del servidor:', e);
			}
		}
	};
	xhr.open('POST', 'cheques.php', true);
	xhr.send(form_data);
}

function validarNumCheque() {
	let numCheque = document.getElementById("numCkInput").value.trim();
	console.log(numCheque);
	if (numCheque !== "") {
		console.log(numCheque);
		$.ajax({
			type: 'POST',
			url: 'cheques.php',
			dataType: 'json', // Especifica que esperamos una respuesta JSON
			data: { numCheque: numCheque },
			success: function (response) {
				try {
					console.log(response);
					if (response.success) {
						// El número de cheque es válido
						$('.error-container').html('<div class="alert alert-success" role="alert">El número de cheque es válido.</div>');
						enableFields();
					} else {
						// Error: El número de cheque ya existe o error de conexión
						let errorMessage = response.error ? response.error : 'Error al conectar con el servidor';
						$('.error-container').html('<div class="alert alert-danger" role="alert">' + errorMessage + '</div>');
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
