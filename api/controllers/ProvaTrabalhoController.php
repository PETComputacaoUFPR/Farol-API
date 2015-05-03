<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ProvaTrabalhoController extends Controller{
    
    public function search($provaTrabalho="", $materia="", $professor="", 
                            $ano=0, $semestre=0){
        $conditions = ($provaTrabalho ? "provaTrabalho = :provaTrabalho: AND " : "")
                     .($materia ? "tbMateria_codigo LIKE :materia: AND " : "1 = 1 AND ")
                     .($professor ? "tbProfessor_codigo LIKE :professor? AND " : "1=1 AND ")
                     .($ano ? "ano = :ano: AND " : "1=1 AND ")
                     .($semestre ? "semestre = :semestre:" : "1=1");
        $bind = array();
        $provaTrabalho  ? $bind["provaTrabalho"] = $provaTrabalho : "";
        $materia        ? $bind["materia"] = "%".$materia."%" : "";
        $professor      ? $bind["professor"] = "%".$professor."%" : "";
        $ano            ? $bind["ano"] = $ano : "";
        $semestre       ? $bind["semestre"] = $semestre : "";
        
        //provas e trabalhos
        $prsTrs = ProvaTrabalho::find(array(
            "conditions"    => $conditions,
            "bind"          => $bind
        ));
        
        $data = array();
        
        foreach($prsTrs as $prTr){
            $data[] = array(
                "id"            => $prTr->getId(),
                "provaTrabalho" => $prTr->getProvaTrabalho(),
                "numero"        => $prTr->getNumero(),
                "substitutiva"  => $prTr->isSubstitutiva(),
                "ano"           => $prTr->getAno(),
                "semestre"      => $prTr->getSemestre(),
                "arquivo"       => $prTr->getArquivo()
            );
        }
        
        echo json_encode($data);
    }
    
}

?>