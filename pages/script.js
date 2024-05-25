// * Función para cargar páginas desde el inico
function cargarPagina(page) {
  fetch(page)
    .then((response) => response.text())
    .then((data) => {
      document.getElementById("contenido").innerHTML = data;
    })
    .catch((error) => console.error("Error al cargar la página:", error));
}

// * Función auxiliar para realizar solicitudes AJAX
function sendAjaxRequest(url, method, data, onSuccess, onError) {
  fetch(url, {
    method: method,
    body: data instanceof FormData ? data : JSON.stringify(data),
    headers: data instanceof FormData ? {} : {
      'Content-Type': 'application/json'
    }
  })
  .then(response => response.json())
  .then(data => onSuccess(data))
  .catch(error => onError(error));
}


// ! ======================================== 			Sección de Grabar Cheque 						==============================================

// * Función para grabar cheques
// Función para grabar cheques
function grabarCheques(event) {
  event.preventDefault();

  let form_data = new FormData(document.getElementById("cheques-form"));

  sendAjaxRequest(
    "logica_cheque.php",
    "POST",
    form_data,
    function(response) {
      if (response.success) {
        $(".error-container").html(
          '<div class="alert alert-success" role="alert">' +
          response.mensaje +
          "</div>"
        );
      } else {
        console.error("Error en el servidor:", response.error);
        $(".error-container").html(
          '<div class="alert alert-danger" role="alert">' +
          response.error +
          "</div>"
        );
      }
    },
    function(error) {
      console.error("Error al enviar la solicitud:", error);
      $(".error-container").html(
        '<div class="alert alert-danger" role="alert">Error al enviar la solicitud</div>'
      );
    }
  );
}


// * Función para validar el número del cheque
// Función para validar el número del cheque
function validarNumCheque() {
  let numCheque = document.getElementById("numCkInput").value.trim();
  if (numCheque !== "") {
    sendAjaxRequest(
      "logica_cheque.php",
      "POST",
      { numCheque: numCheque },
      function(response) {
        if (response.successNumCk) {
          $(".error-container").html(
            '<div class="alert alert-success" role="alert">' +
            response.mensajeNumCk +
            "</div>"
          );
          enableFields();
        } else {
          $(".error-container").html(
            '<div class="alert alert-danger" role="alert"> ' +
            response.mensajeNumCk +
            " </div>"
          );
          disableFields();
        }
      },
      function(error) {
        console.error("Error al conectar con el servidor:", error);
        $(".error-container").html(
          '<div class="alert alert-danger" role="alert">Error al conectar con el servidor</div>'
        );
        disableFields();
      }
    );
  } else {
    $(".error-container").html(
      '<div class="alert alert-danger" role="alert">Error: número de cheque no recibido.</div>'
    );
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

  if (fecha_anulacion === "" || detalle_anulacion === "") {
    $("#mensaje-cliente").html(
      '<div class="alert alert-warning" role="alert"> ⚠️ Llene todos los campos.</div>'
    );
  }

  // Realizar la solicitud AJAX
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        // Analizar la respuesta JSON del servidor
        var response = JSON.parse(xhr.responseText);

        if (response.success) {
          // Mostrar mensaje de éxito si se completó la actualización
          $("#mensaje-cliente").html(
            '<div class="alert alert-success" role="alert">' +
              response.mensaje +
              "</div>"
          );
        } else {
          // Mostrar mensaje de error si hubo problemas en la actualización
          console.error(
            "Error en el servidor:",
            response.mensaje || "Error desconocido"
          );
          $("#mensaje-cliente").html(
            '<div class="alert alert-danger" role="alert">' +
              (response.mensaje || "Error desconocido") +
              "</div>"
          );
        }
      } catch (e) {
        console.error("Error al analizar la respuesta del servidor:", e);
        $("#mensaje-cliente").html(
          '<div class="alert alert-danger" role="alert">Error al analizar la respuesta del servidor</div>'
        );
      }
    }
  };
  // Configurar la solicitud AJAX como POST
  xhr.open("POST", "logica_grabarAnulacion.php", true);
  // Enviar los datos de los campos para la actualización
  xhr.send(formData);
}

