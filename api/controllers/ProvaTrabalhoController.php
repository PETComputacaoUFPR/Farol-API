<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ProvaTrabalhoController extends Controller{
    private $uploadDir = "../uploads/";
    
    public function retrieveById($id){
        $arquivo = ProvaTrabalho::findFirstById($id);
        $response = new Response();
        
        if(!$arquivo){
            $response->setContentType("application/json", "UTF-8")
                     ->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status" => "NOT-FOUND"));
            return $response;
        }
        
        //TODO: colocar o content type correto
        $response->setContentType("image/jpeg");
        $url = "http://". $_SERVER['SERVER_NAME'].$_SERVER['SERVER_URI'];
        $response->setContent(file_get_contents($url.$arquivo->getArquivo()));
        return $response;
    }
    
    public function search($provaTrabalho="", $materia="", $professor="", 
                            $ano=0, $semestre=0){
        $provaTrabalho = trim($provaTrabalho);
        $materia = trim($materia);
        $professor = trim($professor);
        $ano = ($ano == " " ? 0 : $ano);
        $conditions = ($provaTrabalho ? "provaTrabalho = :provaTrabalho: AND " : "")
                     .($materia ? "tbMateria_codigo LIKE :materia: AND " : "1 = 1 AND ")
                     .($professor ? "tbProfessor_codigo LIKE :professor? AND " : "1=1 AND ")
                     .($ano ? "ano = :ano: AND " : "1=1 AND ")
                     .($semestre ? "semestre = :semestre:" : "1=1");
        $bind = array();
        $provaTrabalho  ? $bind["provaTrabalho"] = $provaTrabalho : "";
        $materia        ? $bind["materia"] = "%".$materia."%" : "";
        $professor      ? $bind["professor"] = "%".$professor."%" : "";
        $ano            ? $bind["ano"] = $ano : "";
        $semestre       ? $bind["semestre"] = $semestre : "";
        
        //provas e trabalhos
        $prsTrs = ProvaTrabalho::find(array(
            "conditions"    => $conditions,
            "bind"          => $bind
        ));
        
        $data = array();
        
        foreach($prsTrs as $prTr){
            $data[] = array(
                "id"            => $prTr->getId(),
                "provaTrabalho" => $prTr->getProvaTrabalho(),
                "numero"        => $prTr->getNumero(),
                "substitutiva"  => $prTr->isSubstitutiva(),
                "ano"           => $prTr->getAno(),
                "semestre"      => $prTr->getSemestre(),
                "arquivo"       => $prTr->getArquivo()
            );
        }
        
        $response = new Response();
        $response->setContent(json_encode($data, JSON_PRETTY_PRINT))
                 ->setContentType("application/json", "UTF-8");
        return $response;
    }
    
    public function upload(){
        $response = new Response();
        $response->setContentType("application/json", "UTF-8");
        
        if($this->request->hasFiles()){
            $files = array();
            foreach($this->request->getUploadedFiles() as $file){
                if($file->getError() == 0){
                    $files[] = $this->saveFile($file);
                }
            }
            $data = array("status" => "OK", "files" => $files);
            $response->setContent(json_encode($data, JSON_PRETTY_PRINT));
            return $response;
        }
        
        return $response->setStatusCode(404, "Not Found")
                        ->setJsonContent(array("status" => "NO-FILES"));
    }
    
    private function saveFile($file){
        //echo $file->getName()." ".$file->getSize()."\n";
        $nome = $file->getName();
        $extensao = strrchr($nome, '.');
        $extensao = strtolower($extensao);
        $errors = array();
        $id = -1;
        
        if(strstr('.jpg;.jpeg;.gif;.png', $extensao)){
            $novoNome = md5(microtime()).$extensao;
            $destino = $this->uploadDir.$novoNome;
            if($file->moveTo($destino)){
                $arquivo = new ProvaTrabalho();
                $arquivo->setNome($nome);
                $arquivo->setArquivo("/uploads/".$novoNome);
                $arquivo->setImagem($extensao != ".pdf");
                $arquivo->setStatus(ProvaTrabalho::PENDENTE);
                if(!$arquivo->save()){
                    foreach($arquivo->getMessages() as $message){
                        $errors[] = $message->getMessage();
                    }
                }else{
                    $id = $arquivo->getId();
                }
            }else{
                $errors[] = "Não foi possível mover o arquivo para o destino";
            }
        }else{
            $errors[] = "O arquivo não é suportado no momento";
        }
        
        return array(
            "name"      => $nome,
            "id"        => $id,
            "error"     => (count($errors) > 0),
            "errors"    => $errors
        );
    }
    
}

?>