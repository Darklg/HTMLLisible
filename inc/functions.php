<?php

//     HTMLLisible
//     (c) 2012 Kevin Rocher
//     HTMLLisible may be freely distributed under the MIT license.

class HTMLLisible {

    /* Config globale */
    private $balises_one_line = array('a', 'b', 'button', 'em', 'h1', 'h2', 'h3', 'h4', 'i', 'img', 'input', 'label', 'span' ,'strong', 'textarea', 'title');
    public $types_indentation = array(
        array('', 'aucune'),
        array(' ', '1 espace'),
        array('  ', '2 espaces'),
        array('   ', '3 espaces'),
        array('    ', '4 espaces'),
        array("\t", '1 tab')
    );
    private $blocs_isoles = array(
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
        'pre' => array(
            'regex' => '#<pre(.*)\/pre>#isU',
            'list' => array()
        ),
        'textarea' => array(
            'regex' => '#<textarea(.*)\/textarea>#isU',
            'list' => array()
        ),
        'cond' => array(
            'regex' => '#<!--(.*)-->#isU',
            'list' => array()
        ),
        'solotags' => array(
            'regex' => '#<([a-z0-9-]{3,})([^\>]*)\/>#i',
            'list' => array()
        ),
    );

    /* Valeurs initiales */
    private $limit_str_replace = 1;
    private $html = '';

    /* Valeurs utilisées */
    public $retour_html = '';
    public $user_options = array(
        'indentation' => 4,
        'convert_html_to_xhtml' => 1
    );

    private $options_bool = array(
        'convert_html_to_xhtml'
    );

    public function __construct(){

        if(isset($_COOKIE['options'])){
            $this->get_options(unserialize($_COOKIE['options']));
        }

        if (isset($_POST['html_to_clean'],$_POST['options'])) {
            $this->get_options($_POST['options']);

            $html = $_POST['html_to_clean'];

            if($this->user_options['convert_html_to_xhtml']){
                $html = $this->html_to_xhtml($html);
            }

            $html = $this->mise_ecart_blocs($html);
            $html = $this->HTML_Order($html);
            $html = $this->HTML_Lisible($html);
            $html = $this->remise_blocs($html);
            $html = $this->little_clean($html);

            $this->retour_html = $html;

            if(isset($_POST['api'])){
                exit($html);
            }
            setcookie ("options", serialize($this->user_options), time() + 3600);
        }
    }

    private function get_options($options = array()){

        // Si on transmet un tableau serialisé
        if(!is_array($options)){
            $uns = unserialize($options);
            if(is_array($uns)){
                $options = $uns;
            }
        }

        // On parse les options
        foreach($options as $key => $valeur){
            switch ($key) {
                case 'indentation':
                    if(isset($this->types_indentation[$valeur])){
                        $this->user_options['indentation'] = $valeur;
                    }
                    break;
                default:
                    # code...
                    break;
            }
        }

        // On parse les options de type bool
        foreach($this->options_bool as $option){
            $this->user_options[$option] = (isset($options[$option]) && $options[$option] == '1');
        }

    }

    // On met de côté certains contenus de blocs
    private function mise_ecart_blocs($html){
        foreach($this->blocs_isoles as $type_bloc => $bloc){
            $matches = array();
            preg_match_all($this->blocs_isoles[$type_bloc]['regex'],$html,$matches);
            $i=0;
            if(isset($matches[0])){
                foreach($matches[0] as $a_bloc){
                    $i++;
                    $html = str_replace($a_bloc,'<##__'.$type_bloc.'__'.$i.'__'.$type_bloc.'__##/>',$html,$this->limit_str_replace);
                    $this->blocs_isoles[$type_bloc]['list'][$i] = $a_bloc;
                }
            }
        }
        return $html;
    }

