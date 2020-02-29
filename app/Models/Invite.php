<?php namespace App\Models;

use CodeIgniter\Model;


class Invite extends Model{



	protected $table      = 'invites';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	/*        protected $useSoftDeletes = true;*/

	protected $allowedFields = ['id', 'email', 'added','sended','code' , 'event_id'];
	protected $useTimestamps = false;




	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;




}
