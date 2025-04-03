<?php
/*
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2023   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace test\services;

require_once __DIR__ . '/../../../vendor/autoload.php';


use PDO;
use PDOException;
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../app_resa/services/EmployeService.php';
use services\EmployeService;
use yasmf\DataSource;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertArrayHasKey;

class employeTest extends TestCase {

    private PDO $pdo;
    private EmployeService $employeService;

    
    public function setUp(): void
    {
        parent::setUp();
        // given a pdo for tests
        $datasource = new DataSource(
            $host = 'localhost',      
            $port = 3306,
            $db_name = 'statisallebd',
            $user = 'root',           
            $pass = 'root',           
            $charset = 'utf8mb4'             
    
        );
        $this->pdo = $datasource->getPdo();
        // and a employe service
        $this->employeService = new EmployeService();
    }
    
    #[Covers("/app_resa/fonction/employe::renvoyerEmploye()")]
    public function testRenvoyerEmploye() {
        $this->pdo->beginTransaction();
        try {
            // when I call renvoyerEmploye
            $employes = $this->employeService->renvoyerEmployes($this->pdo);
            foreach ($employes as $value) {
                if ($value["id_employe"] == "A999999") {
                    $employe = $value;
                }
            }
            // then I expect the employe to be returned
            $this->assertEquals('admin', $employe['nom'], "Le nom ne correspond pas.");
            $this->assertEquals('admin', $employe['prenom'], "Le prénom ne correspond pas.");
            $this->assertEquals('admin', $employe['id_compte'], "L'id ne correspond pas.");
            $this->assertEquals('0000', $employe['telephone'], "Le téléphone ne correspond pas.");
    
            $this->pdo->rollBack();
        } catch (PDOException $pb) {
            $this->pdo->rollBack();
            $this->fail("Une exception PDO a été levée : " . $pb->getMessage());
        }
    }

    #[Covers("/app_resa/fonction/employe::verifMdp()")]
    public function testVerifMdpNominal() {
        // Etant donné un mot de passe valide
        $mdp = 'motDePasseTest#';
        // Quand on test sa validité, alors :
        assertTrue($this->employeService->verifMdp($mdp), "Le mot de passe n'est pas valide.");
    }
    
    #[Covers("/app_resa/fonction/employe::verifMdp()")]
    public function testVerifMdpLimite() {
        // Etant donné un mot de passe trop court
        $mdp = 'mot#';
        // Quand on test sa validité, alors :
        assertFalse($this->employeService->verifMdp($mdp), "Le mot de passe n'est pas valide.");

        // Etant donné un mot de passe sans caractères spéciaux
        $mdp = 'motDePasseAssezLong';
        // Quand on test sa validité, alors :
        assertFalse($this->employeService->verifMdp($mdp), "Le mot de passe n'est pas valide.");
    }

    #[Covers("/app_resa/fonction/employe::checkErreurs()")]
    public function testCheckErreursNominal() {
        $erreurs = [];
        // Etant donné un tableau de données
        $donnees = [
            'Dupont',  'Jean', 
            '0123456789', 'dupont.jean',
            'motDePasseTest#', 'motDePasseTest#'
        ];
        // Quand on vérifie les erreurs, alors :
        $this->employeService->checkErreurs($this->pdo, $donnees[0], $donnees[1]
                                            , $donnees[2], $donnees[3], $donnees[4]
                                            , $donnees[5], $erreurs);
        // Alors le tableau d'erreurs est vide
        assertSame($erreurs, [], "Il y a des erreurs dans le tableau.");
    }

    #[Covers("/app_resa/fonction/employe::verifierDonneesSaisies()")]    
    public function testVerifierDonneesSaisiesNominal() {
        // Etant donné un tableau de données valides
        $donnees = [
            'Dupont',  'Jean', 
            '0123456789', 'dupont.jean',
            'motDePasseTest#', 'motDePasseTest#'
        ];
        $tabAttributLogin = [];
        $tabAttributEmploye = [];
        $erreurs = [];
        // Quand on vérifie les données, alors :
            $this->employeService
                        ->verifierDonneesSaisies($this->pdo,
                            $donnees[0], $donnees[1], $donnees[2], $donnees[3],
                            $donnees[4], $donnees[5], $tabAttributLogin,
                            $tabAttributEmploye, $erreurs);
        assertSame($erreurs, [], "Les données saisies ne sont pas valides.");
    }

    #[Covers("/app_resa/fonction/employe::verifierDonneesSaisies()")]    
    public function testVerifierDonneesSaisiesLimite() {
        // Etant donné un tableau de données valides
        $donnees = [
            'UnNomTresTresTresTresTresTresTresTresTresTresTresTresTresTresTresTresTresTresLong'
            ,  'UnPrenomTropTropTropTropTropTropTropTropTropTropTropTropTropTropTropTropTropLong', 
            '01A', 'admin',
            'motDePasseTestPasValide', 'motDePasseTestPasEgal'
        ];
        $tabAttributLogin = ["login" => "pascorrect"];
        $tabAttributEmploye = [];
        $erreurs = [];
        // Quand on vérifie les données, alors :
            $this->employeService
                        ->verifierDonneesSaisies($this->pdo,
                            $donnees[0], $donnees[1], $donnees[2], $donnees[3],
                            $donnees[4], $donnees[5], $tabAttributLogin,
                            $tabAttributEmploye, $erreurs);
        assertTrue(array_key_exists("numTel", $erreurs), "Le numéro n'est pas invalide.");
        assertTrue(array_key_exists("nom", $erreurs), "Le nom n'est pas invalide.");
        assertTrue(array_key_exists("prenom", $erreurs), "Le prénom n'est pas invalide.");
        assertTrue(array_key_exists("login", $erreurs), "Le login n'est pas invalide.");
        assertTrue(array_key_exists("mdp", $erreurs), "Le mot de passe n'est pas invalide.");
        assertTrue(array_key_exists("cmdp", $erreurs), "Le mot de passe de confirmation n'est pas invalide.");
    }

    #[Covers("/app_resa/fonction/employe::verifLogin()")]
    public function testVerifLoginNominal() {
        // Etant donné un login inexistant
        $login = 'pasconnu.lelogin';
        // Quand on test sa validité, alors :
        assertFalse($this->employeService->verifLogin($this->pdo, $login), "Le login n'est pas inconnu.");

        // Etant donné un login existant
        $login = 'admin';
        // Quand on test sa validité, alors :
        assertTrue($this->employeService->verifLogin($this->pdo, $login), "Le login n'est pas connu.");
    }

    #[Covers("/app_resa/fonction/employe::verifLoginExiste()")]
    public function testVerifLoginExisteNominal() { // Test redondant, fonction redondante !!
        // Etant donné un login inexistant
        $login = 'pasconnu.lelogin';
        // Quand on test sa validité, alors :
        assertFalse($this->employeService->verifLoginExiste($this->pdo, $login), "Le login n'est pas inconnu.");

        // Etant donné un login existant
        $login = 'admin';
        // Quand on test sa validité, alors :
        assertTrue($this->employeService->verifLoginExiste($this->pdo, $login), "Le login n'est pas connu.");
    }

    #[Covers("/app_resa/fonction/employe::compterEmployes()")]
    public function testCompterEmployes() { 
        // Quand on compte les employés
        $nbEmployes = $this->pdo->query("SELECT COUNT(*) FROM employe")->fetchColumn();
        // Alors :
        assertEquals($nbEmployes, $this->employeService->compterEmployes($this->pdo), "Le nombre d'employés ne correspond pas.");
    }

    #[Covers("/app_resa/fonction/employe::compterEmployes()")]
    public function testRecupAttributEmploye() { 
        // Etant donné un employé de la base de données
        $this->pdo->beginTransaction();
        try {
            $donnees = [
                "nom" => "Punaise",
                "prenom" => "Papier",
                "telephone" => "0123"
            ];
            $this->pdo->query("INSERT INTO employe (id_employe, nom, prenom, telephone) VALUES ('EE123', 'Punaise', 'Papier', '0123');");
            $donneesEmploye = $this->employeService->recupAttributEmploye($this->pdo, 'EE123');
            // Quand on récupère ses attributs, alors :
            assertEquals($donnees["nom"], $donneesEmploye["nom"], "Le nom ne correspond pas.");
            assertEquals($donnees["prenom"], $donneesEmploye["prenom"], "Le prénom ne correspond pas.");
            assertEquals($donnees["telephone"], $donneesEmploye["telephone"], "Le téléphone ne correspond pas.");
            $this->pdo->rollBack();
        } catch (PDOException $pb) {
            $this->pdo->rollBack();
            $this->fail("Une exception PDO a été levée : " . $pb->getMessage());
        }
    }

    #[Covers("/app_resa/fonction/employe::verifIdType()")]
    public function testVerifIdTypeExistant() {
        $this->pdo->beginTransaction();
        try {
            // GIVEN an existing id_type 
            $existingIdType = 1;

            // WHEN I check an existing id_type
            $result = $this->employeService->verifIdType($this->pdo, $existingIdType);

            // THEN I expect the result to be 1
            $this->assertEquals(1, $result, "L'ID type existant aurait dû être trouvé.");

            $this->pdo->rollBack();
        } catch (PDOException $pb) {
            $this->pdo->rollBack();
            $this->fail("Une exception PDO a été levée : " . $pb->getMessage());
        }
    }

    #[Covers("/app_resa/fonction/employe::verifIdType()")]
    public function testVerifIdTypeInexistant() {
        $this->pdo->beginTransaction();
        try {
            // GIVEN a non existing id_type
            $nonExistingIdType = 9999;

            // WHEN I check a non existing id_type
            $result = $this->employeService->verifIdType($this->pdo, $nonExistingIdType);

            // THEN I expect the result to be 0
            $this->assertEquals(0, $result, "L'ID type inexistant n'aurait pas dû être trouvé.");

            $this->pdo->rollBack();
        } catch (PDOException $pb) {
            $this->pdo->rollBack();
            $this->fail("Une exception PDO a été levée : " . $pb->getMessage());
        }
    }

    #[Covers("/app_resa/fonction/employe::recupAttributLogin()")]
    public function testRecupAttributLogin() {
        try {
            // GIVEN an existing employee id
            $idEmploye = 'E000001'; 

            // WHEN I recove attributes for an employee
            $resultat = $this->employeService->recupAttributLogin($this->pdo, $idEmploye);

            // THEN I expect the result is same than login attributes
            $this->assertNotNull($resultat, "Les attributs du login ne sont pas récupérés.");
            $this->assertArrayHasKey('login', $resultat, "Le login n'est pas présent.");
            $this->assertArrayHasKey('mdp', $resultat, "Le mot de passe n'est pas présent.");
            $this->assertArrayHasKey('id_type', $resultat, "L'ID type n'est pas présent.");

  
            $this->assertEquals('dupont.pierre', $resultat['login'], "Le login ne correspond pas.");
            $this->assertEquals('f409ce90a1cd144912d1df8620215b2dc9fda731', $resultat['mdp'], "Le mot de passe ne correspond pas.");
            $this->assertEquals('2', $resultat['id_type'], "L'ID type ne correspond pas.");

        } catch (Exception $e) {
            $this->fail("Une exception a été levée : " . $e->getMessage());
        }
    }

    #[Covers("/app_resa/fonction/employe::recupAttributLogin()")]
    public function testRecupAttributLoginNok() {
        try {
            // GIVEN a non-existing employee id
            $idEmploye = 'Z987789'; 

            // WHEN I try to recove attributes for an employee
            $resultat = $this->employeService->recupAttributLogin($this->pdo, $idEmploye);

            // THEN I expect the result to be null
            $this->assertFalse($resultat, "Les attributs du login devraient être null pour un employé inexistant.");
            
        } catch (Exception $e) {
            $this->fail("Une exception a été levée : " . $e->getMessage());
        }
    }

    #[Covers("/app_resa/fonction/employe::modifierEmployeSansMdp()")]
    public function testModifierEmployeSansMdpOk() {
        try {
            // GIVEN an existing employee
            $id = 'E000001';
            $nom = 'Dupont';
            $prenom = 'Pierre';
            $login = 'dupont.pierre';
            $numTel = '2614';
            $id_type = 2;

            // WHEN I modify the employee without changing the password
            $this->employeService->modifierEmployeSansMdp($this->pdo, $id, $nom, $prenom, $login, $numTel, $id_type);

            // THEN the employee's information should be updated
            $requete = "SELECT e.nom, e.prenom, e.telephone, l.login, l.id_type 
                        FROM employe e 
                        JOIN login l ON e.id_employe = l.id_employe
                        WHERE e.id_employe = :id";
            $stmt = $this->pdo->prepare($requete);
            $stmt->execute([':id' => $id]);
            $employe = $stmt->fetch();

            $this->assertNotFalse($employe, "L'employé n'a pas été trouvé après modification.");
            $this->assertEquals($nom, $employe['nom'], "Le nom ne correspond pas.");
            $this->assertEquals($prenom, $employe['prenom'], "Le prénom ne correspond pas.");
            $this->assertEquals($numTel, $employe['telephone'], "Le téléphone ne correspond pas.");
            $this->assertEquals($login, $employe['login'], "Le login ne correspond pas.");
            $this->assertEquals($id_type, $employe['id_type'], "L'ID type ne correspond pas.");

        } catch (Exception $e) {
            $this->fail("Une exception a été levée : " . $e->getMessage());
        }
    }

    #[Covers("/app_resa/fonction/employe::modifierEmployeSansMdp()")]
    public function testModifierEmployeSansMdpNok() {
        try {
            // GIVEN a non-existing employee ID
            $id = 'P958476'; 
            $nom = "An";
            $prenom = "toinette";
            $login = "an.toinette";
            $numTel = '2665';
            $id_type = 2;

            // WHEN I try to modify the employee
            $this->employeService->modifierEmployeSansMdp($this->pdo, $id, $nom, $prenom, $login, $numTel, $id_type);

            // THEN I expect that no row was updated
            $requete = "SELECT COUNT(*) FROM employe WHERE id_employe = :id";
            $stmt = $this->pdo->prepare($requete);
            $stmt->execute([':id' => $id]);
            $count = $stmt->fetchColumn();

            $this->assertEquals(0, $count, "L'employé inexistant ne devrait pas être modifié.");

        } catch (Exception $e) {
            $this->fail("Une exception a été levée : " . $e->getMessage());
        }
    }

    #[Covers("/app_resa/fonction/employe::supprimerEmploye()")]
    public function testSupprimerEmploye() {
        try {
            $this->pdo->query("INSERT INTO employe (id_employe, nom, prenom, telephone) VALUES ('E987002', 'NomT', 'PrenomT', '0123456789');");
            // Etant donné un employé à supprimer
            $idEmploye = 'E987002';
            // Quand je supprime l'employé
            $this->employeService->supprimerEmploye($this->pdo, $idEmploye);
            // Alors je m'attends à ce que l'employé n'existe plus dans la base de données
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM employe WHERE id_employe = :id_employe");
            $stmt->bindParam('id_employe', $idEmploye);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            $this->assertEquals(0, $count, "L'employé n'a pas été supprimé de la base de données.");
            $this->pdo->query("DELETE FROM employe WHERE id_employe = 'E987002';");
        } catch (PDOException $pb) {
            $this->pdo->query("DELETE FROM employe WHERE id_employe = 'E987002';");
            $this->fail("Une exception PDO a été levée : " . $pb->getMessage());
        }
    }
}
?>