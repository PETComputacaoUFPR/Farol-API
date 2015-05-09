<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ProvaTrabalhoController extends Controller{
    private $uploadDir = "../uploads/";
    
    public function search($provaTrabalho="", $materia="", $professor="", 
                            $ano=0, $semestre=0){
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
        
        echo json_encode($data);
    }
    
    public function upload(){
        $response = new Response();
        $response->setContentType("application/json", "UTF-8");
        
        if($this->request->hasFiles()){
            $files = array();
            foreach($this->request->getUploadedFiles() as $file){
                if($file->getError() == 0){
                    $err = $this->saveFile($file);
                    $files[] = array(
                        "name"              => $file->getName(),
                        "error"             => (count($err) > 0),
                        "error-messages"    => $err
                    );
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
                }
            }
        }
        return $errors;
    }
    
}

?>