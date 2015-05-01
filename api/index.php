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

$app->mount($professores);

//-------------------404------------------------------------
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo "<h3>Page not found</h3>";
});

$app->handle();
?>