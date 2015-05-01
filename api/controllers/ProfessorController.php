<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ProfessorController extends Controller{
    
    public function create(){
        $jsonObject = $this->app->request->getJsonRawBody();
        $professor = new Professor();
        $professor->setNome($jsonObject->nome);
        
        $response = new Response();
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
        
        if(!$professor){
            $response->setJsonContent(array("status"=>"NOT-FOUND"));
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
                'nome'      => $professor->getNome()
            );
        }
        echo json_encode($data);
    }
    
    public function retrieveById($id){
        $professor = Professor::findFirst($id);
        $response = new Response();
        
        if(!$professor){
            $response->setJsonContent(array("status" => "NOT-FOUND"));
        }else{
            $response->setJsonContent(array(
                "status" => "FOUND",
                "data" => array(
                    "codigo" => $professor->getCodigo(),
                    "nome" => $professor->getNome()
                )
            ));
        }
        
        return $response;
    }
    
    public function delete($id){
        $professor = Professor::findFirst($id);
        $response = new Response();
        
        if(!$professor){
            $response->setJsonContent(array("status" => "NOT-FOUND"));
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
    
}

?>