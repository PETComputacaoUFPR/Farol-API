<?php
namespace ProvaTrabalho\Model\Dao;
use \FrameworkMvc\Dao\GenericDao;

class ProvaTrabalhoDao extends GenericDao{
    protected function getNomeTabela(){
        return 'tbProvaTrabalho';
    }
    
    protected function getModelClassName(){
        return 'ProvaTrabalho\Model\ProvaTrabalho';
    }
    
    protected function getAutoIncrementedColumns(){
        return array('id');
    }
}
?>