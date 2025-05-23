<?php require_once '../controllers/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMIR - Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
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

        <h1 class="mb-4">Inventario</h1>

        <div class="card mb-4">
            <div class="card-header bg-primary">
                <h2 class="h5 mb-0">Listado de productos</h2>
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <label for="buscarProducto" class="form-label">Buscar producto:</label>
                    <input type="text" class="form-control" id="buscarProducto"
                        placeholder="Escribe el nombre del producto...">
                </div>

                <div class="table-responsive  ">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody id="inventarioTablaBody">
                            <!-- Se llenará  -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card ">
            <div class="card-header bg-primary">
                <h2 class="h5 mb-0">Productos con bajo stock</h2>
            </div>
            <div class="card-body">

                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#pedidoModal">
                    Realizar pedido
                </button>

                <div class="table-responsive ">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody id="bajoStockTablaBody">
                            <!-- Se llena dinámicamente  -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('../controllers/InventarioController.php')
                .then(response => response.json())
                .then(data => {
                    const tabla = document.getElementById('inventarioTablaBody');
                    const bajoStock = document.getElementById('bajoStockTablaBody');

                    data.forEach(item => {
                        const fila = `
                            <tr>
                                <td>${item.materialId}</td>
                                <td>${item.nombre}</td>
                                <td>${item.descripcion}</td>
                                <td>${item.cantidad}</td>
                            </tr>
                        `;
                        tabla.innerHTML += fila;

                        if (item.cantidad < 5) {
                            bajoStock.innerHTML += fila;
                        }
                    });
                });
        });
    </script>

    <div class="modal fade" id="pedidoModal" tabindex="-1" aria-labelledby="pedidoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="pedidoForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pedidoModalLabel">Realizar Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="materialNombre" class="form-label">Nombre del Material</label>
                        <input type="text" class="form-control" id="materialNombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidadPedido" class="form-label">Cantidad</label>
                        <input type="number" class="form-control" id="cantidadPedido" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enviar Pedido</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="../../public/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>