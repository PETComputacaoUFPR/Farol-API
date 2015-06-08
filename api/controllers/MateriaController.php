<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class MateriaController extends Controller{
    
    public function create(){
        $jsonObject = $this->app->request->getJsonRawBody();
        $materia = new Materia();
        $materia->setCodigo($jsonObject->codigo);
        $materia->setNome($jsonObject->nome);
        
        $response = new Response();
        $response->setHeader("Content-type", "application/json");
        if($materia->save()){
            $response->setStatusCode(201, "Created");
            $response->setJsonContent(array("status" => "OK", "data" => $jsonObject));
        }else{
            $response->setStatusCode(409, "Conflict");
            $erros = array();
            foreach($materia->getMessages() as $message){
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(array("status" => "ERROR", "messages" => $errors));
        }
        
        return $response;
    }
    
    public function update($codigo){
        $jsonObject = $this->app->request->getJsonRawBody();
        $materia = Materia::findFirstByCodigo($codigo);
        $response = new Response();
        $response->setHeader("Content-type", "application/json");
        
        if(!$materia){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status"=>"NOT-FOUND"));
            return $response;
        }
        $materia->setNome($jsonObject->nome);
        
        if($materia->update()){
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
    
    public function retrieveAll(){
        $data = array();
        foreach(Materia::find() as $materia){
            $data[] = array(
                'codigo'    => $materia->getCodigo(),
                'nome'      => $materia->getNome()
            );
        }
        $response = new Response();
        $response->setContent(json_encode($data, JSON_PRETTY_PRINT))
                 ->setContentType("application/json", "UTF-8");
        return $response;
    }
    
    public function retrieveByCodigo($codigo){
        $materia = Materia::findFirstByCodigo($codigo);
        $response = new Response();
        $response->setHeader("Content-type", "application/json");
        
        if(!$materia){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status" => "NOT-FOUND"));
            return $response;
        }else{
            $response->setJsonContent(array(
                "status" => "FOUND",
                "data" => array(
                    "codigo"    => $materia->getCodigo(),
                    "nome"      => $materia->getNome()
                )
            ));
        }
        
        return $response;
    }
    
    public function delete($codigo){
        $materia = Materia::findFirstByCodigo($codigo);
        $response = new Response();
        $response->setHeader("Content-type", "application/json");
        
        if(!$materia){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status" => "NOT-FOUND"));
            return $response;
        }
        
        if($materia->delete()){
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
    
    public function search($nome="", $codigo=""){
        $nome = trim($nome);
        $codigo = trim($codigo);
        
        $conditions = ($nome ? "nome LIKE :nome: AND " : "")
                     .($codigo ? "codigo LIKE :codigo:" : "1 = 1");
        $bind = array();
        $nome ? $bind["nome"] = "%".$nome."%" : "";
        $codigo ? $bind["codigo"] = "%".$codigo."%" : "";
        
        $materias = Materia::find(array(
            "conditions"    => $conditions,
            "bind"          => $bind
        ));
        
        $data = array();
        foreach($materias as $materia){
            $data[] = array(
                "codigo"    => $materia->getCodigo(),
                "nome"  => $materia->getNome()
            );
        }
        
        $response = new Response();
        $response->setContent(json_encode($data, JSON_PRETTY_PRINT))
                 ->setContentType("application/json", "UTF-8");
        return $response;
    }
    
}

?>