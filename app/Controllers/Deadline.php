<?php namespace App\Controllers;

include_once ROOTPATH . '/php-jwt/src/BeforeValidException.php';
include_once ROOTPATH . '/php-jwt/src/ExpiredException.php';
include_once ROOTPATH . '/php-jwt/src/SignatureInvalidException.php';
include_once ROOTPATH . '/php-jwt/src/JWT.php';

use App\Models\Invite;
use App\Models\Option;
use \Firebase\JWT\JWT;

class Deadline extends BaseController
{
	public function __construct()
	{


		parent::__construct();


	}

	public function index()
	{


		echo date("Y-m-d");

		$event = new \App\Models\Event();
		$events  = $event->where(['deadline <  ' => date("Y-m-d"), 'status ' => 1])->findAll();
		echo "<pre>";
		var_dump($events);
		foreach ($events as $oneEvent){
			$this->endVoting($oneEvent->id);
			$event->update([$oneEvent->id], ['status' => 2]);
		}

	}


}
