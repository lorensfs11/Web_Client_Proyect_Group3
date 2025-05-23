<?php require_once '../controllers/auth.php';
if (!isAdmin()) {
    header('HTTP/1.1 403 Forbidden');
    exit('Acceso denegado');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMIR - Empleados</title>
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
            <a href="login.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>

        <h1 class="mb-4">Usuarios y roles</h1>

        <div class="card mb-4">
            <div class="card-header bg-primary">
                <h2 class="h5 mb-0">Gestión de Usuarios</h2>
            </div>
            <div class="card-body">

                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#usuarioModal"
                    onclick="openUsuarioModal()">
                    Agregar Usuario
                </button>

                <div class="table-responsive  ">
                    <table id="tablaUsuarios" class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="usuarioTablaBody">
                            <!-- Se llenará dinámicamente con JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card ">
            <div class="card-header bg-primary">
                <h2 class="h5 mb-0">Gestión de Roles</h2>
            </div>
            <div class="card-body">


                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#rolModal"
                    onclick="openRolModal()">
                    Agregar Rol
                </button>

                <div class="table-responsive ">
                    <table id="tablaRoles" class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Rol</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="rolTablaBody">
                            <!-- Se llenará dinámicamente con JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="usuarioModal" tabindex="-1" aria-labelledby="usuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="usuarioForm" onsubmit="saveUsuario(event)">
                    <div class="modal-header">
                        <h5 class="modal-title" id="usuarioModalLabel">Agregar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="usuarioId" />

                        <div class="mb-3">
                            <label for="usuarioNombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="usuarioNombre" required />
                        </div>

                        <div class="mb-3">
                            <label for="usuarioEmail" class="form-label">Correo:</label>
                            <input type="email" class="form-control" id="usuarioEmail" required />
                        </div>
                        <div class="mb-3"><label for="usuarioPassword" class="form-label">Contraseña:</label><input
                                type="password" class="form-control" id="usuarioPassword" />
                        </div>
                        <div class="mb-3">
                            <label for="usuarioRol" class="form-label">Rol:</label>
                            <select class="form-select" id="usuarioRol" required>
                                <!-- Se llenará dinámicamente con los rols -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rolModal" tabindex="-1" aria-labelledby="rolModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="rolForm" onsubmit="saveRol(event)">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rolModalLabel">Agregar Rol</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="rolId" />

                        <div class="mb-3">
                            <label for="rolNombre" class="form-label">Nombre del Rol:</label>
                            <input type="text" class="form-control" id="rolNombre" required />
                        </div>

                        <div class="mb-3">
                            <label for="rolDescription" class="form-label">Descripción:</label>
                            <textarea class="form-control" id="rolDescription" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="../../public/js/usuarios.js"></script>
</body>

</html>