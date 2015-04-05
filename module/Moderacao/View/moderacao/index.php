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
            <a href="<?php echo $file->getArquivo(); ?>" data-lightbox="arquivos">
                <img src="<?php echo $file->getArquivo(); ?>" class="uploadThumb"/>
            </a>
        </div>
        <div>
            <span><?php echo $file->stringlize();?></span>
            <br/>
            <button type="button" id="bt-<?php echo $i; ?>" 
                class="button red hover-shadow">
                <i class="fa fa-close fa-fw"></i>
            </button>
            <button type="button" id="bt-<?php echo $i; ?>" 
                class="button normal hover-shadow">
                <i class="fa fa-edit fa-fw"></i>
            </button>
            <button type="button" id="bt-<?php echo $i; ?>" 
                class="button dark hover-shadow">
                <i class="fa fa-check fa-fw"></i>
            </button>
        </div>
    </div>
    <?php
        }
    }
    ?>
</section>