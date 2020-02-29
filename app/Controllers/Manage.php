<?php namespace App\Controllers;

include_once ROOTPATH . '/php-jwt/src/BeforeValidException.php';
include_once ROOTPATH . '/php-jwt/src/ExpiredException.php';
include_once ROOTPATH . '/php-jwt/src/SignatureInvalidException.php';
include_once ROOTPATH . '/php-jwt/src/JWT.php';

use App\Models\Invite;
use App\Models\Option;
use \Firebase\JWT\JWT;

class Manage extends BaseController
{
	public function __construct()
	{


		parent::__construct();


	}

	public function index()
	{

		$error = null;
		$data = null;


		if ($this->check_token() && isset($_GET['id'])) {

			$event = new \App\Models\Event();
			$vote = new \App\Models\Vote();


			$data['options']= $vote->getVoting($_GET['id']);
			$data['event']=  $event->find($_GET['id']);

		}
		return $this->response->setJSON(['error' => $error, 'data' => $data]);

	}


}
