<?php namespace App\Models;

use CodeIgniter\Model;

class EventoModel extends Model
{
    protected $table = 'evento';
    protected $allowedFields = ['descricao', 'localizacao','valor_full','valor_desconto'];
    protected $primaryKey = 'idEvento';
    protected $validationRules = [
        'descricao' => 'required|min_length[3]|max_length[255]',
        'localizacao'  =>'required',
        'valor_full'  => 'required|regex_match[/^\$?[0-9\,]*[0-9\.]?[0-9]{0,2}$/]'
    ];
    protected $validationMessages = [
        'descricao' =>  
            ['required'=>'Este campo é obrigatório, por favor preencha.',
            'min_length'=>'O campo deve conter no mínimo 3 caracteres',
            'max_lehgth'=>'O campo deve conter no máximo 255 caracteres'],
        'localizacao'  => [
            'required'=>'Este campo é obrigatório, por favor preencha.'
        ],
        'valor_full'  =>
            ['required'=>'Este campo é obrigatório, por favor preencha.',
            'regex_match'=>'O campo deve conter somente números'
        ]
    ];
    protected $beforeUpdate = ['cleanNumberField'];
    protected $cleanValidationRules = false;

    protected function cleanNumberField(array $data){
        
        if(isset($data['data']['valor_full'])){
            $str = preg_replace('/\D/', '', $data['data']['valor_full']);
            $data['data']['valor_full'] = \number_format($str, 2, '.', '');
        }
       return $data; 
    }
    public function setCleanValidationRules($val){
        $this->cleanValidationRules = $val;
    }
    public function getEvento($descricao = false)
    {
        if ($descricao === false)
        {
            return $this->findAll();
        }
    
        return $this->asArray()
                    ->where(['descricao' => $descricao])
                    ->first();
    }
/*
    public function find($id = false)
    {
        if ($id === false)
        {
            return false;
        }
    
        return $this->asArray()
                    ->where(['idEvento' => $id])
                    ->first();
    }
*/
}