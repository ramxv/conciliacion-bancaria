<?php
require "../php/db_conciliacion.php";

$consultaProveedores = $conn->query("SELECT * FROM proveedores");
$consultaObjetoGasto = $conn->query("SELECT * FROM objeto_gasto");
?>

<?php require "../includes/header.php" ?>

<div class="container p-3">
  <form action="cheques.php" method="post" class="border border-secondary-subtle rounded">
    <h2 class="form-header p-3 border-bottom border-secondary-subtle">Creación</h2>
    <div class="row p-4">
      <div class="col p-3">
        <h3 class="form-header border border-secondary-subtle rounded p-3 cheque-title">Cheques</h3>
        <div class="ch-container">
          <div class="row justify-content-end">
            <div class="col-4">
              <label for="num-cheque-input" class="form-label">No.Cheque</label>
              <input type="text" class="form-control" id="num-cheque-input" onkeypress="return soloNumeros(event)">
            </div>
            <div class="col-4">
              <label for="fecha-input" class="form-label">Fecha</label>
              <input type="date" name="date" id="fecha-input" class="form-control">
            </div>
          </div>
          <div class="col-12 pt-2">
            <label for="inputOrden" class="form-label">Páguese a la orden de</label>

            <select class="form-select" id="inputOrden">
              <option value=""></option>
              <?php while ($row = $consultaProveedores->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?= $row["codigo"] ?>"> <?= $row["nombre"] ?> </option>
              <?php endwhile ?>
            </select>

          </div>
          <div class="col-12 pt-2">
            <label for="inputMonto" class="form-label">La suma de</label>
            <div class="input-group">
              <input type="text" aria-label="Monto" class="form-control" id="input-monto" placeholder="$" onkeypress="return soloDecimal(event)">
              <input type="text" aria-label="Monto en Letras" class="form-control w-50" id="input-monto-letras" disabled>
            </div>
          </div>
          <div class="col-12 pt-2">
            <label for="inputDetalle" class="form-label">Detalle</label>
            <input type="text" class="form-control" id="inputDetalle" placeholder="">
          </div>
        </div>
      </div>
      <div class="col p-3">
        <h3 class="form-header border border-secondary-subtle rounded p-3 og-title">Objetos de Gastos</h3>
        <div class="og-container">
          <div class="row">
            <div class="col-8">
              <label for="inputObjeto" class="form-label">Objeto</label>

              <select class="form-select" id="inputObjeto">
                <option value=""></option>
                <?php while ($row = $consultaObjetoGasto->fetch(PDO::FETCH_ASSOC)) : ?>
                  <option value="<?= $row["codigo"] ?>"> <?= $row["detalle"] ?> </option>
                <?php endwhile ?>
              </select>

            </div>
            <div class="col-4">
              <label for="inputMonto" class="form-label">Monto</label>
              <input type="text" class="form-control" id="inputMonto" placeholder="" onkeypress="return soloNumeros(event)">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="d-grid gap-5 d-md-flex justify-content-md-center pb-3">
      <button type="submit" class="btn" id="btn-custom">Grabar</button>
      <button type="submit" class="btn" id="btn-custom">Nuevo</button>
    </div>
  </form>
</div>
<?php require "../includes/footer.php" ?>
