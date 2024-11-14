<?php
    require_once "../../config/app.php";
    require_once "../../autoload.php";

    use app\controllers\InstitutoController;

    /* Verifico si llego definido el input modulo instituto */
    if (isset($_POST['modulo_instituto'])) {
        
        /* Instancio el Controller */
        $insInstituto = new InstitutoController();

        /* Llamo al metodo segun el valor de modulo_instituto */
        if ($_POST['modulo_instituto']=="registrar") {
            echo $insInstituto->registrarInstitutoControlador();
        }
        if ($_POST['modulo_instituto']=="eliminar") {
            echo $insInstituto->eliminarInstitutoControlador();
        }
    } else {
        session_destroy();
		header("Location: ".APP_URL."login/");
    }
    