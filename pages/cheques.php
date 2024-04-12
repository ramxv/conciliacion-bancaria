<?php
require "../php/db_conciliacion.php";

$consultaProveedores = $conn->query("SELECT * FROM proveedores");
$consultaObjetoGasto = $conn->query("SELECT * FROM objeto_gasto");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["numCheque"])) {
    $numCheque = $_POST["numCheque"];
    
    $statement = $conn->prepare("SELECT * FROM cheques WHERE numero_cheque = :numCheque");
    $statement->bindParam(":numCheque", $numCheque);
    $statement->execute();
    
    if ($statement->rowCount() > 0) {
      echo "registrada";
    } else {
      // Verificar si algunos campos necesarios están vacíos
      if (
        empty($_POST["fecha"]) || empty($_POST["beneficiario"]) ||
        empty($_POST["monto"]) || empty($_POST["detalle"]) || empty($_POST["objetoGasto"]) || empty($_POST["montoObjeto"])
      ) {
        echo "vacio";
      } elseif ($_POST["monto"] != $_POST["montoObjeto"]) {
        echo "diferente";
      } else {
        // Realizar inserción en la base de datos
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
      }
    }
  } else {
    // Manejo de error si numCheque no está presente en $_POST
    echo "Error: número de cheque no recibido.";
  }
}

?>

<?php require "../includes/header.php" ?>

<div class="container p-3">
  <form method="POST" class="border border-secondary-subtle rounded">
    <h2 class="form-header p-3 border-bottom border-secondary-subtle">Creación</h2>
    <div class="row p-4">
      <!-- Mensajes de aviso al cliente -->
      <div class="error-container"></div>
      <div class="col p-3">
        <h3 class="form-header border border-secondary-subtle rounded p-3 cheque-title">Cheques</h3>
        <!-- Sección de Cheques -->
        <div class="ch-container">
          <div class="row justify-content-end">
            <div class="col-4">
              <label for="numCkInput" class="form-label">No.Cheque</label>
              <input type="text" class="form-control" id="numCkInput" name="numCheque" onkeypress="return soloNumeros(event)" autocomplete="off">
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
              <input type="text" aria-label="Monto" class="form-control" id="input-monto" placeholder="$" name="monto" onkeypress="return soloDecimal(event)" onblur="mostrarMontoEnLetras()">
              <input type="text" aria-label="Monto en Letras" class="form-control w-50" id="input-monto-letras" disabled>
            </div>
          </div>
          <div class="col-12 pt-2">
            <label for="inputDetalle" class="form-label">Detalle</label>
            <input type="text" class="form-control" id="inputDetalle" name="detalle" placeholder="">
          </div>
        </div>
      </div>
      <!-- Seccción de Objetos de Gastos -->
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
              <input type="text" class="form-control" id="inputMonto" placeholder="" name="montoObjeto" onkeypress="return soloDecimal(event)">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Botones -->
    <div class="d-grid gap-5 d-md-flex justify-content-md-center pb-3" id="btn-custom-container">
      <button type="submit" class="btn button-custom" id="">Grabar</button>
      <button type="reset" class="btn button-custom" id="">Nuevo</button>
    </div>
  </form>
</div>

<script src="../js/validations.js"></script>

<?php require "../includes/footer.php" ?>
