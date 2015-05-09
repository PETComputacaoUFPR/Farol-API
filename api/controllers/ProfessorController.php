<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ProfessorController extends Controller{
    
    public function create(){
        $jsonObject = $this->app->request->getJsonRawBody();
        $professor = new Professor();
        $professor->setNome($jsonObject->nome);
        
        $response = new Response();
        $response->setHeader("Content-type", "application/json");
        if($professor->save()){
            $response->setStatusCode(201, "Created");
            $response->setJsonContent(array("status" => "OK", "data" => $jsonObject));
        }else{
            $response->setStatusCode(409, "Conflict");
            $erros = array();
            foreach($professor->getMessages() as $message){
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(array("status" => "ERROR", "messages" => $errors));
        }
        
        return $response;
    }
    
    public function update($id){
        $jsonObject = $this->app->request->getJsonRawBody();
        $professor = Professor::findFirst($id);
        $response = new Response();
        $response->setHeader("Content-type", "application/json");
        
        if(!$professor){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status"=>"NOT-FOUND"));
            return $response;
        }
        $professor->setNome($jsonObject->nome);
        
        if($professor->update()){
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
    
    public function retrieveAll(){
        $data = array();
        foreach(Professor::find() as $professor){
            $data[] = array(
                'id'    => $professor->getId(),
                'nome'  => $professor->getNome()
            );
        }
        $response = new Response();
        $response->setContent(json_encode($data, JSON_PRETTY_PRINT))
                 ->setContentType("application/json", "UTF-8");
        return $response;
    }
    
    public function retrieveById($id){
        $professor = Professor::findFirst($id);
        $response = new Response();
        $response->setHeader("Content-type", "application/json");
        
        if(!$professor){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status" => "NOT-FOUND"));
            return $response;
        }else{
            $response->setJsonContent(array(
                "status" => "FOUND",
                "data" => array(
                    "id"    => $professor->getId(),
                    "nome"  => $professor->getNome()
                )
            ));
        }
        
        return $response;
    }
    
    public function delete($id){
        $professor = Professor::findFirst($id);
        $response = new Response();
        $response->setHeader("Content-type", "application/json");
        
        if(!$professor){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status" => "NOT-FOUND"));
            return $response;
        }
        
        if($professor->delete()){
            $response->setJsonContent(array("status" => "OK"));
        }else{
            $response->setStatusCode(409, "Conflict");
            $errors = array();
            foreach ($professor->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
        }
        return $response;
    }
    
    public function searchByNome($nome){
        $data = array();
        $nome = trim($nome);
        $professores = Professor::find(array(
            "conditions"    => "nome like ?1",
            "bind"          => array(1 => "%".$nome."%")
        ));
        foreach($professores as $professor){
            $data[] = array(
                "id"    => $professor->getId(),
                "nome"  => $professor->getNome()
            );
        }
        
        $response = new Response();
        $response->setContent(json_encode($data, JSON_PRETTY_PRINT))
                 ->setContentType("application/json", "UTF-8");
        return $response;
    }
    
}

?>