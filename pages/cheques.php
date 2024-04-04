<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/cheques.css">
  <script defer src="../js/script.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Horizon</title>
</head>

<body>
  <div class="container p-3">
    <form action="" class="border border-secondary-subtle rounded">
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
                <option selected> </option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
            </div>
            <div class="col-12 pt-2">
              <label for="inputMonto" class="form-label">La suma de</label>
              <div class="input-group">
                <input type="text" aria-label="Monto" class="form-control" id="input-monto" placeholder="$" onkeypress="return soloDecimal(event)" onblur="mostrarMontoEnLetras()">
                <input type="text" aria-label="Monto en Letras" class="form-control w-50" id="input-monto-letras" disabled >
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
                  <option selected> </option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
                </select>
              </div>
              <div class="col-4">
                <label for="inputMonto" class="form-label">Monto</label>
                <input type="text" class="form-control" id="inputMonto" placeholder="" onkeypress="return soloNumeros(event)">
              </div>
            </div>
            <div class="row">
              <div class="col-8 pt-3">
                <label for="inputObjeto2" class="form-label"></label>
                <input type="text" class="form-control" id="inputObjeto2" placeholder="">
              </div>
              <div class="col-4 pt-3">
                <label for="inputMonto2" class="form-label"></label>
                <input type="text" class="form-control" id="inputMonto2" placeholder="">
              </div>
            </div>
            <div class="row">
              <div class="col-8 pt-3">
                <label for="inputObjeto3" class="form-label"></label>
                <input type="text" class="form-control" id="inputObjeto3" placeholder="">
              </div>
              <div class="col-4 pt-3">
                <label for="inputMonto3" class="form-label"></label>
                <input type="text" class="form-control" id="inputMonto3" placeholder="">
              </div>
            </div>
            <div class="row">
              <div class="col-8 pt-3">
                <label for="inputObjeto4" class="form-label"></label>
                <input type="text" class="form-control" id="inputObjeto4" placeholder="">
              </div>
              <div class="col-4 pt-3">
                <label for="inputMonto4" class="form-label"></label>
                <input type="text" class="form-control" id="inputMonto4" placeholder="">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="d-grid gap-2 d-md-flex justify-content-md-center pb-3">
        <button type="button" class="btn" id="btn-custom">Grabar</button>
        <button type="button" class="btn" id="btn-custom">Imprimir</button>
        <button type="button" class="btn" id="btn-custom">Nuevo</button>
      </div>
    </form>
  </div>

</body>

</html>
