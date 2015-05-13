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
    
    public function retrieveByStatus($status){
        $data = array();
        
        foreach(ProvaTrabalho::find("status = '$status'") as $arquivo){
            $materia = ($arquivo->getMateria()? $arquivo->getMateria() : null);
            $professor = ($arquivo->getProfessor()? $arquivo->getProfessor() : null);
            $usuario = ($arquivo->getUsuario()? $arquivo->getUsuario()->setSenha('') : null);
            $data[] = array(
                "id"            => $arquivo->getId(),
                "provaTrabalho" => $arquivo->getProvaTrabalho(),
                "numero"        => $arquivo->getNumero(),
                "substitutiva"  => $arquivo->isSubstitutiva(),
                "ano"           => $arquivo->getAno(),
                "semestre"      => $arquivo->getSemestre(),
                "arquivo"       => $arquivo->getArquivo(),
                "materia"       => $materia,
                "professor"     => $professor,
                "usuario"       => $usuario
            );
        }
        
        $response = new Response();
        $response->setContent(json_encode($data)
                 ->setContentType("application/json", "UTF-8");
        return $response;
    }
    
    public function update($id){
        $jsonObject = $this->app->request->getJsonRawBody();
        $arquivo = ProvaTrabalho::findFirstById($id);
        $response = new Response();
        $response->setContentType("application/json", "UTF-8");
        
        if(!$arquivo){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status" => "NOT-FOUND"));
        }
        
        $arquivo->setProvaTrabalho($jsonObject->provaTrabalho);
        $arquivo->setNumero($jsonObject->numero);
        $arquivo->setSubstitutiva($jsonObject->substitutiva);
        $arquivo->setAno($jsonObject->ano);
        $arquivo->setSemestre($jsonObject->semestre);
        $arquivo->setMateria($jsonObject->materia);
        $arquivo->setProfessor($jsonObject->professor);
        $arquivo->setUsuario($jsonObject->usuario);
        
        if($arquivo->update()){
            $response->setJsonContent(array("status" => "OK"));
        }else{
            $response->setStatusCode(409, "Conflict");
            $errors = array();
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
        }
        return $response;
    }
    
    public function delete($id){
        $arquivo = ProvaTrabalho::findFirst($id);
        $response = new Response();
        $response->setContentType("application/json", "UTF-8");
        
        if(!$arquivo){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status" => "NOT-FOUND"));
            return $response;
        }
        
        $filePath = $arquivo->getArquivo();
        
        if($arquivo->delete()){
            unlink("..".$filePath);
            $response->setJsonContent(array("status" => "OK"));
        }else{
            $response->setStatusCode(409, "Conflict");
            $errors = array();
            foreach ($materia->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
        }
        
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
                     .($semestre ? "semestre = :semestre: " : "1=1 ")
                     ."status = 'aprovado'";
        $bind = array();
        $provaTrabalho  ? $bind["provaTrabalho"] = $provaTrabalho : "";
        $materia        ? $bind["materia"] = "%".$materia."%" : "";
        $professor      ? $bind["professor"] = "%".$professor."%" : "";
        $ano            ? $bind["ano"] = $ano : "";
        $semestre       ? $bind["semestre"] = $semestre : "";
        
        //provas e trabalhos
        $arquivos = ProvaTrabalho::find(array(
            "conditions"    => $conditions,
            "bind"          => $bind
        ));
        
        $data = array();
        
        foreach($arquivos as $arquivo){
            $materia = ($arquivo->getMateria()? $arquivo->getMateria() : null);
            $professor = ($arquivo->getProfessor()? $arquivo->getProfessor() : null);
            $usuario = ($arquivo->getUsuario()? $arquivo->getUsuario()->setSenha('') : null);
            $data[] = array(
                "id"            => $arquivo->getId(),
                "provaTrabalho" => $arquivo->getProvaTrabalho(),
                "numero"        => $arquivo->getNumero(),
                "substitutiva"  => $arquivo->isSubstitutiva(),
                "ano"           => $arquivo->getAno(),
                "semestre"      => $arquivo->getSemestre(),
                "arquivo"       => $arquivo->getArquivo(),
                "materia"       => $materia,
                "professor"     => $professor,
                "usuario"       => $usuario
            );
        }
        
        $response = new Response();
        $response->setContent(json_encode($data)
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
            $response->setContent(json_encode($data));
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