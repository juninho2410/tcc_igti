<?php echo view('templates/header', ['title' => 'Lista','page'=>$page,'module'=>$module]);?>
 <!-- Begin Page Content -->
 <div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800"><?= esc($title); ?></h1>

<div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Lista de Eventos</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="eventosList" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Localização</th>
                        <th>Valor</th>
                        <th>Valor com Desconto</th>
                        <th>Ações</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Descrição</th>
                      <th>Localização</th>
                      <th>Valor</th>
                      <th>Valor com Desconto</th>
                      <th>Ações</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach ($eventos as $evento_item): ?>
                        <tr>
                            <td><?= esc($evento_item['descricao']); ?></td>
                            <td><?= esc($evento_item['localizacao']); ?></td>
                            <td><?= esc($evento_item['valor_full']); ?></td>
                            <td><?= esc($evento_item['valor_desconto']); ?></td>
                            <td><a href="/eventos/edit/<?=$evento_item['idEvento'];?>" role="button" class="btn btn-primary"><i class="fa fa-edit"></i>Editar</a>
                            <a href="#" data-id="<?=$evento_item['idEvento'];?>" role="button" class="btn btn-primary delete-evento"><i class="fa delete"></i>Apagar</a></td>
                        </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
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
    <!-- Page level plugins -->
    <script src="<?=base_url('vendor/datatables/jquery.dataTables.min.js');?>"></script>
    <script src="<?=base_url('vendor/datatables/dataTables.bootstrap4.min.js');?>"></script>
    <script src="<?=base_url('/custom/js/eventos.js');?>"></script>

</body>
</html>