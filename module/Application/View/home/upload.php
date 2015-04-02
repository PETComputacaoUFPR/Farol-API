<form method="post">
    <header class="header">
        <h1>Upload de arquivos</h1>
    </header>
    <section>
            <?php
                $i = 0;
                foreach($this->data['files'] as $file){
                    //Se o arquivo for um pdf e não uma imagem é necessário criar um thumbnail
            ?>
                <div class="uploadInfo">
                    <div>
                        <img src="<?php echo $file->getArquivo(); ?>" class="uploadThumb"/>
                    </div>
                    <div>
                        <button type="button" onclick="openModal<?php echo $i; ?>('<?php echo $file->getNome(); ?>')" class="button button-hover-shadow">Adicionar informações</button>
                    </div>
                </div>
                <script>
                    //Isso fede
                    function openModal<?php echo $i; ?>(name){
                        $vexContent = vex.open({
                    		content: '<h5>'+name+'</h5>'+
                    		'<label for="materia">Matéria: <input type="text" name="materia" id="materia" placeholder="Máteria" required></label>'+
                    		'<br/><label for="professor">Professor: <input type="text" name="professor" id="professor" placeholder="Professor" required></label>'+
                    		'<br/><label for="trabalho"> <input type="radio" name="provaTrabalho" id="trabalho" value="0" required> Trabalho </label>'+
                    		'<label for="prova"> <input type="radio" name="provaTrabalho" id="trabalho" value="1" required> Prova </label>'+
                    		'<br/>Substitutiva? <label for="sim"><input type="radio" id="sim" name="substitutiva" value="1" required>Sim</label>'+
                    		'<label for="nao"><input type="radio" id="nao" name="substitutiva" value="0" required>Não</label>'+
                    		'<br/> <button type="button" onclick="closeVex()" class="button button-hover-shadow">OK</button>',
                    		afterOpen: function($vexContent) {
                    			// return $vexContent.append($el);
                    		},
                    		afterClose: function() {
                    			// return console.log('vexClose');
                    		},
                    		showCloseButton: true,
                    		escapeButtonCloses: false,
                    		overlayClosesOnClick: false
                    	});
                    }
                    
                    function closeVex(){
                        //Validar os dados e fechar
                        vex.close($vexContent.data().vex.id);
                    }
                </script>
            <?php
            $i++;
                }
            ?>
    </section>
</form>