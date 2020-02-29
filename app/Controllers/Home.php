<?php namespace App\Controllers;

include_once ROOTPATH.'/php-jwt/src/BeforeValidException.php';
include_once ROOTPATH.'/php-jwt/src/ExpiredException.php';
include_once ROOTPATH.'/php-jwt/src/SignatureInvalidException.php';
include_once ROOTPATH.'/php-jwt/src/JWT.php';
use \Firebase\JWT\JWT;

class Home extends BaseController
{
	public function __construct()
	{


		parent::__construct();


		$this->ionAuth    = new \IonAuth\Libraries\IonAuth();
		$this->validation = \Config\Services::validation();
		helper(['form', 'url']);
		$this->configIonAuth = config('IonAuth');
		$this->session       = \Config\Services::session();

		$this->ionAuthModel = new \IonAuth\Models\IonAuthModel();

		if (! empty($this->configIonAuth->templates['errors']['list']))
		{
			$this->validationListTemplate = $this->configIonAuth->templates['errors']['list'];
		}
	}

	public function index()
	{


		$data =  file_get_contents( 'php://input' );
		$obj = json_decode($data);

		$token = null;
		$error = null;

		if ( $obj &&   $obj->email )
		{

			if ($this->ionAuth->login( $obj->email ,$obj->password, true))
			{
				$payload = [
					"iss" => base_url(),
					"aud" => base_url(),
					"iat" => time(),
					"nbf" =>  time(),
					"data"=>[
						'id'=> $this->ionAuthModel->user()->row()->id,
						'username'=> $this->ionAuthModel->user()->row()->username,
						'email'=> $this->ionAuthModel->user()->row()->email

					]];
				$token = JWT::encode($payload,TOKEN);
			}
			else
			{
				$error =  'Catn`t login ';
			}
		}else{
			$error =  'Login And Password';
		}


		return $this->response->setJSON(	['error'=>$error,'token'=>$token,]);

	}



	public  function  test(){
		$email = \Config\Services::email();
		$email->setTo('mauguzun@gmail.com');
		$email->setSubject('Email Test');
		$data =  view('email/email',['url'=>'sdf','title'=>'sadfs']);
		$email->setMessage($data);
		echo $email->send() ;
	}

	//--------------------------------------------------------------------

}
