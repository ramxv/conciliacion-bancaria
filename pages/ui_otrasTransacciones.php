<?php require "../php/db_conciliacion.php"; ?>
<?php require "logica_otrasTransacciones.php"; ?>

<div class="container w-50">
  <form class="border border-secondary-subtle rounded" method="post" id="otras-transacciones-form" onsubmit="grabarOtrasTransacciones(event)">
    <h3 class="form-header p-3 border-bottom border-secondary-subtle ">Otras Transacciones - Depósitos, Ajustes y Notas (Db / Cr)</h3>
    <div class="row justify-content-center p-3">
		<div class="error-container" id="mensaje-cliente"></div>
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
      <button type="reset" class="btn button-custom" id="btn-custom" onclick="resetMensaje()">Nuevo</button>
    </div>
  </form>
</div>
