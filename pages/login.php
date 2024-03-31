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
    $statement -> bindParam(":cedula", $_POST["cedula"]);
    $statement -> bindParam(":password", $_POST["password"]);
    $statement -> execute();

    if ($statement->rowCount() > 0) {
      header("Location: inicio.php");
    } else {
      $error = "Cédula o contraseña incorrecta. Verifica e inténtalo nuevamente.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Horizon</title>
</head>

<body>
  <div class="container p-3 container-custom">
    <form class="rounded" action="login.php" method="post">
      <div class="row">
        <div class="col">
          <img src="../assets/images/login/login-logo.png" class="img-fluid rounded-start" alt="Logo de la Empresa">
        </div>
        <div class="col d-flex align-items-center justify-content-center">
          <div class="w-75">
            <h1 class="text-center pb-3">Iniciar Sesión</h1>
            <p> variable </p>
            <?php if ($error) : ?>
              <p class="text-danger">
                <?= $error ?>
              </p>
            <?php endif ?>
            <div class="form-floating mb-3">
              <input type="cedula" class="form-control" id="floatingInput" placeholder="8-888-8888" name="cedula" autocomplete="off">
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
</body>

</html>
