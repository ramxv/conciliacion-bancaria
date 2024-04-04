// FUNCIÓN PARA CARGAR PÁGINAS DESDE EL INICIO
function loadPage(page) {
    fetch(page)
        .then(response => response.text())
        .then(html => {
            document.getElementById('contenido').innerHTML = html;
        })
        .catch(error => console.error('Error al cargar la página:', error));
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


function numeroALetras(numero) {
    const unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
    const especiales = ['diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve'];
    const decenas = ['', '', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
    const centenas = ['', 'cien', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];
    const miles = ['', 'mil', 'millón'];

    let letras = '';

    if (numero >= 1000000) {
        letras += numeroALetras(Math.floor(numero / 1000000)) + ' ' + miles[2] + ' ';
        numero %= 1000000;
    }
    if (numero >= 1000) {
        if (numero >= 1000 && numero <= 1999) {
            letras += 'mil ';
        } else if (numero >= 2000 && numero <= 9999) {
            letras += numeroALetras(Math.floor(numero / 1000)) + ' ' + miles[1] + ' ';
        } else {
            letras += numeroALetras(Math.floor(numero / 1000)) + ' ' + miles[1] + ' ';
        }
        numero %= 1000;
    }
    if (numero >= 100) {
        if (numero === 100) {
            letras += 'cien ';
        } else {
            letras += centenas[Math.floor(numero / 100)] + ' ';
        }
        numero %= 100;
    }
    if (numero >= 10 && numero <= 19) {
        letras += especiales[numero - 10];
        numero = 0; 
    } else if (numero >= 10) {
        letras += decenas[Math.floor(numero / 10)] + ' ';
        numero %= 10;
    }
    if (numero > 0) {
        letras += unidades[numero];
    }

    return letras.trim(); 
}
function mostrarMontoEnLetras() {
    var monto = document.getElementById("input-monto").value;
    var parteEntera = Math.floor(monto);
    var parteDecimal = Math.round((monto - parteEntera) * 100);
    var montoEnLetras = numeroALetras(parteEntera) + ' balboas con ' + (parteDecimal < 10 ? '0' : '') + parteDecimal + '/100';
    document.getElementById("input-monto-letras").value = montoEnLetras;
}