<?php
const PREFIX_TO_RELATIVE_PATH = "/StatiSalle_SAE-S3_WEB-main/sae-s4-a-2024-2025-sw1-3/app_resa";
require $_SERVER[ 'DOCUMENT_ROOT' ] . PREFIX_TO_RELATIVE_PATH . '/lib/vendor/autoload.php';

require_once 'application/DefaultComponentFactory.php';
use application\DefaultComponentFactory;
use yasmf\DataSource;
use yasmf\Router;

/* Chargement du fichier .env */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$dotenv->required(["MYSQL_USER", "MYSQL_DATABASE", "MYSQL_PASSWORD"]);

// Nom affiché sur les pages
$_ENV["nomApplication"] = "ASW";

/* Connexion à la base de données */
$data_source = new DataSource(
    "127.0.0.1", // adresse du serveur
    3306, // port du serveur
    $_ENV["MYSQL_DATABASE"], 
    $_ENV["MYSQL_USER"],
    $_ENV["MYSQL_PASSWORD"],
    "utf8mb4"
);

/* Routage */
$router = new Router(new DefaultComponentFactory());
try {
    $router->route(PREFIX_TO_RELATIVE_PATH, $data_source);
} catch (PDOException $e) {
    $router->route(PREFIX_TO_RELATIVE_PATH); // base de données inaccessible
}