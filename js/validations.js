// Validacion de número de cheque
$(document).ready(function () {
    $("#numCkInput").on("blur", function (event) {

        event.preventDefault();

        var numCheque = $(this).val();

        if (numCheque.trim() !== '') {
            $.ajax({
                url: 'cheques.php',
                type: 'POST',
                data: { numCheque: numCheque },
                success: function (response) {
                    if (response.includes('registrada')) {
                        $('.error-container').html('<div class="alert alert-danger" role="alert">Error: El número de cheque ya existe.</div>');
                        disableFields();
                    } else {
                        $('.error-container').html('<div class="alert alert-success" role="alert">El número de cheque es válido.</div>');
                        enableFields();
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    $('.error-container').html('<div class="alert alert-danger" role="alert">Error al conectar con el servidor</div>');
                }
            });
        } else {
            $('.error-container').html('<div class="alert alert-danger" role="alert">Error: número de cheque no recibido.</div>');
        }
    });
});

// Validación de montos

$(document).ready(function () {
    $("form").submit(function (event) {

        event.preventDefault();

        $.ajax({
            url: 'cheques.php',
            type: 'POST',
            data: $(this).serialize(), // Serializa los datos del formulario
            success: function (response) {
                if (response.includes('diferente')) {
                    $('.error-container').html('<div class="alert alert-danger" role="alert">Error: La suma de y Monto no coinciden.</div>');
                    $('#fecha-input').attr('disabled', 'disabled');
                    $('#inputOrden').attr('disabled', 'disabled');
                    $('#input-monto').empty();
                    $('#inputDetalle').attr('disabled', 'disabled');
                    $('#inputObjeto').attr('disabled', 'disabled');
                    $('#inputMonto').empty();
                } else if (response.includes('vacio')) {
                    $('.error-container').html('<div class="alert alert-danger" role="alert">Llene todos los campos.</div>');
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                $('.error-container').html('<div class="alert alert-danger" role="alert">Error al conectar con el servidor</div>');
            }
        });
    });
});


// Función para deshabilitar campos
function disableFields() {
    $('#fecha-input').attr('disabled', 'disabled');
    $('#inputOrden').attr('disabled', 'disabled');
    $('#input-monto').attr('disabled', 'disabled');
    $('#inputDetalle').attr('disabled', 'disabled');
    $('#inputObjeto').attr('disabled', 'disabled');
    $('#inputMonto').attr('disabled', 'disabled');
}

// Función para habilitar campos
function enableFields() {
    $('#fecha-input').removeAttr('disabled');
    $('#inputOrden').removeAttr('disabled');
    $('#input-monto').removeAttr('disabled');
    $('#inputDetalle').removeAttr('disabled');
    $('#inputObjeto').removeAttr('disabled');
    $('#inputMonto').removeAttr('disabled');
}