// * Función para validar número de Cheque
function validarCkAnulacion() {
  let numeroCheque = document.getElementById("num-cheque-input").value.trim();
  if (numeroCheque !== "") {
    $.ajax({
      type: "POST",
      url: "logica_validarAnulacion.php",
      dataType: "json",
      data: { numeroCheque: numeroCheque },
      success: function (response) {
        try {
          if (response.successCirculacion && response.successAnulado) {
            // El número de cheque es válido
            $("#mensaje-cliente").html(
              '<div class="alert alert-success" role="alert">' +
                response.mensajeNumCk +
                "</div>"
            );
            llenarCampos(response);
            disableFields();
          } else {
            $("#mensaje-cliente").html(
              '<div class="alert alert-danger" role="alert">' +
                response.mensajeNumCk +
                "</div>"
            );
          }
        } catch (error) {
          console.error("Error al analizar la respuesta JSON:", error);
          $("#mensaje-cliente").html(
            '<div class="alert alert-danger" role="alert">Error al analizar la respuesta del servidor</div>'
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al conectar con el servidor:", error);
        $("#mensaje-cliente").html(
          '<div class="alert alert-danger" role="alert">Error al conectar con el servidor</div>'
        );
      },
    });
  } else {
    // Error: número de cheque no recibido
    $("#mensaje-cliente").html(
      '<div class="alert alert-danger" role="alert">Error: número de cheque no recibido.</div>'
    );
  }
}

// ! ======================================== 			Sección Sacar de Circulación 				==============================================
function validarCkCirculacion() {
  let numeroCheque = document.getElementById("num-cheque-input").value.trim();
  if (numeroCheque !== "") {
    $.ajax({
      type: "POST",
      url: "logica_validarCirculacion.php",
      dataType: "json",
      data: { numeroCheque: numeroCheque },
      success: function (response) {
        try {
          if (response.successCirculacion && response.successAnulado) {
            // El número de cheque es válido
            $("#mensaje-cliente").html(
              '<div class="alert alert-success" role="alert">' +
                response.mensajeNumCk +
                "</div>"
            );
            llenarCampos(response);
            disableFields();
          } else {
            $("#mensaje-cliente").html(
              '<div class="alert alert-danger" role="alert">' +
                response.mensajeNumCk +
                "</div>"
            );
            $("#fecha-circulacion").attr("disabled", "disabled");
          }
        } catch (error) {
          console.error("Error al analizar la respuesta JSON:", error);
          $("#mensaje-cliente").html(
            '<div class="alert alert-danger" role="alert">Error al analizar la respuesta del servidor</div>'
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al conectar con el servidor:", error);
        $("#mensaje-cliente").html(
          '<div class="alert alert-danger" role="alert">Error al conectar con el servidor</div>'
        );
      },
    });
  } else {
    // Error: número de cheque no recibido
    $("#mensaje-cliente").html(
      '<div class="alert alert-danger" role="alert">Error: número de cheque no recibido.</div>'
    );
  }
}

function grabarCirculacion(event) {
  event.preventDefault();
  let fecha_circulacion = document.getElementById("fecha-circulacion").value;

  // Crear un objeto FormData con los campos necesarios para la actualización
  let formData = new FormData(document.getElementById("circulacion-form"));

  if (fecha_circulacion === "") {
    $("#mensaje-cliente").html(
      '<div class="alert alert-warning" role="alert"> ⚠️ Llene todos los campos.</div>'
    );
  }

  // Realizar la solicitud AJAX
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        // Registrar la respuesta completa del servidor
        console.log("Respuesta completa del servidor:", xhr.responseText);

        // Analizar la respuesta JSON del servidor
        var response = JSON.parse(xhr.responseText);

        if (response.success) {
          // Mostrar mensaje de éxito si se completó la actualización
          $("#mensaje-cliente").html(
            '<div class="alert alert-success" role="alert">' +
              response.mensaje +
              "</div>"
          );
        } else {
          // Mostrar mensaje de error si hubo problemas en la actualización
          console.error(
            "Error en el servidor:",
            response.mensaje || "Error desconocido"
          );
          $("#mensaje-cliente").html(
            '<div class="alert alert-danger" role="alert">' +
              (response.mensaje || "Error desconocido") +
              "</div>"
          );
        }
      } catch (e) {
        console.error("Error al analizar la respuesta del servidor:", e);
        $("#mensaje-cliente").html(
          '<div class="alert alert-danger" role="alert">Error al analizar la respuesta del servidor</div>'
        );
      }
    }
  };
  // Configurar la solicitud AJAX como POST
  xhr.open("POST", "logica_grabarCirculacion.php", true);
  // Enviar los datos de los campos para la actualización
  xhr.send(formData);
}

// ! ======================================== 			Sección Otras Transacciones 				==============================================
function grabarOtrasTransacciones(event) {
  event.preventDefault();

  let form_data = new FormData(
    document.getElementById("otras-transacciones-form")
  );

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      try {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          $(".error-container").html(
            '<div class="alert alert-success" role="alert">' +
              response.mensaje +
              "</div>"
          );
        } else {
          console.error("Error en el servidor:", response.error);
          $(".error-container").html(
            '<div class="alert alert-danger" role="alert">' +
              response.error +
              "</div>"
          );
        }
      } catch (e) {
        console.error("Error al analizar la respuesta del servidor:", e);
      }
    }
  };
  xhr.open("POST", "logica_otrasTransacciones.php", true);
  xhr.send(form_data);
}

