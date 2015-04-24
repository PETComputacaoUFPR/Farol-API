<?php
namespace Materia\Controller;

use \FrameworkMvc\Mvc\Controller;
use \FrameworkMvc\Hydrator\Hydrator;
use \FrameworkMvc\Dao\Conexao;
use Materia\Model\Materia;
use Materia\Model\Dao\MateriaDao;

class MateriaController extends Controller{
    private $viewData;
    private $hydrator;
    private $materiaDao;
    
    function __construct(){
        parent::__construct();
        $this->hydrator = new Hydrator();
        $this->materiaDao = new MateriaDao(Conexao::getInstance());
    }
    
    public function indexAction(){
        
        $materias = $this->materiaDao->recuperar();
        if($materias > 0){
            $this->viewData['materias'] = $materias;
        }
        return $this->viewData;
    }
    
    public function createAction(){
        $materia = new Materia();
        $this->viewData = array();
        
        if(!empty($_POST)){
            $materia = $this->hydrator->hydrate($_POST, $materia);
            $result = $this->materiaDao->inserir($materia);
            
            if($result > 0){
                $this->viewData['success'] = true;
                header('location:?module=materia&controller=materia&action=index&success=create');
            }else{
                $this->viewData['success'] = false;
                header("location:?module=materia&controller=materia&action=index&success=notcreate");
            }
        }
        
        $this->viewData['materia'] = $materia;
        return $this->viewData;
    }
    
    public function updateAction(){
        $this->viewData = array();
        $materia;
        
        if(isset($_GET['codigo'])){
            $codigo = $_GET['codigo'];
            $result = $this->materiaDao->recuperar(array("codigo" => $codigo));
            if(!empty($result)){
                $materia = $result[0];
                if(!empty($_POST)){
                    $materia = $this->hydrator->hydrate($_POST, $materia);
                    $result = $this->materiaDao->atualizar($materia, array("codigo" => $codigo));
                    if(result > 0){
                        $this->viewData['success'] = true;
                        header("location:?module=materia&controller=materia&action=index&success=update");
                    }else{
                        $this->viewData['success'] = false;
                        header("location:?module=materia&controller=materia&action=index&success=notupdate");
                    }
                }
            }
        }
        
        $this->viewData['materia'] = $materia;
        return $this->viewData;
    }
    
    public function deleteAction(){
        $this->viewData = array();
        if(isset($_GET['codigo'])){
            $result = $this->materiaDao->deletar(array("codigo"=>$_GET['codigo']));
            if ($result > 0){
                header("location:?module=materia&controller=materia&action=index&success=delete");
            }
            else{
                header("location:?module=materia&controller=materia&action=index&success=notdelete");
            }
        }
    }
}
?>