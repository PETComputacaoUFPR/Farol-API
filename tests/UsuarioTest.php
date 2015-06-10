<?php
class UsuarioTest extends PHPUnit_Framework_TestCase {
    protected $client;

    protected function setUp() {
        $this->client = new GuzzleHttp\Client(array(
            "base_url"  => getenv("HOST"),
            "defaults"  => ["exceptions" => false]
        ));
    }

    public function testGetAll(){
        $response = $this->client->get("/api/v1/u/");

        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->json();
        $this->assertInternalType('array', $data);
        //Com o SQL básico, há 1 usuário. Cuidado com erros gerados ao inserir
        //ou remover usuários
        $this->assertEquals(1, count($data));
    }

    public function testGetOnly1(){
        $response = $this->client->get("/api/v1/u/1");

        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('FOUND', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $usuario = $data['data'];
        $this->assertEquals('pet', $usuario['nome']);
    }

    public function testGetUsuarioInexistente(){
        $response = $this->client->get("/api/v1/u/0");

        $this->assertEquals(404, $response->getStatusCode());

        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('NOT-FOUND', $data['status']);
    }

    public function testGetAllAdmin(){
        $response = $this->client->get("/api/v1/u/users/admin");

        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->json();

        $this->assertInternalType('array', $data);
        $this->assertEquals(1, count($data));
    }

    public function testGetAllModerador(){
        $response = $this->client->get("/api/v1/u/users/moderador");

        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->json();

        $this->assertInternalType('array', $data);
        $this->assertEquals(1, count($data));
    }

    public function testGetAllNormal(){
        $response = $this->client->get("/api/v1/u/users/normal");

        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->json();

        $this->assertInternalType('array', $data);
        $this->assertEquals(0, count($data));
    }

    public function testCreateUsuario(){
        $usuario = array(
            "email"  => "wasd@ufpr.br",
            "senha"  => "123456789"
        );
        $response = $this->client->post("/api/v1/u", [
            "json" => $usuario
        ]);

        $this->assertEquals(201, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('OK', $data['status']);
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals($usuario['email'], $data['data']['email']);
    }

    public function testCreateUsuarioEmailInvalido(){
        $usuario = array(
            "email"  => "wasdWasd@gmail.com",
            "senha"  => "123456789"
        );
        $response = $this->client->post("/api/v1/u", [
            "json" => $usuario
        ]);

        $this->assertEquals(409, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('ERROR', $data['status']);
        $this->assertArrayHasKey('messages', $data);
        $this->assertEquals("Somente são aceitos e-mails da UFPR", $data['messages'][0]);
    }

    public function testCreateUsuarioSenhaPequena(){
        $usuario = array(
            "email"  => "wasdAsd@ufpr.br",
            "senha"  => "123"
        );
        $response = $this->client->post("/api/v1/u", [
            "json" => $usuario
        ]);

        $this->assertEquals(409, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('ERROR', $data['status']);
        $this->assertArrayHasKey('messages', $data);
        $this->assertEquals("Senha deve ter no mínimo 8 caracteres", $data['messages'][0]);
    }

    public function testCreateUsuarioSenhaGrande(){
        $usuario = array(
            "email"  => "asdWasd@ufpr.br",
            "senha"  => "1234512345123451234512345123451234512345123451234512345"
        );
        $response = $this->client->post("/api/v1/u", [
            "json" => $usuario
        ]);

        $this->assertEquals(409, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('ERROR', $data['status']);
        $this->assertArrayHasKey('messages', $data);
        $this->assertEquals("Senha deve ter no máximo 50 caracteres", $data['messages'][0]);
    }

    public function testUpdateUsuario(){
        $lastUser = end($this->client->get("/api/v1/u")->json());
        $usuario = array(
            "id"        => $lastUser["id"],
            "nome"      => "Wasd Asd Qwerty",
            "email"     => $lastUser["email"],
            "admin"     => $lastUser["admin"],
            "moderador" => $lastUser["moderador"]
        );
        $response = $this->client->put("/api/v1/u/".$lastUser["id"], array(
            "json" => $usuario
        ));

        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('OK', $data['status']);
    }

    public function testUpdateUsuarioInexistente(){
        $usuario = array(
            "id"    => 0,
            "nome"  => "Mosd"
        );
        $response = $this->client->put("/api/v1/u/0", array(
            "json" => $usuario
        ));

        $this->assertEquals(404, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('NOT-FOUND', $data['status']);
    }

    public function testDeleteUsuario(){
        $lastId = end($this->client->get("/api/v1/u")->json())['id'];
        $response = $this->client->delete("/api/v1/u/".$lastId);

        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('OK', $data['status']);
    }

    public function testDeleteUsuarioInexistente(){
        $response = $this->client->delete("/api/v1/u/0");

        $this->assertEquals(404, $response->getStatusCode());
        $data = $response->json();

        $this->assertArrayHasKey('status', $data);
        $this->assertEquals('NOT-FOUND', $data['status']);
    }
}
?>
