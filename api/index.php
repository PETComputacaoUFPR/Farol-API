<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;

$loader = new Loader();

$loader->registerDirs(array(
    __DIR__.'/models/'
))->register();

$di = new FactoryDefault();

$di->set('db', function(){
   return new PdoMysql(array(
        "host"      => getenv("IP"),
        "username"  => getenv("C9_USER"),
        "password"  => "",
        "dbname"    => "farolDB",
        "charset"   => "utf8"
    )); 
});

$app = new Micro($di);

//-------------------Matérias----------------------------
$app->get('/v1/materias', function(){
    $data = array();
    foreach(Materia::find() as $materia){
        $data[] = array(
            'codigo'    => $materia->getCodigo(),
            'nome'      => $materia->getNome()
        );
    }
    echo json_encode($data);
});

$app->get('/v1/materias/{codigo:[a-zA-Z][a-zA-Z][0-9]+}', function($codigo){
    $materia = Materia::findFirstByCodigo($codigo);
    $response = new Response();
    
    if(!$materia){
        $response->setJsonContent(array("status" => "NOT-FOUND"));
    }else{
        $response->setJsonContent(array(
            "status" => "FOUND",
            "data" => array(
                "codigo" => $materia->getCodigo(),
                "nome" => $materia->getNome()
            )
        ));
    }
    
    return $response;
});

$app->post('/v1/materias', function() use ($app){
    $jsonObject = $app->request->getJsonRawBody();
    $materia = new Materia();
    $materia->setCodigo($jsonObject->codigo);
    $materia->setNome($jsonObject->nome);
    
    $response = new Response();
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
});

$app->put('/v1/materias/{codigo:[a-zA-Z][a-zA-Z][0-9]+}', function($codigo) use ($app){
    $jsonObject = $app->request->getJsonRawBody();
    $materia = Materia::findFirstByCodigo($codigo);
    $response = new Response();
    
    if(!$materia){
        $response->setJsonContent(array("status"=>"NOT-FOUND"));
        return $response;
    }
    $materia->setNome($jsonObject->nome);
    
    if($materia->update()){
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
});

$app->delete('/v1/materias/{codigo:[a-zA-Z][a-zA-Z][0-9]+}', function($codigo) use ($app){
    $materia = Materia::findFirstByCodigo($codigo);
    $response = new Response();
    
    if(!$materia){
        $response->setJsonContent(array("status" => "NOT-FOUND"));
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
});

//-------------------Professores----------------------------
$app->get('/v1/professores', function(){
    $data = array();
    foreach(Professor::find() as $professor){
        $data[] = array(
            "id" => $professor->getId(),
            "nome" => $professor->getNome()
        );
    }
    
    echo json_encode($data, JSON_PRETTY_PRINT);
});

$app->get('/v1/professores/{id:[0-9]+}', function($id){
    $professor = Professor::findFirst($id);
    
    $response = new Response();
    if(!$professor){
        $response->setJsonContent(array("status"=>"NOT-FOUND"));
    }else{
        $response->setJsonContent(array(
            "status"=>"FOUND",
            "data" => array(
                "id" => $professor->getId(),
                "nome" => $professor->getNome()
            )
        ));
    }
    
    return $response;
});

$app->post('/v1/professores', function() use ($app){
    $jsonObject = $app->request->getJsonRawBody();
    $professor = new Professor();
    $professor->setNome($jsonObject->nome);

    $response = new Response();
    if($professor->save()){
        $response->setStatusCode(201, "Created");
        $response->setJsonContent(array("status" => "OK", "data" => $jsonObject));
    }else{
        $response->setStatusCode(409, "Conflict");
        $erros = array();
        foreach ($professor->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }
        $response->setJsonContent(array("status" => "ERROR", "messages" => $errors));
    }
    return $response;
});

$app->put('/v1/professores/{id:[0-9]+}', function($id) use ($app){
    $jsonObject = $app->request->getJsonRawBody();
    $professor = Professor::findFirst($id);
    $professor->setNome($jsonObject->nome);
    
    $response = new Response();
    
    if(!$professor){
        $response->setJsonContent(array("status" => "NOT-FOUND"));
        return $response;
    }
    
    if($professor->update()){
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
});

$app->delete('/v1/professores/{id:[0-9]+}', function($id){
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
});

//Caso venha uma url inválida
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo "<h3>Page not found</h3>";
});

$app->handle();
?>