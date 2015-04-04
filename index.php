<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Farol - Repositório de provas e trabalhos</title>
        <link rel="stylesheet" href="/bower_components/components-font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/public/css/style.min.css">
        <script src="/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="/vex-2.2.1/js/vex.combined.min.js"></script>
    	<script>vex.defaultOptions.className = 'vex-theme-plain';</script>
    	<link rel="stylesheet" href="/vex-2.2.1/css/vex.css" />
    	<link rel="stylesheet" href="/vex-2.2.1/css/vex-theme-plain.css" />
    	<link rel="shortcut icon" href="/public/imgs/favicon-anchor.ico" type="image/x-icon" />
    </head>
    <body>
        <?php
            if($_GET == array()){
                header("location:?module=application&controller=home&action=index");
            }
	        ini_set('display_errors', 1);
	        error_reporting(E_ALL);
            require 'vendor/autoload.php';

            $loader = new \FrameworkMvc\Loader\SplClassLoader(null, 'module');
            spl_autoload_register(array($loader, 'loadClass'));
            $config = require 'config/config.php' ;

            \FrameworkMvc\Dao\Conexao::criarConexao($config['db']);

            $mvc = \FrameworkMvc\Mvc\Mvc::getInstace();
            $mvc->run();
       ?>
    </body>
</html>