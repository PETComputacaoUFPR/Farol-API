<?php
namespace Moderacao\Controller;

use \FrameworkMvc\Mvc\Controller;
use \FrameworkMvc\Dao\Conexao;
use ProvaTrabalho\Model\ProvaTrabalho;
use ProvaTrabalho\Model\Dao\ProvaTrabalhoDao;

class ModeracaoController extends Controller{
    private $viewData;
    
    public function indexAction(){
        return array();
    }
    
    public function moderacaoAction(){
        $provaTrabDao = new ProvaTrabalhoDao(Conexao::getInstance());
        $result = $provaTrabDao->recuperar(array('status' => 0));
        if($result > 0){
            $this->viewData['pendentes'] = $result;
        }
        return $this->viewData;
    }
}
?>