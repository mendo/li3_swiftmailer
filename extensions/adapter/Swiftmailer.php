<?php
/**
 * Swiftmailer adapteris
 *
 * @copyright	SIA Mendo, 2011 (http://www.mendo.lv)
 */
namespace li3_swiftmailer\extensions\adapter;

use lithium\template\View;

use Swift_SmtpTransport;
use Swift_MailTransport;
use Swift_Mailer;
use Swift_Message;
//use Swift_Attachment;

/**
 * Swiftmailer adapteris.
 *
 * Adapteri var, protams, izmantot pa tiešo, bet tad projektā jāiekļauj ari pats swiftmailer, kā
 * ari jāpamaina bootsrap fails.
 *
 * Vienkāršāk šo ir izmantot kā library/pluginu. Tādā gadījumā jāpievieno li3_swiftmailer ar
 * `Libraries::add()` norādot konfigurācijā masīvā `connections` vismaz sekojošo informāciju:
 * 	- type - php vai smtp
 *	- from - dafault from adrese
 *	- host - ja `type` ir `smtp`, tad jānorāda SMTP hosts
 *	- port - dafault ir 25, bet var norādīt citu, ja nepieciešams
 *  - username, password - ja nepieciešams SMTP autentifikācijai
 */
class Swiftmailer extends \lithium\core\Adaptable {

	/**
	 * Stores configurations for cache adapters.
	 *
	 * @var object `Collection` of logger configurations.
	 */
	protected static $_configurations = array();

	/**
	 * Libraries::locate() compatible path to adapters for this class.
	 *
	 * @see lithium\core\Libraries::locate()
	 * @var string Dot-delimited path.
	 */
	protected static $_adapters = 'li3_swiftmailer.extensions.adapter.swiftmailer';

	/**
	 * Nosūta pašu epastu izmantojot SwiftMaileri
	 *
	 * Servera daļa tiek konfigurēta pievienojot libraryu, vai izmantojot Swiftmailer::config().
	 * Swiftmailer sagaida template, kas būs views/controller_name/action.mail.php
	 *
	 * @param object $request Controller-a request objekts ($this->request kontrolierī)
	 * @param array $params Papildu parametri:
	 *  - `to` - array ar saņēmējiem, katram epasts tiks nosūtīts individuāli. Var lietot ari epasta
	 *   adresi kā key un vārdu, kā value
	 *  - `from` - adrese, no kuras tiks izsūtīts epasts. šo var nenorādīt, ja tā ir norādīta
	 *   library konfigurācijā
	 *  - `subject` - subjects
	 *  - `data` - key, value pāri, kas tiks nodoti template
	 */
	public static function send($request, array $params = array()){
		$_connection = self::_config('connection');

		if($request->argv) {
			$webroot = "/";
			$scheme = "http://";
		}
		else {
			$webroot = $request->get('env:HTTP_HOST').$request->get('env:base');
			$scheme = $request->env('HTTPS') ? 'https://' : 'http://';
		}
		$_defaults = array(
			'to' => array(),
			'subject' => 'Testa epasts',
			'data' => null,
		);
		if(isset($_connection['from'])) {
			$_defaults['from'] = $_connection['from'];
		}
		$params += $_defaults;
		$params['data'] += array('root'=>$scheme.$webroot);

		if($request->controller && $request->action) {
			$view  = new View(array(
				'loader' => 'File',
				'renderer' => 'File',
				'paths' => array(
					'template' => '{:library}/views/{:controller}/{:template}.{:type}.php'
				)
			));

			$body = $view->render(
				'template',
				$params['data'],
				array(
					'controller' => $request->controller,
					'template' => $request->action,
					'type' => 'mail',
					'layout' => false
				)
			);
		}
		elseif(!empty($params['template'])) {
			$view  = new View(array(
				'loader' => 'File',
				'renderer' => 'File',
				'paths' => array(
					'template' => '{:library}/views/emails/{:template}.{:type}.php'
				)
			));

			$body = $view->render(
				'template',
				$params['data'],
				array(
					'template' => $params['template'],
					'type' => 'mail',
					'layout' => false
				)
			);
		}
		else {
			$body = "Testa teksts";
		}

		if(!empty($_connection['type']) && $_connection['type'] == 'smtp') {
			$transport = Swift_SmtpTransport::newInstance($_connection['host'], $_connection['port']);
			if(!empty($_connection['username'])) {
				$transport->setUsername($_connection['username']);
			}
			if(!empty($_connection['password'])) {
				$transport->setUsername($_connection['password']);
			}
		}
		else {
			$transport = Swift_MailTransport::newInstance();
		}

		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance();
		$message->setSubject($params['subject']);
		$message->setFrom($params['from']);
		$message->setTo($params['to']);
		$message->setBody($body);
		$message->setContentType("text/html");

		if(count($params['to']) > 1) {
			return $mailer->batchSend($message);
		}
		else {
			return $mailer->send($message);
		}
	}

}
?>