<?php namespace App\Models;

use CodeIgniter\Model;


class Event extends Model{



	protected $table      = 'events';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	/*        protected $useSoftDeletes = true;*/

	protected $allowedFields = ['id', 'status' ,'title', 'notes','type','deadline','user_id'];
	protected $useTimestamps = false;




	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;



}
