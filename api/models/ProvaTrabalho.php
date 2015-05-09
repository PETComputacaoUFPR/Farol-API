<?php
use Phalcon\Mvc\Model;

class ProvaTrabalho extends Model{
    const PROVA = "prova";
    const TRABALHO = "trabalho";
    const PENDENTE = "pendente";
    const APROVADO = "aprovado";
    
    private $id;
    //Nome original do arquivo
    private $nome;
    //Prova ou trabalho
    private $provaTrabalho;
    //1, 2, 3 ou final
    private $numero;
    //É substitutiva ou não (boolean)
    private $substitutiva;
    private $ano;
    private $semestre;
    //Caminho para o arquivo
    private $arquivo;
    //Pendente ou aprovado
    private $status;
    //É imagem ou não (boolean)
    private $imagem;
    private $tbMateria_codigo;
    private $tbProfessor_id;
    private $tbUsuario_id;
    
    /**
     * Retorna o nome da tabela no banco de dados
    */
    public function getSource(){
        return "tbProvaTrabalho";
    }
    
    public function initialize(){
        $this->setSource("tbProvaTrabalho");
        $this->belongsTo("tbMateria_codigo", "Materia", "codigo");
        $this->belongsTo("tbProfessor_id", "Professor", "id");
        $this->belongsTo("tbUsuario_id", "Usuario", "id");
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
        return $this->imagem;
    }
    
    public function getImagem(){
        return $this->isImagem();
    }
    
    public function setImagem($imagem){
        $this->imagem = $imagem;
        return $this;
    }
}
?>