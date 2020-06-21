<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Teste extends Migration
{
	//protected $defaultGroup = 'tests';
	//protected $DBGroup = 'tests';
	public function up()
	{
		$this->forge->addfield([
			'idEvento'          => [
					'type'           => 'INT',
					'constraint'     => 9,
					'unsigned'       => TRUE,
					'auto_increment' => TRUE
			],
			'descricao'       => [
					'type'           => 'VARCHAR',
					'constraint'     => '500'
			],
			'localizacao' => [
				'type'           => 'VARCHAR',
				'constraint'     => '200'
			],
			'valor_full' => [
				'type'           => 'float'
			],
			'valor_desconto' => [
				'type'           => 'float'
			],
	]
		);
	
		$this->forge->addPrimaryKey('idEvento');
		$this->forge->createTable('evento',TRUE);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		//
		$this->forge->dropTable('evento');
	}
}
