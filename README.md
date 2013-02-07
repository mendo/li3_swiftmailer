li3_swiftmailer
===============

A wrapper that helps working with swiftmailer in li3

Installation:

1) clone
2) run `git submodule init`
3) run `git submodule update`. This will import the swiftmailer library to libraries/swiftmailer
4) add to your app. Here is an example:

```php
Libraries::add('li3_swiftmailer', array(
  'connection' => array(
		'development' => array(
			'type' => 'smtp',
			'host' => 'dev.smtp-server.example',
			'port' => 25,
			'from' => 'test@yourapp.com',
		),
		'test' => array(
  		'type' => 'smtp',
			'host' => 'test.smtp-server.example',
			'port' => 25,
			'from' => 'test@yourapp.com',
		),
		'production' => array(
  		'type' => 'smtp',
			'host' => 'production.smtp-server.example',
			'port' => 25,
			'from' => 'you@yourapp.com',
      'username' => 'user',
		  'password' => 'staple horse battery',
		),
	)
));
```
