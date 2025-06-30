<?php
require_once "modelos/mdl_materias_carrera.php";
require_once "modelos/mdl_materias.php";
require_once "modelos/mdl_carreras.php";

$modelo = new MdlMateriasCarrera();
$relaciones = $modelo->obtenerTodos();

$modeloMaterias = new MdlMaterias();
$materias = $modeloMaterias->obtenerTodos();

$modeloCarreras = new MdlCarrera();
$carreras = $modeloCarreras->obtenerTodos();

// Lógica de paginación
$limite = 7; // Número de maestros por página
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($pagina - 1) * $limite;

// Obtener total de maestros
$totalRegistros = $modelo->contarMaterias_carrera();
$totalPaginas = ceil($totalRegistros / $limite);

// Obtener los maestros paginados
$relaciones = $modelo->obtenerPaginado($inicio, $limite);


$mensajeExito = $_GET['exito'] ?? '';
$mensajeError = $_GET['error'] ?? '';

require_once "vistas/parte_superior.php";
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Asignación de Materias a Carreras</h1>
        <button class="btn btn-primary" onclick="abrirModal()">
            <i class="fas fa-plus"></i> Nueva Asignación
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
                <th>Materia</th>
                <th>Carrera</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($relaciones as $relacion): ?>
            <tr>
                <td><?= htmlspecialchars($relacion['materia']) ?></td>
                <td><?= htmlspecialchars($relacion['carrera']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                        onclick="abrirModal(
                            '<?= $relacion['id'] ?>', 
                            '<?= $relacion['materia_id'] ?? '' ?>',
                            '<?= $relacion['carrera_id'] ?? '' ?>'
                        )">
                        Editar
                    </button>
                    <button class="btn btn-danger btn-sm" 
                        onclick="confirmarEliminacion(<?= $relacion['id'] ?>)">
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
                <form method="POST" action="controladores/ctrl_materias_carrera.php">
                    <input type="hidden" name="id" id="Id_relacion">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Materia</label>
                            <select class="form-control" name="id_materia" id="selectMateria" required>
                                <option value="">Seleccione una materia</option>
                                <?php foreach($materias as $materia): ?>
                                    <option value="<?= $materia['id'] ?>"><?= htmlspecialchars($materia['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Carrera</label>
                            <select class="form-control" name="id_carrera" id="selectCarrera" required>
                                <option value="">Seleccione una carrera</option>
                                <?php foreach($carreras as $carrera): ?>
                                    <option value="<?= $carrera['id'] ?>"><?= htmlspecialchars($carrera['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
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

<nav aria-label="Paginación de materias_carrera">
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
    function abrirModal(id = '', idMateria = '', idCarrera = '') {
        document.getElementById('Id_relacion').value = id;
        document.getElementById('selectMateria').value = idMateria;
        document.getElementById('selectCarrera').value = idCarrera;
        
        if (id) {
            document.getElementById('modalTitle').textContent = 'Editar Asignación';
            document.getElementById('accionBoton').textContent = 'Actualizar';
            document.getElementById('accionBoton').value = 'actualizar';
        } else {
            document.getElementById('modalTitle').textContent = 'Nueva Asignación';
            document.getElementById('accionBoton').textContent = 'Guardar';
            document.getElementById('accionBoton').value = 'guardar';
        }
        
        $('#myModal').modal('show');
    }

    function cerrarModal() {
        $('#myModal').modal('hide');
    }

    function confirmarEliminacion(id) {
        if (confirm('¿Eliminar esta asignación?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'controladores/ctrl_materias_carrera.php';

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