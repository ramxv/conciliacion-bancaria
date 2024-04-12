<?php require "../php/db_conciliacion.php" ?>

<?php

// Consulta SQL para desplegar el nombre del beneficiario
$proveedores = $conn->query("SELECT * FROM proveedores");

$numero_cheque = "";
$fecha = "";
$beneficiario = "";
$monto = "";
$descripcion = "";
$fecha_anulado = "";
$detalle_anulado = "";
$error = null;
$correcto = null;

// Manejo del formulario de anulación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $default_date = "0000-00-00";

  if (isset($_POST["numeroCheque"]) && isset($_POST['anular'])) {
    $numero_cheque = $_POST["numeroCheque"];
    $fecha_anulado = $_POST["fechaAnulado"];
    $detalle_anulado = $_POST["detalle_anulado"];

    try {
      // Actualizar la base de datos
      $stmt = $conn->prepare("UPDATE cheques SET fecha_anulado = :fecha_anulado, detalle_anulado = :detalle_anulado WHERE numero_cheque = :numero_cheque");
      $stmt->bindParam(':fecha_anulado', $fecha_anulado);
      $stmt->bindParam(':detalle_anulado', $detalle_anulado);
      $stmt->bindParam(':numero_cheque', $numero_cheque);

      if ($stmt->execute()) {
        $correcto = "El cheque se ha anulado correctamente.";
      } else {
        $error = "Error al anular el cheque.";
      }
    } catch(PDOException $e) {
      $error = "Error al anular el cheque: " . $e->getMessage();
    }
  }
}

// Manejo del formulario principal
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['anular'])) {
  if (isset($_POST["numeroCheque"])) {
    $numero_cheque = $_POST["numeroCheque"];
    $statement = $conn->prepare("SELECT * FROM cheques WHERE numero_cheque = :numero_cheque");
    $statement->bindParam(':numero_cheque', $numero_cheque);
    $statement->execute();

    if ($statement->rowCount() > 0) {
      $ckRow = $statement->fetch(PDO::FETCH_ASSOC);
      $fecha = $ckRow["fecha"];
      $beneficiario = $ckRow["beneficiario"];
      $monto = $ckRow["monto"];
      $descripcion = $ckRow["descripcion"];

      // Obtener la fecha de circulación
      $row_circulacion = $ckRow["fecha_circulacion"];
      if (is_null($row_circulacion) || $row_circulacion == $default_date) {
        $correcto = "✅ El cheque es válido.";
      } else {
        $error = "❗El cheque está fuera de circulación";
      }

      // Obtener la fecha de anulación
      $row_anulado = $ckRow["fecha_anulado"];
      if (is_null($row_anulado) || $row_anulado == $default_date) {
        $correcto = "✅ El cheque es válido.";
      } else {
        $error = "❗El cheque está anulado";
        $fecha_anulado = $row_anulado;
      }
    } else {
      $error = "❌ El número de cheque no existe.";
    }
  }
}

?>

<?php require "../includes/header.php" ?>

<div class="container">
  <form action="" class="border border-secondary-subtle rounded" method="POST">
    <h2 class="form-header p-3 border-bottom border-secondary-subtle">Anulación de Cheque</h2>
    <div class="row p-4">
      <!-- Mensaje de error al usuario -->
      <?php if ($error): ?>
      <div class="error-container alert alert-danger" role="alert"><?= $error ?></div>
      <?php elseif ($correcto): ?>
      <div class="correcto-container alert alert-success" role="alert"><?= $correcto ?></div>
      <?php endif ?>
      <!-- Contenedor de Anulación Cheque -->
      <div class="col p-3">
        <div class="anul-container">
          <!-- Sección buscar cheque -->
          <div class="row justify-content-end align-items-end">
            <div class="col-4">
              <label for="num-cheque-input" class="form-label">No.Cheque</label>
              <input type="text" class="form-control" autocomplete="off" name="numeroCheque" id="num-cheque-input" value="<?= $numero_cheque ?>" onkeypress="return soloNumeros(event)">
            </div>
            <div class="col-4" id="btn-custom-container">
              <button type="submit" class="btn button-custom" id="btn-buscar" name="buscar">Buscar</button>
            </div>
          </div>
          <!-- Sección insertar datos desde la BD -->
          <div class="col-4">
            <label for="fecha-input" class="form-label">Fecha</label>
            <input type="date" name="date" id="fecha-input" class="form-control" value="<?= $fecha ?>" disabled>
          </div>
          <div class="col-12 pt-2">
            <label for="inputOrden" class="form-label">Páguese a la orden de</label>
            <select class="form-select" id="inputOrden" disabled>
              <?php if($proveedores->rowCount() > 0):?>
                <?php while ($consultaProveedores = $proveedores->fetch(PDO::FETCH_ASSOC)): 
                  $codigo = $consultaProveedores["codigo"];
                  $nombre = $consultaProveedores["nombre"];
                ?>
                  <?php if($codigo == $beneficiario):?>
                    <option><?= $nombre ?></option>
                  <?php endif ?>
                <?php endwhile ?>
              <?php endif ?>
            </select>
          </div>
          <div class="col-4 pt-2">
            <label for="inputMonto" class="form-label">La suma de</label>
            <div class="input-group">
              <input type="text" aria-label="First name" class="form-control" id="inputMonto" placeholder="$" value="<?= $monto ?>" disabled>
            </div>
          </div>
          <div class="col-12 pt-2">
            <label for="inputDetalle" class="form-label">Detalle</label>
            <input type="text" class="form-control" id="inputDetalle" placeholder="" disabled value="<?= $descripcion ?>">
          </div>
        </div>
      </div>
      <div class="col p-3">
        <div class="col-4">
          <label for="fecha-input" class="form-label">Fecha de Anulación</label>
          <?php if($error): ?>
            <input type="date" name="fechaAnulado" id="fecha-anulada" class="form-control" value="<?= $fecha_anulado ?>" disabled> 
          <?php else: ?>
            <input type="date" name="fechaAnulado" id="fecha-anulada" class="form-control">
          <?php endif ?>
        </div>
        <div class="col-12 pt-2">
          <label for="floatingTextarea2" class="form-label">Detalle de la Anulación</label>
          <?php if($error): ?>
            <textarea class="form-control" placeholder="" id="floatingTextarea2" name="detalle_anulado" style="height: 100px" disabled></textarea>
          <?php else: ?>
            <textarea class="form-control" placeholder="" id="floatingTextarea2" name="detalle_anulado" style="height: 100px"></textarea>
          <?php endif ?>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-center pt-5" id="btn-custom-container">
          <button type="submit" class="btn button-custom" id="btn-anular" name="anular">Anular</button>
        </div>
      </div>
    </div>
  </form>
</div>

<?php require "../includes/footer.php" ?>
