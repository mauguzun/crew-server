<?php namespace App\Controllers;

include_once ROOTPATH . '/php-jwt/src/BeforeValidException.php';
include_once ROOTPATH . '/php-jwt/src/ExpiredException.php';
include_once ROOTPATH . '/php-jwt/src/SignatureInvalidException.php';
include_once ROOTPATH . '/php-jwt/src/JWT.php';

use App\Models\Invite;
use App\Models\Option;
use \Firebase\JWT\JWT;

class Status extends BaseController
{
	public function __construct()
	{


		parent::__construct();


	}

	public function index()
	{


		$error = null;
		$data = null;


		if ($this->check_token() && isset($_GET['id']) && isset($_GET['status'])) {

			$event = new \App\Models\Event();


			$data = $event->find($_GET['id']);


			if ($_GET['status'] < $data->status) {
				$error = "Can`t change ";
			} else {
				$event->update([$_GET['id']], ['status' => $_GET['status']]);

				switch ($_GET['status']) {
					case '2':
						$this->endVoting($data->id);
						break;
					default :
						$this->cancelVoting($data->id);

				}
			}


		}
		return $this->response->setJSON(['error' => $error, 'data' => $data]);

	}


}
