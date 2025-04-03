<?php

namespace tests;

use PDO;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use PHPUnit\Metadata\Covers;
use src\Api;
use function PHPUnit\Framework\assertEquals;

// To run tests (cmd from root directory) : 
// php -d xdebug.mode=coverage ./vendor/bin/phpunit --coverage-html reports/

class ApiTest extends TestCase {

    private Api $api;
    private PDO $pdo;
    private Api $failedApi;
    private $failedPdo;

    public function setUp(): void
    {
        parent::setUp();
    
        $host='localhost';	// Serveur de BD
		$db='statisallebd';		// Nom de la BD
		$user='root';		// User 
		$pass='root';		// Mot de passe
		$charset='utf8mb4';	// charset utilisé
		
		$dsn="mysql:host=$host;dbname=$db;charset=$charset";
		
		// Réglage des options
		$options=[																				 
			PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES=>false];

            
        // Étant donné un objet pdo
        $this->pdo = new PDO($dsn,$user,$pass,$options);
        // et un objet pdo qui ne fonctionne pas bien
        $this->failedPdo = self::createMock(PDO::class);     

        // Configuration du mock pour la méthode fetch
        $this->failedPdo->expects($this->any())
                        ->method('query')
                        ->willThrowException(new PDOException());

        $this->failedPdo->expects($this->any())
                        ->method('prepare')
                        ->willThrowException(new PDOException());
        
        // et une API associée
        $this->api = new Api($this->pdo);
        $this->failedApi = new Api($this->failedPdo);
        // et une nouvelle personne dans la base de donnée
        $sql = "INSERT INTO employe (id_employe, nom, prenom, telephone) VALUES ('M000000', 'test', 'test', '0000')";
        $this->pdo->query($sql);
        $sql = "INSERT INTO login (login, mdp, id_type, id_employe, api_key) VALUES ('test', '12dea96fec20593566ab75692c9949596833adc9', 1, 'M000000', 1234)";
        $this->pdo->query($sql);
    }

    
    #[Covers("/src/Api::getApiKey")]
    public function testGetApiKey(): void {
        // Quand on récupère la clé api
        $donnees = [
            'login'    => 'test',
            'password' => 'user'
        ];
        $cle = $this->api->getApiKey($donnees)["api_key"];

        // Alors on récupère la bonne clé
        self::assertEquals(1234, $cle);
    }

    #[Covers("/src/Api::getApiKey")]
    public function testGetApiKeyKeyLength0(): void {

        $this->pdo->beginTransaction();
        try {
            // Étant donné un utilisateur avec une clé 'vide'
            $this->pdo->query("INSERT INTO login (login, mdp, id_type, id_employe, api_key) VALUES ('other_test_user', sha1('password'), 1, 'E000001', '');");
            // Quand on récupère la clé api
            $donnees = [
                'login'    => 'other_test_user',
                'password' => 'password'
            ];
            $cle = $this->api->getApiKey($donnees)["api_key"];

            // Alors on récupère une clé non vide
            self::assertNotEquals("", $cle);
            $this->pdo->rollBack();
        } catch (PDOException $e) {
            self::assertEquals("500", $e->getMessage());
            $this->pdo->rollBack();
        }
    }
    

    #[Covers("/src/Api::generateNewKey")]
    public function testgenerateNewKey() {

        // Etant donné une clée à générer
        // Lorsqu'on génère une nouvelle clé
        $newKey = $this->api->generateNewKey(16);
        // Then on obtient une nouvelle clé
        self::assertNotNull($newKey);
    }

    #[Covers("/src/Api::getReservations")]
    public function testGetReservationsAucune(): void {
        // Quand on récupère la liste des réservations
        $resa = $this->api->getReservations(1234);
        // Alors aucune réservation n'est renvoyée
        self::assertEquals(0, count($resa));
    }

