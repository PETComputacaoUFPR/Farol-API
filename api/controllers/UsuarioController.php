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
        $usuario->setAdmin(false);
        $usuario->setModerador(false);

        $response = new Response();
        $response->setHeader("Content-type", "application/json");
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
        $response->setHeader("Content-type", "application/json");

        if(!$usuario){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status"=>"NOT-FOUND"));
            return $response;
        }
        $usuario->setNome($jsonObject->nome);
        $usuario->setEmail($jsonObject->email);
        if($jsonObject->senha) {
            $usuario->setSenha($jsonObject->senha);
        }
        $usuario->setAdmin($jsonObject->admin);
        $usuario->setModerador($jsonObject->moderador);

        if($usuario->update()){
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
        $response = new Response();
        $response->setContent(json_encode($data, JSON_PRETTY_PRINT))
                 ->setContentType("application/json", "UTF-8");
        return $response;
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
        $response = new Response();
        $response->setContent(json_encode($data, JSON_PRETTY_PRINT))
                 ->setContentType("application/json", "UTF-8");
        return $response;
    }

    public function retrieveById($id){
        $usuario = Usuario::findFirst($id);
        $response = new Response();
        $response->setHeader("Content-type", "application/json");

        if(!$usuario){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status" => "NOT-FOUND"));
            return $response;
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
        $response->setHeader("Content-type", "application/json");

        if(!$usuario){
            $response->setStatusCode(404, "Not Found")
                     ->setJsonContent(array("status" => "NOT-FOUND"));
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

    public static function userExists($email, $password) {
        $usuario = Usuario::findFirstByEmail($email);
        if($usuario && $usuario->getSenha() == md5($password)) {
            return $usuario;
        }
        return null;
    }

    public function login() {
        $jsonObject = $this->app->request->getJsonRawBody();
        $usuario = UsuarioController::userExists($jsonObject->email, $jsonObject->password);
        $response = new Response();
        $response->setHeader("Content-type", "application/json");
        if($usuario) {
            $response->setJsonContent(array("status" => "OK"));
        } else {
            $response->setStatusCode(409, "Conflict");
            $response->setJsonContent(array("status" => "INVALID"));
        }
        return $response;
    }

}

?>
