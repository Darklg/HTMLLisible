<?php

/*
@TODO : Gestion des blocs PHP <?php ?>
@TODO : Gestion des STYLE et SCRIPT
@TODO : Gestion des balises inline en front
@TODO : Style basique pour la page
@TODO : Algo : Si une balise inline a un enfant block : reindenter
*/

include dirname(__FILE__).'/inc/header.php';
?>
<!DOCTYPE HTML>
<html lang="fr-FR">
	<head>
		<title>HTMLLisible</title>
		<meta charset="UTF-8" />
	</head>
	<body>
		<h1>HTMLLisible</h1>
		<form action="" method="post">
			<div>
				<label for="html_to_clean">
					HTML à nettoyer
				</label>
				<textarea rows="30" cols="100" style="display:block;" name="html_to_clean" id="html_to_clean"><?php echo htmlentities($content, ENT_COMPAT,'utf-8'); ?></textarea>
			</div>
			<button>Reindent</button>
		</form>
	</body>
</html>