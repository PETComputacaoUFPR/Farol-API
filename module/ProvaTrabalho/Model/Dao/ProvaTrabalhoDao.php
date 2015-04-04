<?php
namespace ProvaTrabalho\Model\Dao;
use \FrameworkMvc\Dao\GenericDao;
use Materia\Model\Dao\MateriaDao;

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

    protected function getAttributesPersist($provaTrab){
        $data = parent::getAttributesPersist($provaTrab);
        unset($data['materia']);

        if(is_object($provaTrab->getMateria())){
            $data['tbMateria_codigo'] = $provaTrab->getMateria()->getCodigo();
        }else{
            $data['tbMateria_codigo'] = $provaTrab->getMateria();
        }
        return $data;
    }

    public function recuperar(array $where = array()){
        $result = parent::recuperar($where);
        foreach($result as $key => $provaTrab){
            if(!empty($provaTrab->getMateria())){
                $materiaDao = new MateriaDao($this->conexao);
                $materia = $materiaDao->recuperar(array('id' => $provaTrab->getMateria()));
                $result[$key]->setMateria($materia[0]);
            }
        }
        return $result;
    }

    protected function makeDatabaseAttrClassAttr(array $value){
		$materia = $value['tbMateria_codigo'];
		$value['materia'] = $materia;
		unset($value['tbMateria_codigo']);

        return $value;
    }
}
?>