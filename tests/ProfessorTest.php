<?php
class ProfessorTest extends PHPUnit_Framework_TestCase {
    protected $client;

    protected function setUp() {
        $this->client = new GuzzleHttp\Client(array(
            "base_url"  => getenv("HOST"),
            "defaults"  => ["exceptions" => false]
        ));
    }

    public function testGetAll(){
        $response = $this->client->get("/api/v1/professores/");

        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->json();
        $this->assertInternalType('array', $data);
        //Com o SQL básico, há 40 professores. Cuidado com erros gerados ao inserir
        //ou remover professores
        $this->assertEquals(40, count($data));
    }

    public function testGetOnly25(){
        $response = $this->client->get("/api/v1/professores/25");

        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('FOUND', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $professor = $data['data'];
        $this->assertEquals('Luis Allan Kunzle', $professor['nome']);
    }

    public function testGetProfessorInexistente(){
        $response = $this->client->get("/api/v1/professores/0");

        $this->assertEquals(404, $response->getStatusCode());

        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('NOT-FOUND', $data['status']);
    }

    public function testSearchAle(){
        $response = $this->client->get("/api/v1/professores/search/ale");

        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->json();

        $this->assertInternalType('array', $data);
        $this->assertEquals(2, count($data));
    }

    public function testCreateProfessor(){
        $professor = array(
            "nome"  => "Wasd Asd"
        );
        $response = $this->client->post("/api/v1/professores", [
            "json" => $professor
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('OK', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals($professor['nome'], $data['data']['nome']);
    }

    public function testUpdateProfessor(){
        $lastId = end($this->client->get("/api/v1/professores")->json())['id'];
        $professor = array(
            "id"    => $lastId,
            "nome"  => "Wasd Asd Qwerty"
        );
        $response = $this->client->put("/api/v1/professores/".$lastId, array(
            "json" => $professor
        ));

        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('OK', $data['status']);
    }

    public function testUpdateProfessorInexistente(){
        $professor = array(
            "id"    => 0,
            "nome"  => "Mosd"
        );
        $response = $this->client->put("/api/v1/professores/0", array(
            "json" => $professor
        ));

        $this->assertEquals(404, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('NOT-FOUND', $data['status']);
    }

    public function testDeleteProfessor(){
        $lastId = end($this->client->get("/api/v1/professores")->json())['id'];
        $response = $this->client->delete("/api/v1/professores/".$lastId);

        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('OK', $data['status']);
    }

    public function testDeleteProfessorInexistente(){
        $response = $this->client->delete("/api/v1/professores/0");

        $this->assertEquals(404, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('NOT-FOUND', $data['status']);
    }
}
?>
