function openModal(n, name){
    $vexContent = vex.open({
        content: '<h5>'+name+'</h5>'+
        		'<label for="materia">Matéria: <input type="text" name="materia" id="materia" placeholder="Máteria" autofocus></label>'+
        		'<br/><label for="professor">Professor: <input type="text" name="professor" id="professor" placeholder="Professor"></label>'+
        		'<br/><label for="ano">Ano: <input type="year" name="ano" id="ano" placeholder="Ano"></label>'+
        		'<br/>Semestre: <label for="semestre-1"><input type="radio" name="semestre" id="semestre-1" value="1"> 1 </label>'+
        		'<label for="semestre-2"><input type="radio" name="semestre" id="semestre-2" value="2"> 2 </label>'+
        		'<br/><label for="trabalho"> <input type="radio" name="provaTrabalho" id="trabalho" value="0"> Trabalho </label>'+
        		'<label for="prova"> <input type="radio" name="provaTrabalho" id="prova" value="1"> Prova </label>'+
        		'<br/>Número: <label for="1"><input type="radio" name="numero" id="1" value="1">1</label>'+
        		'<label for="2"><input type="radio" name="numero" id="2" value="2">2</label>'+
        		'<label for="3"><input type="radio" name="numero" id="3" value="3">3</label>'+
        		'<label for="final"><input type="radio" name="numero" id="final" value="final">Final</label>'+
        		'<br/>Substitutiva? <label for="sim"><input type="radio" id="sim" name="substitutiva" value="1">Sim</label>'+
        		'<label for="nao"><input type="radio" id="nao" name="substitutiva" value="0" checked>Não</label>'+
        		'<br/> <button type="button" onclick="closeVex('+n+')" class="button normal hover-shadow">OK</button>',
        afterOpen: function($vexContent){
            $('#materia').val($("input[name='files["+n+"][materia]']").val());
            $('#professor').val($("input[name='files["+n+"][professor]']").val());
            $('#ano').val($("input[name='files["+n+"][ano]']").val());
            
            $("#"+$("input[name='files["+n+"][numero]']").val()).prop("checked", true);
            $("#semestre-"+$("input[name='files["+n+"][semestre]']").val()).prop("checked", true);
            
            if( $("input[name='files["+n+"][provaTrabalho]']").val() == 1){
                $('#prova').prop("checked", true);
            }else{
                $('#trabalho').prop("checked", true);
            }
            if( $("input[name='files["+n+"][substitutiva]']").val() == 1){
                $('#sim').prop("checked", true);
            }else{
                $('#nao').prop("checked", true);
            }
        },
        afterClose: function(){
        },
        showCloseButton: true,
        escapeButtonCloses: false,
        overlayClosesOnClick: false
    });
}

function closeVex(n){
    if(!$('#materia').val()){
        $('#materia').addClass('warning');
        return;
    }
    if(!$('#ano').val()){
        $('#ano').addClass('warning');
        return;
    }
    
    $("input[name='files["+n+"][materia]']").val($('#materia').val());
    $("input[name='files["+n+"][professor]']").val($('#professor').val());
    $("input[name='files["+n+"][ano]']").val($('#ano').val());
    $("input[name='files["+n+"][semestre]']").val($("input[name='semestre']:checked").val());
    $("input[name='files["+n+"][provaTrabalho]']").val($("input[name='provaTrabalho']:checked").val());
    $("input[name='files["+n+"][numero]']").val($("input[name='numero']:checked").val());
    $("input[name='files["+n+"][substitutiva]']").val($("input[name='substitutiva']:checked").val());
    $('#bt-'+n).text("Editar informações").removeClass('normal').addClass('light');
    vex.close($vexContent.data().vex.id);
    
    if(typeof update == 'function'){
        update();
    }
}

function enviar(numFiles){
    for(var i=0; i < numFiles; i++){
        if(!$("input[name='files["+i+"][materia]']").val()){
            //Mostra uma mesagem de erro e retorna
            vex.dialog.alert({
                message: "Preencha as informações de todos os arquivos!",
                buttons: [{
                    type: 'submit',
                    text: 'OK',
                    className: 'error vex-dialog-button-primary'
                }]
            });
            return;
        }
    }
    $("form").submit();
}