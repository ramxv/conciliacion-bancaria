<?php
require "../php/db_conciliacion.php";

$consultaProveedores = $conn->query("SELECT * FROM proveedores");
$consultaObjetoGasto = $conn->query("SELECT * FROM objeto_gasto");

$error = null;
$correcto = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($_POST["monto"] != $_POST["montoObjeto"]) {
    $error = "Los montos ingresados no coinciden. Por favor, inténtalo de nuevo.";
  } elseif (
    empty($_POST["numCheque"]) || empty($_POST["fecha"]) || empty($_POST["beneficiario"]) ||
    empty($_POST["monto"]) || empty($_POST["detalle"]) || empty($_POST["objetoGasto"]) || empty($_POST["montoObjeto"])
  ) {
    $error = "Por favor, completa todos los campos.";
  } else {
    $numCheque = $_POST["numCheque"];
    $statement = $conn->prepare("SELECT * FROM cheques WHERE numero_cheque = :numCheque");
    $statement->bindParam(":numCheque", $_POST["numCheque"]);
    $statement->execute();
    if ($statement->rowCount() > 0) {
      $error = "El número de cheque ya está registrado.";
    } else {
      $conn
        ->prepare("INSERT INTO cheques (numero_cheque,fecha,beneficiario,monto,descripcion,codigo_objeto1,monto_objeto1) VALUES (:numCheque, :fecha, :beneficiario, :monto, :detalle, :objetoGasto, :montoObjeto)")
        ->execute([
          ":numCheque" => $_POST["numCheque"],
          ":fecha" => $_POST["fecha"],
          ":beneficiario" => $_POST["beneficiario"],
          ":monto" => $_POST["monto"],
          ":detalle" => $_POST["detalle"],
          ":objetoGasto" => $_POST["objetoGasto"],
          ":montoObjeto" => $_POST["montoObjeto"]
        ]);
        header("Location: inicio.php");
    }
  }
}

?>

<?php require "../includes/header.php" ?>

<div class="container p-3">
  <form action="inicio.php" method="POST" class="border border-secondary-subtle rounded">
    <h2 class="form-header p-3 border-bottom border-secondary-subtle">Creación</h2>
    <div class="row p-4">
      <?php if ($error) : ?>
        <p class="text-danger"> <strong> <?= $error ?> </strong> </p>
      <?php endif ?>
      <?php if ($correcto) : ?>
        <p class="text-success"> <strong> <?= $error ?> </strong> </p>
      <?php endif ?>
      <div class="col p-3">
        <h3 class="form-header border border-secondary-subtle rounded p-3 cheque-title">Cheques</h3>
        <div class="ch-container">
          <div class="row justify-content-end">
            <div class="col-4">
              <label for="num-cheque-input" class="form-label">No.Cheque</label>
              <input type="text" class="form-control" id="num-cheque-input" name="numCheque" onkeypress="return soloNumeros(event)" autocomplete="off">
            </div>
            <div class="col-4">
              <label for="fecha-input" class="form-label">Fecha</label>
              <input type="date" name="fecha" id="fecha-input" class="form-control">
            </div>
          </div>
          <div class="col-12 pt-2">
            <label for="inputOrden" class="form-label">Páguese a la orden de</label>

            <select class="form-select" id="inputOrden" name="beneficiario">
              <option value=""></option>
              <?php while ($row = $consultaProveedores->fetch(PDO::FETCH_ASSOC)) : ?>
                <option value="<?= $row["codigo"] ?>"> <?= $row["nombre"] ?> </option>
              <?php endwhile ?>
            </select>

          </div>
          <div class="col-12 pt-2">
            <label for="inputMonto" class="form-label">La suma de</label>
            <div class="input-group">
              <input type="text" aria-label="Monto" class="form-control" id="input-monto" placeholder="$" name="monto" onkeypress="return soloDecimal(event)">
              <input type="text" aria-label="Monto en Letras" class="form-control w-50" id="input-monto-letras" disabled>
            </div>
          </div>
          <div class="col-12 pt-2">
            <label for="inputDetalle" class="form-label">Detalle</label>
            <input type="text" class="form-control" id="inputDetalle" name="detalle" placeholder="">
          </div>
        </div>
      </div>
      <div class="col p-3">
        <h3 class="form-header border border-secondary-subtle rounded p-3 og-title">Objetos de Gastos</h3>
        <div class="og-container">
          <div class="row">
            <div class="col-8">
              <label for="inputObjeto" class="form-label">Objeto</label>
              <select class="form-select" id="inputObjeto" name="objetoGasto">
                <option value=""></option>
                <?php
                $labels = array(
                  "label1" => "SERVICIOS NO PERSONALES",
                  "label2" => "MATERIALES DE SUMINISTRO",
                  "label3" => "MAQUINARIA Y EQUIPOS"
                );
                foreach ($labels as $key => $label) :
                ?>
                  <optgroup label="<?= $label ?>">
                    <?php
                    $consultaObjetoGasto->execute();
                    while ($row = $consultaObjetoGasto->fetch(PDO::FETCH_ASSOC)) :
                      $codigo = $row["codigo"];
                      $objetoGasto = substr($codigo, 0, 1);
                      if ($objetoGasto == substr($key, -1)) :
                    ?>
                        <option value="<?= $row["codigo"] ?>"> <?= $row["detalle"] ?> </option>
                      <?php endif ?>
                    <?php endwhile ?>
                  </optgroup>
                <?php endforeach ?>
              </select>
            </div>
            <div class="col-4">
              <label for="inputMonto" class="form-label">Monto</label>
              <input type="text" class="form-control" id="inputMonto" placeholder="" name="montoObjeto" onkeypress="return soloNumeros(event)">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="d-grid gap-5 d-md-flex justify-content-md-center pb-3">
      <button type="submit" class="btn" id="btn-custom">Grabar</button>
      <button type="reset" class="btn" id="btn-custom">Nuevo</button>
    </div>
  </form>
</div>


<?php require "../includes/footer.php" ?>
