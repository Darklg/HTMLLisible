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
			
			<p>
				<strong>HTMLLisible</strong> est un script permettant de réindenter vos bouts de code HTML, afin de les rendre plus lisibles. 
				Il filtrera les parties en JS, CSS et PHP afin de ne rien casser.
			</p>
			
			<form class="cssn_form" action="" method="post">
				<div>
					<label for="html_to_clean">HTML à réindenter</label>
					<textarea rows="20" cols="100" name="html_to_clean" id="html_to_clean"><?php 
					echo htmlentities($HTML_Lisible->retour_html, ENT_COMPAT,'utf-8'); 
					?></textarea>
				</div>
				<div class="submit">
					<button class="le-button"><span>Réindenter</span></button>
				</div>
				<fieldset class="options">
					<h3>Options</h3>
					<ul>
					    <li class="box"><?php include dirname(__FILE__).'/inc/tpl/form_type_indentation.php'; ?></li>
					</ul>
				</fieldset>
			</form>
			<a href="http://github.com/darklg/HTMLLisible"><img id="fork_me" src="fork-me.png" alt="Fork me on GitHub" /></a>
		</div>
	</body>
</html>