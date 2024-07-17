<?php
namespace App\Service;

use MongoDB\Client;

class MongoDBService
{
    private $client;
    private $database;

    public function __construct()
    {
        $mongodbUri = $_ENV['MONGODB_URI']; // URI pour la connexion
        $mongodbDatabase = $_ENV['MONGODB_DATABASE']; // Nom de la base de données

        // Connexion à MongoDB Atlas
        $this->client = new Client($mongodbUri);
        $this->database = $this->client->selectDatabase($mongodbDatabase);

        // Optionnel : Vérifier la connexion
        try {
            $this->client->listDatabases(); // Exemple de requête pour vérifier la connexion
            echo "Connexion réussie à MongoDB Atlas avec la base de données : $mongodbDatabase";
        } catch (\Exception $e) {
            error_log("Erreur lors de la connexion à MongoDB Atlas : " . $e->getMessage());
            throw new \Exception("Erreur lors de la connexion à MongoDB Atlas : " . $e->getMessage());
        }
    }

    public function getCollection(string $collectionName)
    {
        return $this->database->selectCollection($collectionName);
    }
}
