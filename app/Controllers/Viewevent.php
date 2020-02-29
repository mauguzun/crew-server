<?php namespace App\Controllers;

include_once ROOTPATH . '/php-jwt/src/BeforeValidException.php';
include_once ROOTPATH . '/php-jwt/src/ExpiredException.php';
include_once ROOTPATH . '/php-jwt/src/SignatureInvalidException.php';
include_once ROOTPATH . '/php-jwt/src/JWT.php';

use App\Models\Invite;
use App\Models\Option;
use \Firebase\JWT\JWT;

class Viewevent extends BaseController
{
	public function __construct()
	{


		parent::__construct();


	}

	public function index()
	{

		$token = null;
		$error = null;
		$data = null;


		if (isset($_GET) && isset($_GET['id']) ){
			$eventModel = new \App\Models\Event();
			$event = $eventModel->find($_GET['id']);
			if ($event) {
				$data['event'] = $event;


				$json = file_get_contents('php://input');
				$obj = json_decode($json);

				if(isset($obj->viewLogin->email)){

					$vote = new \App\Models\Vote();
					$votes = $vote->where(['email'=>$obj->viewLogin->email])->findAll();
					$data['voted']  = [];
					foreach ($votes as $vote){
						array_push($data['voted'] , $vote->option_id);
					}
				}

				$optionModel = new Option();
				$data['options'] = $optionModel->where(['event_id'=>$_GET['id']])->findAll();

			}else{
				$error = 'Event not exist ';
			}


			//  did is i ?
			/*if ($this->check_token()) {
				if($data['event']['user_id'] === $this->tokenUser->id){

				}
			}*/
			$optionModel = new Option();
		}



		$inviteModel = new Invite();
		// check id !


		return $this->response->setJSON(['error' => $error, 'data' => $data]);

	}




}
