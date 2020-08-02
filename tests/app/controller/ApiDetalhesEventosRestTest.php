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
class ApiDetalhesEventosRestTest extends \CodeIgniter\Test\CIUnitTestCase
{

        use ControllerTester;
    public function setUp(): void
	{
		parent::setUp();
	}

	public function tearDown(): void
	{
		parent::tearDown();
    }
    public function test_create_one_event_detail_in_api(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&id_evento=1&data_evento_inicio=2020-07-23%2000:00:00&data_evento_fim=2020-07-23%2002:00:00';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/detalhes-eventos'), $input, new UserAgent());
        $request->setMethod('POST');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\DetalhesEventosRest::class)
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
    public function test_get_specific_event_detail_from_api()
    {

        $logger = new Logger(new LoggerConfig());
		$result = $this->withURI('http://tcc.localhost.com/api/detalhes-eventos/1')
				->withLogger($logger)
				->controller(\App\Controllers\DetalhesEventosRest::class)
				->execute('show',1);

        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $body = json_decode($result->getBody())[0];
        
        $this->assertEquals(HTTP_CODE_OK,$code);
        $this->assertEquals(1,$body->id_evento);
        $this->assertEquals('2020-07-23 00:00:00',$body->data_evento_inicio);
        $this->assertEquals('2020-07-23 02:00:00',$body->data_evento_fim);
        $this->assertTrue($result->isOK());    

        
    }

    public function test_try_to_create_one_event_detail_in_api_only_id_evento(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&id_evento=2';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/detalhes-eventos'), $input, new UserAgent());
        $request->setMethod('POST');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');
        
        $result = $this
        ->withLogger($logger)
        ->withRequest($request)
        ->controller(\App\Controllers\DetalhesEventosRest::class)
        ->execute('create');

        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue(!$result->isOK());
        $this->assertEquals(HTTP_CODE_ERROR,$code);
        $this->assertEquals(HTTP_MESSAGE_ERROR_CREATE,$reason);

        $body = json_decode($result->getBody());
        
        $this->assertEquals('Este campo é obrigatório, por favor preencha.',$body->messages->data_evento_inicio);
        $this->assertEquals('Este campo é obrigatório, por favor preencha.',$body->messages->data_evento_fim);
    
    }
    public function test_try_to_create_one_event_detail_in_api_only_id_evento_data_evento_inicio(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&id_evento=2&data_evento_inicio=2020-07-23%2000:00:00';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/detalhes-eventos'), $input, new UserAgent());
        $request->setMethod('POST');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');
        
        $result = $this
        ->withLogger($logger)
        ->withRequest($request)
        ->controller(\App\Controllers\DetalhesEventosRest::class)
        ->execute('create');

        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        $this->assertTrue(!$result->isOK());
        $this->assertEquals(HTTP_CODE_ERROR,$code);
        $this->assertEquals(HTTP_MESSAGE_ERROR_CREATE,$reason);

        $body = json_decode($result->getBody());
        $this->assertEquals('Este campo é obrigatório, por favor preencha.',$body->messages->data_evento_fim);

    }
    public function test_update_one_event_detail_in_api(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&id_evento=1&data_evento_inicio=2020-07-23%2000:00:00&data_evento_fim=2020-07-23%2004:00:00';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/detalhes-eventos'), $input, new UserAgent());
        $request->setMethod('PUT');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\DetalhesEventosRest::class)
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
    public function test_update_one_event_detail_in_api_only_id_evento(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&id_evento=2';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/detalhes-eventos'), $input, new UserAgent());
        $request->setMethod('PUT');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\DetalhesEventosRest::class)
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
    public function test_update_one_event_detail_in_api_only_id_evento_data_evento_inicio(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&id_evento=1&data_evento_inicio=2020-07-23%2000:00:00';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/detalhes-eventos'), $input, new UserAgent());
        $request->setMethod('PUT');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\DetalhesEventosRest::class)
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
    /*
    public function test_try_to_delete_one_event_detail_in_api(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        $input='';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost/api/detalhes-evento/10000'), $input, new UserAgent());
        $request->setMethod('DELETE');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\DetalhesEventosRest::class)
				->execute('delete',10000);
        $body = json_decode($result->getBody());
        $code = $result->response()->getStatusCode();
        $reason = $result->response()->getReason();
        var_dump($body);
        $this->assertTrue(!$result->isOK());
        $this->assertEquals(HTTP_CODE_ERROR_NOT_FOUND,$code);
        $this->assertEquals(HTTP_MESSAGE_ERROR_NOT_FOUND,$reason);
        $this->assertEquals(HTTP_CODE_ERROR_NOT_FOUND,$body->status);
        $this->assertEquals(HTTP_MESSAGE_ERROR_NOT_FOUND_2,$body->messages->error);  
    }*/
    public function test_delete_one_event_detail_in_api(){
        $code=0;
        $logger = new Logger(new LoggerConfig());
        $config = new App();
        //Inserir um Item para depois deletar
        $input='csrf_test_name=ecf83cff872a08d5c490a478402743b5&id_evento=2&data_evento_inicio=2020-07-23%2000:00:00&data_evento_fim=2020-07-23%2004:00:00';
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/detalhes-eventos'), $input, new UserAgent());
        $request->setMethod('POST');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\DetalhesEventosRest::class)
				->execute('create');
        $body = json_decode($result->getBody());
        $insertId = $body->id;
        //Deletar o item
        $input="csrf_test_name=ecf83cff872a08d5c490a478402743b5&id_evento=$insertId";
        $request = new IncomingRequest($config, new URI('http://tcc.localhost.com/api/detalhes-eventos'), $input, new UserAgent());
        $request->setMethod('DELETE');
        $request->setHeader('Accept','application/json');
        $request->setHeader('Content-Type','application/x-www-form-urlencoded');
        $request->appendHeader('Content-Type','charset=UTF-8');

		$result = $this
                ->withLogger($logger)
                ->withRequest($request)
				->controller(\App\Controllers\DetalhesEventosRest::class)
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
