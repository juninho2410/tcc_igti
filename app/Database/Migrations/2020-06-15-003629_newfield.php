<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Newfield extends Migration
{
	//protected $defaultGroup = 'tests';
	//protected $DBGroup = 'tests';
	public function __construct(Forge $forge = null)
	{
		if(isset($_GET['dbgroup'])){
			$this->DBGroup = $_GET['dbgroup'];
		}
		
		$this->forge = ! is_null($forge) ? $forge : \Config\Database::forge(($this->DBGroup | $_GET['dbgroup']) ?? config('Database')->defaultGroup);

		$this->db = $this->forge->getConnection();
	}

	public function up()
	{

		$this->forge->addColumn('evento', array(
				'nome'	=> array(
				'type'			=> 'VARCHAR',
				'constraint'	=> 200,
				'null'			=> TRUE,
				'default'		=> NULL
				)
			)
		);
		
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropColumn('evento','nome');
	}
}
