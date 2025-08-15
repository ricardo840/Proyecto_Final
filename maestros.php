<?php
define('SECURE_ACCESS', true);
require_once "init.php"; 
require_once "modelos/mdl_maestros.php";

$modelo = new MdlMaestros();
// Lógica de paginación
$limite = 8; // Número de maestros por página
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($pagina - 1) * $limite;

// Obtener total de maestros
$totalRegistros = $modelo->contarMaestros();
$totalPaginas = ceil($totalRegistros / $limite);

// Obtener los maestros paginados
$maestros = $modelo->obtenerPaginado($inicio, $limite);

$mensajeExito = $_GET['exito'] ?? '';
$mensajeError = $_GET['error'] ?? '';

require_once "vistas/parte_superior.php";
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Maestros</h1>
        <button class="btn btn-primary" onclick="abrirModal()">
            <i class="fas fa-plus"></i> Nuevo Maestro
        </button>
    </div>

    <?php if ($mensajeExito): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensajeExito) ?></div>
    <?php endif; ?>
    
    <?php if ($mensajeError): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($mensajeError) ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Nombre del Maestro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($maestros as $maestro): ?>
            <tr>
                <td><?= htmlspecialchars($maestro['nombre']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                        onclick="abrirModal('<?= $maestro['id'] ?>', '<?= htmlspecialchars($maestro['nombre'], ENT_QUOTES) ?>')">
                        Editar
                    </button>
                    <button class="btn btn-danger btn-sm" 
                        onclick="confirmarEliminacion(<?= $maestro['id'] ?>)">
                        Eliminar
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" onclick="cerrarModal()">&times;</button>
                </div>
                <form method="POST" action="Controladores/ctrl_maestros.php" onsubmit="return validarFormularioMaestro()">
                    <input type="hidden" name="id" id="Id_maestro">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre del Maestro</label>
                            <input type="text" class="form-control" name="nombre_maestro" id="nombreMaestro" required>
                            <small id="errorNombre" class="text-danger" style="display:none;">No se permiten números</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                        <button type="submit" class="btn btn-primary" name="action" id="accionBoton"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<nav aria-label="Paginación de maestros">
    <ul class="pagination justify-content-center">
        <?php if ($pagina > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?pagina=<?= $pagina - 1 ?>">Anterior</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($pagina < $totalPaginas): ?>
            <li class="page-item">
                <a class="page-link" href="?pagina=<?= $pagina + 1 ?>">Siguiente</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>


<script>

    // Función de validación
    function validarFormularioMaestro() {
        const nombre = document.getElementById('nombreMaestro').value;
        const errorElement = document.getElementById('errorNombre');
        let valido = true;
        
        // Validación para que no contenga números
        if (/\d/.test(nombre)) {
            errorElement.style.display = 'block';
            valido = false;
        } else {
            errorElement.style.display = 'none';
        }
        
        return valido;
    }

    function abrirModal(id = '', nombre = '') {
        // Resetear mensaje de error
        document.getElementById('errorNombre').style.display = 'none';
        
        document.getElementById('Id_maestro').value = id;
        document.getElementById('nombreMaestro').value = nombre;
        
        if (id) {
            document.getElementById('modalTitle').textContent = 'Editar Maestro';
            document.getElementById('accionBoton').textContent = 'Actualizar';
            document.getElementById('accionBoton').value = 'actualizar';
        } else {
            document.getElementById('modalTitle').textContent = 'Nuevo Maestro';
            document.getElementById('accionBoton').textContent = 'Guardar';
            document.getElementById('accionBoton').value = 'guardar';
        }
        
        $('#myModal').modal('show');
    }
        function cerrarModal() {
        $('#myModal').modal('hide');
    }

    function confirmarEliminacion(id) {
        if (confirm('¿Eliminar este maestro?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'Controladores/ctrl_maestros.php';

            const accion = document.createElement('input');
            accion.type = 'hidden';
            accion.name = 'action';
            accion.value = 'eliminar';

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = id;

            form.appendChild(accion);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?php
require_once "vistas/parte_inferior.php";
?>
