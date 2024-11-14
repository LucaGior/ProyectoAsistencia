<?php

    require_once "../../config/app.php";
    require_once "../../autoload.php";

    use app\controllers\AlumnoController;
    

    
    
    if (isset($_GET)) {
        /* Instancio controlador */
        $insAlumno = new AlumnoController();

        if (isset($_GET['materia_id']) && isset($_GET['fecha'])) {
                
            $materia_id = $_GET['materia_id'];
            $fecha = $_GET['fecha'];
            
            header('Content-Type: application/json');
            /* Llamo al metodo para obtener alumnos por materia con su estado de asistencia */
            echo json_encode($insAlumno->obtenerAlumnosPorMateriaAsistencia($materia_id,$fecha));
            exit;
        }
        if (isset($_GET['materia_id_condicion'])) {
            $materia_id = $_GET['materia_id_condicion'];
            
            header('Content-Type: application/json');
            /* Llamo al metodo para obtener alumnos con sus promedios */
            echo json_encode($insAlumno->obtenerAlumnosCondiciones($materia_id));
            exit;
        }
        if (isset($_GET['materia_id_notas'])){
            $materia_id = $_GET['materia_id_notas'];
            header('Content-Type: application/json');
            /* Llamo al metodo para obtener alumnos con sus notas */
            echo json_encode($insAlumno->obtenerAlumnosPorMateriaNotas($materia_id));
            exit;
        }
        
        if (isset($_GET['materia_id_lista'])) {
            $materia_id = $_GET['materia_id_lista'];

            header('Content-Type: application/json');
            /* Llamo al metodo para obtener alumnos por su materia */
            echo json_encode($insAlumno->obtenerAlumnosPorMateria($materia_id));
            exit;
        }
    }
    /* Verifico si modulo alumno esta definido y llamo al metodo segun su valor */
    if (isset($_POST['modulo_alumno'])) {
        $insAlumno = new AlumnoController();
        
        if ($_POST['modulo_alumno']=="registrar") {
            echo $insAlumno->registrarAlumnoControlador();
        }
        if ($_POST['modulo_alumno'] == "actualizar") {
            header('Content-Type: application/json');
            echo $insAlumno->actualizarAlumnoControlador($_POST);
        }
        if ($_POST['modulo_alumno'] == "eliminar") {
            echo $insAlumno->eliminarAlumnoControlador();
        }
    }else {
        session_destroy();
		header("Location: ".APP_URL."login/");
    }
    
    