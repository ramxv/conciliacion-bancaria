<?php require "../php/db_conciliacion.php";

$queryTransaccionesLibros = $conn->query("SELECT * FROM transacciones LIMIT 5");
$queryTransaccionesBanco = $conn->query("SELECT * FROM transacciones LIMIT 2 OFFSET 5");
$queryTransaccionesTransferencia = $conn->query("SELECT * FROM transacciones LIMIT 2 OFFSET 7");

$warning = null;
$error = null;
$correcto = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $transaccion = $_POST["transaccion"];
  $fecha_transaccion = $_POST["fecha_transaccion"];
  $monto_transaccion = $_POST["monto_transaccion"];
  if (empty($transaccion) || empty($fecha_transaccion) || empty($monto_transaccion)) {
    $warning = "⚠️ Llene todos los campos";
  } else {
    $correcto = "✅ Se registro exitosamente la transacción";
    $statement = $conn->prepare("INSERT INTO otros (transaccion, fecha, monto) VALUES (:transaccion, :fecha_transaccion, :monto_transaccion)");
    $statement->bindParam(':transaccion', $transaccion);
    $statement->bindParam(':fecha_transaccion', $fecha_transaccion);
    $statement->bindParam(':monto_transaccion', $monto_transaccion);
    try {
      $statement->execute();
      $correcto = "✅ Se registro exitosamente la transacción.";
    } catch (PDOException $e) {
      $error = "❌ Error al registrar transacción: " . $e->getMessage();
    }
  }
}

?>

<?php require "../includes/header.php" ?>

<div class="container w-50">
  <form action="" class="border border-secondary-subtle rounded" method="post">
    <h3 class="form-header p-3 border-bottom border-secondary-subtle ">Otras Transacciones - Depósitos, Ajustes y Notas (Db / Cr)</h3>
    <div class="row justify-content-center p-3">
      <?php if ($warning) : ?>
        <div class="p-3">
          <div class="warning-container alert alert-warning" role="alert"><?= $warning ?></div>
        </div>
      <?php elseif ($correcto) : ?>
        <div class="p-3">
          <div class="correcto-container alert alert-success" role="alert"><?= $correcto ?></div>
        </div>
      <?php endif ?>
      <div class="col-4">
        <label for="transacciones" class="form-label">Transacciones</label>
        <select class="form-select" aria-label="Transacciones" id="transacciones" name="transaccion">
          <option value=""></option>
          <optgroup label="LIBROS">
            <?php while ($row = $queryTransaccionesLibros->fetch(PDO::FETCH_ASSOC)) : ?>
              <option value="<?= $row["codigo"] ?>"> <?= $row["detalle"] ?> </option>
            <?php endwhile ?>
          </optgroup>
          <optgroup label="BANCO">
            <?php while ($row = $queryTransaccionesBanco->fetch(PDO::FETCH_ASSOC)) : ?>
              <option value="<?= $row["codigo"] ?>"> <?= $row["detalle"] ?> </option>
            <?php endwhile ?>
          </optgroup>
          <optgroup label="TRANSFERENCIA">
            <?php while ($row = $queryTransaccionesTransferencia->fetch(PDO::FETCH_ASSOC)) : ?>
              <option value="<?= $row["codigo"] ?>"> <?= $row["detalle"] ?> </option>
            <?php endwhile ?>
          </optgroup>
        </select>
      </div>
    </div>
    <div class="row p-3">
      <div class="col-4">
        <label for="fecha-input" class="form-label">Fecha</label>
        <input type="date" name="fecha_transaccion" id="fecha-input" class="form-control">
      </div>
      <div class="col-4">
        <label for="inputMonto" class="form-label">Monto</label>
        <input type="text" class="form-control" name="monto_transaccion" id="inputMonto" placeholder="" onkeypress="return soloDecimal(event)">
      </div>
    </div>
    <div class="d-flex gap-2 justify-content-md-center p-3" id="btn-custom-container">
      <button type="submit" class="btn button-custom" id="btn-custom">Grabar</button>
      <button type="reset" class="btn button-custom" id="btn-custom">Nuevo</button>
    </div>
  </form>
</div>

<?php require "../includes/footer.php" ?>
