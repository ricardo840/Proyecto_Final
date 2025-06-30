<?php
require_once "Modelos/mdl_carreras.php";

$modelo = new MdlCarrera();
// Lógica de paginación
$limite = 8; // Número de maestros por página
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($pagina - 1) * $limite;

// Obtener total de maestros
$totalRegistros = $modelo->contarCarreras();
$totalPaginas = ceil($totalRegistros / $limite);

// Obtener los maestros paginados
$carreras = $modelo->obtenerPaginado($inicio, $limite);

$mensajeExito = $_GET['exito'] ?? '';
$mensajeError = $_GET['error'] ?? '';

require_once "vistas/parte_superior.php";
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Carreras</h1>
        <button class="btn btn-primary" onclick="abrirModal()">
            <i class="fas fa-plus"></i> Nueva Carrera
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
                <th>Nombre de la carrera</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($carreras as $carrera): ?>
            <tr>
                <td><?= htmlspecialchars($carrera['nombre']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                        onclick="abrirModal('<?= $carrera['id'] ?>', '<?= htmlspecialchars($carrera['nombre'], ENT_QUOTES) ?>')">
                        Editar
                    </button>
                    <button class="btn btn-danger btn-sm" 
                        onclick="confirmarEliminacion(<?= $carrera['id'] ?>)">
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
                <form method="POST" action="Controladores/ctrl_carreras.php">
                    <input type="hidden" name="id" id="Id_carrera"> <!-- aca esta el hidden -->
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre de la carrera</label>
                            <input type="text" class="form-control" name="nombre_carrera" id="nombreCarrera" required>
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
</div>

<nav aria-label="Paginación de carreras">
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
    //Funcion de validacion
    function validarFormularioCarrera() {
        const nombre = document.getElementById('nombreCarrera').value;
        const errorElement = document.getElementById('errorNombre');
        
        //Validacion para que no contenga numeros
        if (/\d/.test(nombre)) {
            errorElement.style.display = 'block';
            return false;
        }
        
        errorElement.style.display = 'none';
        return true;
    }

    // Modifica tu función abrirModal para resetear el mensaje de error
    function abrirModal(id = '', nombre = '') {
        document.getElementById('errorNombre').style.display = 'none';
    }
    function abrirModal(id = '', nombre = '') {
        document.getElementById('Id_carrera').value = id;
        document.getElementById('nombreCarrera').value = nombre;
        
        if (id) {
            document.getElementById('modalTitle').textContent = 'Editar Carrera';
            document.getElementById('accionBoton').textContent = 'Actualizar';
            document.getElementById('accionBoton').value = 'actualizar';
        } else {
            document.getElementById('modalTitle').textContent = 'Nueva Carrera';
            document.getElementById('accionBoton').textContent = 'Guardar';
            document.getElementById('accionBoton').value = 'guardar';
        }
        
        $('#myModal').modal('show');
    }

    function cerrarModal() {
        $('#myModal').modal('hide');
    }

    function confirmarEliminacion(id) {
        if (confirm('¿Eliminar esta carrera?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'Controladores/ctrl_carreras.php';

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
