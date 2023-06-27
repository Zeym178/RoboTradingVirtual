<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros</title>
</head>
<body>
    <div class="container">
        <h1>Registros de Transacciones</h1>
        <div class="row justify-content-start">
            <div class="col-lg-2 mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" id="fecha" class="form-control">
            </div>

            <div class="col-lg-4 mb-3">
                <label for="accion" class="form-label">Acción</label>
                <select id="accion" class="form-select">
                    <option value="">Todas</option>
                    <option value="Dow Jones Industrial Average (DJIA)">Dow Jones Industrial Average (DJIA)</option>
                    <option value="S&P 500">S&P 500</option>
                    <option value="NASDAQ Composite">NASDAQ Composite</option>
                    <option value="Russell 2000">Russell 2000</option>
                    <option value="NYSE Composite">NYSE Composite</option>
                </select>
            </div>
            <div class="col-lg-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Acción</th>
                            <th>Tipo</th>
                            <th>Fecha y Hora</th>
                            <th>Cantidad</th>
                            <th>Precio de Accion</th>
                            <th>Monto Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
            <div class="">
                <div id="loading-indicator" style="display: none;">
                    Cargando resultados...
                </div>
                <button class="btn btn-primary mb-4" id="show-more-btn" style="display: none;">Mostrar más</button>
            </div>
        </div>
    </div>
    <script src="./js/registros.js"></script>
</body>
</html>