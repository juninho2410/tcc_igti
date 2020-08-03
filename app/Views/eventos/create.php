
<?php echo view('templates/header', ['title' => $title]);?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800"><?= esc($title); ?></h1>

            <form action="/api/eventos/<?=isset($evento)?$evento['idEvento']:"";?>" method="post" enctype="application/x-www-form-urlencoded" id="evento">
            <?php if(isset($evento)): ?>
                <input type="hidden" name="_method" value="PUT" />
                <input type="hidden" name="idEvento" value="<?=$evento['idEvento'];?>" />
                <input type="hidden" name="id_detalhes_evento" value="<?=$evento['id_detalhes_evento'];?>" />

            <?php endif;?>
            
                <?= csrf_field() ?>
                <div class="form-group row">
                    <label for="descricao" class="col-sm-2 col-form-label  col-form-label-sm"">Descrição</label>
                    <div class="col-sm-10">
                        <textarea class="form-control form-control-sm" name="descricao" id="descricao" placeholder="Descrição do Evento"><?=isset($evento['descricao'])?$evento['descricao']:"";?></textarea>
                        <div class="invalid-feedback">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Localização</label>
                    <div class="col-sm-10">
                        <input  class="form-control form-control-sm" type="localizacao" name="localizacao" id="localizacao" placeholder="Localização do Evento" value="<?=isset($evento['localizacao'])?$evento['localizacao']:"";?>"/><br />
                        <div class="invalid-feedback">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Valor</label>
                    <div class="col-sm-10">
                      <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                          </div>
                          <input type="text" class="form-control" name="valor_full" id="valor_full" placeholder="Valor do Evento" value="<?=isset($evento['valor_full'])?$evento['valor_full']:"";?>"
                          data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'R$ ', 'placeholder': '0'" inputmode="numeric" />
                          <div class="input-group-append">
                            <span class="input-group-text">.00</span>
                          </div>
                          <div class="invalid-feedback"></div>
                      </div>
                        
                    </div>
                </div>
              
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Valor com Desconto</label>
                    <div class="col-sm-10">
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">$</span>
                        </div>
                        <input type="text" class="form-control" name="valor_desconto" id="valor_desconto" placeholder="Valor do Evento com Desconto" value="<?=isset($evento['valor_desconto'])?$evento['valor_desconto']:"";?>"
                        data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'R$ ', 'placeholder': '0'" inputmode="numeric"/>
                        <div class="input-group-append">
                          <span class="input-group-text">.00</span>
                        </div>
                        <div class="invalid-feedback"></div>
                      </div>
                      
                    </div>
                </div>
                <div class='col-md-5'>
                  <div class="form-group">
                    <div class="input-group date" id="data_evento_inicio" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#data_evento_inicio" name="data_evento_inicio"
                          value="<?php if(isset($evento['data_evento_inicio'])){$x=explode('-',$evento['data_evento_inicio']);$y=explode(' ',$x[2]); echo $y[0]."/".$x[1]."/".$x[0]." ".$y[1]; }?>"
                          />
                          <div class="input-group-append" data-target="#data_evento_inicio" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class='col-md-5'>
                  <div class="form-group">
                    <div class="input-group date" id="data_evento_fim" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#data_evento_fim" name="data_evento_fim"
                          value="<?php if(isset($evento['data_evento_fim'])){$x=explode('-',$evento['data_evento_fim']);$y=explode(' ',$x[2]);echo $y[0]."/".$x[1]."/".$x[0]." ".$y[1];}?>"
                          />
                          <div class="input-group-append" data-target="#data_evento_fim" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                      </div>
                  </div>
              </div>

               <!-- <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Data Inicio</label>
                    <div class="col-sm-10">
                        <input  class="form-control form-control-sm" type="text" name="data_evento_inicio" id="data_evento_inicio" placeholder="Data Evento" value="<?php if(isset($evento['data_evento_inicio'])){$x=explode('-',$evento['data_evento_inicio']);echo explode(' ',$x[2])[0]."/".$x[1]."/".$x[0]; }?>"/><br />
                        <div class="invalid-feedback">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Data Fim</label>
                    <div class="col-sm-10">
                        <input  class="form-control form-control-sm" type="text" name="data_evento_fim" id="data_evento_fim" placeholder="Data Fim" value="<?php if(isset($evento['data_evento_fim'])){$x=explode('-',$evento['data_evento_fim']);echo explode(' ',$x[2])[0]."/".$x[1]."/".$x[0]; }?>"/><br />
                        <div class="invalid-feedback">
                        </div>
                    </div>
                </div>-->
                <hr/>
                <div class="card">
                  <div class="card-header">
                    Detalhes Evento
                  </div>
                  <div class="card-body">
                    
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <div class="custom-switch">
                            <input type="checkbox" class="custom-control-input" id="tem_estacionamento" name="tem_estacionamento" <?=(isset($evento['tem_estacionamento']) && $evento['tem_estacionamento'] ==1)?'checked="checked"':"";?>/>
                            <label class="custom-control-label" for="tem_estacionamento">Tem Estacionamento</label>
                          </div>
                        </div>
                      </div>
                      <input type="text" class="form-control" aria-label="Estacionamento" placeholder="Valor do Estacionamento" id="valor_estacionamento" name="valor_estacionamento" 
                      data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'R$ ', 'placeholder': '0'" inputmode="numeric" 
                      value="<?=isset($evento['valor_estacionamento'])?$evento['valor_estacionamento']:"";?>" >
                      <div class="input-group-append">
                          <span class="input-group-text">.00</span>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col">
                        <div class="custom-switch form-check-inline">
                          <input type="checkbox" class="custom-control-input" id="tem_vagas_proximidades" name="tem_vagas_proximidades" <?=(isset($evento['tem_vagas_proximidades']) && $evento['tem_vagas_proximidades'] ==1)?'checked="checked"':"";?>>
                          <label class="custom-control-label" for="tem_vagas_proximidades">Tem Vagas nas proximidades</label>
                        </div>
                      </div>
                      <div class="col">
                        <div class="custom-switch form-check-inline">
                          <input type="checkbox" class="custom-control-input" id="tem_comida_especial" name="tem_comida_especial" <?=(isset($evento['tem_comida_especial']) && $evento['tem_comida_especial'] ==1)?'checked="checked"':"";?>>
                          <label class="custom-control-label" for="tem_comida_especial">Tem Comida especial</label>
                        </div>
                      </div>
                      <div class="w-100"></div>
                      <div class="col">
                        <div class="custom-switch form-check-inline">
                          <input type="checkbox" class="custom-control-input" id="tem_lista_aniversariantes" name="tem_lista_aniversariantes" <?=(isset($evento['tem_lista_aniversariantes'])  && $evento['tem_lista_aniversariantes'] ==1)?'checked="checked"':"";?>>
                          <label class="custom-control-label" for="tem_lista_aniversariantes">Tem Lista de aniversariantes</label>
                        </div>  
                      </div>
                      <div class="col">
                        <div class="custom-switch form-check-inline">
                          <input type="checkbox" class="custom-control-input" id="tem_lanchonete" name="tem_lanchonete" <?=(isset($evento['tem_lanchonete']) && $evento['tem_lanchonete'] ==1)?'checked="checked"':"";?>>
                          <label class="custom-control-label" for="tem_lanchonete">Tem Lanchonete</label>
                        </div>  
                      </div>
                      <div class="w-100"></div>
                      <div class="col">
                        <div class="custom-switch form-check-inline">
                          <input type="checkbox" class="custom-control-input" id="tem_personais" name="tem_personais" <?=(isset($evento['tem_personais']) && $evento['tem_personais'] ==1)?'checked="checked"':"";?>>
                          <label class="custom-control-label" for="tem_personais">Tem Personais</label>
                        </div>  
                      </div>
                      <div class="col">
                        <div class="custom-switch form-check-inline">
                          <input type="checkbox" class="custom-control-input" id="tem_dress_code" name="tem_dress_code" <?=(isset($evento['tem_dress_code']) && $evento['tem_dress_code'] ==1)?'checked="checked"':"";?>>
                          <label class="custom-control-label" for="tem_dress_code">Tem Dress code</label>
                        </div>  
                      </div>
                      <div class="w-100"></div>
                      <div class="col">
                        <div class="custom-switch form-check-inline">
                          <input type="checkbox" class="custom-control-input" id="tem_chapelaria" name="tem_chapelaria" <?=(isset($evento['tem_chapelaria']) && $evento['tem_chapelaria'] ==1)?'checked="checked"':"";?>>
                          <label class="custom-control-label" for="tem_chapelaria">Tem Chapelaria</label>
                        </div>
                      </div>
                    </div>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <div class="custom-switch">
                            <input type="checkbox" class="custom-control-input" id="tem_reserva_mesas" name="tem_reserva_mesas" <?=(isset($evento['tem_reserva_mesas']) && $evento['tem_reserva_mesas'] ==1)?'checked="checked"':"";?>>
                            <label class="custom-control-label" for="tem_reserva_mesas">Tem Reserva Mesas</label>
                          </div>
                        </div>
                      </div>
                      <input type="text" class="form-control" aria-label="valor_mesas" placeholder="Valor Mesas" id="valor_mesas" name="valor_mesas" 
                      data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'digits': 2, 'digitsOptional': false, 'prefix': 'R$ ', 'placeholder': '0'" inp utmode="numeric"
                      value="<?=isset($evento['valor_mesas'])?$evento['valor_mesas']:"";?>">
                      <div class="input-group-append">
                          <span class="input-group-text">.00</span>
                        </div>
                    </div> 
                  </div>
                </div>
                <input type="submit" class="btn btn-primary"  name="submit" value="Salvar" />
            </form>
        </div>

        <?php echo view('templates/footer');?>
        <!-- Modal -->
<div class="modal fade" id="modal-register" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Cadastro de Eventos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
        <script src="<?=base_url('/vendor/input-mask/inputmask.min.js');?>"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/locale/pt-br.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
        <script src="<?=base_url('/custom/js/eventos.js');?>"></script>
    </body>
</html>
