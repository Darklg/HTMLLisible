<?php
include dirname(__FILE__).'/inc/header.php';
?><!DOCTYPE HTML>
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
			<a href="http://github.com/darklg/HTMLLisible"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://a248.e.akamai.net/assets.github.com/img/7afbc8b248c68eb468279e8c17986ad46549fb71/687474703a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6461726b626c75655f3132313632312e706e67" alt="Fork me on GitHub" /></a>
		</div>
	</body>
</html>