// ! ======================================== 			Sección Conciliación 			==============================================
function realizarConciliacion() {
  let mes = document.getElementById("inputMeses").value;
  let anio = document.getElementById("inputAnio").value;
  if (mes !== "" || anio !== "") {
    $.ajax({
      type: "POST",
      url: "logica_conciliacion.php",
      dataType: "json",
      data: { meses: mes, anio: anio },
      success: function (response) {
        try {
          if (response.success) {
            // El número de cheque es válido
            $("#mensaje-cliente").html(
              '<div class="alert alert-warning" role="alert">' +
                response.mensaje +
                "</div>"
            );
            llenarLabels(response);
            llenarConciliacionRegistrada(response);
            $("#inputSaldoBanco").attr("disabled", "disabled");
          } else {
            $("#inputSaldoBanco").removeAttr("disabled");
            if (response.successConciliacion) {
              $("#mensaje-cliente").html(
                '<div class="alert alert-success" role="alert">' +
                  response.mensajeConciliacion +
                  "</div>"
              );
              llenarLabels(response);
              vaciarCamposConciliacion();
              llenarConciliacion(response);
              calcularSubtotales();
            } else {
              $("#mensaje-cliente").html(
                '<div class="alert alert-danger" role="alert">' +
                  response.mensajeConciliacion +
                  "</div>"
              );
              vaciarCamposConciliacion();
              vaciarLabels();
              $("#inputSaldoBanco").attr("disabled", "disabled");
            }
          }
        } catch (error) {
          console.error("Error al analizar la respuesta JSON:", error);
          $("#mensaje-cliente").html(
            '<div class="alert alert-danger" role="alert">❗Error al analizar la respuesta del servidor</div>'
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al conectar con el servidor:", error);
        $("#mensaje-cliente").html(
          '<div class="alert alert-danger" role="alert">❌ Error al conectar con el servidor</div>'
        );
      },
    });
  } else {
    $("#mensaje-cliente").html(
      '<div class="alert alert-warning" role="alert">⚠️ Ingrese la fecha antes de realizar la conciliacion </div>'
    );
    $("#inputSaldoBanco").attr("disabled", "disabled");
  }
}

function grabarConciliacion(event) {
  event.preventDefault();

  let formData = new FormData(document.getElementById("form-conciliacion"));

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 200) {
        try {
          var response = JSON.parse(xhr.responseText);

          const errorContainer = document.getElementById("mensaje-cliente");

          if (response.success) {
            errorContainer.innerHTML =
              '<div class="alert alert-success" role="alert">' +
              response.mensaje +
              "</div>";
          } else {
            console.error("Error en el servidor:", response.error);
            errorContainer.innerHTML =
              '<div class="alert alert-danger" role="alert">' +
              response.error +
              "</div>";
          }
        } catch (e) {
          console.error("Error al analizar la respuesta del servidor:", e);
        }
      } else {
        console.error("Error en la solicitud: ", xhr.statusText);
      }
    }
  };
  xhr.open("POST", "logica_grabarConciliacion.php", true);
  xhr.send(formData);
}

