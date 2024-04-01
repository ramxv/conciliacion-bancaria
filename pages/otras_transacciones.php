<?php require "../includes/header.php" ?>

<?php require "../php/db_conciliacion.php";

$consultaTransacciones = $conn->query("SELECT * FROM transacciones");
?>

<div class="container w-50">
  <form action="" class="border border-secondary-subtle rounded">
    <h3 class="form-header p-3 border-bottom border-secondary-subtle ">Otras Transacciones - Depósitos, Ajustes y Notas (Db / Cr)</h3>
    <div class="row justify-content-center p-3">
      <div class="col-4">
        <label for="transacciones" class="form-label">Transacciones</label>
        <select class="form-select" aria-label="Transacciones">
          <option value=""></option>
          <?php while ($row = $consultaTransacciones->fetch(PDO::FETCH_ASSOC)) : ?>
            <option value="<?= $row["codigo"] ?>"> <?= $row["detalle"] ?> </option>
          <?php endwhile ?>
        </select>
      </div>
    </div>
    <div class="row p-3">
      <div class="col-4">
        <label for="fecha-input" class="form-label">Fecha</label>
        <input type="date" name="date" id="fecha-input" class="form-control">
      </div>
      <div class="col-4">
        <label for="inputMonto" class="form-label">Monto</label>
        <input type="text" class="form-control" id="inputMonto" placeholder="" onkeypress="return soloDecimal(event)">
      </div>
    </div>
    <div class="d-flex gap-2 justify-content-md-center p-3">
      <button type="submit" class="btn" id="btn-custom">Grabar</button>
      <button type="submit" class="btn" id="btn-custom">Nuevo</button>
    </div>
  </form>
</div>

<?php require "../includes/footer.php" ?>
