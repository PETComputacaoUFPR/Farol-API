<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class UsuarioController extends Controller{
    
    public function create(){
        $jsonObject = $this->app->request->getJsonRawBody();
        $usuario = new Usuario();
        $usuario->setNome($jsonObject->nome);
        $usuario->setEmail($jsonObject->email);
        $usuario->setSenha(md5($jsonObject->senha));
        $usuario->setAdmin($jsonObject->admin);
        $usuario->setModerador($jsonObject->moderador);
        
        $response = new Response();
        if($usuario->save()){
            $response->setStatusCode(201, "Created");
            $response->setJsonContent(array("status" => "OK", "data" => $jsonObject));
        }else{
            $response->setStatusCode(409, "Conflict");
            $erros = array();
            foreach($usuario->getMessages() as $message){
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(array("status" => "ERROR", "messages" => $errors));
        }
        
        return $response;
    }
    
    public function update($id){
        $jsonObject = $this->app->request->getJsonRawBody();
        $usuario = Usuario::findFirst($id);
        $response = new Response();
        
        if(!$usuario){
            $response->setJsonContent(array("status"=>"NOT-FOUND"));
            return $response;
        }
        $usuario->setNome($jsonObject->nome);
        $usuario->setEmail($jsonObject->email);
        $usuario->setSenha($jsonObject->senha);
        $usuario->setAdmin($jsonObject->admin);
        $usuario->setModerador($jsonObject->moderador);
        
        if($usuario->update()){
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
        foreach(Usuario::find() as $usuario){
            $data[] = array(
                'id'        => $usuario->getId(),
                'nome'     => $usuario->getNome(),
                'email'     => $usuario->getEmail(),
                'admin'     => $usuario->isAdmin(),
                'moderador' => $usuario->isModerador()
            );
        }
        echo json_encode($data);
    }
    
    public function retrieveAllByType($tipo = null){
        $data = array();
        $where = "";
        if($tipo !== "admin" && $tipo !== "moderador"){
            $where = "admin = 0 AND moderador = 0";
        }else{
            $where = $tipo." = 1";
        }
        foreach(Usuario::find($where) as $usuario){
            $data[] = array(
                'id'        => $usuario->getId(),
                'nome'     => $usuario->getNome(),
                'email'     => $usuario->getEmail(),
                'admin'     => $usuario->isAdmin(),
                'moderador' => $usuario->isModerador()
            );
        }
        echo json_encode($data);
    }
    
    public function retrieveById($id){
        $usuario = Usuario::findFirst($id);
        $response = new Response();
        
        if(!$usuario){
            $response->setJsonContent(array("status" => "NOT-FOUND"));
        }else{
            $response->setJsonContent(array(
                "status" => "FOUND",
                "data" => array(
                    'id'        => $usuario->getId(),
                    'nome'     => $usuario->getNome(),
                    'email'     => $usuario->getEmail(),
                    'admin'     => $usuario->isAdmin(),
                    'moderador' => $usuario->isModerador()
                )
            ));
        }
        
        return $response;
    }
    
    public function delete($id){
        $usuario = Usuario::findFirst($id);
        $response = new Response();
        
        if(!$usuario){
            $response->setJsonContent(array("status" => "NOT-FOUND"));
            return $response;
        }
        
        if($usuario->delete()){
            $response->setJsonContent(array("status" => "OK"));
        }else{
            $response->setStatusCode(409, "Conflict");
            $errors = array();
            foreach ($usuario->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
        }
        return $response;
    }
    
}

?>