<?php

class MateriaTest extends PHPUnit_Framework_TestCase {
    protected $client;
    
    protected function setUp(){
        $this->client = new GuzzleHttp\Client(array(
            "base_url"  => "https://farol-vytorcalixto.c9.io",
            "defaults"  => ["exceptions" => false]
        ));
    }
    
    public function testGetAll(){
        $response = $this->client->get("/api/v1/materias/");
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->json();
        $this->assertInternalType('array', $data);
        //Com o SQL básico, há 51 matérias. Cuidado com erros gerados ao inserir
        //ou remover matérias
        $this->assertEquals(51, count($data));
    }
    
    public function testGetOnlyCI055(){
        $response = $this->client->get("/api/v1/materias/ci055");
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->json();
        
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('FOUND', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $materia = $data['data'];
        $this->assertEquals('CI055', $materia['codigo']);
    }
    
    public function testGetMateriaInexistente(){
        $response = $this->client->get("/api/v1/materias/ab000");
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->json();
        
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('NOT-FOUND', $data['status']);
    }
    
    public function testSearchAlg(){
        //Pega as máterias de algoritmos, grafos, análise, algebra e introdução
        $response = $this->client->get("/api/v1/materias/search/alg");
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->json();
        
        $this->assertInternalType('array', $data);
        $this->assertEquals(7, count($data));
    }
    
    public function testSearchAlgoritmos(){
        //Pega as 3 máterias de algoritmos
        $response = $this->client->get("/api/v1/materias/search/algo/ci05");
        
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->json();
        
        $this->assertInternalType('array', $data);
        $this->assertEquals(3, count($data));
        $materia = $data[0]; //Alg I
        $this->assertArrayHasKey('codigo', $materia);
        $this->assertEquals('CI055', $materia['codigo']);
    }
    
    public function testCreateMateria(){
        $materia = array(
            "codigo"    => "ab0", 
            "nome"      => "Tópicos Avançados em Testes Unitários"
        );
        $response = $this->client->post("/api/v1/materias", [
            "json" => $materia
        ]);
        
        $this->assertEquals(201, $response->getStatusCode());
        $data = $response->json();
        
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('OK', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals($materia, $data['data']);
    }
    
    public function testUpdateMateria(){
        $materia = array(
            "codigo"    => "ab0", 
            "nome"      => "Tópicos Avançados em Zoeiras Unitárias"
        );
        $response = $this->client->put("/api/v1/materias/ab0", array(
            "json" => $materia
        ));
        
        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();
        
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('OK', $data['status']);
    }
    
    public function testUpdateMateriaInexistente(){
        $materia = array(
            "codigo"    => "ab000",
            "nome"      => "Algoritmos Literários"
        );
        $response = $this->client->put("/api/v1/materias/ab000", array(
            "json" => $materia
        ));
        
        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();
        
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('NOT-FOUND', $data['status']);
    }
    
    public function testDeleteMateria(){
        $response = $this->client->delete("/api/v1/materias/ab0");
        
        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();
        
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('OK', $data['status']);
    }
    
    public function testDeleteMateriaInexistente(){
        $response = $this->client->delete("/api/v1/materias/ab000");
        
        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();
        
        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('NOT-FOUND', $data['status']);
    }
}
?>