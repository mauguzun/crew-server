<?php namespace App\Models;

use CodeIgniter\Model;


class Vote extends Model
{


	protected $table = 'votes';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	/*        protected $useSoftDeletes = true;*/

	protected $allowedFields = ['user_id', 'option_id', 'email', 'name', 'added'];
	protected $useTimestamps = false;


	protected $validationRules = [];
	protected $validationMessages = [];
	protected $skipValidation = false;

	public function getEmails($id)
	{
		return $this->db->query("SELECT
			DISTINCT votes.email,votes.`name`
			from votes
			LEFT JOIN `options` on votes.option_id = options.id
				WHERE options.event_id = $id")->getResultArray();

	}



	public function getVoting($id)
	{
		$data = $this->db->query("SELECT 
			options.* ,
			GROUP_CONCAT(votes.email ,'|',  votes.name SEPARATOR  ';')  as vote ,
				COUNT( votes.email) AS place
			from options
			LEFT JOIN votes on votes.option_id = options.id
				WHERE options.event_id = $id
			GROUP BY options.id
		ORDER BY place DESC
			
			 ")->getResultArray();
		$result = [];


		foreach ($data as $row) {
			$array = $row['vote'] == NULL ? NULL : explode(';', $row['vote']);
			$voting = [];
			if ($array) {
				foreach ($array as $one) {
					$value = explode('|', $one);
					$voting[] = [
						'email' => $value[0],
						'name' => $value[1],
					];
				}
			}
			$row['vote'] = $voting;
			$result[] = $row;
		}
//		var_dump($result);
		return $result;
	}

}
