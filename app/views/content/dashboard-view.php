<div class="container is-fluid mb-6">
    <h1 class="title">Estados de alumnos</h1>
    <h3 class="subtitle">Seleccionar institución y materia</h3>
</div>

<div class="container pb-6 pt-6">
    <div class="columns">
        <div class="column">
            <div class="control">
                <label>Instituto</label>
                <div class="control has-icons-left">
                    <div class="select is-medium">
                        <select name="institucion_id" id="institucion_id" onchange="cargarMaterias()" required>
                            <option value="0">--seleccione una opción--</option>
                            <?php
                                use app\controllers\InstitutoController;
                                $selectInstituto = new InstitutoController();
                                echo $selectInstituto->selectInstitutoControlador();
                            ?>
                        </select>
                    </div>
                    <span class="icon is-medium is-left">
                        <i class="fas fa-globe"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="column">
            <div class="control">
                <label>Materia</label>
                <div class="control has-icons-left">
                    <div class="select is-medium">
                        <select name="materia_id" id="materia_id" onchange="cargarAlumnosCondicion()">
                            <option value="">--seleccione una opción--</option>
                        </select>
                    </div>
                    <span class="icon is-medium is-left">
                        <i class="fas fa-globe"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de los alumnos -->
    <h3 class="subtitle">Lista de Alumnos</h3>
    <div class="table-container">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th class="has-text-centered">#</th>
                    <th class="has-text-centered">Nombre</th>
                    <th class="has-text-centered">Apellido</th>
                    <th class="has-text-centered">Promedio</th>
                    <th class="has-text-centered">Asistencia (%)</th>
                    <th class="has-text-centered">Condición</th>
                </tr>
            </thead>
            <tbody id="alumnosMateria">
                
            </tbody>
        </table>
    </div>
</div>

<script>
/* Funcion para cargar materias segun el instituto */
function cargarMaterias() {
    const institucionId = document.getElementById('institucion_id').value;
    const materiaSelect = document.getElementById('materia_id');

    if (!institucionId) {
        materiaSelect.innerHTML = '<option value="">Seleccione una materia</option>';
        return;
    }

    fetch('<?php echo APP_URL;?>app/ajax/materiaAjax.php?institucion_id=' + institucionId)
        .then(response => response.json())
        .then(data => {
            materiaSelect.innerHTML = '<option value="">Seleccione una materia</option>';
            data.forEach(materia => {
                materiaSelect.innerHTML += `<option value="${materia.id}">${materia.nombre}</option>`;
            });
        })
        .catch(error => console.error('Error al cargar materias', error));
}
/* Funcion para cargar alumnos con su condicion */
function cargarAlumnosCondicion() {
    const materia_id = document.getElementById('materia_id').value;
    const alumnosTabla = document.getElementById('alumnosMateria');

    if (!materia_id) {
        alumnosTabla.innerHTML = "<tr><td colspan='6' class='has-text-centered'>Seleccione una materia</td></tr>";
        return;
    }
	const url = `<?php echo APP_URL;?>app/ajax/alumnoAjax.php?materia_id_condicion=${materia_id}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            alumnosTabla.innerHTML = ""; // Limpiar contenido previo
			
            if (data.length > 0) {
                data.forEach((alumno, index) => {
					const promedio = parseFloat(alumno.promedio_notas).toFixed(2);
					const porcentaje = parseFloat(alumno.porcentaje_asistencia).toFixed(2);
				
                    alumnosTabla.innerHTML += `
                        <tr class="has-text-centered">
                            <td>${index + 1}</td>
                            <td>${alumno.nombre}</td>
                            <td>${alumno.apellido}</td>
                            <td>${promedio}</td>
                            <td>${porcentaje}%</td>
                            <td>${alumno.condicion}</td>
                        </tr>`;
                });
            } else {
                alumnosTabla.innerHTML = '<tr><td colspan="6" class="has-text-centered">No hay alumnos disponibles</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error al cargar alumnos:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error al cargar alumnos',
                text: error.message,
            });
        });
}
</script>
