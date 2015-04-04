<header>
    <h1><i class="fa fa-anchor"></i> Farol</h1>
</header>
<?php
if(isset($this->data['erros'])){
    foreach($this->data['erros'] as $file){
        echo "<span> Erro ao inserir arquivo ".$file->getNome()."</span><br/>";
    }
}
?>