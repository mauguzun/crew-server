<?php namespace App\Controllers;

include_once ROOTPATH . '/php-jwt/src/BeforeValidException.php';
include_once ROOTPATH . '/php-jwt/src/ExpiredException.php';
include_once ROOTPATH . '/php-jwt/src/SignatureInvalidException.php';
include_once ROOTPATH . '/php-jwt/src/JWT.php';

use App\Models\Invite;
use App\Models\Option;
use \Firebase\JWT\JWT;

class Register extends BaseController
{
    public function __construct()
    {


        parent::__construct();
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->validation = \Config\Services::validation();
        helper(['form', 'url']);
        $this->configIonAuth = config('IonAuth');
        $this->session = \Config\Services::session();

        if (!empty($this->configIonAuth->templates['errors']['list'])) {
            $this->validationListTemplate = $this->configIonAuth->templates['errors']['list'];
        }

    }

    public function index()
    {

        $data = null;
        $error = null;

        $tables = $this->configIonAuth->tables;
        $identityColumn = $this->configIonAuth->identity;


        $this->validation->setRule('email', lang('Auth.create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->validation->setRule('password', lang('Auth.create_user_validation_password_label'),
            'required|min_length[6]');


        if ($this->request->getPost() && $this->validation->withRequest($this->request)->run()) {


            $email = strtolower($this->request->getPost('email'));
            $identity = ($identityColumn === 'email') ? $email : $this->request->getPost('identity');
            $password = $this->request->getPost('password');

            $additionalData = [
                'username' => $this->request->getPost('username'),

            ];
        }
        if ($this->request->getPost() &&
            $this->validation->withRequest($this->request)->run() &&
            $this->ionAuth->register($identity, $password, $email, $additionalData)) {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $data = 'Email activation send';
        } else {
            // display the create user form
            // set the flash data error message if there is one

            foreach ($this->validation->getErrors() as $value) {
                $error .= $value;
            }


        }

        return $this->response->setJSON(['error' => $error, 'data' => $data]);

    }


}
