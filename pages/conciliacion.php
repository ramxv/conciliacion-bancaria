<?php require "../includes/header.php" ?>

<div class="container-fluid w-75 mt-5 p-5">

  <form action="" class="border border-secondary-subtle rounded">
    <h3 class="form-header p-3 border-bottom border-secondary-subtle ">Conciliación Bancaria</h3>

    <!-- Primera sección -->
    <div class="row p-3 justify-content-end align-items-end d-flex">
      <div class="col-2 me-3">
        <label for="inputMeses" class="form-label"><strong>Meses</strong></label>
        <select class="form-select" name="meses" id="inputMeses">
          <option> </option>
          <option value="1">One</option>
          <option value="2">Two</option>
          <option value="3">Three</option>
        </select>
      </div>

      <div class="col-2 me-3">
        <label for="year-input" class="form-label"><strong>Año</strong></label>
        <input type="text" name="text" id="year-input" class="form-control">
      </div>

      <div class="col-3">
        <button type="submit" class="btn" id="btn-custom">Realizar Conciliación</button>
      </div>
    </div>

    <!-- Segunda sección -->

    <div class="row justify-content-between pe-5 ps-2">
      <label for="inputSaldoLibro" class="col-sm-2 col-form-label"><strong>Saldo según libro Al </strong></label>
      <div class="col-3">
        <input type="saldoLibro" class="form-control" id="inputSaldoLibro" disabled>
      </div>
    </div>

    <div class="row ps-4 mb-2">
      <label for="inputDeposito" class="col-md-3 col-form-label"><strong>Más: Deposito</strong></label>
      <div class="col-3 offset-2">
        <input type="deposito" class="form-control" id="inputDeposito" disabled>
      </div>
    </div>

    <div class="row mb-2 ps-4">
      <label for="inputChequesAnulados" class="col-md-3 col-form-label"><strong>Cheques Anulados</strong></label>
      <div class="col-3 offset-2">
        <input type="chequesAnulados" class="form-control" id="inputChequesAnulados" disabled>
      </div>
    </div>

    <div class="row ps-4 mb-2">
      <label for="inputNotasCredito" class="col-md-3 col-form-label"><strong>Notas de Crédito</strong></label>
      <div class="col-3 offset-2">
        <input type="notasCredito" class="form-control" id="inputNotasCredito" disabled>
      </div>
    </div>

    <div class="row ps-4 mb-2">
      <label for="inputAjustesLibro" class="col-md-3 col-form-label"><strong>Ajustes</strong></label>
      <div class="col-3 offset-2">
        <input type="ajustesLibro" class="form-control" id="inputAjustesLibro" disabled>
      </div>
    </div>

    <div class="row mb-2 justify-content-end pe-5 ps-2">
      <label for="inputSubtotal" class="col-sm-2 col-form-label"><strong>Subtotal</strong></label>
      <div class="col-3">
        <input type="subtotal" class="form-control" id="inputSubtotal" disabled>
      </div>
    </div>

    <!-- Tercera Sección -->

    <div class="row justify-content-between pe-5 ps-2">
      <label for="inputSubtotalFinal" class="col-sm-2 col-form-label"><strong>SUBTOTAL</strong></label>
      <div class="col-3">
        <input type="subtotalFinal" class="form-control" id="inputSubtotalFinal" disabled>
      </div>
    </div>

    <div class="row ps-4 mb-2">
      <label for="inputCkGirados" class="col-md-3 col-form-label"><strong>Menos: Cheques girados en el mes</strong></label>
      <div class="col-3 offset-2">
        <input type="ckGirados" class="form-control" id="inputCkGirados" disabled>
      </div>
    </div>

    <div class="row ps-4 mb-2">
      <label for="inputNotasDebito" class="col-md-3 col-form-label"><strong>Notas de Débitos</strong></label>
      <div class="col-3 offset-2">
        <input type="notasDebito" class="form-control" id="inputNotasDebito" disabled>
      </div>
    </div>

    <div class="row ps-4 mb-2">
      <label for="inputAjusteCkGirados" class="col-md-3 col-form-label"><strong>Ajustes</strong></label>
      <div class="col-3 offset-2">
        <input type="ajusteCkGirados" class="form-control" id="inputAjusteCkGirados" disabled>
      </div>
    </div>

    <div class="row mb-2 justify-content-end pe-5 ps-2">
      <label for="inputSubtotalMenos" class="col-sm-2 col-form-label"><strong>Subtotal</strong></label>
      <div class="col-3">
        <input type="subtotalMenos" class="form-control" id="inputSubtotalMenos" disabled>
      </div>
    </div>

    <div class="row justify-content-between pe-5 ps-2">
      <label for="inputSaldoConsiliado" class="col col-form-label"><strong>SALDO CONCILIADO SEGÚN LIBROS AL</strong></label>
      <div class="col-3">
        <input type="saldoConsiliado" class="form-control" id="inputSaldoConsiliado" disabled>
      </div>
    </div>
    
    <hr>

    <!-- Sección Cuatro -->

    <div class="row justify-content-between pe-5 ps-2">
      <label for="inputSaldoBanco" class="col-sm-2 col-form-label"><strong>SALDO EN BANCO AL</strong></label>
      <div class="col-3">
        <input type="saldoBanco" class="form-control" id="inputSaldoBanco">
      </div>
    </div>

    <div class="row ps-4 mb-2">
      <label for="inputDepositoTransito" class="col-md-3 col-form-label"><strong>Más: Depósitos en Tránsitos</strong></label>
      <div class="col-3 offset-2">
        <input type="depositoTransito" class="form-control" id="inputDepositoTransito" disabled>
      </div>
    </div>

    <div class="row ps-4 mb-2">
      <label for="inputChequesCirculacion" class="col-md-3 col-form-label"><strong>Menos: Cheques en Circulación</strong></label>
      <div class="col-3 offset-2">
        <input type="chequesCirculacion" class="form-control" id="inputChequesCirculacion" disabled>
      </div>
    </div>

    <div class="row ps-4 mb-2">
      <label for="inputAjusteBanco" class="col-md-3 col-form-label"><strong>Más: Ajustes</strong></label>
      <div class="col-3 offset-2">
        <input type="ajusteBanco" class="form-control" id="inputAjusteBanco" disabled>
      </div>
    </div>

    <hr>

    <!-- Sección Botones -->

    <div class="d-grid gap-5 d-md-flex justify-content-md-center pb-3">
      <button type="submit" class="btn" id="btn-custom">Grabar</button>
      <button type="submit" class="btn" id="btn-custom">Nuevo</button>
    </div>

  </form>
</div>
<?php require "../includes/footer.php" ?>
