<?php namespace App\Controllers;
use App\Models\EventoModel;
use CodeIgniter\Controller;
use CodeIgniter\RESTful\ResourcePresenter;


class Eventos extends ResourcePresenter
{
    protected $modelName = 'App\Models\EventoModel';

    public function index()
    {
        $data = [
            'eventos'  => $this->model->findAll(),
            'title' => 'Eventos',
            'page'=>'list',
            'module'=>'eventos'
        ];
        echo view('eventos/list', $data);
        
        
    }

    public function show($id = null)
    {
  
        $data['evento'] = $this->model->find($id);
    
        if (empty($data['evento']))
        {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Evento Não Encontrado: '. $id);
        }
        $data['title'] = $data['evento']['descricao'];
            
        echo view('templates/header', $data);
        echo view('eventos/view', $data);
        echo view('templates/footer', $data);
    }
    public function new()
    {
        $data = ['title'=>'Novo',
                 'page'=>'new',
                 'module'=>'eventos'];
		
        helper('form');
        
        echo view("eventos/create", $data);
       
    }
    public function edit($id = null){

        if (($object = $this->ensureExists($id)) instanceof RedirectResponse)
		{
			return $object;
		}
		
        $data = ['evento' => $object,
                 'title'=>'Editar',
                 'page'=>'edit',
                 'module'=>'eventos'];
                 
		
        helper('form');
        
        echo view("eventos/create", $data);

    }
    public function create()
    {
        $model = new EventoModel();
    
        if (! $this->validate([
            'descricao' => 'required|min_length[3]|max_length[255]',
            'localizacao'  => 'required'
        ]))
        {
            echo view('templates/header', ['title' => 'Eventos']);
            echo view('eventos/create');
            echo view('templates/footer');
        }
        else
        {
            $model->save([
                'descricao' => $this->request->getVar('descricao'),
                'localizacao'  => $this->request->getVar('localizacao'),
                
            ]);
    
            echo view('eventos/success');
        }
    }

    protected function ensureExists($id = null)
	{
		if ($object = $this->model->find($id))
		{
			return $object;
		}
		
		$error = ['error'=>'Evento não encontrado'];

		$this->alert('danger', $error);

		return redirect()->back()->withInput()->with('errors', [$error]);
	}

}