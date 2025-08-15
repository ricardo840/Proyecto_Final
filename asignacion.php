<?php
define('SECURE_ACCESS', true);
require_once "init.php"; 
require_once "modelos/mdl_asignacion.php";

$modelo = new MdlAsignacion();
// Lógica de paginación
$limite = 8; // Número de asignaciones por página
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$inicio = ($pagina - 1) * $limite;

// Obtener total de asignaciones
$totalRegistros = $modelo->contarAsignaciones();
$totalPaginas = ceil($totalRegistros / $limite);

// Obtener las asignaciones paginadas
$asignaciones = $modelo->obtenerPaginado($inicio, $limite);

// Obtener datos para los selects
$grupos = $modelo->obtenerGrupos();
$maestros = $modelo->obtenerMaestros();
$materias = $modelo->obtenerMaterias();
$alumnos = $modelo->obtenerTodosAlumnos();

$mensajeExito = $_GET['exito'] ?? '';
$mensajeError = $_GET['error'] ?? '';

require_once "vistas/parte_superior.php";
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tutorias</h1>
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
                <th>Grupo</th>
                <th>Profesor</th>
                <th>Materia</th>
                <th>Fecha y Hora</th>
                <th>Alumnos</th>
                <th>Hombres</th>
                <th>Mujeres</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($asignaciones as $asignacion): ?>
            <tr>
                <td><?= htmlspecialchars($asignacion['grupo'] ?? '') ?></td>
                <td><?= htmlspecialchars($asignacion['profesor'] ?? '') ?></td>
                <td><?= htmlspecialchars($asignacion['materia'] ?? '') ?></td>
                <td><?= htmlspecialchars($asignacion['fecha_hora'] ?? '') ?></td>
                <td><?= htmlspecialchars($asignacion['nombres_alumnos'] ?? '') ?></td>
                <td><?= htmlspecialchars($asignacion['hombres'] ?? '0') ?></td>
                <td><?= htmlspecialchars($asignacion['mujeres'] ?? '0') ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                        onclick="abrirModalEditar('<?= $asignacion['id_asignacion'] ?>')">
                        Editar
                    </button>
                    <button class="btn btn-danger btn-sm" 
                        onclick="confirmarEliminacion('<?= $asignacion['id_asignacion'] ?>')">
                        Eliminar
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="modal fade" id="modalAsignacion">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="close" onclick="cerrarModal()">&times;</button>
                </div>
                <form method="POST" action="Controladores/ctrl_asignacion.php" id="formAsignacion">
                    <input type="hidden" name="id_asignacion" id="id_asignacion">
                    <input type="hidden" name="action" id="accionAsignacion" value="guardar">
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Grupo</label>
                                    <select class="form-control select2" name="id_grupo" id="selectGrupo" required>
                                        <option value="">Seleccione un grupo</option>
                                        <?php foreach($grupos as $grupo): ?>
                                            <option value="<?= $grupo['id_grupo'] ?>"><?= htmlspecialchars($grupo['grupo']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Profesor</label>
                                    <select class="form-control select2" name="id_maestro" id="selectMaestro" required>
                                        <option value="">Seleccione un profesor</option>
                                        <?php foreach($maestros as $maestro): ?>
                                            <option value="<?= $maestro['id_maestros'] ?>"><?= htmlspecialchars($maestro['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Materia</label>
                                    <select class="form-control select2" name="id_materia" id="selectMateria" required>
                                        <option value="">Seleccione una materia</option>
                                        <?php foreach($materias as $materia): ?>
                                            <option value="<?= $materia['id_materia'] ?>"><?= htmlspecialchars($materia['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="date" class="form-control" name="fecha" id="inputFecha" required>
                                <small class="text-muted">Solo días hábiles (Lunes a Viernes)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora</label>
                                <select class="form-control" name="hora" id="selectHora" required>
                                    <option value="08:00:00">8:00 AM</option>
                                    <option value="08:50:00">8:50 AM</option>
                                    <option value="09:40:00">9:40 AM</option>
                                    <option value="10:30:00">10:30 AM</option>
                                    <option value="10:55:00">10:55 AM</option>
                                    <option value="11:45:00">11:45 AM</option>
                                    <option value="12:35:00">12:35 PM</option>
                                    <option value="13:25:00">1:00 PM</option>
                                    <option value="13:25:00">1:25 PM</option>
                                    <option value="13:25:00">1:50 PM</option>
                                    <option value="14:40:00">2:40 PM</option>
                                    <option value="15:30:00">3:30 PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                        
                        <div class="form-group">
                            <label>Alumnos</label>
                            <div class="alumnos-checkbox-container" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                <?php foreach($alumnos as $alumno): ?>
                                <div class="form-check">
                                    <input class="form-check-input alumno-checkbox" type="checkbox" 
                                        name="alumnos[]" 
                                        id="alumno_<?= $alumno['id_alumno'] ?>" 
                                        value="<?= $alumno['id_alumno'] ?>"
                                        data-genero="<?= $alumno['genero'] ?>">
                                    <label class="form-check-label" for="alumno_<?= $alumno['id_alumno'] ?>">
                                        <?= htmlspecialchars($alumno['nombre_completo']) ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hombres</label>
                                    <input type="text" class="form-control" name="hombres" id="inputHombres" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mujeres</label>
                                    <input type="text" class="form-control" name="mujeres" id="inputMujeres" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<nav aria-label="Paginación de asignaciones">
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script>
    $(document).ready(function() {
        // Inicializar contadores
        actualizarContadores();
        
        // Evento para checkboxes de alumnos
        $('.alumno-checkbox').change(function() {
            actualizarContadores();
        });
    });

    function abrirModal() {
        // Limpiar el formulario
        $('#formAsignacion')[0].reset();
        $('.alumno-checkbox').prop('checked', false);
        $('#inputHombres').val('0');
        $('#inputMujeres').val('0');
        
        // Configurar fecha mínima (hoy)
        const hoy = new Date();
        const hoyLocal = new Date(hoy.getTime() - hoy.getTimezoneOffset() * 60000);
        const inputFecha = document.getElementById('inputFecha');
        inputFecha.min = hoyLocal.toISOString().split('T')[0];
        
        // Validar día hábil
        inputFecha.addEventListener('input', function() {
            const fechaSeleccionada = new Date(this.value + 'T00:00:00');
            const dia = fechaSeleccionada.getDay(); // 0=Dom, 1=Lun, ..., 6=Sáb
            
            if (dia === 0 || dia === 6) {
                alert('Solo se permiten días hábiles (Lunes a Viernes)');
                this.value = '';
            } else {
                const fechaSeleccionadaLocal = new Date(fechaSeleccionada.getTime() - fechaSeleccionada.getTimezoneOffset() * 60000);
                const hoySinHora = new Date(hoyLocal.toISOString().split('T')[0]);
                
                if (fechaSeleccionadaLocal < hoySinHora) {
                    alert('No se pueden seleccionar fechas pasadas');
                    this.value = '';
                }
            }
        });
        
        $('#modalAsignacion').modal('show');
    }

    function actualizarContadores() {
        let hombres = 0;
        let mujeres = 0;
        
        $('.alumno-checkbox:checked').each(function() {
            if ($(this).data('genero') === 'M') {
                hombres++;
            } else if ($(this).data('genero') === 'F') {
                mujeres++;
            }
        });
        
        $('#inputHombres').val(hombres);
        $('#inputMujeres').val(mujeres);
    }

    document.getElementById('selectAlumnos').addEventListener('change', actualizarContadores);

    function abrirModalEditar(id) {
        fetch(`Controladores/ctrl_asignacion.php?action=obtener&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    // Limpiar selecciones anteriores
                    $('.alumno-checkbox').prop('checked', false);
                    
                    // Establecer valores básicos
                    $('#id_asignacion').val(data.id_asignacion);
                    $('#selectGrupo').val(data.id_grupo).trigger('change');
                    $('#selectMaestro').val(data.id_maestros).trigger('change');
                    $('#selectMateria').val(data.id_materia).trigger('change');
                    $('#inputFecha').val(data.fecha);
                    $('#selectHora').val(data.hora);
                    $('#accionAsignacion').val('actualizar');
                    $('#modalTitle').text('Editar Asignación');
                    $('#btnSubmit').text('Actualizar');
                    
                    // Marcar alumnos seleccionados
                    if (data.alumnos_ids) {
                        const alumnosIds = data.alumnos_ids.split(',');
                        alumnosIds.forEach(id => {
                            $(`#alumno_${id}`).prop('checked', true);
                        });
                    }
                    
                    // Actualizar contadores
                    actualizarContadores();

                    // Configurar fecha mínima (hoy)
                    const hoy = new Date();
                    const hoyLocal = new Date(hoy.getTime() - hoy.getTimezoneOffset() * 60000);
                    const inputFecha = document.getElementById('inputFecha');
                    inputFecha.min = hoyLocal.toISOString().split('T')[0];
                    
                    inputFecha.addEventListener('input', function() {
                    const fechaSeleccionada = new Date(this.value + 'T00:00:00');
                    const dia = fechaSeleccionada.getDay(); // 0=Dom, 1=Lun, ..., 6=Sáb
                    
                    if (dia === 0 || dia === 6) {
                        alert('Solo se permiten días hábiles (Lunes a Viernes)');
                        this.value = '';
                    } else {
                        const fechaSeleccionadaLocal = new Date(fechaSeleccionada.getTime() - fechaSeleccionada.getTimezoneOffset() * 60000);
                        const hoySinHora = new Date(hoyLocal.toISOString().split('T')[0]);
                        
                        if (fechaSeleccionadaLocal < hoySinHora) {
                            alert('No se pueden seleccionar fechas pasadas');
                            this.value = '';
                        }
                    }
                });


                    $('#modalAsignacion').modal('show');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos de la asignación');
            });
    }

    function cerrarModal() {
        $('#modalAsignacion').modal('hide');
    }

    function confirmarEliminacion(id) {
        if (confirm('¿Eliminar esta asignación?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'Controladores/ctrl_asignacion.php';

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

    // Inicializar contadores al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('inputHombres').value = '0';
        document.getElementById('inputMujeres').value = '0';
        
    });
</script>

<?php
require_once "vistas/parte_inferior.php";
?>