<header>
    <h1>Farol</h1>
    <h3>Área de moderação</h3>
</header>
<section>
    <?php 
    if(isset($this->data['pendentes'])){
        foreach($this->data['pendentes'] as $file){
    ?>
    <div class="uploadInfo">
        <div>
            <img src="<?php echo $file->getArquivo(); ?>" class="uploadThumb"/>
        </div>
        <div>
            <button type="button" id="bt-<?php echo $i; ?>" 
                class="button normal hover-shadow">
                Ver detalhes
            </button>
        </div>
    </div>
    <?php
        }
    }
    ?>
</section>