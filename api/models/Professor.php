<?php
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Professor extends Model{
    private $id;
    private $nome;
    
    public function getSource(){
        return "tbProfessor";
    }
    
    public function initialize(){
        $this->setSource("tbProfessor");
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
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