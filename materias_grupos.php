<?php 
require_once "modelos/mdl_materias_grupos.php";
require_once "modelos/mdl_materias.php";
require_once "modelos/mdl_grupos.php";
require_once "modelos/mdl_maestros.php";

$modelo = new MdlMateriasGrupos();
$asignaciones = $modelo->obtenerTodos();

$modeloMaterias = new MdlMaterias();
$materias = $modeloMaterias->obtenerTodos();

$modeloGrupos = new MdlGrupos();
$grupos = $modeloGrupos->obtenerTodos();

$modeloMaestros = new MdlMaestros();
$maestros = $modeloMaestros->obtenerTodos();

// Lógica de paginación
$limite = 8; // Número de maestros por página
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($pagina - 1) * $limite;

// Obtener total de maestros
$totalRegistros = $modelo->contarMaterias_grupos();
$totalPaginas = ceil($totalRegistros / $limite);

// Obtener los maestros paginados
$asignaciones = $modelo->obtenerPaginado($inicio, $limite);

$mensajeExito = $_GET['exito'] ?? '';
$mensajeError = $_GET['error'] ?? '';

require_once "vistas/parte_superior.php";
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Asignación de Materias a Grupos</h1>
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
                <th>Grupo</th>
                <th>Maestro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($asignaciones as $asignacion): ?>
            <tr>
                <td><?= htmlspecialchars($asignacion['materia']) ?></td>
                <td><?= htmlspecialchars($asignacion['grupo']) ?></td>
                <td><?= htmlspecialchars($asignacion['maestro']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                        onclick="abrirModal(
                            '<?= $asignacion['id'] ?>', 
                            '<?= $asignacion['materia'] ?>',
                            '<?= $asignacion['grupo'] ?>',
                            '<?= $asignacion['maestro'] ?>'
                        )">
                        Editar
                    </button>
                    <button class="btn btn-danger btn-sm" 
                        onclick="confirmarEliminacion(<?= $asignacion['id'] ?>)">
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
                <form method="POST" action="controladores/ctrl_materias_grupos.php">
                    <input type="hidden" name="id" id="Id_asignacion">
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
                            <label>Grupo</label>
                            <select class="form-control" name="id_grupo" id="selectGrupo" required>
                                <option value="">Seleccione un grupo</option>
                                <?php foreach($grupos as $grupo): ?>
                                    <option value="<?= $grupo['id'] ?>"><?= htmlspecialchars($grupo['grupo']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Maestro</label>
                            <select class="form-control" name="id_maestro" id="selectMaestro" required>
                                <option value="">Seleccione un maestro</option>
                                <?php foreach($maestros as $maestro): ?>
                                    <option value="<?= $maestro['id'] ?>"><?= htmlspecialchars($maestro['nombre']) ?></option>
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

<nav aria-label="Paginación de materias_grupos">
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
    function abrirModal(id = '', materia = '', grupo = '', maestro = '') {
        document.getElementById('Id_asignacion').value = id;
        
        // Seleccionar la opción correcta en los dropdowns
        if (materia) {
            const selectMateria = document.getElementById('selectMateria');
            for (let i = 0; i < selectMateria.options.length; i++) {
                if (selectMateria.options[i].text === materia) {
                    selectMateria.selectedIndex = i;
                    break;
                }
            }
        }
        
        if (grupo) {
            const selectGrupo = document.getElementById('selectGrupo');
            for (let i = 0; i < selectGrupo.options.length; i++) {
                if (selectGrupo.options[i].text === grupo) {
                    selectGrupo.selectedIndex = i;
                    break;
                }
            }
        }
        
        if (maestro) {
            const selectMaestro = document.getElementById('selectMaestro');
            for (let i = 0; i < selectMaestro.options.length; i++) {
                if (selectMaestro.options[i].text === maestro) {
                    selectMaestro.selectedIndex = i;
                    break;
                }
            }
        }
        
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
            form.action = 'controladores/ctrl_materias_grupos.php';

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
