<div class="container is-fluid mb-6">
	<h1 class="title">Instituciones</h1>
	<h2 class="subtitle">Lista de Instituciones</h2>
</div>
<div class="container pb-6 pt-6">

	<?php
		use app\controllers\InstitutoController;

		$insInstituto= new InstitutoController();

		echo $insInstituto->listarInstitutoControlador();
	?>	

</div>