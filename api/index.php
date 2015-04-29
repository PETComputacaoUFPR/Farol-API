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

/*
* Tabela de urls
* Todas as urls são precedidas pela versão da API
* MÉTODO    | URL                   | AÇÃO
* -------------------------------------------------------
* get       | /materias             | Retorna todas as matérias
* get       | /materias/id          | Retorna matéria com id
* post      | /materias             | Cadastra uma nova matéria
* put       | /materias/id          | Atualiza a matéria com id
* delete    | /materias/id          | Deleta matéria com id
* -------------------------------------------------------
* get       | /professores          | Retorna todos os professores
* get       | /professores/id       | Retorna professor com id
* post      | /professores          | Cadastra novo professor
* put       | /professores/id       | Atualiza professor com id
* delete    | /professores/id       | Deleta professor com id
* -------------------------------------------------------
* get       | /users                | Retorna todos os usuários
* get       | /users/id             | Retorna usuário com id
* post      | /users                | Cadastra novo usuário
* put       | /users/id             | Atualiza usuário com id
* delete    | /users/id             | Deleta usuário com id
* -------------------------------------------------------
* get       | /provas/search/materia            | Retorna todas as provas da matéria
* get       | /provas/search/materia/ano        | Retorna todas as provas da matéria do ano
* get       | /provas/search/professor          | Retorna todas as provas do professor
* get       | /provas/search/professor/ano      | Retorna todas as provas do professor do ano
* get       | /provas/search/materia/professor  | Retorna todas as provas da matéria com professor
* get       | /provas/search/materia/professor/ano | Retorna todas as provas da matéria com professor e ano
*/


//Matéria
$app->get('/v1/materias', function() use ($app){
    $mat = new Materia();
    $phql = "SELECT * FROM Materia";
    $materias = $app->modelsManager->executeQuery($phql);
    
    $data = array();
    foreach($materias as $materia){
        $data[] = array(
            'codigo'    => $materia->getCodigo(),
            'nome'      => $materia->getNome()
        );
    }
    
    echo json_encode($data, JSON_PRETTY_PRINT, 5);
});

$app->get('/v1/materias/{codigo:[a-zA-Z][a-zA-Z][0-9]+}', function($codigo) use ($app){
    $phql = "SELECT * FROM Materia WHERE codigo = :codigo:";
    $materia = $app->modelsManager->executeQuery($phql, array(
        "codigo" => $codigo
    ))->getFirst();
    
    $response = new Response();
    
    if($materia == false){
        $response->setJsonContent(array("status"=>"NOT-FOUND"));
    }else{
        $response->setJsonContent(array(
            "status" => "FOUND",
            "data" => array(
                "codigo" => $materia->getCodigo(),
                "nome"   => $materia->getNome()
            )
        ));
    }
    
    return $response;
});

$app->post('/v1/materias', function() use ($app){
    $materia = $app->request->getJsonRawBody();
    $phql = "INSERT INTO Materia VALUES (:codigo:, :nome:)";
    
    $status = $app->modelsManager->executeQuery($phql, array(
        "codigo" => $materia->codigo,
        "nome" => $materia->nome
    ));
    
    $response = new Response();
    if($status->success() == true){
        $response->setStatusCode(201, "Created");
        $response->setJsonContent(array("status" => "OK", "data" => $materia));
    }else{
        $response->setStatusCode(409, "Conflict");
        $erros = array();
        foreach($status->getMessages() as $message){
            $errors[] = $message->getMessage();
        }
        $response->setJsonContent(array("status" => "ERROR", "messages" => $errors));
    }
    
    return $response;
});

$app->put('/v1/materias/{codigo:[a-zA-Z][a-zA-Z][0-9]+}', function($codigo) use ($app){
    $materia = $app->request->getJsonRawBody();
    
    $phql = "UPDATE Materia SET nome = :nome: WHERE codigo = :codigo:";
    $status = $app->modelsManager->executeQuery($phql, array(
        "codigo"     => $codigo,
        "nome"   => $materia->nome
    ));
    
    $response = new Response();
    
    if($status->success() == true){
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
    $phql = "DELETE FROM Materia WHERE codigo = :codigo:";
    $status = $app->modelsManager->executeQuery($phql, array(
        "codigo" => $codigo
    ));
    
    $response = new Response();
    if($status->success() == true){
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

//Caso venha uma url inválida
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo "<h3>Page not found</h3>";
});

$app->handle();
?>