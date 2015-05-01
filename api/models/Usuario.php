<?php
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Usuario extends Model{
    private $id;
    private $email;
    private $senha;
    private $admin;
    private $moderador;
    
    public function getSource(){
        return "tbUsuario";
    }
    
    public function initialize(){
        $this->setSource("tbUsuario");
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function setEmail($email){
        $this->email = $email;
        return $this;
    }
    
    public function getSenha(){
        return $this->senha;
    }
    
    public function setSenha($senha){
        $this->senha = $senha;
        return $this;
    }
    
    public function isAdmin(){
        return $this->admin;
    }
    
    public function setAdmin($admin){
        $this->admin = $admin;
        return $this;
    }
    
    public function isModerador(){
        return $this->moderador;
    }
    
    public function setModerador($moderador){
        $this->moderador = $moderador;
        return $this;
    }
    
}
?>