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
                'titulo' => $data['descricao'],
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
    public function getEventsInCalendar(){
        $events = $this->model->getAll();
        define('ICAL_FORMAT', 'Ymd\THis');
        $UID_PREFIX = "TCC_IGTI";
        $tz  = 'America/Sao_Paulo';
        $dtz = new \DateTimeZone($tz);
        $header = $this->getHeaderCalendar();
        
        foreach($header as $line){
            $icalObject[] = $line;
        }
        foreach ($events as $event) {
            $icalObject[] = "BEGIN:VEVENT";
            $icalObject[] = "DTSTART;TZID=$tz:" . date(ICAL_FORMAT, strtotime($event->data_evento_inicio));
            $icalObject[] = "DTEND;TZID=$tz:" . date(ICAL_FORMAT, strtotime($event->data_evento_fim));
            $icalObject[] = "DTSTAMP:" . date(ICAL_FORMAT, strtotime($event->created_at));
            $desc = $event->descricao;
            $flags=$this->buildDescricao($event);            
            $descricao = $this->fold("DESCRIPTION:". $event->descricao);
            foreach($descricao as $line)
                $icalObject[] = $line."\\n";
            foreach($flags as $line)
                $icalObject[] = $line;
            $icalObject[] = "SUMMARY:" . $event->titulo;
            $icalObject[] = "CLASS:PUBLIC";
            $icalObject[] = "UID:" . $UID_PREFIX."_".$event->idEvento;
            $icalObject[] = "STATUS:" . strtoupper($event->status);
            $icalObject[] = "LAST-MODIFIED:" . date(ICAL_FORMAT, strtotime($event->updated_at));
            $icalObject[] = "LOCATION:" . $event->localizacao;
            $icalObject[] = "END:VEVENT";

        }
        // close calendar
        $icalObject[] = "END:VCALENDAR";
        // Set the headers
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');
        echo implode("\r\n",$icalObject);
        
    }
    protected function getHeaderCalendar(){
        $icalObject [] = "BEGIN:VCALENDAR";
        $icalObject [] = "VERSION:2.0";
        $icalObject [] = "PRODID:-//José Mendes//Eventos de Dança//PT-BR";
        $icalObject [] = "METHOD:PUBLISH";
        $icalObject [] = "CALSCALE:GREGORIAN";
        $icalObject [] = "X-WR-TIMEZONE:America/Sao_Paulo";
        $icalObject [] = "BEGIN:VTIMEZONE";
        $icalObject [] = "TZID:America/Sao_Paulo";
        $icalObject [] = "X-LIC-LOCATION:America/Sao_Paulo";
        $icalObject [] ='BEGIN:DAYLIGHT';
        $icalObject [] ='TZNAME:CEST';
        $icalObject [] ='TZOFFSETFROM:-0300';
        $icalObject [] ='TZOFFSETTO:-0300';
        $icalObject [] ='DTSTART:19700101T000000';
        $icalObject [] ='END:DAYLIGHT';
        $icalObject [] = "BEGIN:STANDARD";
        $icalObject [] = "TZOFFSETFROM:-0300";
        $icalObject [] = "TZOFFSETTO:-0300";
        $icalObject [] = "TZNAME:-03;".
        $icalObject [] = "DTSTART:19700101T000000";
        $icalObject [] = "END:STANDARD";
        $icalObject [] = "END:VTIMEZONE";
        return $icalObject;
    }
    protected function buildDescricao($event){
        $descricao[] =sprintf(" Valor do Evento: R$ %s\\n",$event->valor_full);
        $descricao[] =sprintf(" Valor do Evento com desconto: R$ %s\\n", $event->valor_desconto);
        if($event->tem_estacionamento==1){
            $descricao[] =" Tem estacionamento no local? sim\\n";
            $descricao[] =sprintf(" Valor do estacionamento: R$ %s\\n" , $event->valor_estacionamento);
        }
        $descricao[] =sprintf(" Tem vagas nas proximidades? %s\\n", $event->tem_vagas_proximidades==1?"sim":"não");
        $descricao[] =sprintf(" Tem comida especial? %s\\n",$event->tem_comida_especial==1?"sim":"não");
        $descricao[] =sprintf(" Tem lista de aniversariantes? %s\\n",$event->tem_lista_aniversariantes==1?"sim":"não");
        $descricao[] =sprintf(" Tem lanchonete? %s\\n" , $event->tem_lanchonete==1?"sim":"não");
        $descricao[] =sprintf(" Quantidade de Salas? %s\\n", $event->qtde_salas==NULL?"não informado":$event->qtde_salas);
        $descricao[] =sprintf(" Tem personais? %s\\n", $event->tem_personais==1?"sim":"não");
        $descricao[] =sprintf(" Tem dress code? %s\\n", $event->tem_dress_code==1?"sim":"não");
        $descricao[] =sprintf(" Tem chapelaria? %s\\n", $event->tem_chapelaria==1?"sim":"não");
        if($event->tem_reserva_mesas==1){
            $descricao[] =" Tem Reserva de mesas? sim %s\\n";
            $descricao[] =sprintf(" Valor da Reserva: R$ %s\\n", $event->valor_mesas);
        }



        return $descricao;

    }


  /**
     * Folds a single line.
     *
     * According to RFC 5545, all lines longer than 75 characters should be folded
     *
     * @see https://tools.ietf.org/html/rfc5545#section-5
     * @see https://tools.ietf.org/html/rfc5545#section-3.1
     *
     * @param string $string
     *
     * @return array
     */
    protected function fold($string)
    {
        $lines = [];

        if (function_exists('mb_strcut')) {
            while (strlen($string) > 0) {
                if (strlen($string) > 75) {
                    $lines[] = mb_strcut($string, 0, 75, 'utf-8');
                    $string = ' ' . mb_strcut($string, 75, strlen($string), 'utf-8');
                } else {
                    $lines[] = $string;
                    $string = '';
                    break;
                }
            }
        } else {
            $array = preg_split('/(?<!^)(?!$)/u', $string);
            $line = '';
            $lineNo = 0;
            foreach ($array as $char) {
                $charLen = strlen($char);
                $lineLen = strlen($line);
                if ($lineLen + $charLen > 75) {
                    $line = ' ' . $char;
                    ++$lineNo;
                } else {
                    $line .= $char;
                }
                $lines[$lineNo] = $line;
            }
        }

        return $lines;
    }

}