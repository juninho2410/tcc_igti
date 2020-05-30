<?php
    namespace App\test;
    use PHPUnit\Framework\TestCase;
    use GuzzleHttp\Client;

    const HEADER_FORM_URLENCODED = 'application/x-www-form-urlencoded';
    const HTTP_CODE_CREATE = 201;
    const HTTP_MESSAGE_CREATE = 'Criado com sucesso';
    const HTTP_CODE_OK = 200;
    const HTTP_CODE_ERROR = 400;
    const HTTP_MESSAGE_ERROR_CREATE = 'Create Failed';
    const HTTP_MESSAGE_UPDATE = 'Evento Atualizado';
    const HTTP_MESSAGE_UPDATE_2 = 'Atualizado com sucesso';

    const BASE_URL_TEST = 'http://tcc.localhost/api/';

    class ApiTestCase extends TestCase{


        protected $headers =[   
            'Content-Type'     => HEADER_FORM_URLENCODED
        ];
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

        /** @test */
        public function get_event_list_from_api(){

            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => BASE_URL_TEST,
                // You can set any number of default request options.
                'timeout'  => 2.0,
            ]);

            $response = $client->request('GET', 'eventos');
            
            $code = $response->getStatusCode();
            $reason = $response->getReasonPhrase();
            
            $this->assertEquals(HTTP_CODE_OK,$code);
        }

        /** @test */
        public function get_specific_event_from_api(){

            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => BASE_URL_TEST,
                // You can set any number of default request options.
              //  'timeout'  => 2.0,
              'debug' => true
            ]);

            $response = $client->request('GET', 'eventos/1');
            
            $code = $response->getStatusCode();
            $reason = $response->getReasonPhrase();
            $body = json_decode($response->getBody())[0];
            
            $this->assertEquals(HTTP_CODE_OK,$code);
            $this->assertEquals(1,$body->idEvento);
            $this->assertEquals($this->options['update']['complete']['descricao'],$body->descricao);
            $this->assertEquals($this->options['update']['complete']['localizacao'],$body->localizacao);
            $this->assertEquals($this->options['update']['complete']['valor_full'],$body->valor_full);
            $this->assertEquals($this->options['update']['complete']['valor_desconto'],$body->valor_desconto);
        }
        /** @test */
        public function create_one_event_in_api(){
            $code=0;
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => BASE_URL_TEST//,
                // You can set any number of default request options.
             //   'timeout'  => 2.0,
            ]);
            try{
                $response = $client->request('POST', 'eventos',[
                    'form_params' => $this->options['create']['complete'],
                    'headers' => $this->headers
                ]);
            
                $code = $response->getStatusCode();
                $reason = $response->getReasonPhrase(); 
                $body = json_decode($response->getBody());
            }
            catch(Exception  $e){
                print_r($e);
            }
            $this->assertEquals(HTTP_CODE_CREATE,$code);
            $this->assertEquals(HTTP_MESSAGE_CREATE,$reason);
            $this->assertEquals(HTTP_CODE_CREATE,$body->status);
            $this->assertEquals(HTTP_MESSAGE_CREATE,$body->messages);
        }

        /** @test */
        public function try_to_create_one_event_in_api_only_descricao(){
        $code=0;
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => BASE_URL_TEST//,
            // You can set any number of default request options.
            //   'timeout'  => 2.0,
        ]);
        try{
            $response = $client->request('POST', 'eventos',[
                'form_params' => $this->options['create']['one_field'],
                'headers' => $this->headers,
                'http_errors' => false
            ]);
        
            $code = $response->getStatusCode();
            $reason = $response->getReasonPhrase();
            $body = $response->getBody();
            echo $body;
        }
        catch(RequestException  $e){
            print_r($e);
        }
        $this->assertEquals(HTTP_CODE_ERROR,$code);
        $this->assertEquals(HTTP_MESSAGE_ERROR_CREATE,$reason);
    }
    /** @test */
    public function try_to_create_one_event_in_api_only_descricao_localizacao(){
        $code=0;
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => BASE_URL_TEST//,
            // You can set any number of default request options.
            //   'timeout'  => 2.0,
        ]);
        try{
            $response = $client->request('POST', 'eventos',[
                'form_params' => $this->options['create']['two_fields'],
                 'headers' => $this->headers,
                 'http_errors' => false
            ]);
        
            $code = $response->getStatusCode(); 
            $reason = $response->getReasonPhrase(); 
            $body = $response->getBody();
            echo $body;
        }
        catch(RequestException  $e){
            print_r($e);
        }
        $this->assertEquals(HTTP_CODE_ERROR,$code);
        $this->assertEquals(HTTP_MESSAGE_ERROR_CREATE,$reason);
    }   
    /** @test */
    public function create_one_event_in_api_only_descricao_localizacao_valor_full(){
        $code=0;
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => BASE_URL_TEST//,
            // You can set any number of default request options.
            //   'timeout'  => 2.0,
        ]);
        try{
            $response = $client->request('POST', 'eventos',[
                'form_params' =>  $this->options['create']['three_fields'],
                'headers' => $this->headers,
                'http_errors' => false
            ]);
        
            $code = $response->getStatusCode();
            $reason = $response->getReasonPhrase();
            $body = json_decode($response->getBody());
            
        }
        catch(RequestException  $e){
            print_r($e);
        }
        
        $this->assertEquals(HTTP_CODE_CREATE,$code);
        $this->assertEquals(HTTP_MESSAGE_CREATE,$reason);
        $this->assertEquals(HTTP_CODE_CREATE,$body->status);
        $this->assertEquals(HTTP_MESSAGE_CREATE,$body->messages);
    }
    /** @test */
    public function update_one_event_in_api(){
        $code=0;
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => BASE_URL_TEST//,
            // You can set any number of default request options.
         //   'timeout'  => 2.0,
        ]);
        try{
            $response = $client->request('PUT', 'eventos/1',[
                'form_params' =>$this->options['update']['complete'],
                'headers' => $this->headers
            ]);
        
            $code = $response->getStatusCode();
            $reason = $response->getReasonPhrase(); 
            $body = json_decode($response->getBody());
            
            
            
        }
        catch(Exception  $e){
            print_r($e);
        }
        $this->assertEquals(HTTP_CODE_OK,$code);
        $this->assertEquals(HTTP_MESSAGE_UPDATE,$reason);
        $this->assertEquals(HTTP_CODE_OK,$body->status);
        $this->assertEquals(HTTP_MESSAGE_UPDATE_2,$body->messages);
    }   
    /** @test */
    public function update_one_event_in_api_only_descricao(){
                $code=0;
                $client = new Client([
                    // Base URI is used with relative requests
                    'base_uri' => BASE_URL_TEST//,
                    // You can set any number of default request options.
                    //   'timeout'  => 2.0,
                ]);
                try{
                    $response = $client->request('PUT', 'eventos/2',[
                        'form_params' => $this->options['update']['one_field'],
                        'headers' => $this->headers,
                        'http_errors' => false
                    ]);
                
                    $code = $response->getStatusCode();
                    $reason = $response->getReasonPhrase();
                    $body = json_decode($response->getBody());
                }
                catch(RequestException  $e){
                    print_r($e);
                }
                
                $this->assertEquals(HTTP_CODE_OK,$code);
                $this->assertEquals(HTTP_MESSAGE_UPDATE,$reason);
                $this->assertEquals(HTTP_CODE_OK,$body->status);
                $this->assertEquals(HTTP_MESSAGE_UPDATE_2,$body->messages);
            }
             /** @test */
    public function update_one_event_in_api_only_descricao_localizacao(){
        $code=0;
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => BASE_URL_TEST//,
            // You can set any number of default request options.
            //   'timeout'  => 2.0,
        ]);
        try{
            $response = $client->request('PUT', 'eventos/3',[
                'form_params' => $this->options['update']['two_fields'],
                'headers' => $this->headers,
                'http_errors' => false
            ]);
        
            $code = $response->getStatusCode(); 
            $reason = $response->getReasonPhrase(); 
            $body = json_decode($response->getBody());
           
        }
        catch(RequestException  $e){
            print_r($e);
        }
        $this->assertEquals(HTTP_CODE_OK,$code);
        $this->assertEquals(HTTP_MESSAGE_UPDATE,$reason);
        $this->assertEquals(HTTP_CODE_OK,$body->status);
        $this->assertEquals(HTTP_MESSAGE_UPDATE_2,$body->messages);
    }   
     /** @test */
     public function update_one_event_in_api_only_descricao_localizacao_valor_full(){
        $code=0;
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => BASE_URL_TEST//,
            // You can set any number of default request options.
            //   'timeout'  => 2.0,
        ]);
        try{
            $response = $client->request('PUT', 'eventos/4',[
                'form_params' => $this->options['update']['three_fields'],
                'headers' => $this->headers,
                'http_errors' => false
            ]);
        
            $code = $response->getStatusCode(); 
            $reason = $response->getReasonPhrase();
            $body = json_decode($response->getBody());
            
        }
        catch(RequestException  $e){
            print_r($e);
        }
        $this->assertEquals(HTTP_CODE_OK,$code);
        $this->assertEquals(HTTP_MESSAGE_UPDATE,$reason);
        $this->assertEquals(HTTP_CODE_OK,$body->status);
        $this->assertEquals(HTTP_MESSAGE_UPDATE_2,$body->messages);
    }
}




?>