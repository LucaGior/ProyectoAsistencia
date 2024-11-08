<div class="container is-fluid mb-6">
	<h1 class="title">Materias</h1>
	<h2 class="subtitle">Lista de Materias</h2>
</div>
<div class="container pb-6 pt-6">


	<div class="container pb-6 pt-6">
		<h3 class="subtitle">Lista de Materias</h3>
		<div class="table-container">
			<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
				<tbody id="materias">
					<?php
						use app\controllers\MateriaController;
						$insMateria = new MateriaController();
						echo $insMateria->listarMateriaControlador();
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
