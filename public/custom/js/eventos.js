
TCC = window.TCC || {};
TCC.EVENTO = (function () {
  var formFields = {
      name:"evento",
      id:"idEvento",
      modalId: "modal-register",
      urlAPI:"/api/eventos",
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
    if(idEvento == undefined){
      TCC.EVENTO.insert(data)
    }
    else{
      data.idEvento = idEvento
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
            maskedFields = $('#valor_full,#valor_desconto')
            Inputmask({"autoUnmask":true}).mask(maskedFields)
          }
          $('#modal-register').on('hidden.bs.modal', redirect)

      },
      insert:function(data){
        $.ajax({
          method: "POST",
          url: formFields.urlAPI,
          data: data.formData,
          dataType: "json",
          statusCode: {
              400: errorValidate
            }
        })
        .done(successValidate);
      },
      update: function(data){
        $.ajax({
          method: "PUT",
          url: formFields.urlAPI+'/'+data.idEvento,
          data: data.formData,
          dataType: "json",
          statusCode: {
              400: errorValidate
            }
        })
        .done(successValidate);
      },
      delete: function(data){
        $.ajax({
          method: "DELETE",
          url: formFields.urlAPI+'/'+data.idEvento,
          dataType: "json",
          statusCode: {
              400: errorValidate
            }
        })
        .done(successValidate);
      }

    };
})();
$.when($.ready ).then(TCC.EVENTO.init())


  