    private function remise_blocs($retour_html){
        $blocks_isoles = array_reverse($this->blocs_isoles);
        // On remet les blocks dans le bon ordre
        foreach($blocks_isoles as $type_bloc => $bloc){
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

    // Petit ménage
    private function little_clean($html){

        // Attributs contenant du PHP
        $html = preg_replace('#"([\s]+)<\?php#isU', '"<?php', $html);
        $html = preg_replace('#\?>([\s]+)"#isU', '?>"', $html);

        // Suppression des éventuelles lignes vides
        $l_html = explode("\n",$html);
        $r_html = '';
        foreach($l_html as $ligne){
            if(!preg_match('#^([\s]+)$#',$ligne) && $ligne != ''){
                $r_html .= $ligne."\n";
            }
        }
        $html = $r_html;

        // Trim final
        $html = trim($html);

        return $html;
    }

    // Convert HTML to xHTML
    private function html_to_xhtml($html){
        $html = preg_replace('/\<(img|input|meta|link|param|base)([^>]*)([ "\'a-z]{1})\>/isU', '<$1$2$3/>', $html);

        $html = str_replace('</param>','',$html);
        $html = str_replace(array('<br >','<br>','<br/>'),'<br />',$html);
        $html = str_replace(array('<hr >','<hr>','<hr/>'),'<hr />',$html);

        $html = str_replace('<DOCTYPE HTML','<!DOCTYPE HTML',$html);

        return $html;
    }


    private function HTML_Lisible($html) {

        $retour_html = '';

        // On découpe ligne par ligne
        $lignes_html = explode("\n", $html);
        $indentation_lvl = 0;
        $was_content = false;
        $indent_before = false;
        $line_before = false;
        $line_after = true;

        foreach ($lignes_html as $id_ligne => $ligne) {

            $old_indent_before = $indent_before;
            $old_line_after = $line_after;

            $ligne = trim($ligne);

            // On détecte si la ligne est une balise ouvrante ou fermante
            $is_fermante = (isset($ligne[1]) && $ligne[1] == '/');
            $is_ouvrante = !$is_fermante && (isset($ligne[0]) && $ligne[0] == '<');
            $is_unique = (substr($ligne, -2) == '/>' || substr($ligne, 0, 2) == '<!');

            // On détecte si la ligne suivante ou précédente contient du contenu
            $is_content = (isset($ligne[0]) && $ligne[0] != '<');
            $will_be_content = isset($lignes_html[$id_ligne+1], $lignes_html[$id_ligne+1][0]) && $lignes_html[$id_ligne+1][0] != '<';

            // On détecte si les ligne suivantes et précédentes contient une balise ouvrante ou fermante
            $was_fermante = isset($lignes_html[$id_ligne-1], $lignes_html[$id_ligne-1][1]) && $lignes_html[$id_ligne-1][1] == '/';
            $was_ouvrante = isset($lignes_html[$id_ligne-1], $lignes_html[$id_ligne-1][1]) && $lignes_html[$id_ligne-1][1] != '/' && $lignes_html[$id_ligne-1][0] == '<';
            $will_be_fermante = isset($lignes_html[$id_ligne+1], $lignes_html[$id_ligne+1][1]) && $lignes_html[$id_ligne+1][1] == '/';
            $will_be_ouvrante = isset($lignes_html[$id_ligne+1], $lignes_html[$id_ligne+1][1]) && $lignes_html[$id_ligne+1][1] != '/' && $lignes_html[$id_ligne+1][0] == '<';

            // On traite l'indentation et on charge le fichier
            if ($is_fermante && !$is_unique)
                $indentation_lvl--;

            // On gère les paramètres d'indentation avant la ligne
            $indent_before = $old_line_after;

            // On gère les paramètres de retour après la ligne
            $line_after = true;
            if($is_ouvrante && $will_be_content)
                $line_after = false;
            if($was_ouvrante && $is_content && $will_be_fermante && !$old_line_after)
                $line_after = false;
            if($is_ouvrante && $will_be_fermante)
                $line_after = false;
            if($is_unique)
                $line_after = true;

            $line_before = false;
            if($was_ouvrante && $is_content && $will_be_ouvrante){
                $indent_before = true;
                $line_before = true;
                $old_line_before = true;
            }

            $retour_html .=
                ($line_before ? "\n" : '').
                ($indent_before ? $this->hl_pad($this->types_indentation[$this->user_options['indentation']][0], $indentation_lvl) : '') .
                $ligne .
                ($line_after ? "\n" : '');

            if ($is_ouvrante && !$is_unique)
                $indentation_lvl++;
            $was_content = $is_content;

        }


        // Suppression des sauts de ligne avant br
        $retour_html = preg_replace('/(\s+)<br \/>/isU', '<br />', $retour_html);

        return $retour_html;

    }


    private function hl_pad($value, $nb) {
        $retour = '';
        for ($i = 0; $i < $nb; $i++) {
            $retour .= $value;
        }
        return $retour;
    }
}
