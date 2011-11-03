<?php 
include dirname(__FILE__).'/functions.php';
include dirname(__FILE__).'/config.php';

// Si les magic quotes sont activées
if (get_magic_quotes_gpc()) {
    function magicQuotes_awStripslashes(&$value, $key) {
        $value = stripslashes($value);
    }
    $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    array_walk_recursive($gpc, 'magicQuotes_awStripslashes');
}

$content = '';
if (isset($_POST['html_to_clean'])) {
    $content = HTMLLisible($_POST['html_to_clean'], "\t", $balises_one_line);
}