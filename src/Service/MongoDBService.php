<?php
namespace App\Service;

use MongoDB\Client;

class MongoDBService
{
    private $client;
    private $database;

    public function __construct(string $mongodbUrl, string $mongodbDatabase)
    {
        $this->client = new Client($mongodbUrl);
        $this->database = $this->client->selectDatabase($mongodbDatabase);
    }

    public function getCollection(string $collectionName)
    {
        return $this->database->selectCollection($collectionName);
    }
}
