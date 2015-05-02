<?php
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\StringLength;

class Usuario extends Model{
    private $id;
    private $nome;
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
    
    public function validation(){
        $this->validate(new Uniqueness(
            array(
                "field"     => "email",
                "message"   => "Este e-mail ja esta sendo utilizado por outra conta"
            )
        ));
        
        $this->validate(new EmailValidator(
            array(
                "field"     => "email",
                "message"   => "O e-mail não é válido"
            )
        ));
        
        $this->validate(new StringLength(
            array(
                "field"             => "senha",
                "min"               => 8,
                "max"               => 45,
                "messageMinimun"    => "Senha deve ter no mínimo 8 caracteres",
                "messageMaximun"    => "Senha deve ter no máximo 45 caracteres"
            )
        ));
        
        if($this->validationHasFailed()){
            return false;
        }
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