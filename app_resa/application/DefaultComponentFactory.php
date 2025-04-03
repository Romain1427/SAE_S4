<?php
namespace application;

// use <Nom du/des controleur(s)>
require_once 'controllers/HomeController.php';
require_once 'controllers/EmployeController.php';
require_once 'services/EmployeService.php';
use controllers\EmployeController;
use controllers\HomeController;
use services\EmployeService;
use yasmf\NoControllerAvailableForNameException;
use yasmf\ComponentFactory;
use yasmf\NoServiceAvailableForNameException;

/**
 * la factory pour instancier les contrôleurs.
 * 
 * @autor Groupe S3
 */
class DefaultComponentFactory implements ComponentFactory {

    // private ?<ClasseService> $<service> = null;
    private ?EmployeService $employesService = null;

    /**
     * @param string $controller_name le nom du contrôleur à instancier
     * @return mixed le contrôleur créé
     * @throws NoControllerAvailableForNameException si aucun contrôleur
     *         n'a été trouvé
     */
    public function buildControllerByName(string $controller_name): mixed {
        return match ($controller_name) {
            "home" => $this->buildHomeController(),
            "employe" => $this->buildEmployeController(),
            default => throw new NoControllerAvailableForNameException($controller_name)
        };
    }

    /**
     * @param string $service_name le nom du service
     * @return mixed le service créé
     * @throws NoServiceAvailableForNameException si le service est
     *         introuvable
     */
    public function buildServiceByName(string $service_name): mixed {
        return match($service_name) {
            "employe" => $this->buildEmployeService(),
            //"<service name>" => $this-><service builder>(),
            default => throw new NoServiceAvailableForNameException($service_name)
        };
    }

    /** @return HomeController le contrôleur de la page d'accueil */
    private function buildHomeController(): HomeController {
        return new HomeController();
    }
    

    private function buildEmployeService(): EmployeService {
        if ($this->employesService == null) {
            $this->employesService = new EmployeService();
        }
        return $this->employesService;
    }

    private function buildEmployeController() : EmployeController {
        return new EmployeController($this->buildServiceByName("employe"));
    }
}