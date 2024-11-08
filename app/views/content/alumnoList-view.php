<div class="container is-fluid mb-6">
    <h1 class="title">Lista de Alumnos</h1>
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
                        <select name="materia_id" id="materia_id" onchange="cargarAlumnos()">
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

    <!-- Tabla de condiciones de los alumnos -->
    <h3 class="subtitle">Lista de Alumnos</h3>
    <div class="table-container">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th class="has-text-centered">#</th>
                    <th class="has-text-centered">Nombre</th>
                    <th class="has-text-centered">Apellido</th>
                    <th class="has-text-centered">Fecha Nacimiento</th>
                    <th class="has-text-centered">Email</th>
                    <th class="has-text-centered">DNI</th>
                    <th class="has-text-centered">actualizar</th>
                    <th class="has-text-centered">eliminar</th>
                </tr>
            </thead>
            <tbody id="alumnosMateria">
                <!-- Datos cargados dinámicamente -->
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
/* Funcion para cargar alumno */
function cargarAlumnos() {
    const materia_id = document.getElementById('materia_id').value;
    const alumnosTabla = document.getElementById('alumnosMateria');

    if (!materia_id) {
        alumnosTabla.innerHTML = "<tr><td colspan='6' class='has-text-centered'>Seleccione una materia</td></tr>";
        return;
    }
	const url = `<?php echo APP_URL;?>app/ajax/alumnoAjax.php?materia_id_lista=${materia_id}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            alumnosTabla.innerHTML = ""; // Limpiar contenido previo
			
            if (data.length > 0) {
                data.forEach((alumno, index) => {
					alumnosTabla.innerHTML += `
						<tr class="has-text-centered">
							<td>${index + 1}</td>
							<td>${alumno.nombre}</td>
							<td>${alumno.apellido}</td>
							<td>${alumno.fecha_nac}</td>
							<td>${alumno.email}</td>
							<td>${alumno.dni}</td>
							<td>
								<button class="button is-success is-rounded is-small" onclick="activarEdicion(${alumno.id}, ${index})">Actualizar</button>
							</td>
							<td>
                                <button class="button is-danger is-rounded is-small" onclick="eliminarAlumno(${alumno.id})">Eliminar</button>
                            </td>

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
/* Funcion para activar los campos para modificar alumno */
function activarEdicion(alumnoId, index) {
    const alumnosTabla = document.getElementById('alumnosMateria');
    const fila = alumnosTabla.rows[index];

    // Cambia las celdas a inputs para editar y agrega la clase "input"
    const nombre = fila.cells[1].innerText;
    const apellido = fila.cells[2].innerText;
    const fecha_nac = fila.cells[3].innerText;
    const email = fila.cells[4].innerText;
    const dni = fila.cells[5].innerText;

    fila.cells[1].innerHTML = `<input type="text" value="${nombre}" class="input" data-field="nombre">`;
    fila.cells[2].innerHTML = `<input type="text" value="${apellido}" class="input" data-field="apellido">`;
    fila.cells[3].innerHTML = `<input type="date" value="${fecha_nac}" class="input"data-field="fecha_nac" >`;
    fila.cells[4].innerHTML = `<input type="email" value="${email}" class="input" data-field="email">`;
    fila.cells[5].innerHTML = `<input type="text" value="${dni}" class="input" data-field="dni" >`;
    fila.cells[6].innerHTML = `
        <button class="button is-primary is-rounded is-small" type="submit" onclick="guardarCambios(${alumnoId}, ${index})">Guardar</button>
    `;
}
/* Funcion para guardar cambios de alumno */
function guardarCambios(alumnoId, index) {
    const alumnosTabla = document.getElementById('alumnosMateria');
    const fila = alumnosTabla.rows[index];

    const nombre = fila.cells[1].querySelector('input[data-field="nombre"]').value;
    const apellido = fila.cells[2].querySelector('input[data-field="apellido"]').value;
    const fecha_nac = fila.cells[3].querySelector('input[data-field="fecha_nac"]').value;
    const email = fila.cells[4].querySelector('input[data-field="email"]').value;
    const dni = fila.cells[5].querySelector('input[data-field="dni"]').value;

    const url = `<?php echo APP_URL; ?>app/ajax/alumnoAjax.php`;

    const data = new FormData();
    data.append('modulo_alumno', 'actualizar');
    data.append('alumno_id', alumnoId);
    data.append('nombre', nombre);
    data.append('apellido', apellido);
    data.append('fecha_nac', fecha_nac);
    data.append('email', email);
    data.append('dni', dni);

    fetch(url, {
        method: 'POST',
        body: data
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(alertData => {
        console.log(alertData)
        Swal.fire({
            icon: alertData.icono, 
            title: alertData.titulo, 
            text: alertData.texto, 
        });

        /* Recargar la lista de alumnos*/
        cargarAlumnos();
    })
    .catch(error => {
        console.error('Error al guardar cambios:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ha ocurrido un error inesperado.',
        });
    });
}
/* Funcion para eliminar alumno */
function eliminarAlumno(alumnoId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminara el alumno del instituto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = '<?php echo APP_URL; ?>app/ajax/alumnoAjax.php';
            const data = new FormData();
            data.append('modulo_alumno', 'eliminar');
            data.append('alumno_id', alumnoId);

            fetch(url, {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(alertData => {
                Swal.fire({
                    icon: alertData.icono, 
                    title: alertData.titulo, 
                    text: alertData.texto, 
                });

                /* Recargar la lista de alumnos después de eliminar*/
                cargarAlumnos();
            })
            .catch(error => {
                console.error('Error al eliminar alumno:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo eliminar el alumno.',
                });
            });
        }
    });
}

</script>