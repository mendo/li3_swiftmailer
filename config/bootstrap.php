<?php
/**
 * Includes swiftmailer libraries
 */
\lithium\core\Libraries::add('swiftmailer', array(
	'path' => dirname(__DIR__) . '/libraries/swiftmailer/lib',
     'bootstrap' => 'swift_required.php'
));
?>