<?php
namespace App\Service;

use MongoDB\Client;
use Exception;

class MongoDBService
{
    private $client;
    private $database;

    public function __construct()
    {
        $mongodbUri = $_ENV['MONGODB_URI'] ?? null; // Utilise `MONGODB_URI`
        $mongodbDatabase = $_ENV['MONGODB_DATABASE'] ?? null; // Utilise `MONGODB_DATABASE`

        if (!$mongodbUri) {
            throw new Exception('L\'URI MongoDB n\'est pas défini dans les variables d\'environnement.');
        }

        if (!$mongodbDatabase) {
            throw new Exception('Le nom de la base de données MongoDB n\'est pas défini dans les variables d\'environnement.');
        }

        try {
            $this->client = new Client($mongodbUri);
            $this->database = $this->client->selectDatabase($mongodbDatabase);
            $this->client->listDatabases(); // Vérifie la connexion
        } catch (\Exception $e) {
            error_log("Erreur lors de la connexion à MongoDB Atlas : " . $e->getMessage());
            throw new Exception("Erreur lors de la connexion à MongoDB Atlas : " . $e->getMessage());
        }
    }

    public function getCollection(string $collectionName)
    {
        return $this->database->selectCollection($collectionName);
    }
}
