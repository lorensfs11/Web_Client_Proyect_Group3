<?php require_once '../controllers/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMIR - Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="sidebar">
        <h4 class="text-center">SMIR</h4>
        <?php if (isAdmin()): ?>
            <a class="nav-link text-white" href="usuarios.php"><i class="fas fa-users"></i> Usuarios </a>
            <a class="nav-link text-white" href="index.php"><i class="fas fa-home"></i> Inicio </a>
            <a class="nav-link text-white" href="registro_materiales.php"><i class="fas fa-box"></i> Registro de Materiales
            </a>
            <a class="nav-link text-white" href="inventario.php"><i class="fas fa-archive"></i> Inventario </a>
            <a class="nav-link text-white" href="reportes.php"><i class="fas fa-chart-bar"></i> Reportes </a>
        <?php elseif (isUsuario()): ?>
            <a class="nav-link text-white" href="index.php"><i class="fas fa-home"></i> Inicio </a>
            <a class="nav-link text-white" href="registro_materiales.php"><i class="fas fa-box"></i> Registro de Materiales
            </a>
            <a class="nav-link text-white" href="inventario.php"><i class="fas fa-archive"></i> Inventario </a>
            <a class="nav-link text-white" href="reportes.php"><i class="fas fa-chart-bar"></i> Reportes </a>
        <?php endif; ?>
    </div>

    <div class="content my-4">
        <div class="d-flex justify-content-end mb-3">
            <a href="../controllers/logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>

        <h1 class="mb-4">Inicio</h1>

        <div class="container">
            <div class="jumbotron bg-light p-4">
                <h1 class="display-5">¡Bienvenido a SMIR!</h1>
                <p class="lead">Este sistema de gestión de inventario te permite registrar, monitorear y gestionar los
                    materiales de manera eficiente.</p>
                <hr class="my-3">
                <a class="btn btn-primary btn-lg" href="registro_materiales.php" role="button">
                    <i class="fas fa-box"></i> Registrar Materiales
                </a>
                <a class="btn btn-primary btn-lg" href="inventario.php" role="button">
                    <i class="fas fa-archive"></i> Inventario
                </a>
                <?php if (isAdmin()): ?>
                    <a class="btn btn-primary btn-lg" href="usuarios.php" role="button">
                        <i class="fas fa-users"></i> Usuarios
                    </a>
                <?php endif; ?>
                <a class="btn btn-primary btn-lg" href="reportes.php" role="button">
                    <i class="fas fa-chart-bar"></i> Reportes
                </a>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Total de Productos</div>
                        <div class="card-body">
                            <h2 id="total_productos">0</h2>
                        </div>
                    </div>
                </div>
                <?php if (isAdmin()): ?>
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Usuarios Registrados</div>
                            <div class="card-body">
                                <h2 id="usuarios_registrados">0</h2>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            Inventario por Categoría
                        </div>
                        <div class="card-body">
                            <canvas id="inventarioChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function () {
                $.getJSON("../controllers/panel_datos.php", function (data) {
                    $('#total_productos').text(data.totalProductos);
                    $('#usuarios_registrados').text(data.totalUsuarios);

                    const catCtx = document.getElementById('inventarioChart').getContext('2d');
                    const catLabels = data.categorias.map(c => c.nombre);
                    const catDatos = data.categorias.map(c => c.total);

                    const coloresCategorias = [
                        '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#17a2b8'
                    ];
                    const catColores = catLabels.map((_, i) => coloresCategorias[i % coloresCategorias.length]);

                    new Chart(catCtx, {
                        type: 'bar',
                        data: {
                            labels: catLabels,
                            datasets: [{
                                label: 'Cantidad',
                                data: catDatos,
                                backgroundColor: catColores,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: { y: { beginAtZero: true } }
                        }
                    });

                    const actCtx = document.getElementById('actividadChart').getContext('2d');
                    const labels = data.actividad.map(a => `${a.tipo} - ${a.material}`);
                    const cantidades = data.actividad.map(a => a.cantidad);
                    const colores = data.actividad.map(a => a.tipo === 'Entrada' ? '#007bff' : '#dc3545');

                    new Chart(actCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: cantidades,
                                backgroundColor: colores
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                });
            });
        </script>
</body>

</html>