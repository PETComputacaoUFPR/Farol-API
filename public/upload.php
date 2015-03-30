<?php
//uploads podem ser pdfs ou imagens
$uploadDir = "../uploads/";

if(isset($_FILES['file']['name']) && $_FILES['file']['error'] == 0 && !empty($_POST)){
    echo "<pre>";
    print_r($_FILES['file']);
    print_r($_POST);
    echo "</pre>";
    
    //Arquivo no servidor
    $arquivo = $_FILES['file']['tmp_name'];
    //Nome original
    $nome = $_FILES['file']['name'];
    //Extensão do arquivo
    $extensao = strrchr($nome, '.');
    $extensao = strtolower($extensao);
    
    //Checa se é um arquivo com extensão válida (talvez seja melhor checar pelo type)
    if(strstr('.jpg;.jpeg;.gif;.png;.pdf', $extensao))
    {
        // Cria um nome único para esta imagem
        // Evita que duplique as imagens no servidor.
        $novoNome = md5(microtime()) . $extensao;
         
        // Concatena a pasta com o nome
        $destino = $uploadDir.$novoNome;
         
        // tenta mover o arquivo para o destino
        if(move_uploaded_file($arquivo, $destino))
        {
            echo "Arquivo salvo com sucesso em : <strong>" . $destino . "</strong><br/>";
            echo "<img src=\"" . $destino . "\" />";
            //Insere no banco com status pendente
        }
        else{
            echo "Erro ao salvar o arquivo.<br/>";
        }
    }
}
?>

