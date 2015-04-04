<form method="post" id="save" action="?module=application&controller=home&action=save">
    <header>
        <h1><i class="fa fa-anchor"></i> Farol</h1>
        <button class="button normal float-right float-button margin-horizontal hover-shadow" 
            type="button" onclick="enviar(<?php echo count($this->data['files']); ?>)">
            Enviar <i class="fa fa-check fa-lg"></i>
        </button>
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
                    <button type="button" id="bt-<?php echo $i; ?>" 
                        class="button normal hover-shadow" 
                        onclick="openModal(<?php echo $i.','.'\''.$file->getNome().'\''; ?>)">
                        Adicionar informações
                    </button>
                </div>
            </div>
            <?php
                echo "<input type='hidden' name=\"files[$i][materia]\">".
                    "<input type='hidden' name=\"files[$i][professor]\">".
                    "<input type='hidden' name=\"files[$i][ano]\">".
                    "<input type='hidden' name=\"files[$i][semestre]\" value='1'>".
                    "<input type='hidden' name=\"files[$i][provaTrabalho]\" value='1'>".
                    "<input type='hidden' name=\"files[$i][numero]\" value='1'>".
                    "<input type='hidden' name=\"files[$i][substitutiva]\" value='0'>".
                    "<input type='hidden' name=\"files[$i][arquivo]\" value='".$file->getArquivo()."'> ".
                    "<input type='hidden' name=\"files[$i][nome]\" value='".$file->getNome()."'>".
                    "<input type='hidden' name=\"files[$i][imagem]\" value='".$file->isImagem()."'> ";
                $i++;
            }
            ?>
    </section>
</form>
<script type="text/javascript" src="public/js/uploadModal.js"></script>