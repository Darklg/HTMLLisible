<?php 
include dirname(__FILE__).'/functions.php';

// Si les magic quotes sont activées
if (get_magic_quotes_gpc()) {
    function magicQuotes_awStripslashes(&$value, $key) {
        $value = stripslashes($value);
    }
    $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    array_walk_recursive($gpc, 'magicQuotes_awStripslashes');
}

$HTML_Lisible = new HTMLLisible();

