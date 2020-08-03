<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class DetalhesEventosRest extends ResourceController
{
    protected $modelName = 'App\Models\DetalhesEventoModel';
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
                'id_evento' => $data['id_evento'],
                'data_evento_inicio'  => $data['data_evento_inicio'],
                'data_evento_fim'  => $data['data_evento_fim'],
                'tem_estacionamento'  => isset($data['tem_estacionamento'])?$data['tem_estacionamento']:null,
                'valor_estacionamento'  => isset($data['valor_estacionamento'])?$data['valor_estacionamento']:null,
                'tem_vagas_proximidades'  => isset($data['tem_vagas_proximidades'])?$data['tem_vagas_proximidades']:null,
                'tem_lista_aniversariantes'  => isset($data['tem_lista_aniversariantes'])?$data['tem_lista_aniversariantes']:null,
                'tem_comida_especial'  => isset($data['tem_comida_especial'])?$data['tem_comida_especial']:null,
                'tem_lanchonete'  => isset($data['tem_lanchonete'])?$data['tem_lanchonete']:null,
                'qtde_salas'  => isset($data['qtde_salas'])?$data['qtde_salas']:null,
                'tem_personais'  => isset($data['tem_personais'])?$data['tem_personais']:null,
                'tem_dress_code'  => isset($data['tem_dress_code'])?$data['tem_dress_code']:null,
                'tem_chapelaria'  => isset($data['tem_chapelaria'])?$data['tem_chapelaria']:null,
                'tem_reserva_mesas'  => isset($data['tem_reserva_mesas'])?$data['tem_reserva_mesas']:null,
                'valor_mesas'  => isset($data['valor_mesas'])?$data['valor_mesas']:null            
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
        $id_detalhes_evento = $object['id_detalhes_evento'];
        if (! $this->model->delete($id_detalhes_evento))
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
        $object = $this->model->getDetalhesEvento($id);
		if ($object)
		{
			return $object;
		}
		
		return $this->failNotFound('Not Found', null, "Não encontrado");
	}

}
