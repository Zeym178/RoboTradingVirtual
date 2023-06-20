<!DOCTYPE html>
<html>
<head>
    <title>Gráfico</title>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-5">Robot en Bolsa de Valores</h1>
        <div class="mb-3 row" >
            <label for="index-select" class="form-label">Índice bursátil:</label>
            <div class="col-sm-10 col-md-7 col-lg-3">
                <select class="form-select" id="index-select">
                    <option value="index1">Dow Jones Industrial Average (DJIA)</option>
                    <option value="index2">S&P 500</option>
                    <option value="index3">NASDAQ Composite</option>
                    <option value="index4">Russell 2000</option>
                    <option value="index5">NYSE Composite</option>
                </select>
            </div>
        </div>
        <div id="chart" class="mb-5"></div>

        <div class="row justify-content-center">
            <div class="col-lg-3">
                <h2>Mi inversión</h2>
                <ul class="list-group">
                    <li class="list-group-item">Dinero en Cuenta: $<span id="dinerocuenta"></span></li>
                    <li class="list-group-item">Acciones: <span id="valacciones"></span></li>
                    <li class="list-group-item">Valor en el Mercado: <span id="valmercado"></span></li>
                </ul>
            </div>

            <div class="col-lg-3">
                <h2>Indicadores</h2>
                <ul class="list-group">
                    <li class="list-group-item">Open: $<span id="indicator-open"></span></li>
                    <li class="list-group-item">Máximo hoy: $<span id="indicator-high"></span></li>
                    <li class="list-group-item">Mínimo hoy: $<span id="indicator-low"></span></li>
                    <li class="list-group-item">Máximo mensual: $<span id="indicator-monthly-high"></span></li>
                    <li class="list-group-item">Mínimo mensual: $<span id="indicator-monthly-low"></span></li>
                </ul>
            </div>
            <div class="col-1 text-center align-self-center">
                <div class="form-check form-switch my-4">
                    <input class="form-check-input" type="checkbox" id="botSwitch">
                    <label class="form-check-label" for="botSwitch">Activar Bot</label>
                </div>
                <div class="pb-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#buyModal">Comprar</button>
                </div>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#sellModal">Vender</button>
            </div>
            
        </div>

    </div>
    
    <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="buyModalLabel">Compra de Acciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="buyForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cantidadcompra">Cantidad en dólares a invertir:</label>
                            <input type="number" class="form-control" id="cantidadcompra" name="cantidadcompra" step="0.01" autocomplete="off">
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item">Precio actual: $<span class="preciocompra"></span></li>
                            <li class="list-group-item">Estimado de compra: <span id="estimadocompra"></span></li>
                        </ul>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Confirmar Compra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sellModal" tabindex="-1" aria-labelledby="sellModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellModalLabel">Venta de Acciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="sellForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cantidadventa">Cantidad a vender (Dolares):</label>
                            <input type="number" class="form-control" id="cantidadventa" name="cantidadventa" step="0.01" autocomplete="off">
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item">Precio actual: $<span class="preciocompra"></span></li>
                            <li class="list-group-item">Estimado de venta (Acciones): <span id="estimadoventa"></span></li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Confirmar Venta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./js/grafica.js"></script>
</body>
</html>
