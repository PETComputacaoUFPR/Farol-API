<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Materia extends Model{
    private $codigo;
    private $nome;
    
    public function getSource(){
        return "tbMateria";
    }
    
    public function initialize(){
        $this->setSource("tbMateria");
        $this->hasMany("codigo", "ProvaTrabalho", "tbMateria_codigo");
    }
    
    public function validation(){
        $this->validate(new Uniqueness(
            array(
                "field"     => "codigo",
                "message"   => "O código da matéria deve ser único"
            ))
        );
        
        if(!preg_match("/\b[a-zA-Z][a-zA-Z][0-9]+\b/", $this->codigo)){
            $this->appendMessage(new Message("O código da matéria é inválido"));
        }
                
        if($this->validationHasFailed()){
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