    #[Covers("/src/Api::getReservations")]
    public function testGetReservationsFailedPDO(): void {
        // Alors une exception est levée
        self::expectException(PDOException::class);
        // Quand on récupère la liste des réservations avec un pdo qui ne fonctionne pas
        $resa = $this->failedApi->getReservations(1234);
        assertEquals(500, $resa["code"]);
    }

    #[Covers("/src/Api::addKey")]
    public function testAddKey(): void {
        // Etant donné un compte utilisateur et une clé
        $login = 'test_user';
        $key = "12345";
        $this->pdo->beginTransaction();
        try {
            $this->pdo->query("INSERT INTO login (login, mdp, id_type, id_employe) VALUES ('test_user', 'password', 1, 'E000001');");
            // Quand on ajoute une clé à cet utilisateur
            $this->api->addKey($login, $key);
            // Alors la clé est bien ajoutée
            $sql = "SELECT api_key FROM login WHERE login = 'test_user'";
            self::assertEquals($key, $this->pdo->query($sql)->fetch()['api_key']);
            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
        }
    }

    #[Covers("Src\Api::getReservations")]
    public function testGetReservationsUne(): void {
        try {
            // Étant donné une nouvelle réservation au nom de l'employé
            $this->pdo->beginTransaction();
            $sql = "INSERT INTO reservation (id_reservation, id_salle, id_employe, id_activite, date_reservation, heure_debut, heure_fin) VALUES ('R999999', '00000001', 'M000000', 'A0000004', '2030-10-07', '17:00:00', '19:00:00')";
            $this->pdo->query($sql);
            // Quand on récupère la liste des réservations
            $resa = $this->api->getReservations(1234);
            // Alors aucune réservation n'est renvoyée
            self::assertEquals(1, count($resa));
            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
        }
    }

    #[Covers("Src\Api::getSignalements")]
    public function testGetSignalementsAucune(): void {
        // Quand on récupère la liste des signalements sans api_key valide
        $signalements = $this->api->getSignalements(1234);
        // Alors aucun signalement n'est renvoyé
        self::assertEquals(0, count($signalements));
    }

    #[Covers("Src\Api::getSignalements")]
    public function testGetSignalementsNotNull(): void {
        // Quand on récupère la liste des signalements avec api_key valide
        // Et que l'utilisateur associé a des signalements
        $this->pdo->beginTransaction();
        try {
            $apiKey = $this->api->getApiKey(['login' => 'dupont.pierre', 'password' => 'hashed_password'])["api_key"];
            $this->pdo->query("INSERT INTO signalements (id_reservation, resume, description, id_incident, contact, date) VALUES ('R000002', 'Test', 'Ceci est une description', 1, 0, '2030-10-08')");
            $signalements = $this->api->getSignalements($apiKey);
            // Alors au moins 1 signalement est renvoyé
            self::assertNotEquals(0, $signalements);
            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
        }
    }

    #[Covers("Src\Api::getSignalements")]
    public function testGetSignalementsWithReservationId(): void {
        // Quand on récupère la liste des signalements d'une réservation
        // Et que l'utilisateur associé a des signalements
        $this->pdo->beginTransaction();
        try {
            $idReservation = "R000002";
            $apiKey = $this->api->getApiKey(['login' => 'dupont.pierre', 'password' => 'hashed_password'])["api_key"];
            $this->pdo->query("INSERT INTO signalements (id_reservation, resume, description, id_incident, contact, date) VALUES ($idReservation, 'Test', 'Ceci est une description', 1, 0, '2030-10-08')");
            $signalements = $this->api->getSignalements($apiKey);
            // Alors au moins 1 signalement est renvoyé
            self::assertNotEquals(0, $signalements);
            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
        }
    }

