<?php

namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use App\Models\Invite;
use CodeIgniter\Controller;

include_once ROOTPATH . '/php-jwt/src/BeforeValidException.php';
include_once ROOTPATH . '/php-jwt/src/ExpiredException.php';
include_once ROOTPATH . '/php-jwt/src/SignatureInvalidException.php';
include_once ROOTPATH . '/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

class BaseController extends Controller
{
	protected $tokenUser;

	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods:OPTIONS, GET, POST, OPTIONS, PUT, DELETE");

		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			header('Access-Control-Allow-Origin: *');
			header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
			header("Access-Control-Allow-Methods:OPTIONS, GET, POST, OPTIONS, PUT, DELETE");
			header("HTTP/1.1 200 OK");
			die();
		}
	}

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
	}

	protected function check_token()
	{
		if (isset($_GET) && isset($_GET['token'])) {
			$token = (array)JWT::decode($_GET['token'], TOKEN, array('HS256'));
			if ($token && array_key_exists('data', $token)) {
				if ($token['data']->id) {
					$this->tokenUser = $token['data'];
					return true;
				}

			}

		}
		return false;
	}

	protected function endVoting($id)
	{

		$vote = new \App\Models\Vote();
		$event = new \App\Models\Event();
		$data = $vote->getVoting($id);
		$event = $event->find($id);

		if ($data[0]['place'] == 0) {
			$vote->insert([
				'name' => 'auto',
				'email' => '',
				'option_id' => $data[0]['id']
			]);
		}

		$selectedDate = $data[0];

		$title = $event->title . ' :  ' . $selectedDate['start'] . ' - ' . $selectedDate['end'];
		$url = $this->add_to_gcal($event->title, $selectedDate['start'], $selectedDate['end'], $event->notes);
		$emails = $vote->getEmails($id);


		$emailDone = [];
		foreach ($emails as $email) {
			array_push($emailDone, $email['email']);
			$this->sendEmail($email['email'], $title, $url);
		}
		$invite = new Invite();
		$invited = $invite->where(['event_id' => $id])->findAll();
		foreach ($invited as $email) {
			array_push($emailDone, $email->email);
			if (in_array($email->email, $emailDone)) {
				$this->sendEmail($email->email, $title, $url);
			}
		}

	}

	protected function cancelVoting($id)
	{

		$vote = new \App\Models\Vote();
		$event = new \App\Models\Event();

		$event = $event->find($_GET['id']);
		$emails = $vote->getEmails($id);

		foreach ($emails as $email) {
			$this->sendEmail($email['email'], 'Canceled  event ' . $event->title, FRONT);
		}


	}

	protected function add_to_gcal(
		$name,
		$startdate,
		$enddate,
		$description = false,
		$location = false)
	{


		// calculate the start and end dates, convert to ISO format


		if ($enddate && !empty($enddate) && strlen($enddate) > 2) {

			$startdate = date('Ymd\THis', strtotime($startdate));
			$enddate = date('Ymd\THis', strtotime($enddate));

		} else {
			$enddate = date('Ymd\THis', strtotime($startdate . ' + 2 hours'));
		}
		// build the url
		$url = 'http://www.google.com/calendar/event?action=TEMPLATE';
		$url .= '&text=' . rawurlencode($name);
		$url .= '&dates=' . $startdate . '/' . $enddate;

		if ($description) {
			$url .= '&details=' . rawurlencode($description);
		}
		if ($location) {
			$url .= '&location=' . rawurlencode($location);
		}
		// build the link output

		return $url;
	}

	/**
	 * @param $email
	 * @param $title
	 * @param $url
	 * @return bool
	 */
	protected function sendEmail($email, $title, $url)
	{


		$emailSettings = \Config\Services::email();
		$emailSettings->setTo($email);
		$emailSettings->setSubject($title);
		$data = view('email/email', ['url' => $url, 'title' => $title]);
		$emailSettings->setMessage($data);
		return $emailSettings->send();

	}


}
