<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/anulacion.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script defer src="../js/script.js"></script>
  <title>Horizon</title>
</head>

<body>
  <div class="container">
    <form action="" class="border border-secondary-subtle rounded">
      <h2 class="form-header p-3 border-bottom border-secondary-subtle">Anulación de Cheque</h2>
      <div class="row p-4">
        <div class="col p-3">
          <div class="anul-container">
            <div class="row justify-content-end align-items-end">
              <div class="col-4">
                <label for="num-cheque-input" class="form-label">No.Cheque</label>
                <input type="text" class="form-control" id="num-cheque-input" onkeypress="return soloNumeros(event)">
              </div>
              <div class="col-4">
                <button type="button" class="btn" id="btn-custom">Buscar</button>
              </div>
            </div>
            <div class="col-4">
              <label for="fecha-input" class="form-label">Fecha</label>
              <input type="date" name="date" id="fecha-input" class="form-control" disabled>
            </div>
            <div class="col-12 pt-2">
              <label for="inputOrden" class="form-label">Páguese a la orden de</label>
              <select class="form-select" id="inputOrden" disabled>
                <option selected> </option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
              </select>
            </div>
            <div class="col-4 pt-2">
              <label for="inputMonto" class="form-label">La suma de</label>
              <div class="input-group">
                <input type="text" aria-label="First name" class="form-control" id="inputMonto" placeholder="$" disabled>
              </div>
            </div>
            <div class="col-12 pt-2">
              <label for="inputDetalle" class="form-label">Detalle</label>
              <input type="text" class="form-control" id="inputDetalle" placeholder="" disabled>
            </div>
          </div>
        </div>
        <div class="col p-3">
          <div class="col-4">
            <label for="fecha-input" class="form-label">Fecha de Anulación</label>
            <input type="date" name="date" id="fecha-input" class="form-control">
          </div>
          <div class="col-12 pt-2">
            <label for="floatingTextarea2" class="form-label">Detalle de la Anulación</label>
            <textarea class="form-control" placeholder="" id="floatingTextarea2" style="height: 100px"></textarea>
          </div>
          <div class="d-grid gap-2 d-md-flex justify-content-md-center pt-5">
            <button type="button" class="btn" id="btn-custom">Anular</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</body>

</html>