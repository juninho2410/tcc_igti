<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NewControls extends Migration
{
	//protected $DBGroup = 'tests';
	public function up()
	{


		$this->forge->addColumn('evento',[
			//'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
			//'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
			'created_at' => [
				'type'           => 'TIMESTAMP',
				'DEFAULT' => 'CURRENT_TIMESTAMP'
			],
			'updated_at' => [
				'type'           => 'TIMESTAMP',
				'DEFAULT' => 'CURRENT_TIMESTAMP'
			],
			'status' => [
				'type'           => 'VARCHAR',
				'constraint'     => '50',
				'DEFAULT' => 'CONFIRMED'
			],
			'titulo' => [
				'type'           => 'VARCHAR',
				'constraint'     => '50',
				'DEFAULT' => NULL
			]

		]);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropColumn('evento',['created_at','updated_at','status','titulo']);
	}
}
