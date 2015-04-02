<?php
namespace ProvaTrabalho\Model\Dao;
use \FrameworkMvc\Dao\GenericDao;

class ProvaTrabalhoDao extends GenericDao{
    protected function getNomeTabela(){
        return 'tbProvaTrabalho';
    }
    
    protected function getModelClassName(){
        return 'ContaBancaria\Model\ContaBancaria';
    }
    
    protected function getAutoIncrementedColumns(){
        return array('id');
    }
}
?>