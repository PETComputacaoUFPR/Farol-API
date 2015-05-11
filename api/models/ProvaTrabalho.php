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
        if($nome){
            $this->nome = $nome;
        }
        return $this;
    }
    
    public function getProvaTrabalho(){
        return $this->provaTrabalho;
    }
    
    public function setProvaTrabalho($provaTrabalho){
        if($provaTrabalho){
            $this->provaTrabalho = $provaTrabalho;
        }
        return $this;
    }
    
    public function getNumero(){
        return $this->numero;
    }
    
    public function setNumero($numero){
        if($numero){
            $this->numero = $numero;
        }
        return $this;
    }
    
    public function isSubstitutiva(){
        return $this->substitutiva;
    }
    
    public function setSubstitutiva($substitutiva){
        if($substitutiva){
            $this->substitutiva = $substitutiva;
        }
        return $this;
    }
    
    public function getAno(){
        return $this->ano;
    }
    
    public function setAno($ano){
        if($ano && $ano > 0){
            $this->ano = $ano;
        }else{
            throw new \InvalidArgumentException('ProvaTrabalho.ano não pode ser negativo');
        }
        return $this;
    }
    
    public function getSemestre(){
        return $this->semestre;
    }
    
    public function setSemestre($semestre){
        if($semestre){
            $this->semestre = $semestre;
        }
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
        if($status){
            $this->status = $status;
        }
        return $this;
    }
    
    public function isImagem(){
        return $this->imagem;
    }
    
    public function setImagem($imagem){
        $this->imagem = $imagem;
        return $this;
    }
    
    public function setMateria($codigo){
        if($codigo){
            $materia = Materia::findFirstByCodigo($codigo);
            if($materia){
                $this->tbMateria_codigo = $codigo;
            }
        }
        return $this;
    }
    
    public function setProfessor($id){
        if($id){
            $professor = Professor::findFirst($id);
            if($professor){
                $this->tbProfessor_id = $id;
            }
        }
        return $this;
    }
    
    public function setUsuario($id){
        if($id){
            $usuario = Usuario::findFirst($id);
            if($usuario){
                $this->tbUsuario_id = $id;
            }
        }
        return $this;
    }
}
?>