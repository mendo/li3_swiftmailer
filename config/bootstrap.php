<?php
/**
 * Bootsrapo li3_swiftmailer pluginu
 * Includo pašu swiftmaileru, kā ari ielādē servera konfigurāciju
 */
use lithium\core\Libraries;
use li3_swiftmailer\extensions\adapter\Swiftmailer;

/**
 * Includes swiftmailer libraries
 */
Libraries::add('swiftmailer', array(
	'path' => dirname(__DIR__) . '/libraries/swiftmailer/lib',
     'bootstrap' => 'swift_required.php'
));


/**
 * Configure adapter
 */
Swiftmailer::config(Libraries::get('li3_swiftmailer') + array(
	'type' => 'php',
	'from' => 'me@mydomain.com',
	'host' => 'localhost',
	'port' => 25
));
?>