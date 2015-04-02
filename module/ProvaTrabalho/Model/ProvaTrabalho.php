<?php

namespace ProvaTrabalho\Model;

class ProvaTrabalho{
    private $id;
    //Nome original do arquivo
    private $nome;
    //Prova ou trabalho
    private $provaTrabalho;
    //Prova 1 ou 2 ou final, trabalho 1 ou 2 ou final
    private $numero;
    private $substitutiva;
    //Caminho para o arquivo
    private $arquivo;
    //Pendente ou aprovado
    private $status;
    //É imagem ou não
    private $image;
    private $professor;
    private $materia;
    
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
        $this->numero = numero;
        return $this;
    }
    
    public function isSubstitutiva(){
        return $this->substitutiva;
    }
    
    public function setSuvstitutiva($substitutiva){
        $this->substitutiva = $substitutiva;
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
    
    public function isImage(){
        return $this->image;
    }
    
    public function setImage($image){
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