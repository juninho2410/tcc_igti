<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class EventosRest extends ResourceController
{
    protected $modelName = 'App\Models\EventoModel';
    protected $format    = 'json';

    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    public function show($id = null)
    {
        if (($object = $this->ensureExists($id)) instanceof \CodeIgniter\HTTP\ResponseInterface)
		{
			return $object;
		}

		return $this->respond([$this->model->find($id)]);
    }
    public function create()
    {
        $data = $this->request->getRawInput();

        if (! $this->model->validate($data))
        {
            $messages=$this->model->errors();          
            return $this->fail($messages,400,null,$customMessage='Create Failed');     
        }
        else{
            if( ! $id =  $this->model->save([
                'descricao' => $data['descricao'],
                'localizacao'  => $data['localizacao'],
                'valor_full'  => $data['valor_full'],
                'valor_desconto'  => isset($data['valor_desconto'])?$data['valor_desconto']:null
                ])
            ){
                
                $response = [
                    'status'   => 400,
                    'error'    => "Create Failed",
                    'messages' => $this->model->errors()
                ];
                
                $this->respond($response,400,"Create Failed");

            }
            else{
                $insertId = $this->model->getInsertID();
                $response = [
                    'status'   => 201,
                    'messages' => "Criado com sucesso",
                    'id' => $insertId
                ];
                return $this->respondCreated($response, "Criado com sucesso");
            }      
    
        }
    }
    public function update($id = null)
	{
        $data = $this->request->getRawInput();
        
		if (($object = $this->ensureExists($id)) instanceof \CodeIgniter\HTTP\ResponseInterface)
		{
			return $object;
		}

            $this->model->setCleanValidationRules(true);
            if (! $this->model->validate($data))
            {
                $messages=$this->model->errors();
                return $this->fail($messages);  
            }else{
                if (! $this->model->update($id,$data))
                {
                    return $this->fail(['Erro no update do banco']); 
                }else{
                $response = [
                    'status'   => 200,
                    'messages' => "Atualizado com sucesso"
                ];
                return $this->respond($response,200, "Evento Atualizado");
            }
            }
        
	
    }
    public function delete($id = null)
	{
        if (($object = $this->ensureExists($id)) instanceof \CodeIgniter\HTTP\ResponseInterface)
		{
			return $object;
        }
        if (! $this->model->delete($id))
            {
                return $this->fail(['Erro no delete do banco']); 
            }else{
            $response = [
                'status'   => 200,
                'messages' => "Apagado com sucesso"
            ];
            return $this->respond($response,200, "Evento apagado com sucesso");
        }
    }
	protected function ensureExists($id = null)
	{
        $object = $this->model->find($id);
		if ($object)
		{
			return $object;
		}
		
		return $this->failNotFound('Not Found', null, "Não encontrado");
	}

}