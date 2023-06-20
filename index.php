<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Robot en Bolsa de Valores</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 col-sm-3 col-2 px-sm-2 px-0 position-relative bg-dark sidebar min-vh-100 mw-20">
                <div class="position-fixed d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline">Menu</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link align-middle px-0" data-page="grafica.php">
                                <i class="fs-4 bi-align-center"></i> <span class="ms-1 d-none d-sm-inline">Simulador</span>
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <div class="dropdown pb-4">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://rvideos2.memedroid.com/videos/UPLOADED573/63b8cc8e4258b.webp" alt="hugenerd" width="30" height="30" class="rounded-circle">
                            <span class="d-none d-sm-inline mx-1">usuario</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                            <li><a class="dropdown-item" href="#">Configuracion</a></li>
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Salir</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col py-3 bg-dark text-light" id="content">
                
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>

    <script>
        $(document).ready(function() {
            loadContent('./paginas/grafica.php');

            $('#menu a.nav-link').click(function(e) {
                e.preventDefault();
                var page = './paginas/'+$(this).data('page');
                if (!$(this).attr('data-bs-toggle')) {
                    stopInterval();
                    loadContent(page);
                }
            });

            function loadContent(page) {
                $.ajax({
                    url: page,
                    success: function(response) {
                        $('#content').html(response);
                    },
                    error: function() {
                        $('#content').html('<p>Error al cargar el contenido.</p>');
                    }
                });
            }
        });
    </script>
</body>
</html>
