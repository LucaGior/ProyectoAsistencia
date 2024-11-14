<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="<?php echo APP_URL; ?>dashboard/">
            <img src="<?php echo APP_URL; ?>app\views\img\icons8-asistencia-100.png" alt="">
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
            
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Instituto</a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="<?php echo APP_URL; ?>institutoNew/">Nuevo</a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>institutoList/">Lista</a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Materia</a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="<?php echo APP_URL; ?>materiaNew/">Nueva</a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>materiaList/">Lista</a>
                </div>
            </div>

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">Alumnos</a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="<?php echo APP_URL; ?>alumnoNew/">Nuevo</a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>alumnoList/">Lista</a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>asistencia/">Asistencia</a>
                    <a class="navbar-item" href="<?php echo APP_URL; ?>notas/">Notas</a>
                </div>
            </div>
            <a class="navbar-item" href="<?php echo APP_URL; ?>dashboard/">Estadisticas Alumnos</a>            
        </div>
        <div class="navbar-end">          
            <a class="navbar-item" href="<?php echo APP_URL."logOut/"; ?>" id="btn_exit" >
                Salir
            </a>

                
            
        </div>
    </div>
</nav>