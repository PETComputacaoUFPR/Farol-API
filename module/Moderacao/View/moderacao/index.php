<header>
    <h1>Farol</h1>
    <h3>Área de moderação</h3>
</header>
<section>
    <?php 
    if(isset($this->data['pendentes'])){
        $i = 0;
        foreach($this->data['pendentes'] as $file){
    ?>
    <div class="uploadInfo">
        <div>
            <a href="<?php echo $file->getArquivo(); ?>" data-lightbox="arquivos">
                <img src="<?php echo $file->getArquivo(); ?>" class="uploadThumb"/>
            </a>
        </div>
        <div>
            <span id="string"><?php echo $file->stringlize()?></span>
            <br/>
            <button type="button" class="button red hover-shadow">
                <i class="fa fa-close fa-fw"></i>
            </button>
            <button type="button" class="button normal hover-shadow">
                <i class="fa fa-edit fa-fw"></i>
            </button>
            <button type="button" class="button dark hover-shadow">
                <i class="fa fa-check fa-fw"></i>
            </button>
        </div>
    </div>
    <?php
            echo "<input type='hidden' name=\"files[$i][materia]\" value='{$file->getMateria()->getCodigo()}'>".
                "<input type='hidden' name=\"files[$i][professor]\" value='{$file->getProfessor()}'>".
                "<input type='hidden' name=\"files[$i][ano]\" value='{$file->getAno()}'>".
                "<input type='hidden' name=\"files[$i][semestre]\" value='{$file->getSemestre()}'>".
                "<input type='hidden' name=\"files[$i][provaTrabalho]\" value='{$file->getProvaTrabalho()}'>".
                "<input type='hidden' name=\"files[$i][numero]\" value='{$file->getNumero()}'>".
                "<input type='hidden' name=\"files[$i][substitutiva]\" value='{$file->isSubstitutiva()}'>".
                "<input type='hidden' name=\"files[$i][arquivo]\" value='{$file->getArquivo()}'> ".
                "<input type='hidden' name=\"files[$i][nome]\" value='{$file->getNome()}'>".
                "<input type='hidden' name=\"files[$i][imagem]\" value='{$file->isImagem()}'> ";
            $i++;
        
        }
    }
    ?>
</section>