// ! ======================================== 			Sección Asistencias 			==============================================
// Función para manejar el evento submit del formulario
function grabarArchivoAsistencia(e) {
  e.preventDefault();

  if (!e.target.checkValidity()) {
    e.target.classList.add("was-validated");
    return;
  }

  const okButton = document.getElementById("okButton");
  const form = document.getElementById("form-archivo");
  var formData = new FormData(e.target);
  var xhr = new XMLHttpRequest();

  xhr.open("POST", "logica_grabarArchivo.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      try {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
          var modalExito = new bootstrap.Modal(
            document.getElementById("modalExito")
          );
          modalExito.show();
          okButton.addEventListener("click", function () {
            form.reset();
            form.classList.remove("was-validated");
            modalExito.hide();
          });
        }
      } catch (e) {
        console.error("Error al analizar JSON:", e);
      }
    } else {
      console.error("Error en la solicitud:", xhr.status, xhr.statusText);
    }
  };
  xhr.onerror = function () {
    console.error("Error de red o servidor no disponible.");
  };
  xhr.send(formData);
}

function mostrarReporte(event) {
	event.preventDefault();

	var fechaInicio = document.getElementById('fecha-desde-input').value;
	var fechaFinal = document.getElementById('fecha-hasta-input').value;
	var codigoMarcacion = document.getElementById('inputNombres').value;

	if (Date.parse(fechaInicio) <= Date.parse(fechaFinal)) {
		var reporteUrl = `reporte.php?fecha-desde=${fechaInicio}&fecha-hasta=${fechaFinal}&codigo-marcacion=${codigoMarcacion}`;
		window.open(reporteUrl, '_blank');
	} else {
		mostrarModalError();
	}

	
}

function mostrarModalEsperar() {
	const okButton = document.getElementById("okButtonEsperar");
	var modalEsperando = new bootstrap.Modal(
		document.getElementById("modalEsperando")
	);
	modalEsperando.show();
	okButton.addEventListener("click", function () {
		modalEsperando.hide();
	});
}

function mostrarModalError(){
	const form = document.getElementById("form-reporte");
	const okButton = document.getElementById("okButtonError");
	var modalError = new bootstrap.Modal(
		document.getElementById("modalError")
	);
	modalError.show();
	okButton.addEventListener("click", function () {
		modalError.hide();
	});
}

// ! ========================================		Sección Funciones	Complementarias		==============================================

function resetMensaje() {
  const mensajeCliente = document.getElementById("mensaje-cliente");
  mensajeCliente.innerHTML = "";
  vaciarLabels();
}

// * Esta funcón llena los campos del formulario (Anulación y Circulación) con los datos recibidos.
function llenarCampos(response) {
  $("#fecha-input").val(response.fecha);
  $("#inputOrden").val(response.beneficiario);
  $("#inputMonto").val(response.monto);
  $("#inputDetalle").val(response.descripcion);
  $("#fecha-anulada").val(response.fecha_anulado);
}

