<header class="header">
    <h1>Upload de arquivos</h1>
</header>
<section>
    <?php
        foreach($this->data['files'] as $file){
            //Se o arquivo for um pdf e não uma imagem é necessário criar um thumbnail
    ?>
        <div class="uploadInfo">
            <div>
                <img src="<?php echo $file->getArquivo(); ?>" class="uploadThumb"/>
            </div>
            <div>
                <button class="button button-hover-shadow">Adicionar informações</button>
            </div>
        </div>
    <?php
        }
    ?>
</section>