    #[Covers('src\Api::insertSignalements')]
    public function testInsertSignalement(): void {
        try {
            $this->pdo->beginTransaction();
            // Étant donné une nouvelle réservation au nom de l'employé
            $sql = "INSERT INTO reservation (id_reservation, id_salle, id_employe, id_activite, date_reservation, heure_debut, heure_fin) 
                    VALUES ('R999999', '00000001', 'M000000', 'A0000004', '2030-10-07', '17:00:00', '19:00:00')";
            $this->pdo->query($sql);

            $donnees = [
                'resume'      => 'Test',
                'description' => 'Ceci est une description',
                'incident'    => 1,
                'contact'     => 0
            ];
            // Quand on insère un nouveau signalement pour cette réservation
            $result = $this->api->insertSignalement('R999999', $donnees, 1234);
            // Alors on obtient une réponse, indiquant que le message a bien été ajouté
            self::assertEquals('Signalement ajouté', $result['message']);

            // Quand on récupère la liste des signalements
            $signalements = $this->api->getSignalements(1234);
            // Alors il y en a bien un d'enregistré
            self::assertEquals(1, count($signalements));
            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
        }
    }

    #[Covers("src\Api::editSignalements")]
    public function testEditSignalement(): void {
        $this->pdo->beginTransaction();
        try {
            // Étant donné une nouvelle réservation au nom de l'employé
            $sql = "INSERT INTO reservation (id_reservation, id_salle, id_employe, id_activite, date_reservation, heure_debut, heure_fin) 
                    VALUES ('R999999', '00000001', 'M000000', 'A0000004', '2030-10-07', '17:00:00', '19:00:00')";
            $this->pdo->query($sql);

            // et un signalement pour cette réservation
            $sql = "INSERT INTO signalements (id, id_reservation, resume, description, id_incident, contact, date) 
                    VALUES (1111, 'R999999', 'Test', 'Ceci est une description', 1, 0, '2030-10-08')";
            $this->pdo->query($sql);


            // Quand on modifie le signalement
            $donnees = [
                'resume'      => 'Signalement modifié',
                'description' => 'Description modifiée',
                'incident'    => 2
            ];
            $result = $this->api->editSignalement(1111, $donnees, 1234);
            // Alors on reçoit un message indiquant que le signalement est bien modifié
            self::assertEquals('Signalement modifié', $result['message']);
            
            $sql = "SELECT * FROM signalements WHERE id = 1111";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $signalement = $stmt->fetch();
            // et le signalement est bel et bien modifié
            self::assertEquals('Signalement modifié', $signalement['resume']);
            self::assertEquals('Description modifiée', $signalement['description']);
            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
        }
    }

    #[Covers("/src/Api::deleteSignalement")]
    public function testDeleteSignalement(): void {
        $this->pdo->beginTransaction();
        try {
            // Étant donné une nouvelle réservation au nom de l'employé
            $sql = "INSERT INTO reservation (id_reservation, id_salle, id_employe, id_activite, date_reservation, heure_debut, heure_fin) 
                    VALUES ('R999999', '00000001', 'M000000', 'A0000004', '2030-10-07', '17:00:00', '19:00:00')";
            $this->pdo->query($sql);

            // et un signalement pour cette réservation
            $sql = "INSERT INTO signalements (id, id_reservation, resume, description, id_incident, contact, date) 
                    VALUES (1111, 'R999999', 'Test', 'Ceci est une description', 1, 0, '2030-10-08')";
            $this->pdo->query($sql);


            // Quand on supprime le signalement
            $result = $this->api->deleteSignalement(1111, 1234);

            // Alors on reçoit un message indiquant que le signalement est supprimé
            self::assertEquals('Signalement supprimé', $result['message']);
            // et la liste des signalements est vide
            $signalements = $this->api->getSignalements(1234);
            self::assertEquals(0, count($signalements));
            $this->pdo->rollBack();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
        }
    }

    public function tearDown(): void {
        // Suppression des données du setUp()
        $this->pdo->query("DELETE FROM login WHERE login = 'test'");
        $this->pdo->query("DELETE FROM employe WHERE id_employe = 'M000000'");

        parent::tearDown();
    }
}