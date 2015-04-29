<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class Materia extends Model{
    private $codigo;
    private $nome;
    
    public function getSource(){
        return "tbMateria";
    }
    
    public function initialize(){
        $this->setSource("tbMateria");
    }
    
    public function validation(){
        $this->validate(new Uniqueness(
            array(
                "field" => "codigo",
                "message" => "O código da matéria deve ser único"
                )));
        
                
        if($this->validationHasFailed() == true){
            return false;
        }
    }
    
    public function getCodigo(){
        return $this->codigo;
    }
    
    public function setCodigo($codigo){
        $this->codigo = $codigo;
        return $this;
    }
    
    public function getNome(){
        return $this->nome;
    }
    
    public function setNome($nome){
        $this->nome = $nome;
        return $this;
    }
}
?>