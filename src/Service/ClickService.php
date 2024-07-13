<?php 

// src/Service/ClickService.php
namespace App\Service;

class ClickService
{
    private $mongodbService;

    public function __construct(MongoDBService $mongodbService)
    {
        $this->mongodbService = $mongodbService;
    }

    public function incrementClickCount(string $prenom): void
    {
        $collection = $this->mongodbService->getCollection('animal_clicks');
        $collection->updateOne(
            ['prenom' => $prenom],
            ['$inc' => ['count' => 1]],
            ['upsert' => true]
        );
        // Ajoute ce log pour vérifier l'appel de la méthode
        error_log("Clic enregistré pour l'animal: $prenom");
    }

    public function getClicksByAnimal(): array
    {
        $collection = $this->mongodbService->getCollection('animal_clicks');
        $cursor = $collection->find();

        // Formater le résultat pour un usage plus facile dans le template Twig
        $result = [];
        foreach ($cursor as $click) {
            $result[] = [
                'animalPrenom' => $click['prenom'],
                'clickCount' => $click['count']
            ];
        }

        return $result;
    }
}
