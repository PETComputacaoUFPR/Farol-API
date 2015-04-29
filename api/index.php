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

$app->get('/v1/materias/{codigo:[a-z][a-z][0-9]+}', function($codigo) use ($app){
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




$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo 'This is crazy, but this page was not found!';
});

$app->handle();
?>