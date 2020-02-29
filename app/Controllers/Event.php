<?php namespace App\Controllers;

include_once ROOTPATH . '/php-jwt/src/BeforeValidException.php';
include_once ROOTPATH . '/php-jwt/src/ExpiredException.php';
include_once ROOTPATH . '/php-jwt/src/SignatureInvalidException.php';
include_once ROOTPATH . '/php-jwt/src/JWT.php';

use App\Models\Invite;
use App\Models\Option;
use \Firebase\JWT\JWT;

class Event extends BaseController
{
	public function __construct()
	{


		parent::__construct();


	}

	public function index()
	{

		$token = null;
		$error = null;

		if ($this->check_token()) {

			$data = file_get_contents('php://input');
			$obj = json_decode($data);


			$eventModel = new \App\Models\Event();
			$optionModel = new Option();
			$inviteModel = new Invite();
			// check id !


			$eventId = $obj->event->id;
			$eventModel->insert([
				'id' => $eventId,
				'deadline' => $obj->event->deadline,
				'type' => $obj->event->type,
				'notes' => $obj->event->notes,
				'title' => $obj->event->title,
				'user_id' => $this->tokenUser->id,
			]);

			foreach ($obj->event->options as $option) {
				$optionModel->insert([
					'id' => $option->id,
					'event_id' => $eventId,
					'start' => $option->start,
					'end' => $option->end,
				]);
			}

			foreach ($obj->event->emails as $email) {

				$code = time() . rand(111, 999999);

				$url = FRONT . 'view/' . $eventId . '?code=' . $code;
				$sended = $this->sendEmail($email, $obj->event->title, $url);

				$inviteModel->insert([
					'email' => $email,
					'event_id' => $eventId,
					'code' => $code,
					'sended' => $sended
				]);
			}
		} else {
			$error = "Please login first";
		}
		return $this->response->setJSON(['error' => $error, 'token' => $token,]);

	}


}
