<nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom">
  <div class="container-fluid">
    <a class="navbar-brand" href="#" onclick="loadPage('inicio.php')">
      <img src="../assets/images/navbar.png" alt="Horizon" class="d-inline-block align-text-center img-custom">
      <span class="text-white">Horizon</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item pe-4" id="nav-item-custom">
          <a class="nav-link text-white" href="#" onclick="loadPage('inicio.php')">Inicio</a>
        </li>
        <li class="nav-item pe-4" id="nav-item-custom">
          <a class="nav-link text-white" href="#" onclick="loadPage('cheques.php')">Cheques</a>
        </li>
        <li class="nav-item dropdown pe-4" id="nav-item-custom">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            OperacionesCks
          </a>
          <ul class="dropdown-menu pe-4" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="#" onclick="loadPage('anulacion.php')">Anulación</a></li>
            <li><a class="dropdown-item" href="#" onclick="loadPage('ck_circulacion.php')">Sacar de circulación</a></li>
          </ul>
        </li>
        <li class="nav-item pe-4">
          <a class="nav-link text-white" href="#" onclick="loadPage('otras_transacciones.php')">Otras Transacciones</a>
        </li>
        <li class="nav-item pe-4" id="nav-item-custom">
          <a class="nav-link text-white" href="#" onclick="loadPage('conciliacion.php')">Conciliación</a>
        </li>
        <li class="nav-item pe-4" id="nav-item-custom">
          <a class="nav-link text-white" href="#">Reportes</a>
        </li>
        <li class="nav-item pe-4" id="nav-item-custom">
          <a class="nav-link text-white" href="#">Mantenimiento</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
