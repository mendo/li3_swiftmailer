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
			'to' => array('your.email@your.host'),
			'subject' => 'test',
			'data' => array('one'=>1),
		));
		$this->assertTrue($sent);

		$sent = Swiftmailer::send($this->request, array(
			'to' => array('your.email@your.host', 'your.email@your-other.host'),
			'subject' => 'test 2',
			'data' => array('one'=>1),
		));
		$this->assertEqual($sent, 2);
	}

}