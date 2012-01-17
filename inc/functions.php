<?php


class HTMLLisible {
	
	private $blocs = array();
	private $limit_str_replace = 1;
	private $html = '';
	private $retour_html = '';
	
	
	public function __construct(){
		$this->blocs = array(
			'php' => array(
				'regex' => '#<\?php(.*)\?>#isU',
				'list' => array()
			),
			'css' => array(
				'regex' => '#<style(.*)\/style>#isU',
				'list' => array()
			),
			'js' => array(
				'regex' => '#<script(.*)\/script>#isU',
				'list' => array()
			),
		);
	}
	
	// On met de côté certains contenus de blocs
	private function mise_ecart_blocs($html){
		foreach($this->blocs as $type_bloc => $bloc){
			$matches = array();
			preg_match_all($this->blocs[$type_bloc]['regex'],$html,$matches);
			$i=0;
			if(isset($matches[0])){
				foreach($matches[0] as $a_bloc){
					$i++;
					$html = str_replace($a_bloc,'<##__'.$type_bloc.'__'.$i.'__'.$type_bloc.'__##/>',$html,$this->limit_str_replace);
					$this->blocs[$type_bloc]['list'][$i] = $a_bloc;
				}
			}
		}
		return $html;
	}
	
	private function remise_blocs($retour_html){
		// On remet les blocks
		foreach($this->blocs as $type_bloc => $bloc){
			foreach($bloc['list'] as $id_bloc => $bloca){
				$retour_html = str_replace('<##__'.$type_bloc.'__'.$id_bloc.'__'.$type_bloc.'__##/>', $bloca, $retour_html);
			}
		}
		return $retour_html;
	}
	
	// Rangement du HTML
	private function HTML_Order($html){
		
	    // Tout sur une ligne
	    $html = preg_replace('#([\n\t\r]+)#', '', $html);
	    $html = preg_replace('#([\s]+)([ ]+)([\s]+)#', '', $html);

	    // Chaque balise sur une ligne
	    $html = str_replace('>', '>' . "\n", $html);
	    $html = str_replace('<', "\n" . '<', $html);

	    // On dédoublonne les sauts de ligne
	    $html = str_replace("\n\n", "\n", $html);
		
		return $html;
	}
	
	
	public function HTML_Lisible($html, $indentation_pad = "\t", $balises_one_line = array()) {

		$this->html = $this->mise_ecart_blocs($html);
		
		$this->html = $this->HTML_Order($this->html);

	    // On découpe ligne par ligne
	    $this->retour_html = '';
	    $lignes_html = explode("\n", $this->html);
	    $indentation_lvl = 0;
		$was_content = false;
	    foreach ($lignes_html as $id_ligne => $ligne) {
			$ligne = trim($ligne);
		
	        // On détecte si la ligne est une balise ouvrante ou fermante
	        $is_fermante = (isset($ligne[1]) && $ligne[1] == '/');
	        $is_ouvrante = !$is_fermante && (isset($ligne[0]) && $ligne[0] == '<');
	        $is_unique = (substr($ligne, -2) == '/>' || substr($ligne, 0, 2) == '<!');
			
			// On détecte si la ligne suivante ou précédente contient du contenu
			$is_content = (isset($ligne[1]) && $ligne[0] != '<');
			$will_be_content = isset($lignes_html[$id_ligne+1], $lignes_html[$id_ligne+1][1]) && $lignes_html[$id_ligne+1][0] != '<';

	        // On traite l'indentation et on charge le fichier
	        if ($is_fermante && !$is_unique)
	            $indentation_lvl--;

	        $this->retour_html .= 
				($is_content || $was_content ? '' : $this->hl_pad($indentation_pad, $indentation_lvl)) .
	 			$ligne . 
				($is_content || $will_be_content ? '':"\n");
				
	        if ($is_ouvrante && !$is_unique)
	            $indentation_lvl++;
	

			$was_content = $is_content;
			
	    }


		$this->retour_html = $this->remise_blocs($this->retour_html);


	    return trim($this->retour_html);
	}
	
	
	private function hl_pad($value, $nb) {
	    $retour = '';
	    for ($i = 0; $i < $nb; $i++) {
	        $retour .= $value;
	    }
	    return $retour;
	}
}
