<?php
namespace Materia\Model\Dao;
use \FrameworkMvc\Dao\GenericDao;

class MateriaDao extends GenericDao{
    protected function getNomeTabela(){
        return 'tbMateria';
    }
    
    protected function getModelClassName(){
        return 'Materia\Model\Materia';
    }
    
    protected function getAutoIncrementedColumns(){
        return array();
    }
}
?>