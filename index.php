<?php
include dirname(__FILE__).'/inc/header.php';
?>
<!DOCTYPE HTML>
<html lang="fr-FR">
	<head>
		<meta charset="UTF-8" />
		<title>HTMLLisible</title>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body>
		<div id="main-container">
		<h1>HTMLLisible</h1>
		<form class="cssn_form" action="" method="post">
			<div>
				<label for="html_to_clean">HTML à réindenter</label>
				<textarea rows="30" cols="100" style="display:block;" name="html_to_clean" id="html_to_clean"><?php echo htmlentities($content, ENT_COMPAT,'utf-8'); ?></textarea>
			</div>
			<div class="submit">
				<button class="le-button"><span>Réindenter</span></button>				
			</div>
		</form>
		</div>
	</body>
</html>