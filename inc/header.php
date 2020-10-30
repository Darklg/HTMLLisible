<?php
include dirname(__FILE__) . '/functions.php';

// Si les magic quotes sont activées
if (version_compare(phpversion(), '7.4', '<') && get_magic_quotes_gpc()) {
    function magicQuotes_awStripslashes(&$value, $key) {
        $value = stripslashes($value);
    }
    $gpc = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    array_walk_recursive($gpc, 'magicQuotes_awStripslashes');
}

$HTML_Lisible = new HTMLLisible();

// Bugfix Chrome
header('X-XSS-Protection: 0');
