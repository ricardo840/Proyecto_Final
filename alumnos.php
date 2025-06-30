<?php
require_once "Modelos/mdl_alumnos.php";

$modelo = new MdlAlumnos();

// Parámetros de paginación
$limite = 8; // Número de alumnos por página
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($pagina - 1) * $limite;

// Obtener total de alumnos
$totalRegistros = $modelo->contarAlumnos();
$totalPaginas = ceil($totalRegistros / $limite);

// Obtener los alumnos paginados
$alumnos = $modelo->obtenerPaginado($inicio, $limite);

$mensajeExito = $_GET['exito'] ?? '';
$mensajeError = $_GET['error'] ?? '';

require_once "vistas/parte_superior.php";
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Alumnos</h1>
        <button class="btn btn-primary" onclick="abrirModal()">
            <i class="fas fa-plus"></i> Nuevo Alumno
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
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Género</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($alumnos as $alumno): ?>
            <tr>
                <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                <td><?= htmlspecialchars($alumno['ape_pa']) ?></td>
                <td><?= htmlspecialchars($alumno['ape_ma']) ?></td>
                <td><?= htmlspecialchars($alumno['genero']) ?></td>
                <td>
                    <?php 
                        switch($alumno['activo']) {
                            case 0: echo 'Activo'; break;
                            case 1: echo 'Baja'; break;
                            case 2: echo 'Graduado'; break;
                            default: echo 'Desconocido';
                        }
                    ?>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                        onclick="abrirModal(
                            '<?= $alumno['id_alumno'] ?>', 
                            '<?= htmlspecialchars($alumno['nombre'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($alumno['ape_pa'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($alumno['ape_ma'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($alumno['genero'], ENT_QUOTES) ?>',
                            '<?= $alumno['activo'] ?>'
                        )">
                        Editar
                    </button>
                    <button class="btn btn-danger btn-sm" 
                        onclick="confirmarEliminacion(<?= $alumno['id_alumno'] ?>)">
                        Eliminar
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav aria-label="Paginación de alumnos">
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

    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" onclick="cerrarModal()">&times;</button>
                </div>
                <form method="POST" action="Controladores/ctrl_alumnos.php" onsubmit="return validarFormularioAlumno()">
                    <input type="hidden" name="id_alumno" id="Id_alumno">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombreAlumno" required>
                            <small id="errorNombre" class="text-danger" style="display:none;">No se permiten números en el nombre</small>
                        </div>
                        <div class="form-group">
                            <label>Apellido Paterno</label>
                            <input type="text" class="form-control" name="ape_pa" id="apePaAlumno" required>
                            <small id="errorApePa" class="text-danger" style="display:none;">No se permiten números en el apellido paterno</small>
                        </div>
                        <div class="form-group">
                            <label>Apellido Materno</label>
                            <input type="text" class="form-control" name="ape_ma" id="apeMaAlumno" required>
                            <small id="errorApeMa" class="text-danger" style="display:none;">No se permiten números en el apellido materno</small>
                        </div>
                        <div class="form-group">
                            <label>Género</label>
                            <select class="form-control" name="genero" id="generoAlumno" required>
                                <option value="">Seleccione...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <select class="form-control" name="activo" id="activoAlumno" required>
                                <option value="0">Activo</option>
                                <option value="1">Baja</option>
                                <option value="2">Graduado</option>
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

<script>
    function validarFormularioAlumno() {
        const nombre = document.getElementById('nombreAlumno').value;
        const apePa = document.getElementById('apePaAlumno').value;
        const apeMa = document.getElementById('apeMaAlumno').value;
        let valido = true;
        
        // Validar nombre
        if (/\d/.test(nombre)) {
            document.getElementById('errorNombre').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('errorNombre').style.display = 'none';
        }
        
        // Validar apellido paterno
        if (/\d/.test(apePa)) {
            document.getElementById('errorApePa').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('errorApePa').style.display = 'none';
        }
        
        // Validar apellido materno
        if (/\d/.test(apeMa)) {
            document.getElementById('errorApeMa').style.display = 'block';
            valido = false;
        } else {
            document.getElementById('errorApeMa').style.display = 'none';
        }
        
        return valido;
    }

    function abrirModal(id = '', nombre = '', ape_pa = '', ape_ma = '', genero = '', activo = '0') {
        // Resetear mensajes de error
        document.getElementById('errorNombre').style.display = 'none';
        document.getElementById('errorApePa').style.display = 'none';
        document.getElementById('errorApeMa').style.display = 'none';
        
        // Llenar campos del formulario
        document.getElementById('Id_alumno').value = id;
        document.getElementById('nombreAlumno').value = nombre;
        document.getElementById('apePaAlumno').value = ape_pa;
        document.getElementById('apeMaAlumno').value = ape_ma;
        document.getElementById('generoAlumno').value = genero;
        document.getElementById('activoAlumno').value = activo;
        
        if (id) {
            document.getElementById('modalTitle').textContent = 'Editar Alumno';
            document.getElementById('accionBoton').textContent = 'Actualizar';
            document.getElementById('accionBoton').value = 'actualizar';
        } else {
            document.getElementById('modalTitle').textContent = 'Nuevo Alumno';
            document.getElementById('accionBoton').textContent = 'Guardar';
            document.getElementById('accionBoton').value = 'guardar';
        }
        
        $('#myModal').modal('show');
    }

    function cerrarModal() {
        $('#myModal').modal('hide');
    }

    function confirmarEliminacion(id) {
        if (confirm('¿Eliminar este alumno?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'Controladores/ctrl_alumnos.php';

            const accion = document.createElement('input');
            accion.type = 'hidden';
            accion.name = 'action';
            accion.value = 'eliminar';

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id_alumno';
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