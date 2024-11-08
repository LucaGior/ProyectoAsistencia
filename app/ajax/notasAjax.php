<?php
    require_once "../../config/app.php";
    require_once "../../autoload.php";

    use app\controllers\NotaController;

    /* Verifico que modulo notas este definido */
    if (isset($_POST['modulo_notas'])) {
        $insNotas = new NotaController();
        if ($_POST['modulo_notas']=="registrar") {
            echo $insNotas->registrarNotasControlador();
        }
    } else {
        header("Location: ".APP_URL."dashboard/");
    }