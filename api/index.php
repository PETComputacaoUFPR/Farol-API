<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use Phalcon\DI\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Http\Response;
use Phalcon\Events\Manager as EventsManager;

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

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

// Cabeçalhos necessário para requisições CORS
$app->before(function() use ($app) {
    $origin = $app->request->getHeader("ORIGIN") ? $app->request->getHeader("ORIGIN") : '*';

    $app->response->setHeader("Access-Control-Allow-Origin", $origin)
        ->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST,DELETE,OPTIONS')
        ->setHeader("Access-Control-Allow-Headers", 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
        ->setHeader("Access-Control-Allow-Credentials", true);
    $app->response->send();
});

$app->options('/{catch:(.*)}', function() use ($app) { 
    $response = new Response();
    $response->setStatusCode(200, "OK");
    return $response;
});

$eventManager = new EventsManager();

$eventManager->attach('micro', function($event, $app) {

    if ($event->getType() == 'beforeExecuteRoute') {
        /* Neste momento, uma rota válida foi encontrada. É necessário verificar se
        *  a rota necessita de autenticação e se os dados do usuário estão corretos,
        *  caso seja necessário
        */
        
        $usuario = null;
        $method = $app->__get('request')->getMethod();
        $route = $app->getRouter()->getRewriteUri();
        
        // Basic Auth
        if($app->request->getServer("PHP_AUTH_USER")) {
            $email = $app->request->getServer("PHP_AUTH_USER");
            $password = $app->request->getServer("PHP_AUTH_PW");
            $usuario = UsuarioController::userExists($email, $password);
        }
        
        // Todas as requisições OPTIONS, retornam true
        if($method == "OPTIONS") {
            return true;
        }
        
        // Requisições GET retornam true, exceto algumas rotas
        if($method == "GET") {
            if(startsWith($route, "/v1/arquivos/status")) {
                if(!$usuario || !$usuario->isModerador()) {
                    $app->response->setStatusCode(401, "UNAUTHORIZED")->send();
                    return false;
                }
            }
            
            if(startsWith($route, "/v1/u")) {
                if(!$usuario || !$usuario->isAdmin()) {
                    $app->response->setStatusCode(401, "UNAUTHORIZED")->send();
                    return false;
                }
            }
            
            return true;
        }
        
        // Requisições do tipo POST e PUT só podem ser feitas por moderadores, com exceções
        if($method == "POST" || $method == "PUT"){
            if(startsWith($route, "/v1/u/login")) {
                return true;
            }
            
            if(!$usuario) {
                $app->response->setStatusCode(401, "UNAUTHORIZED")->send();
                return false;
            }
            
            if(startsWith($route, "/v1/arquivos")) {
                $app->response->setStatusCode(401, "UNAUTHORIZED")->send();
                return false;
            }
            
            if(startsWith($route, "/v1/u")) {
                $id = $app->getRouter()->getMatches()[1];
                // Somente o próprio usuário ou um admin pode alterar um usuário
                if($id != $usuario->getId() && !$usuario->isAdmin() && $method == "PUT") {
                    $app->response->setStatusCode(401, "UNAUTHORIZED")->send();
                    return false;
                }
            }
            
            if(!$usuario->isModerador()) {
                $app->response->setStatusCode(401, "UNAUTHORIZED")->send();
                return false;
            }
            
            return true;
        }
        
        // Requisições do tipo DELETE só podem ser feitas por moderadores
        if($method == "DELETE" && $usuario->isAdmin()) {
            return true;
        }
        
        // Qualquer outra coisa e retornamos 401
        $app->response->setStatusCode(401, "UNAUTHORIZED")->send();
        return false;
    }

});

$app->setEventsManager($eventManager);

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
// "Login": retorna true se o usuário existe, false caso os dados estejam errados
$usuarios->post("/login", "login");

$app->mount($usuarios);

//-------------------Arquivos------------------------------
$arquivos = new MicroCollection();
$arquivos->setHandler("ProvaTrabalhoController")->setLazy(true);
//Adiciona o prefixo /v1/arquivos
$arquivos->setPrefix("/v1/arquivos");

$arquivos->post("/", "upload");
$arquivos->get("/{id:[0-9]+}", "retrieveById");
$arquivos->get("/status/{status:(pendente|aprovado)}", "retrieveByStatus");
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
    $origin = $app->request->getHeader("ORIGIN") ? $app->request->getHeader("ORIGIN") : '*';
    $app->response->setStatusCode(404, "Not Found")
        ->setContentType("application/json", "UTF-8")
        ->setHeader("Access-Control-Allow-Origin", $origin)
        ->setHeader("Access-Control-Allow-Methods", 'GET,PUT,POST,DELETE,OPTIONS')
        ->setHeader("Access-Control-Allow-Headers", 'Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type, Authorization')
        ->setHeader("Access-Control-Allow-Credentials", true)
        ->sendHeaders();
    echo json_encode(array("status" => "PAGE-NOT-FOUND"));
});

$app->handle();
?>