// * Esta función llena los inputs con los valores del objeto response.
function llenarConciliacionRegistrada(response) {
  $("#inputSaldoLibro").val(response.saldo_anterior);
  $("#inputDeposito").val(response.mas_depositos);
  $("#inputChequesAnulados").val(response.mas_cheques_anulados);
  $("#inputNotasCredito").val(response.mas_notas_credito);
  $("#inputAjustesLibro").val(response.mas_ajustes_libro);
  $("#inputSubtotal").val(response.sub_primera);
  $("#inputSubtotalFinal").val(response.subtotal_primera);
  $("#inputCkGirados").val(response.menos_cheques_girados);
  $("#inputNotasDebito").val(response.menos_notas_debito);
  $("#inputAjusteCkGirados").val(response.menos_ajustes_libro);
  $("#inputSubtotalMenosLibros").val(response.sub_segunda);
  $("#inputSaldoConsiliadoLibros").val(response.saldo_libros);
  $("#inputSaldoBanco").val(response.saldo_banco);
  $("#inputDepositoTransito").val(response.mas_depositos_transito);
  $("#inputChequesCirculacion").val(response.menos_cheques_circulacion);
  $("#inputAjusteBanco").val(response.mas_ajustes_banco);
  $("#inputSubtotalMenosBanco").val(response.sub_tercero);
  let sub3 = parseFloat($("#inputSubtotalMenosBanco").val());
  let sub3Formateado = formatearNumeroNegativo(sub3);
  parseFloat($("#inputSubtotalMenosBanco").val(sub3Formateado));
  $("#inputSaldoConsiliadoBanco").val(response.saldo_conciliado);
}

function llenarLabels(response) {
  // Obtener fechas de las variables de respuesta
  var dia = response.dia;
  var mes_libro = response.mes_libro;
  var dia_actual = response.dia_actual;
  var mes_actual = response.mes_actual;
  var anio_anterior = response.anio_anterior;
  var anio = response.anio;

  // Desplegar las fechas en los labels correspondientes
  $("#labelSaldoLibro").html(
    `<strong>SALDO SEGÚN LIBRO AL ${dia} de ${mes_libro} de ${anio_anterior}</strong>`
  );
  $("#labelSaldoConsiliadoLibros").html(
    `<strong>SALDO CONCILIADO SEGÚN LIBROS AL ${dia_actual} de ${mes_actual} de ${anio}</strong>`
  );
  $("#labelSaldoBanco").html(
    `<strong>SALDO EN BANCO AL ${dia_actual} de ${mes_actual} de ${anio}</strong>`
  );
  $("#labelSaldoConsiliadoBanco").html(
    `<strong>SALDO CONCILIADO IGUAL A BANCO AL ${dia_actual} de ${mes_actual} de ${anio}</strong>`
  );
}

function vaciarLabels() {
  // Vaciar los labels seleccionando cada uno por su ID
  $("#labelSaldoLibro").html(`<strong>SALDO SEGÚN LIBRO AL</strong>`);
  $("#labelSaldoConsiliadoLibros").html(
    `<strong>SALDO CONCILIADO SEGÚN LIBROS AL</strong>`
  );
  $("#labelSaldoBanco").html(`<strong>SALDO EN BANCO AL</strong>`);
  $("#labelSaldoConsiliadoBanco").html(
    `<strong>SALDO CONCILIADO IGUAL A BANCO AL</strong>`
  );
}

function vaciarCamposConciliacion() {
  // Vaciar los campos de entrada seleccionando cada uno por su ID y estableciendo su valor en una cadena vacía ('')
  $("#inputSaldoLibro").val("");
  $("#inputDeposito").val("");
  $("#inputChequesAnulados").val("");
  $("#inputNotasCredito").val("");
  $("#inputAjustesLibro").val("");
  $("#inputSubtotal").val("");
  $("#inputSubtotalFinal").val("");
  $("#inputCkGirados").val("");
  $("#inputNotasDebito").val("");
  $("#inputAjusteCkGirados").val("");
  $("#inputSubtotalMenosLibros").val("");
  $("#inputSaldoConsiliadoLibros").val("");
  $("#inputSaldoBanco").val("");
  $("#inputDepositoTransito").val("");
  $("#inputChequesCirculacion").val("");
  $("#inputAjusteBanco").val("");
  $("#inputSubtotalMenosBanco").val("");
  $("#inputSaldoConsiliadoBanco").val("");
}

