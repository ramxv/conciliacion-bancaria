<?php

require "../php/db_computo.php";

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["cedula"]) || empty($_POST["password"])) {
    $error = "Por favor, completa todos los campos";
  } elseif (strlen($_POST["cedula"]) > 11) {
    $error = "Cédula no encontrada. Verifica los datos ingresados.";
  } else {
    $statement = $conn->prepare("SELECT * FROM usuario WHERE cedula = :cedula AND password = :password");
    $statement->bindParam(":cedula", $_POST["cedula"]);
    $statement->bindParam(":password", $_POST["password"]);
    $statement->execute();

    if ($statement->rowCount() == 0) {
      $error = "Credenciales inválidas. Verifica e inténtalo nuevamente.";
    } else {
      header("Location: inicio.php");
    }
  }
}
?>

<?php require "../includes/header.php" ?>

<div class="container p-3 container-custom">
  <form class="rounded" action="login.php" method="post">
    <div class="row">
      <div class="col">
        <img src="../assets/images/login/login-logo.png" class="img-fluid rounded-start" alt="Logo de la Empresa">
      </div>
      <div class="col d-flex align-items-center justify-content-center">
        <div class="w-75">
          <h1 class="text-center pb-3">Iniciar Sesión</h1>
          <?php if ($error) : ?>
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <div class="form-floating mb-3">
            <input type="cedula" class="form-control" id="floatingInput" placeholder="8-888-8888" name="cedula" autocomplete="off" onkeypress="return cedulaVal(event)">
            <label for="floatingInput">Cédula</label>
          </div>
          <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Contraseña" name="password">
            <label for="floatingPassword">Contraseña</label>
          </div>

          <div class="pt-4">
            <button type="submit" class="btn d-block mx-auto" id="btn-custom">Acceder</button>
          </div>

        </div>
      </div>
    </div>
  </form>
</div>

<?php require "../includes/footer.php" ?>
