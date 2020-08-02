
TCC = window.TCC || {};
TCC.EVENTO = (function () {
  var formFields = {
      name:"evento",
      id:"idEvento",
      id_detalhes_evento:"id_detalhes_evento",
      modalId: "modal-register",
      urlAPI:"/api/eventos",
      urlAPI_detalhes:"/api/detalhes-evento",
      urlRedirect:"/eventos/",
      tableId: "eventosList"
  }
  var  isListPage = function(){
    return $('#'+formFields.tableId).length>0?true:false
  }

  var successValidate = function( data ) {
    $('.is-invalid').removeClass("is-invalid")
    console.log(data.messages)
    $('#modal-register .modal-body').text(data.messages)
    $('#modal-register').modal('show')
  }
  var errorValidate = function (responseObject, textStatus, jqXHR) {
    messages = responseObject.responseJSON.messages
    console.warn('ERROR:'+responseObject.responseText)
    $('.is-invalid').removeClass("is-invalid")
    Object.entries(messages).forEach(([field,message]) => {
      $('#'+field).addClass('is-invalid')
      $('#'+field).siblings('.invalid-feedback').text(message)
    });
  }
  var submitFunction = function(e){
    console.info('Event form submited')
    e.preventDefault()
    var dataJSON = JSON.stringify( $(this).serializeArray() );
    console.info('NOTE: dataJSON:'+dataJSON)
    data = {formData:$(this).serializeArray()}
    var idEvento = $("input[name='"+formFields.id+"']").val()
    var id_detalhes_evento = $("input[name='"+formFields.id_detalhes_evento+"']").val()
    fieldsEvento = ['descricao','localizacao','valor_full','valor_desconto']
    fieldsDetalhesEvento = [
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
  ]
    data['evento']= []
    data['detalhes_evento']=[]
    foundFields=[]
    isInvalidDataEvento=false
    invalidFields=[]

    $.each(data.formData,function(k,v){
        if(fieldsEvento.includes(v.name)){
          data['evento'].push(v)
          foundFields.push(v.name)
        }
        else{
          if(fieldsDetalhesEvento.includes(v.name)){
            if(v.name =="data_evento_inicio" || v.name =="data_evento_fim"){
              if(v.value==null || v.value==""){
              isInvalidDataEvento=true
              invalidFields.push(v.name)
              }
              else{
                  dateSp=v.value.split("/")
                  if(dateSp.length==3){
                    dtSp=dateSp[2].split(' ')
                    v.value=dtSp[0]+"-"+dateSp[1]+"-"+dateSp[0] + " "+dtSp[1]
                  }
                  else
                    isInvalidDataEvento=true
              }
            }
            if(v.name.startsWith('tem') && v.value=="on"){
              v.value=1
            }   
            data['detalhes_evento'].push(v)
            foundFields.push(v.name)
          }
        }
    })
    diff = fieldsDetalhesEvento.filter(x => !foundFields.includes(x));

    $.each(diff,function(k,field){
      if(field.startsWith('tem')){
        data['detalhes_evento'].push({"name":field,"value":0})
        console.log(field)
      }
    })
    
    if(isInvalidDataEvento){
      $('.is-invalid').removeClass("is-invalid")
      $.each(invalidFields,function(k,sel){
        $('#'+sel).addClass('is-invalid')
        $('#'+sel).siblings('.invalid-feedback').text("Este campo é obrigatório, por favor preencha.")
      })
      return false
    }
  
    if(idEvento == undefined){
      TCC.EVENTO.insert(data)
    }
    else{
      data.idEvento = idEvento
      data.id_detalhes_evento=id_detalhes_evento
      TCC.EVENTO.update(data);
    }
    return false
  }
  var redirect = function(){
    console.log("Modal Closed")// do something…
    window.location = formFields.urlRedirect;
  }
  var deleteEvento= function(e){
    console.log("Delete Event Method")// do something…
    e.preventDefault()
    data = new Object()
    data.idEvento = $(this).data('id')
    TCC.EVENTO.delete(data);
  }
  return {
      init:function(){
        console.info('Inicializando módulo de Eventos')
          $('#'+formFields.name).submit(submitFunction)
          if(isListPage()){
            $('#'+formFields.tableId).DataTable();
            $('.delete-evento').on('click',deleteEvento)
          }
          else{
            maskedFields = $('#valor_full,#valor_desconto,#valor_estacionamento,#valor_mesas')
            Inputmask({"autoUnmask":true}).mask(maskedFields)
            $('#data_evento_inicio').datetimepicker();
            $('#data_evento_fim').datetimepicker({
                useCurrent: false
            });
            $("#data_evento_inicio").on("change.datetimepicker", function (e) {
                $('#data_evento_fim').datetimepicker('minDate', e.date);
            });
            $("#data_evento_fim").on("change.datetimepicker", function (e) {
                $('#data_evento_inicio').datetimepicker('maxDate', e.date);
            });

          }
          $('#modal-register').on('hidden.bs.modal', redirect)
      

      },
      insert:function(data){
        dataEvento = data.evento
        dataDetalhesEvento=data.detalhes_evento
        $.ajax({
          method: "POST",
          url: formFields.urlAPI,
          data: dataEvento,
          dataType: "json",
          statusCode: {
              400: errorValidate
            },
          success:function(data){
            if(data.status == 201){
              idEvento = data['id']
              dataDetalhesEvento.push({"name":"id_evento","value":idEvento})
              $.ajax({
                method: "POST",
                url: formFields.urlAPI_detalhes,
                data: dataDetalhesEvento,
                dataType: "json",
                statusCode: {
                    400: errorValidate
                  }
              }).done(successValidate)
            }
          }
        })
      },
      update: function(data){
        dataEvento = data.evento
        dataDetalhesEvento=data.detalhes_evento
        dataEvento.idEvento = data.idEvento
        dataDetalhesEvento.id_detalhes_evento= data.id_detalhes_evento 
        $.ajax({
          method: "PUT",
          url: formFields.urlAPI+'/'+data.idEvento,
          data: dataEvento,
          dataType: "json",
          statusCode: {
              400: errorValidate
            },
          success:function(data){
            if(data.status == 200){
              idEvento = data['id']
              dataDetalhesEvento.push({"name":"id_evento","value":dataEvento.idEvento})              
              $.ajax({
                method: "PUT",
                url: formFields.urlAPI_detalhes+'/'+dataDetalhesEvento.id_detalhes_evento,
                data: dataDetalhesEvento,
                dataType: "json",
                statusCode: {
                    400: errorValidate
                  }
              }).done(successValidate)
            }
          }
        })
      },
      delete: function(data){         
        idEvento = data.idEvento
        $.ajax({
          method: "DELETE",
          url: formFields.urlAPI_detalhes+'/'+idEvento,
          dataType: "json",
          statusCode: {
              400: errorValidate
            },
          success:function(data){
            if(data.status == 200){
              $.ajax({
                method: "DELETE",
                url: formFields.urlAPI+'/'+idEvento,
                dataType: "json",
                statusCode: {
                    400: errorValidate
                  }
      
                }
              ).done(successValidate)
            }

          }
        })
        
      }

    };
})();
$.when($.ready ).then(TCC.EVENTO.init())



