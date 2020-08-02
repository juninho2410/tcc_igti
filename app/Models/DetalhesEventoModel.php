<?php namespace App\Models;

use CodeIgniter\Model;

class DetalhesEventoModel extends Model
{
    protected $table = 'detalhes_evento';
    protected $allowedFields = [
        'id_evento',
        'data_evento_inicio', 
        'data_evento_fim', 
        'tem_estacionamento', 
        'valor_estacionamento',
        'tem_vagas_proximidades',
        'tem_comida_especial',
        'tem_lista_aniversariantes',
        'tem_lanchonete',
        'qtde_salas',
        'tem_personais',
        'tem_dress_code',
        'tem_chapelaria',
        'tem_reserva_mesas',
        'valor_mesas'
    ];
    protected $primaryKey = 'id_detalhes_evento';
    protected $validationRules = [
        'id_evento' => 'required',
        'data_evento_inicio'  => 'required',
        'data_evento_fim'  => 'required'
    ];
    protected $validationMessages = [
        'id_evento'     => ['required'=>'Este campo é obrigatório, por favor preencha.'],
        'data_evento_inicio'   => ['required'=>'Este campo é obrigatório, por favor preencha.'],
        'data_evento_fim'   => ['required'=>'Este campo é obrigatório, por favor preencha.']
    ];
    protected $beforeUpdate = ['cleanNumberField'];
    protected $cleanValidationRules = false;

    protected function cleanNumberField(array $data){
        
     /*   if(isset($data['data']['valor_full'])){
            $str = preg_replace('/\D/', '', $data['data']['valor_full']);
            $data['data']['valor_full'] = \number_format($str, 2, '.', '');
    }*/
     return $data; 
    }
    public function setCleanValidationRules($val){
        $this->cleanValidationRules = $val;
    }
    public function getDetalhesEvento($id = false)
    {
        if ($id === false)
        {
            return false;
        }
        $builder = $this->table('detalhes_evento')
                ->select('*')
                ->join('evento', 'evento.idEvento = detalhes_evento.id_evento')
                ->where(['id_evento' => $id]);
        $sql=$builder->getCompiledSelect();
        return $builder->get()->getRowArray();
                    
    }
}
