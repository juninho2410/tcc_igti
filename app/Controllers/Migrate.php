<?php
/**
 * CodeIgniter Migrate
 *
 * @author  Natan Felles <natanfelles@gmail.com>
 * @link    http://github.com/natanfelles/codeigniter-migrate
 */
namespace App\Controllers;

use \Codeigniter\Services; 
use \CodeIgniter\Database\Config;
use \Codeigniter\Config\Database; 
/**
 * Class Migrate
 */
class Migrate extends \CodeIgniter\Controller {


	/**
	 * @var array Migrations
	 */
	protected $migrations;

	/**
	 * @var bool Migration Status
	 */
    protected $migration_enabled;
    
    protected $config;
    protected $configDb;
    protected $db;
    protected $security;
    protected $migration;


	/**
	 * Migrate constructor
	 */
	public function __construct()
	{
		//parent::__construct();
		//helper('url');
	//	$this->config->load('migration');
	
        $this->config = config('Migrations');
        //$this->db = $this->configDb::connect($dbGroup);
         $this->migration_enabled = $this->config->enabled;
		if ($this->migration_enabled && uri_string() != 'migrate/token')
		{
        //$config      = config('Database');
		//$this->group = $config->defaultGroup;
			//$this->load->database($this->input->get('dbgroup') ? : '');
            //$migrationRunner = new \Codeigniter\Database\MigrationRunner($config);
           
		}
	}


	/**
	 * Index page
	 */
	public function index()
	{
		$this->getConnection();
		$this->getMigrations();

		

		if ($this->migration_enabled)
		{
			foreach ($this->migrations as $version => $filepath)
			{
				$fp = explode(DIRECTORY_SEPARATOR, $filepath->path);
				$data['migrations'][] = [
					'version' => $version,
					'file'    => $fp[count($fp) - 1],
				];
			}
		   
		   $dbGroup=$this->request->getGet('dbgroup');
		   $dbGroup = $dbGroup!=null?$dbGroup:$this->configDb->defaultGroup;
		   $version='';
		   $sql="SELECT * FROM `migrations` where `group` = '{$dbGroup}' order by id desc limit 1";
	
			$query=$this->db->query($sql);
			if($row = $query->getRow()){
				$version = $row->class;
			}
		   
			$data['current_version'] =$version;
		}
		else
		{
			$data['migration_disabled'] = TRUE;
		}
		// You can change the assets links to other versions or to be site relative
		/*$data['assets'] = [
			'bootstrap_css' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
			'bootstrap_js'  => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
			'jquery'        => 'https://code.jquery.com/jquery-2.2.4.min.js',
		];*/

		$data['assets'] = [
			'bootstrap_css' => 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css',
			'bootstrap_js'  => base_url('/vendor/bootstrap/js/bootstrap.bundle.min.js'),
			'jquery'        => base_url('/vendor/jquery/jquery.min.js'),
		];

		
		
		$data['dbgroups']      = ['default','tests'];
		$data['active_group']  = $dbGroup!=null?$dbGroup:$this->configDb->defaultGroup;

		echo view('migrate', $data);
	}


	/**
	 * Post page
	 */
	public function post()
	{
		$this->getConnection();
		$this->getMigrations();
		if ($this->request->isAjax() && $this->migration_enabled)
		{
			// If you works with Foreign Keys look this helper:
			// https://gist.github.com/natanfelles/4024b598f3b31db47c3e139d82dec281
			//helper('db');
			$version = $this->request->getPost('version');
			if ($version == 0)
			{
				$this->migration->version(0);
				$response = [
					'type'    => 'success',
					'header'  => 'Sucess!',
					'content' => "Migrations has ben reseted.",
				];
			}
			elseif (array_key_exists($version, $this->migrations))
			{
				$file=$this->migrations[$version]->path;
				$dbGroup=$this->request->getGet('dbgroup');
				$dbGroup = $dbGroup!=null?$dbGroup:$this->configDb->defaultGroup;
				$v = $this->migration->force($file,'App',$dbGroup);
				if (is_numeric($v))
				{
					$response = [
						'type'    => 'success',
						'header'  => 'Sucess!',
						'content' => "The current version is <strong>{$v}</strong> now.",
					];
				}
				elseif ($v === TRUE)
				{
					$response = [
						'type'    => 'info',
						'header'  => 'Info',
						'content' => 'Migration continues in the same version.',
					];
				}
				elseif ($v === FALSE)
				{
					$response = [
						'type'    => 'danger',
						'header'  => 'Error!',
						'content' => 'Migration failed.',
					];
				}
			}
			else
			{
				$response = [
					'type'    => 'warning',
					'header'  => 'Warning!',
					'content' => 'The migration version <strong>' . htmlentities($version) . '</strong> does not exists.',
				];
			}
			header('Content-Type: application/json');
			echo json_encode(isset($response) ? $response : '');
		}
	}


	/**
	 * Token page
	 */
	public function token()
	{
		header('Content-Type: application/json');
		$this->security=\Config\Services::security();
		echo json_encode([
			'name'  => $this->security->getCSRFTokenName(),
			'value' => $this->security->getCSRFHash(),
		]);
	}

	/**
	 * Get Database Config file info
	 *
	 * @return array
	 */
	protected function get_dbconfig()
	{
		// Is the config file in the environment folder?
		if ( ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
			&& ! file_exists($file_path = APPPATH.'config/database.php'))
		{
			show_error('The configuration file database.php does not exist.');
		}

		include($file_path);

		return [
			'dbgroups'      => array_keys($db),
			'active_group'  => $active_group,
		];
	}
	public function getConnection(){
		$dbGroup=null;
		if(isset($this->request))$dbGroup=$this->request->getGet('dbgroup');
		$this->configDb  = $this->configDb!=null?$this->configDb:config('Database');
		$this->db=($this->db instanceof BaseConnection)?$this->db:$this->configDb::connect($dbGroup);
	
	}
	protected function getMigrations(){
		if ($this->migration_enabled && uri_string() != 'migrate/token')
		{
			$this->migration = Services::migrations(null,$this->db);
			$this->migrations = $this->migration->findMigrations();
		}
	}
}