function llenarConciliacion(response) {
  $("#inputSaldoLibro").val(response.saldo_anterior);
  $("#inputDeposito").val(response.mas_depositos);
  $("#inputChequesAnulados").val(response.mas_cheques_anulados);
  $("#inputNotasCredito").val(response.mas_notas_credito);
  $("#inputAjustesLibro").val(response.mas_ajustes_libro);
  $("#inputCkGirados").val(response.menos_cheques_girados);
  $("#inputNotasDebito").val(response.menos_notas_debito);
  $("#inputAjusteCkGirados").val(response.menos_ajustes_libro);
  $("#inputDepositoTransito").val(response.mas_depositos_transito);
  $("#inputChequesCirculacion").val(response.menos_cheques_circulacion);
  $("#inputAjusteBanco").val(response.mas_ajustes_banco);
}

function calcularSubtotales() {
  // Obtener saldo conciliado
  let saldo_conciliado_anterior = parseFloat($("#inputSaldoLibro").val());

  // Sección 1
  let input_deposito = parseFloat($("#inputDeposito").val());
  let input_cks_anulado = parseFloat($("#inputChequesAnulados").val());
  let input_notas_credito = parseFloat($("#inputNotasCredito").val());
  let input_ajuste_libro = parseFloat($("#inputAjustesLibro").val());
  let sub1 =
    input_deposito +
    input_cks_anulado +
    input_notas_credito +
    input_ajuste_libro;
  // Redondea a dos decimales
  sub1 = sub1.toFixed(2);
  $("#inputSubtotal").val(sub1);

  let subtotal_primera = saldo_conciliado_anterior + parseFloat(sub1);
  // Redondea a dos decimales
  subtotal_primera = subtotal_primera.toFixed(2);
  $("#inputSubtotalFinal").val(subtotal_primera);

  // Sección 2
  let input_cks_girados = parseFloat($("#inputCkGirados").val());
  let input_notas_debito = parseFloat($("#inputNotasDebito").val());
  let input_ajuste_cks_girados = parseFloat($("#inputAjusteCkGirados").val());
  let sub2 = input_cks_girados + input_notas_debito + input_ajuste_cks_girados;
  // Redondea a dos decimales
  sub2 = sub2.toFixed(2);
  $("#inputSubtotalMenosLibros").val(sub2);

  let subtotal_segunda = parseFloat(subtotal_primera) - parseFloat(sub2);
  // Redondea a dos decimales
  subtotal_segunda = subtotal_segunda.toFixed(2);
  $("#inputSaldoConsiliadoLibros").val(subtotal_segunda);

  // Sección 3
  let input_deposito_transito = parseFloat($("#inputDepositoTransito").val());
  let input_cks_circulacion = parseFloat($("#inputChequesCirculacion").val());
  let input_ajuste_banco = parseFloat($("#inputAjusteBanco").val());

  let sub3 =
    input_deposito_transito - input_cks_circulacion + input_ajuste_banco;
  // Redondea a dos decimales
  sub3 = sub3.toFixed(2);
  // Aplica el formateo de número negativo
  let sub3Formateado = formatearNumeroNegativo(sub3);
  $("#inputSubtotalMenosBanco").val(sub3Formateado);

  let input_saldo_banco = 0;
  document
    .getElementById("inputSaldoBanco")
    .addEventListener("blur", function () {
      input_saldo_banco = parseFloat($("#inputSaldoBanco").val());
      let sub_tercero = parseFloat(sub3 + input_saldo_banco).toFixed(2);

      $("#inputSaldoConsiliadoBanco").val(sub_tercero);
      if (isNaN($("#inputSaldoConsiliadoBanco").val())) {
        $("#inputSaldoConsiliadoBanco").val(0);
      }
    });
}

function formatearNumeroNegativo(valor) {
  // Convertir el valor a número
  let numero = parseFloat(valor);

  // Si el número es negativo, devolverlo entre paréntesis
  if (numero < 0) {
    return `(${Math.abs(numero).toFixed(2)})`;
  }
  // Si no es negativo, devolver el número normalmente
  return numero.toFixed(2);
}
