<?php namespace App\Controllers;

include_once ROOTPATH . '/php-jwt/src/BeforeValidException.php';
include_once ROOTPATH . '/php-jwt/src/ExpiredException.php';
include_once ROOTPATH . '/php-jwt/src/SignatureInvalidException.php';
include_once ROOTPATH . '/php-jwt/src/JWT.php';

use App\Models\Invite;
use App\Models\Option;
use \Firebase\JWT\JWT;

class Eventlist extends BaseController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($id = null)
	{

		$data = null;
		$error = null;

		if ($this->check_token()) {

			$event = new \App\Models\Event();
			$data = $event->where(['user_id'=>$this->tokenUser->id])->findAll();

		}


		return $this->response->setJSON(['error' => $error, 'data' => $data]);

	}


}
