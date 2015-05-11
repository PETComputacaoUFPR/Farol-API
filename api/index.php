<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;

$loader = new Loader();

$loader->registerDirs(array(
    __DIR__.'/models/',
    __DIR__.'/controllers/'
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
$di->set('app', $app, true);

//-------------------Matérias----------------------------
$materias = new MicroCollection();
$materias->setHandler("MateriaController")->setLazy(true);
//Adiciona o prefixo /v1/materias
$materias->setPrefix("/v1/materias");


$materias->post("/", "create");                                             //C
$materias->get("/", "retrieveAll");                                         //R
$materias->get("/{codigo:[a-zA-Z][a-zA-Z][0-9]+}", "retrieveByCodigo");
$materias->put("/{codigo:[a-zA-Z][a-zA-Z][0-9]+}", "update");               //U
$materias->delete("/{codigo:[a-zA-Z][a-zA-Z][0-9]+}", "delete");            //D
$materias->get("/search/{nome}[/]?{codigo}", "search");

$app->mount($materias);

//-------------------Professores----------------------------
$professores = new MicroCollection();
$professores->setHandler("ProfessorController")->setLazy(true);
//Adiciona o prefixo /v1/professores
$professores->setPrefix("/v1/professores");

$professores->post("/", "create");                          //C
$professores->get("/", "retrieveAll");                      //R
$professores->get("/{id:[0-9]+}", "retrieveById");
$professores->put("/{id:[0-9]+}", "update");                //U
$professores->delete("/{id:[0-9]+}", "delete");             //D
$professores->get("/search/{nome}", "searchByNome");

$app->mount($professores);

//-------------------Usuarios-------------------------------
$usuarios = new MicroCollection();
$usuarios->setHandler("UsuarioController")->setLazy(true);
//Adiciona o prefixo /v1/u
$usuarios->setPrefix("/v1/u");

$usuarios->post("/", "create");                             //C
$usuarios->get("/", "retrieveAll");                         //R
$usuarios->get("/users/{tipo:(admin|moderador|normal)}", "retrieveAllByType");
$usuarios->get("/{id:[0-9]+}", "retrieveById");
$usuarios->put("/{id:[0-9]+}", "update");                   //U
$usuarios->delete("/{id:[0-9]+}", "delete");                //D

$app->mount($usuarios);

//-------------------Arquivos------------------------------
$arquivos = new MicroCollection();
$arquivos->setHandler("ProvaTrabalhoController")->setLazy(true);
//Adiciona o prefixo /v1/arquivos
$arquivos->setPrefix("/v1/arquivos");

$arquivos->post("/", "upload");
$arquivos->get("/{id:[0-9]+}", "retrieveById");
$arquivos->put("/{id:[0-9]+}", "update");
$arquivos->delete("/{id:[0-9]+}", "delete");

$app->mount($arquivos);

//-------------------Busca---------------------------------
$busca = new MicroCollection();
$busca->setHandler("ProvaTrabalhoController")->setLazy(true);
//Adiciona o prefixo /v1/u
$busca->setPrefix("/v1/search");

$urlBusca = "/{provaTrabalho}"
            ."[/]?{materia}"
            ."[/]?{professor}"
            ."[/]?{ano}"
            ."[/]?{semestre}";

$busca->get($urlBusca, "search");

$app->mount($busca);

//-------------------404------------------------------------
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")
        ->setHeader("Content-type", "application/json")
        ->sendHeaders();
    echo json_encode(array("status" => "PAGE-NOT-FOUND"));
});

$app->handle();
?>