<?php
namespace ProvaTrabalho\Model;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class ProvaTrabalho extends Model{
    private $id;
    //Nome original do arquivo
    private $nome;
    //Prova ou trabalho
    private $provaTrabalho;
    //Prova 1 ou 2 ou final, trabalho 1 ou 2 ou final
    private $numero;
    private $substitutiva;
    private $ano;
    private $semestre;
    //Caminho para o arquivo
    private $arquivo;
    //Pendente ou aprovado (0, 1)
    private $status;
    //É imagem ou não
    private $imagem;
    private $professor;
    private $materia;
    
    /**
     * Retorna o nome da tabela no banco de dados
    */
    public function getSource(){
        return "tbProvaTrabalho";
    }
    
    public function initialize(){
        $this->setSource("tbProvaTrabalho");
    }
    
    public function validation(){
        $this->validate(new InclusionIn(
            array(
                "field" => "provaTrabalho",
                "domain" => array("prova","trabalho") 
                )));
        
    }
    
    //Getters and setters
    
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
    
    public function getProvaTrabalho(){
        return $this->provaTrabalho;
    }
    
    public function setProvaTrabalho($provaTrabalho){
        $this->provaTrabalho = $provaTrabalho;
        return $this;
    }
    
    public function getNumero(){
        return $this->numero;
    }
    
    public function setNumero($numero){
        $this->numero = $numero;
        return $this;
    }
    
    public function isSubstitutiva(){
        return $this->substitutiva;
    }
    
    public function setSubstitutiva($substitutiva){
        $this->substitutiva = $substitutiva;
        return $this;
    }
    
    public function getAno(){
        return $this->ano;
    }
    
    public function setAno($ano){
        if($ano < 0){
            throw new \InvalidArgumentException('ProvaTrabalho.ano não pode ser negativo');
        }
        $this->ano = $ano;
        return $this;
    }
    
    public function getSemestre(){
        return $this->semestre;
    }
    
    public function setSemestre($semestre){
        $this->semestre = $semestre;
        return $this;
    }
    
    public function getArquivo(){
        return $this->arquivo;
    }
    
    public function setArquivo($arquivo){
        $this->arquivo = $arquivo;
        return $this;
    }
    
    public function getStatus(){
        return $this->status;
    }
    
    public function setStatus($status){
        $this->status = $status;
        return $this;
    }
    
    public function isImagem(){
        return $this->image;
    }
    
    public function setImagem($image){
        $this->image = $image;
        return $this;
    }
    
    public function getProfessor(){
        return $this->professor;
    }
    
    public function setProfessor($professor){
        $this->professor = $professor;
        return $this;
    }
    
    public function getMateria(){
        return $this->materia;
    }
    
    public function setMateria($materia){
        $this->materia = $materia;
        return $this;
    }
    
    
}
?>