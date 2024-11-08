<?php
    require_once "./config/app.php";
    require_once "./autoload.php";
    
    /* Si no hay una view definida devolver principal(dashboard) */
    $url = isset($_GET['views']) ? explode("/", $_GET['views']) : ["dashboard"];   
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once "./app/views/inc/head.php"; ?>
</head>
<body> 
    <?php 
        /* Instanciacion ViewsController y Metodo para vistas */
        use app\controllers\ViewsController;
        $viewsController= new ViewsController();
        $vista = $viewsController->obtenerVistasControlador($url[0]);

        /* Si vista es 404 muetra pagina de error sino muestra pagina y aplica scripts */
        if($vista=="404"){
            require_once "./app/views/content/".$vista."-view.php";
        }else{
            require_once "./app/views/inc/navbar.php";
            require_once $vista;
        }
        require_once "./app/views/inc/script.php";
     ?>
</body>
</html>