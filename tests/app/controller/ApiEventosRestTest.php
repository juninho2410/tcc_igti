<?php namespace App\Controller;

use CodeIgniter\Test\ControllerTester;
use CodeIgniter\Test\CIDatabaseTestCase;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\URI;
use CodeIgniter\HTTP\UserAgent;
use CodeIgniter\Log\Logger;
use CodeIgniter\Test\Mock\MockLogger as LoggerConfig;
use Config\App;
use Config\Services;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\Request;

const HEADER_FORM_URLENCODED = 'application/x-www-form-urlencoded';
const HTTP_CODE_CREATE = 201;
const HTTP_MESSAGE_CREATE = 'Criado com sucesso';
const HTTP_CODE_OK = 200;
const HTTP_CODE_ERROR = 400;
const HTTP_CODE_ERROR_NOT_FOUND = 404;
const HTTP_MESSAGE_ERROR_CREATE = 'Create Failed';
const HTTP_MESSAGE_ERROR_NOT_FOUND = 'Não encontrado';
const HTTP_MESSAGE_ERROR_NOT_FOUND_2 = 'Not Found';
const HTTP_MESSAGE_UPDATE = 'Evento Atualizado';
const HTTP_MESSAGE_UPDATE_2 = 'Atualizado com sucesso';
const HTTP_MESSAGE_DELETE = 'Evento apagado com sucesso';
const HTTP_MESSAGE_DELETE_2 = 'Apagado com sucesso';
/**
 * Exercise our Controller class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState         disabled
 */
class ApiEventosRestTest extends \CodeIgniter\Test\CIUnitTestCase
{

    protected $options = [
        'create'=>[
            'one_field'=>
                [
                    'descricao' => 'Teste Unitário only Descrição'
                ],
            'two_fields'=>[
                'descricao' => 'Teste Unitário Update Descricao Localização',
                'localizacao' => 'Localizacão Teste Unitario'
            ],
            'three_fields'=>[
                'descricao' => 'Teste Unitário só Valor Full',
                'localizacao' => 'Localizacão Teste Unitario só Valor Full',
                'valor_full' =>20
            ],
            'complete'=>[
                'descricao' => 'Teste Unitário Valor Full e Desconto',
                'localizacao' => 'Localizacão Teste Unitario Valor Full e Desconto',
                'valor_full' =>20,
                'valor_desconto' =>10
            ]
        ],
        'update'=>[
            'one_field'=>[
                'descricao' => 'Teste Unitário Update only Descrição'
            ],
            'two_fields'=>[
                    'descricao' => 'Teste Unitário Update Descricao Localização',
                    'localizacao' => 'Localizacão Teste Unitario'
            ],
            'three_fields'=>[
                    'descricao' => 'Teste Unitário Update só Valor Full',
                    'localizacao' => 'Localizacão Teste Unitario só Valor Full',    
                    'valor_full' =>20
            ],
            'complete'=>[
                    'descricao' => 'Teste Unitário Update Valor Full e Desconto',
                    'localizacao' => 'Localizacão Teste Unitario Valor Full e Desconto',
                    'valor_full' =>20,
                    'valor_desconto' =>10
            ]
        ]
    ];

    use ControllerTester;
    public function setUp(): void
	{
		parent::setUp();
	}

	public function tearDown(): void
	{
		parent::tearDown();
    }
    public function test_create_one_event_in_api(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&descricao=TEste1&localizacao=Localizacao&valor_full=50.00&valor_desconto=30.00';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('POST');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\EventosRest::class)
				->execute('create');
        $body = json_decode($result->getBody());
        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue($result->isOK());
        $this->assertEquals(HTTP_CODE_CREATE,$code);
        $this->assertEquals(HTTP_MESSAGE_CREATE,$reason);
        $this->assertEquals(HTTP_CODE_CREATE,$body->status);
        $this->assertEquals(HTTP_MESSAGE_CREATE,$body->messages);  
    }
    public function test_get_specific_event_from_api()
    {

        $logger = new Logger(new LoggerConfig());
		$result = $this->withURI('http://tcc.localhost.com/api/eventos/1')
				->withLogger($logger)
				->controller(\App\Controllers\EventosRest::class)
				->execute('show',1);

        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $body = json_decode($result->getBody())[0];
        
        $this->assertEquals(HTTP_CODE_OK,$code);
        $this->assertEquals(1,$body->idEvento);
        $this->assertEquals('TEste1',$body->descricao);
        $this->assertEquals("Localizacao",$body->localizacao);
        $this->assertEquals(50.0,$body->valor_full);
        $this->assertEquals(30.0,$body->valor_desconto);
        $this->assertTrue($result->isOK());    

        
    }

    public function test_try_to_create_one_event_in_api_only_descricao(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&descricao=teste';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('POST');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');
        
        $result = $this
        ->withLogger($logger)
        ->withRequest($request)
        ->controller(\App\Controllers\EventosRest::class)
        ->execute('create');

        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue(!$result->isOK());
        $this->assertEquals(HTTP_CODE_ERROR,$code);
        $this->assertEquals(HTTP_MESSAGE_ERROR_CREATE,$reason);

