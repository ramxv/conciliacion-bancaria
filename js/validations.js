// Espera a que el documento esté completamente cargado
$(document).ready(function(){
  // Adjunta un evento de submit al formulario
  $("#numCkInput").on("blur",function(event){
      // Evita que el formulario se envíe de manera predeterminada
      event.preventDefault();
      
      // Realiza una petición AJAX al servidor
      $.ajax({
          url: 'cheques.php', // Reemplaza 'tu_script_php.php' con la ruta correcta a tu script PHP
          type: 'POST',
          data: $(this).serialize(), // Serializa los datos del formulario
          success: function(response){ // Función que se ejecuta si la petición es exitosa
              
              // Verifica si la respuesta contiene el mensaje 'registrada'
              if(response.includes('registrada')){
                  // Muestra un mensaje de éxito en la pantalla
                  $('.error-container').html('<div class="alert alert-danger" role="alert">Error: El número de cheque ya existe.</div>');
                  $('#fecha-input').attr('disabled','disabled');
                  $('#inputOrden').attr('disabled','disabled');
                  $('#input-monto').attr('disabled','disabled');
                  $('#inputDetalle').attr('disabled','disabled');
                  $('#inputObjeto').attr('disabled','disabled');
                  $('#inputMonto').attr('disabled','disabled');
              }
              if(response.includes('vacio')){
                // Muestra un mensaje de éxito en la pantalla
                $('.error-container').html('<div class="alert alert-danger" role="alert">Error: número de cheque no recibido.</div>');
                $('#fecha-input').attr('disabled','disabled');
                $('#inputOrden').attr('disabled','disabled');
                $('#input-monto').attr('disabled','disabled');
                $('#inputDetalle').attr('disabled','disabled');
                $('#inputObjeto').attr('disabled','disabled');
                $('#inputMonto').attr('disabled','disabled'); 
              }else {
                  // Muestra un mensaje de error en la pantalla
                  $('.error-container').html('<div class="alert alert-success" role="alert">El número de cheque es válido.</div>');
                  $('#fecha-input').removeAttr('disabled');
                  $('#inputOrden').removeAttr('disabled');
                  $('#input-monto').removeAttr('disabled');
                  $('#inputDetalle').removeAttr('disabled');
                  $('#inputObjeto').removeAttr('disabled');
                  $('#inputMonto').removeAttr('disabled');
              }
          },
          error: function(xhr, status, error){ // Función que se ejecuta si hay un error en la petición
              // Muestra un mensaje de error en la consola para fines de depuración
              console.error(error);
              
              // Muestra un mensaje de error en la pantalla
              $('.error-container').html('<div class="alert alert-danger" role="alert">Error al conectar con el servidor</div>');
          }
      });
  });
});

$(document).ready(function(){
  // Adjunta un evento de submit al formulario
  $("form").submit(function(event){
      // Evita que el formulario se envíe de manera predeterminada
      event.preventDefault();
      
      // Realiza una petición AJAX al servidor
      $.ajax({
          url: 'cheques.php', // Reemplaza 'tu_script_php.php' con la ruta correcta a tu script PHP
          type: 'POST',
          data: $(this).serialize(), // Serializa los datos del formulario
          success: function(response){ // Función que se ejecuta si la petición es exitosa
              
              // Verifica si la respuesta contiene el mensaje 'registrada'
              if(response.includes('diferente')){
                  // Muestra un mensaje de éxito en la pantalla
                  $('.error-container').html('<div class="alert alert-danger" role="alert">Error: La suma de y Monto no coinciden.</div>');
                  $('#fecha-input').attr('disabled','disabled');
                  $('#inputOrden').attr('disabled','disabled');
                  $('#input-monto').empty();
                  $('#inputDetalle').attr('disabled','disabled');
                  $('#inputObjeto').attr('disabled','disabled');
                  $('#inputMonto').empty();
              }
              if(response.includes('vacio')){
                // Muestra un mensaje de éxito en la pantalla
                $('.error-container').html('<div class="alert alert-danger" role="alert">Llene todos los campos.</div>');
            } else {
                  // Muestra un mensaje de error en la pantalla
                  $('.error-container').html('<div class="alert alert-success" role="alert">Los montos coinciden.</div>');
              }
          },
          error: function(xhr, status, error){ // Función que se ejecuta si hay un error en la petición
              // Muestra un mensaje de error en la consola para fines de depuración
              console.error(error);
              
              // Muestra un mensaje de error en la pantalla
              $('.error-container').html('<div class="alert alert-danger" role="alert">Error al conectar con el servidor</div>');
          }
      });
  });
});
