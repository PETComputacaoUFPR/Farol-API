<form method="post" id="save">
    <header>
        <h1>Farol</h1>
        <button class="button normal float-right float-button margin-horizontal" type="button" onclick="enviar(<?php echo count($this->data['files']);?>)">Enviar</button>
    </header>
    <section>
            <?php
                $i = 0;
                foreach($this->data['files'] as $file){
                    //Se o arquivo for um pdf e não uma imagem é necessário criar uma thumbnail
            ?>
                <div class="uploadInfo">
                    <div>
                        <img src="<?php echo $file->getArquivo(); ?>" class="uploadThumb"/>
                    </div>
                    <div>
                        <button type="button" id="bt-<?php echo $i; ?>" onclick="openModal<?php echo $i; ?>('<?php echo $file->getNome(); ?>')" class="button normal hover-shadow">Adicionar informações</button>
                    </div>
                </div>
                <script>
                    //Isso fede
                    function openModal<?php echo $i; ?>(name){
                        i = <?php echo $i; ?>;
                        $vexContent = vex.open({
                    		content: '<h5>'+name+'</h5>'+
                    		'<label for="materia">Matéria: <input type="text" name="materia" id="materia" placeholder="Máteria" required autofocus></label>'+
                    		'<br/><label for="professor">Professor: <input type="text" name="professor" id="professor" placeholder="Professor" required></label>'+
                    		'<br/><label for="trabalho"> <input type="radio" name="provaTrabalho" id="trabalho" value="0" required> Trabalho </label>'+
                    		'<label for="prova"> <input type="radio" name="provaTrabalho" id="prova" value="1" required> Prova </label>'+
                    		'<br/>Número: <label for="1"><input type="radio" name="numero" id="1" value="1">1</label>'+
                    		'<label for="2"><input type="radio" name="numero" id="2" value="2">2</label>'+
                    		'<label for="3"><input type="radio" name="numero" id="3" value="3">3</label>'+
                    		'<label for="final"><input type="radio" name="numero" id="final" value="final">Final</label>'+
                    		'<br/>Substitutiva? <label for="sim"><input type="radio" id="sim" name="substitutiva" value="1" required>Sim</label>'+
                    		'<label for="nao"><input type="radio" id="nao" name="substitutiva" value="0" required>Não</label>'+
                    		'<br/> <button type="button" onclick="closeVex('+i+')" class="button normal hover-shadow">OK</button>',
                    		afterOpen: function($vexContent) {
                    		    if(localStorage['file-'+i] != undefined){
                    		        var file = JSON.parse(localStorage['file-'+i]);
                    		        $('#materia').val(file['materia']);
                    		        $('#professor').val(file['professor']);
                    		        
                    		        if(file['provaTrabalho'] == 0){ 
                    		            $('#trabalho').checked = true; 
                    		            $('#trabalho').prop('checked', true);
                    		        }else{
                    		            $('#prova').checked = true; 
                    		            $('#prova').prop('checked', true);
                    		        }
                    		        //Para os números das provas
                    		        $('#'+file['numero']).checked = true;
                    		        $('#'+file['numero']).prop('checked', true);
                    		        if(file['substitutiva'] == 0) {
                    		            $('#nao').checked = true; 
                    		            $('#nao').prop('checked', true);
                    		        }else{
                    		            $('#sim').checked = true; 
                    		            $('#sim').prop('checked', true);
                    		        }
                    		    }else{
                    		        $('#prova').checked = true;
                    		        $('#prova').prop('checked', true);
                    		        $('#1').checked = true;
                    		        $('#1').prop('checked', true);
                    		        $('#nao').checked = true;
                    		        $('#nao').prop('checked', true);
                    		    }
                    		},
                    		afterClose: function() {
                    		},
                    		showCloseButton: true,
                    		escapeButtonCloses: false,
                    		overlayClosesOnClick: false
                    	});
                    }
                </script>
            <?php
            $i++;
                }
            ?>
            <script>        
            function closeVex(n){
                var i = n;
                //Validar os dados e fechar
                if(!$('#materia').val()){
                    $('#materia').addClass('warning');
                    return;
                }
                var file = {};
    		    file['materia'] = $('#materia').val();
    		    file['professor'] = $('#professor').val();
    		    file['provaTrabalho'] = $("input[name='provaTrabalho']:checked").val();
    		    file['numero'] = $("input[name='numero']:checked").val();
    		    file['substitutiva'] = $("input[name='substitutiva']:checked").val();
    		    localStorage['file-'+i] = JSON.stringify(file);
    		    $('#bt-'+i).text("Editar informações").removeClass('normal').addClass('light');
                vex.close($vexContent.data().vex.id);
            }
            
            function enviar(numFiles){
                var postFiles = {};
                if(localStorage.length < numFiles){
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
                var i = 0;
                for(var key in localStorage){
                    var file = JSON.parse(localStorage[key]);
                    var array = [];
                    for(var k in file){
                        array[k] = file[k];
                    }
                    postFiles[i] = array;
                    i++;
                }
                console.log(postFiles);
                $.post('?module=application&controller=home&action=save', {"files": postFiles}, function(result){
                    console.log(result);
                });
            }
            </script>
    </section>
</form>