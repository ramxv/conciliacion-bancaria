<?php require "../includes/header.php" ?>

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
            <li><a class="dropdown-item" href="#">Sacar de circulación</a></li>
            <li><a class="dropdown-item" href="#">Reintegro</a></li>
          </ul>
        </li>
        <li class="nav-item pe-4">
          <a class="nav-link text-white" href="#">Otras Transacciones</a>
        </li>
        <li class="nav-item pe-4" id="nav-item-custom">
          <a class="nav-link text-white" href="#">Consolidación</a>
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

<div id="contenido">
  <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active object-fit-contain">
        <img src="../assets/images/slide/img1.jpg" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="../assets/images/slide/img2.jpg" class="d-block w-100" alt="...">
      </div>
      <div class="carousel-item">
        <img src="../assets/images/slide/img3.jpg" class="d-block w-100" alt="...">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>

<script src="../js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<?php require "../includes/footer.php" ?>
