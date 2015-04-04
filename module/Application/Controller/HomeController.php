<?php
namespace Application\Controller;

use FrameworkMvc\Mvc\Controller;
use FrameworkMvc\Hydrator\Hydrator;
use FrameworkMvc\Dao\Conexao;
use ProvaTrabalho\Model\ProvaTrabalho;
use ProvaTrabalho\Model\Dao\ProvaTrabalhoDao;

class HomeController extends Controller{
    private $viewData;
    private $uploadDir = "uploads/";
    
    public function indexAction(){
        return array();
    }
    
    public function searchAction(){
        $this->viewData = array();
        $search = "";
        if(isset($_POST['search'])){
            $search = $_POST['search'];
        }
        $this->viewData['search'] = $search;
        return $this->viewData;
    }
    
    public function uploadAction(){
        $this->viewData = array();
        //Array que guarda todas os arquivos enviados com sucesso
        $uploadedFiles = array();
        if(!empty($_POST)){
            //Reorganiza o array de arquivos
            $files = $this->reorganizeArrayFiles($_FILES['file']);

            foreach($files as $file){
                if($file['error'] == 0){
                    $tmpFile = $file['tmp_name'];
                    $nome = $file['name'];
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
                        $destino = $this->uploadDir.$novoNome;
                        // tenta mover o arquivo para o destino
                        if(move_uploaded_file($tmpFile, $destino))
                        {
                            //echo "<img src=\"" . $destino . "\" />";
                            $arquivo = new ProvaTrabalho();
                            $arquivo->setArquivo($destino);
                            $arquivo->setImagem(($extensao != ".pdf"));
                            $arquivo->setNome($nome);
                            $uploadedFiles[] = $arquivo;
                        }
                        else{
                            echo "<pre> ${print_r($file)} </pre>";
                            echo "<h1>Erro ao salvar o arquivo</h1>";
                        }
                    }
                }
            }
            $this->viewData['files'] = $uploadedFiles;
        }
        return $this->viewData;
    }
    
    public function saveAction(){
        $hydrator = new Hydrator();
        $this->viewData = array();
        
        if(isset($_POST['files'])){
            $this->viewData['files'] = $_POST['files'];
            //Array com arquivos que não foram inseridos
            $erroInserir = array();
            foreach($_POST['files'] as $file){
                $provaTrabalho = new ProvaTrabalho();
                $provaTrabalho = $hydrator->hydrate($file, $provaTrabalho);
                //Status 0 = pendente
                $provaTrabalho->setStatus(0);
                $provaTrabalhoDao = new ProvaTrabalhoDao(Conexao::getInstance());
                try{
                    $result = $provaTrabalhoDao->inserir($provaTrabalho);
                }catch(\Exception $ex){
                    //Se deu erro ao inserir, deletamos e
                    // mostramos ao usuário quais arquivos não foram salvos
                    $erroInserir[] = $provaTrabalho;
                }
            }
            
            if(count($erroInserir) > 0){
                foreach($erroInserir as $file){
                unlink($file->getArquivo());
                }
                $this->viewData['erros'] = $erroInserir;
            }
        }
        
        return $this->viewData;
    }
    
    /**
     * Reorganiza o array de arquivos para um padrão mais claro
     * 
     * A configuração final do array fica:
     * Array
     * (
     *      [0] => Array
     *      (
     *          [name] => arquivo.txt
     *          [type] => text/plain
     *          [tmp_name] => /tmp/as313as
     *      )
     *      [1] => Array
     *      (
     *          [name] => foto.png
     *          [type] => image/png
     *          [tmp_name] => /tmp/qwe41as
     *      )
     * )
     * @param array $file_post Array de arquivos
     * @return array Array ordenado de forma mais clara
     */
    private function reorganizeArrayFiles(&$file_post) {
        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);
    
        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }
    
        return $file_ary;
    }
}
?>