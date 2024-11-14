<?php
    require_once "../../config/app.php";
    require_once "../../autoload.php";

    use app\controllers\MateriaController;
    
    /* Verifico si hay una solicitud GET para obtener materias */
    if (isset($_GET['institucion_id'])) {
        
        $institucionId = $_GET['institucion_id'];
        $insMateria = new MateriaController();
        /* Llamo al metodo para devolver las materias de un instituto */
        header('Content-Type: application/json');
        echo json_encode($insMateria->obtenerMateriasPorInstitucion($institucionId));
        exit;
    }

    /* Verifico si llego definido el input modulo materia */
    if (isset($_POST['modulo_materia'])) {

        $insMateria = new MateriaController();

        /* Llamo al metodo segun el valor de modulo_materia */
        if ($_POST['modulo_materia']=="registrar") {
            echo $insMateria->registrarMateriaControlador();
        }
        if ($_POST['modulo_materia']=="eliminar") {
            echo $insMateria->eliminarMateriaControlador();
        }
        
    } else {
        session_destroy();
		header("Location: ".APP_URL."login/");
    }
    