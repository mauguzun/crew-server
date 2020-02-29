<?php namespace App\Models;

use CodeIgniter\Model;


class Option extends Model{



	protected $table      = 'options';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	/*        protected $useSoftDeletes = true;*/

	protected $allowedFields = ['id', 'event_id', 'start','end','event_id'];
	protected $useTimestamps = false;




	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;



}
