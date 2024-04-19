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
			header("Location: inicio.html");
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Metadatos -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Estilos CSS -->
	<link rel="stylesheet" href="../css/style.css">

	<!-- Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<!-- JavaScript personalizado -->
	<script defer src="script.js"></script>
	<!-- Título de la página -->
	<title>Horizon</title>
</head>

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

					<div class="pt-4" id="btn-custom-container">
						<button type="submit" class="btn d-block mx-auto button-custom">Acceder</button>
					</div>

				</div>
			</div>
		</div>
	</form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>
