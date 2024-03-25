// FUNCIÓN PARA CARGAR PÁGINAS DESDE EL INICIO
function loadPage(page) {
    fetch(page)
        .then(response => response.text())
        .then(html => {
            document.getElementById('contenido').innerHTML = html;
        })
        .catch(error => console.error('Error al cargar la página:', error));
}