<?php
function hl_pad($value,$nb){
	$retour = '';
	for($i=0;$i<$nb;$i++){
		$retour .= $value;
	}
	return $retour;
}


function HTMLLisible($html,$indentation_pad="\n"){
	// Balises sur une seule ligne
	$balises_one_line = array('a', 'b', 'button', 'em', 'h1', 'h2', 'h3', 'h4', 'i', 'label', 'strong','textarea','title');
	
	// Tout sur une ligne
	$html = preg_replace('#([\n\t\r]+)#','',$html);
	$html = preg_replace('#([\s]+)([ ]+)([\s]+)#','',$html);
	
	
	// Chaque balise sur une ligne
	$html = str_replace('>','>'."\n",$html);
	$html = str_replace('<',"\n".'<',$html);
	
	// On dédouble les sauts de ligne
	$html = str_replace("\n\n","\n",$html);
	
	// On découpe ligne par ligne
	$retour_html = '';
	$lignes_html = explode("\n",$html);
	$indentation_lvl = 0;
	foreach($lignes_html as $ligne){
		// On détecte si la ligne est une balise ouvrante ou fermante
		$is_fermante = (isset($ligne[1]) && $ligne[1] == '/');
		$is_ouvrante = !$is_fermante && (isset($ligne[0]) && $ligne[0] == '<');
		$is_unique = (substr($ligne,-2) == '/>' || substr($ligne,0,2) == '<!');
			
		// On traite l'indentation et on charge le fichier
		if($is_fermante && !$is_unique) $indentation_lvl--;
		$retour_html .= hl_pad($indentation_pad,$indentation_lvl).$ligne."\n";
		if($is_ouvrante && !$is_unique) $indentation_lvl++;
	}

	foreach($balises_one_line as $balise){
		$retour_html = preg_replace('#([\n\t]*)</'.$balise.'>#U','</'.$balise.'>',$retour_html);
		$retour_html = preg_replace('#<'.$balise.'>(.*)([\S])#sU','<'.$balise.'>$2',$retour_html);
		$retour_html = preg_replace('#<'.$balise.' (.*)>(.*)([\S])#sU','<'.$balise.' $1>$3',$retour_html);
	}
	
	return trim($retour_html);
}

$content = '';

if (get_magic_quotes_gpc()) {
    function magicQuotes_awStripslashes(&$value, $key) {$value = stripslashes($value);}
    $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    array_walk_recursive($gpc, 'magicQuotes_awStripslashes');
}

if(isset($_POST['html_to_clean'])){
	$content = HTMLLisible($_POST['html_to_clean'],"\t");
}


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