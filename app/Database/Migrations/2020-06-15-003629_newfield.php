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
		
		$this->forge = ! is_null($forge) ? $forge : \Config\Database::forge(($this->DBGroup) ?? config('Database')->defaultGroup);

		$this->db = $this->forge->getConnection();
	}

	public function up()
	{
		
		
		$this->forge->addField([
			'id_detalhes_evento'	=> [
				'type'           => 'INT',
				'constraint'     => 9,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			],
			'id_evento'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> FALSE,
			],
			'data_evento_inicio'	=> [
				'type'			=> 'DATETIME',
				'null'			=> FALSE
			],
			'data_evento_fim'	=> [
				'type'			=> 'DATETIME',
				'null'			=> FALSE
			],
			'tem_estacionamento'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'valor_estacionamento' => [
				'type'           => 'float',
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'tem_vagas_proximidades'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'tem_lista_aniversariantes'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'tem_lanchonete'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'tem_comida_especial'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'qtde_salas'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'tem_personais'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'tem_dress_code'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'tem_chapelaria'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'tem_reserva_mesas'	=> [
				'type'			=> 'int',
				'constraint'	=> 9,
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			],
			'valor_mesas' => [
				'type'           => 'float',
				'null'			=> TRUE,
				'DEFAULT'		=> NULL
			]
		]
			
		);
		$this->forge->addPrimaryKey(['id_detalhes_evento','id_evento']);
		$this->forge->addUniqueKey('id_evento');
		$this->forge->createTable('detalhes_evento', TRUE);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('detalhes_evento');
	}
}
