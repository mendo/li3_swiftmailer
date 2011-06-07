<?php
namespace li3_swiftmailer\tests\cases\extensions\adapter;

use lithium\action\Request;
use li3_swiftmailer\extensions\adapter\Swiftmailer;

class SwiftmailerTest extends \lithium\test\Unit {

	public function setUp() {
		$this->_server = $_SERVER;
		$this->_env = $_ENV;
		$this->request = new Request(array('init' => false));
	}

	public function tearDown() {
		$_SERVER = $this->_server;
		$_ENV = $this->_env;
		unset($this->Request);
	}


	function testSend() {
		$sent = Swiftmailer::send($this->request, array(
			'to' => array('edmunds@mendo.lv'),
			'subject' => 'whatevaaa',
			'data' => array('one'=>1),
		));
		$this->assertTrue($sent);

		$sent = Swiftmailer::send($this->request, array(
			'to' => array('edmunds@mendo.lv', 'edmunds@kalnins.net'),
			'subject' => 'whatevaaa 2',
			'data' => array('one'=>1),
		));
		$this->assertEqual($sent, 2);
	}

}