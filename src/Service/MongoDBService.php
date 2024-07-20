<?php
namespace App\Service;

use MongoDB\Client;

class MongoDBService
{
    private $client;
    private $database;

    public function __construct(string $mongodbUri, string $mongodbDatabase)
    {
        // Crée un client MongoDB avec l'URL fournie
        $this->client = new Client($mongodbUri);

        // Sélectionne la base de données
        $this->database = $this->client->selectDatabase($mongodbDatabase);
    }

    // Récupère une collection spécifique de la base de données
    public function getCollection(string $collectionName)
    {
        return $this->database->selectCollection($collectionName);
    }
}
