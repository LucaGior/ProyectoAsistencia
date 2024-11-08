<div class="container is-fluid mb-6">
    <h1 class="title">Notas</h1>
    <h2 class="subtitle">registro de notas</h2>
    <h3 class="subtitle">Seleccionar institucion y materia</h3>
</div>

<div class="container pb-6 pt-6">
    <form class="FormularioAjax" action="<?php echo APP_URL;?>app/ajax/notasAjax.php" method="POST" autocomplete="off">
        <input type="hidden" name="modulo_notas" value="registrar">
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Instituto</label>
                    <div class="control has-icons-left">
                        <div class="select is-medium">
                            <div class="table-container">
                                <select name="institucion_id" id="institucion_id" onchange="cargarMaterias()" required>
                                    <option value="0">--seleccione una opcion--</option>
                                    <?php
                                        use app\controllers\InstitutoController;
                                        $selectInstituto= new InstitutoController();
                                        echo $selectInstituto->selectInstitutoControlador();
                                    ?>
                                </select>
                            </div>
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
                            <select name="materia_id" id="materia_id" onchange="cargarAlumnosNotas()">
                                <option value="">--seleccione una opcion--</option>
                            </select>
                        </div>
                        <span class="icon is-medium is-left">
                            <i class="fas fa-globe"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    <!-- tabla de alumnos -->
    
        <h3 class="subtitle">Lista de Alumnos</h3>
        <div class="table-container">
            <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                    <tr>
                        <th class="has-text-centered">#</th>
                        <th class="has-text-centered">Alumno</th>
                        <th class="has-text-centered">Apellido</th>
                        <th class="has-text-centered">1er parcial</th>
                        <th class="has-text-centered">2do parcial</th>
                        <th class="has-text-centered">final</th>
                    </tr>
                </thead>
                <tbody id="alumnosMateria"> 
                </tbody>
            </table>
        </div>
        <div class="has-text-centered">
            <button type="submit" class="button is-info is-rounded">Guardar Notas</button>
        </div>
    </form>
</div>

<script>
/* Funcion para cargar materias segun el instituto */
function cargarMaterias() {
    const institucionId = document.getElementById('institucion_id').value;
    const alumnosTabla = document.getElementById('materia_id');

    if (!institucionId) {
        alumnosTabla.innerHTML = '<option value="">Seleccione una materia</option>';
        return;
    }

    fetch('<?php echo APP_URL;?>app/ajax/materiaAjax.php?institucion_id=' + institucionId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener materias');
            }
            return response.json();
        })
        .then(data => {
            alumnosTabla.innerHTML = '<option value="">Seleccione una materia</option>';

            if (data.length > 0) {
                data.forEach(materia => {
                    alumnosTabla.innerHTML += `<option value="${materia.id}">${materia.nombre}</option>`;
                });
            } else {
                alumnosTabla.innerHTML = '<option value="">No hay materias disponibles</option>';
            }
        })
        .catch(error => console.error('Error al cargar materias', error));
}
/* Funcion para cargar alumnos con sus notas */
function cargarAlumnosNotas() {
    const materia_id = document.getElementById('materia_id').value;
    const alumnosTabla = document.getElementById('alumnosMateria');

    if (!materia_id) {
        alumnosTabla.innerHTML = "<tr><td colspan='6' class='has-text-centered'>Seleccione una materia</td></tr>";
        return;
    }

    const url = `<?php echo APP_URL;?>app/ajax/alumnoAjax.php?materia_id_notas=${materia_id}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener alumnos');
            }
            return response.json();
        })
        .then(data => {
            alumnosTabla.innerHTML = ""; // Limpiar contenido previo

            if (data.length > 0) {
                data.forEach((alumno, index) => {                 
                    alumnosTabla.innerHTML += `
                        <tr class="has-text-centered">
                            <td>${index + 1}</td>
                            <td>${alumno.nombre}</td>
                            <td>${alumno.apellido}</td>
                            <td><input class="input" type="text" name="nota1_${alumno.id_inscripcion}" value="${alumno.nota1 || ''}"></td>
                            <td><input class="input" type="text" name="nota2_${alumno.id_inscripcion}" value="${alumno.nota2 || ''}"></td>
                            <td><input class="input" type="text" name="nota3_${alumno.id_inscripcion}" value="${alumno.nota3 || ''}"></td>
                            <input type="hidden" name="id_inscripcion[]" value="${alumno.id_inscripcion}">
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