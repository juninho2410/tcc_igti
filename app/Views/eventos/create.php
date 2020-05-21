
<?php echo view('templates/header', ['title' => $title]);?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800"><?= esc($title); ?></h1>

            <form action="/api/eventos/<?=isset($evento)?$evento['idEvento']:"";?>" method="post" enctype="application/x-www-form-urlencoded" id="evento">
            <?php if(isset($evento)): ?>
                <input type="hidden" name="_method" value="PUT" />
                <input type="hidden" name="idEvento" value="<?=$evento['idEvento'];?>" />

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
        <script src="<?=base_url('/custom/js/eventos.js');?>"></script>

    </body>
</html>
