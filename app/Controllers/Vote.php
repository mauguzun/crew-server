<?php namespace App\Controllers;

include_once ROOTPATH . '/php-jwt/src/BeforeValidException.php';
include_once ROOTPATH . '/php-jwt/src/ExpiredException.php';
include_once ROOTPATH . '/php-jwt/src/SignatureInvalidException.php';
include_once ROOTPATH . '/php-jwt/src/JWT.php';

use App\Models\Invite;
use App\Models\Option;
use \Firebase\JWT\JWT;

class Vote extends BaseController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($id = null)
	{

		$token = null;
		$error = null;
		$data = null;

		if (isset($_GET) && isset($_GET['id'])) {


			$data = file_get_contents('php://input');
			$obj = json_decode($data);


			if (isset($obj->viewLogin->name) && isset($obj->viewLogin->email)) {
//				//code
//				$obj->code

				$event = new \App\Models\Event();
				$currentEvent  =$event->find($_GET['id']) ;
				if(!$currentEvent){
					$error  = 'Event not exist !';
				}
				else if ($currentEvent->status != 1){
					$error  = 'Event closed !';
				}else{
					$vote = new \App\Models\Vote();
					$vote->where(['email' => $obj->viewLogin->email])->delete();

					foreach ($obj->selected as $option_id) {
						$vote->insert([
							'name' => $obj->viewLogin->name,
							'email' => $obj->viewLogin->email,
							'option_id' => $option_id,
						]);
					}
				}



			}


		}


		return $this->response->setJSON(['error' => $error, 'data' => $data]);

	}


}
