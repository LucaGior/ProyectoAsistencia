<?php
    require_once "../../config/app.php";
    require_once "../../autoload.php";

    use app\controllers\AsistenciaController;

    /*  Verifico si modulo alumno esta definido */
    if (isset($_POST['modulo_asistencia'])) {
        
        $insAsistencia = new AsistenciaController();
            /* Verifico si los campos estan definidos para luego registrar la asistencia */
            if ($_POST['modulo_asistencia']=="registrar" && isset($_POST['asistencia']) && isset($_POST['materia_id']) && isset($_POST['fecha'])) {
                
                $insAsistencia = new AsistenciaController();
                echo $insAsistencia->registrarAsistenciaControlador();
            } 
    } else {
        session_destroy();
		header("Location: ".APP_URL."login/");
    }