        $body = json_decode($result->getBody());
        $this->assertEquals('Este campo é obrigatório, por favor preencha.',$body->messages->localizacao);
        $this->assertEquals('Este campo é obrigatório, por favor preencha.',$body->messages->valor_full);
    
    }
    public function test_try_to_create_one_event_in_api_only_descricao_localizacao(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&descricao=teste&localizacao=LocalizacaoTeste';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('POST');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');
        
        $result = $this
        ->withLogger($logger)
        ->withRequest($request)
        ->controller(\App\Controllers\EventosRest::class)
        ->execute('create');

        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue(!$result->isOK());
        $this->assertEquals(HTTP_CODE_ERROR,$code);
        $this->assertEquals(HTTP_MESSAGE_ERROR_CREATE,$reason);

        $body = json_decode($result->getBody());
        $this->assertEquals('Este campo é obrigatório, por favor preencha.',$body->messages->valor_full);

    }
    public function test_create_one_event_in_api_only_descricao_localizacao_valor_full(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&descricao=teste&localizacao=teste&valor_full=50.00';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('POST');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\EventosRest::class)
				->execute('create');
        $body = json_decode($result->getBody());
        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue($result->isOK());
        $this->assertEquals(HTTP_CODE_CREATE,$code);
        $this->assertEquals(HTTP_MESSAGE_CREATE,$reason);
        $this->assertEquals(HTTP_CODE_CREATE,$body->status);
        $this->assertEquals(HTTP_MESSAGE_CREATE,$body->messages);  
    }
    public function test_update_one_event_in_api(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&descricao=TEste1&localizacao=Localizacao&valor_full=50&valor_desconto=30';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('PUT');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\EventosRest::class)
				->execute('update',1);
        $body = json_decode($result->getBody());
        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue($result->isOK());
        $this->assertEquals(HTTP_CODE_OK,$code);
        $this->assertEquals(HTTP_MESSAGE_UPDATE,$reason);
        $this->assertEquals(HTTP_CODE_OK,$body->status);
        $this->assertEquals(HTTP_MESSAGE_UPDATE_2,$body->messages);  
    }
    public function test_update_one_event_in_api_only_descricao(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&descricao=TEste2';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('PUT');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\EventosRest::class)
				->execute('update',2);
        $body = json_decode($result->getBody());
        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue($result->isOK());
        $this->assertEquals(HTTP_CODE_OK,$code);
        $this->assertEquals(HTTP_MESSAGE_UPDATE,$reason);
        $this->assertEquals(HTTP_CODE_OK,$body->status);
        $this->assertEquals(HTTP_MESSAGE_UPDATE_2,$body->messages);  
    }
    public function test_update_one_event_in_api_only_descricao_localizacao(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&descricao=TEste2&localizacao=Localizacao';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('PUT');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\EventosRest::class)
				->execute('update',2);
        $body = json_decode($result->getBody());
        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue($result->isOK());
        $this->assertEquals(HTTP_CODE_OK,$code);
        $this->assertEquals(HTTP_MESSAGE_UPDATE,$reason);
        $this->assertEquals(HTTP_CODE_OK,$body->status);
        $this->assertEquals(HTTP_MESSAGE_UPDATE_2,$body->messages);  
    }
    public function test_update_one_event_in_api_only_descricao_localizacao_valor_full(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&descricao=TEste2&localizacao=Localizacao&valor_full=50';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('PUT');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\EventosRest::class)
				->execute('update',2);
        $body = json_decode($result->getBody());
        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue($result->isOK());
        $this->assertEquals(HTTP_CODE_OK,$code);
        $this->assertEquals(HTTP_MESSAGE_UPDATE,$reason);
        $this->assertEquals(HTTP_CODE_OK,$body->status);
        $this->assertEquals(HTTP_MESSAGE_UPDATE_2,$body->messages);  
    }
    public function test_try_to_delete_one_event_in_api(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&idEvento=10000';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('DELETE');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\EventosRest::class)
				->execute('delete',10000);
        $body = json_decode($result->getBody());
        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue(!$result->isOK());
        $this->assertEquals(HTTP_CODE_ERROR_NOT_FOUND,$code);
        $this->assertEquals(HTTP_MESSAGE_ERROR_NOT_FOUND,$reason);
        $this->assertEquals(HTTP_CODE_ERROR_NOT_FOUND,$body->status);
        $this->assertEquals(HTTP_MESSAGE_ERROR_NOT_FOUND_2,$body->messages->error);  
    }
    public function test_delete_one_event_in_api(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        //Inserir um Item para depois deletar
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&descricao=teste&localizacao=teste&valor_full=50.00&valor_desconto=30.00';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('POST');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\EventosRest::class)
				->execute('create');
        $body = json_decode($result->getBody());
        $insertId = $body->id;
        //Deletar o item
        $input="csrf_test_name=ecf83cff872a08d5c490a478402743b5&idEvento=$insertId";
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/eventos'), $input, new UserAgent());
        $request->setMethod('DELETE');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\EventosRest::class)
                ->execute('delete',$insertId);
                
        $body = json_decode($result->getBody());
        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue($result->isOK());
        $this->assertEquals(HTTP_CODE_OK,$code);
        $this->assertEquals(HTTP_MESSAGE_DELETE,$reason);
        $this->assertEquals(HTTP_CODE_OK,$body->status);
        $this->assertEquals(HTTP_MESSAGE_DELETE_2,$body->messages);  
    }
}