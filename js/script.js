// FUNCIÓN PARA CARGAR PÁGINAS DESDE EL INICIO
function loadPage(page) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById('contenido').innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", page, true);
    xhttp.send();
}

// * Función para restringir números en campos de nombre
function soloLetras(evento) {
    var code = (evento.which) ? evento.which : evento.keycode;
    if (code == 8 || code == 32) {
        return true;
    } else if (code >= 65 && code <= 90 || code >= 97 && code <= 122) {
        return true;
    } else {
        return false;
    }
}

// * Función para restringir letras de campos números
function soloNumeros(evento) {
    var code = (evento.which) ? evento.which : evento.keycode;
    if (code == 8) {
        return true;
    } else if (code >= 48 && code <= 57) {
        return true;
    } else {
        return false;
    }
}

// * Función para validar el campo de cedula
function cedulaVal(evento) {
  var code = (evento.which) ? evento.which : evento.keycode;
  if (code == 8) {
      return true;
  } else if (code == 45 || code >= 48 && code <= 57) {
      return true;
  } else {
      return false;
  }
}

// * Función acepta punto decimal en los campos de decimales
function soloDecimal(evento) {
    var code = (evento.which) ? evento.which : evento.keycode;
    if (code == 8) {
        return true;
    } else if (code == 46 || code >= 48 && code <= 57) {
        return true;
    } else {
        return false;
    }
}
