<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HabitatController extends AbstractController
{
    #[Route('/noshabitats', name: 'habitat')]
    public function index(): Response
    {
        $habitats = [
            ['name' => 'Savane Africaine', 'slug' => 'savane-africaine', 'image' => 'assets/styles/images/habitats/savane.jpg'],
            ['name' => 'Forêt Tropicale', 'slug' => 'foret-tropicale', 'image' => 'assets/styles/images/habitats/foret_tropicale.jfif'],
            ['name' => 'Île', 'slug' => 'ile', 'image' => 'assets/styles/images/habitats/ile.jpg'],
            ['name' => 'Prairies', 'slug' => 'prairies', 'image' => 'assets/styles/images/habitats/prairie.webp'],
            ['name' => 'Désert', 'slug' => 'desert', 'image' => 'assets/styles/images/habitats/desert.jpg'],
            ['name' => 'Forêt Tempérée', 'slug' => 'foret-temperee', 'image' => 'assets/styles/images/habitats/foret_temperee.jfif'],
        ];

        return $this->render('habitat/habitat.html.twig', [
            'habitats' => $habitats,
        ]);
    }

    #[Route('/noshabitats/{slug}', name: 'habitat_name')]
    public function detail(string $slug): Response
    {
        $habitats = [
            'savane-africaine' => ['name' => 'Savane Africaine', 'description' => "Les vastes espaces des savanes du monde se trouvent principalement dans les régions tropicales du globe. Le mot savane vient du mot du XVIe siècle zavanna, qui signifie « plaine sans arbres ». Cependant, le terme est utilisé pour décrire un habitat plus varié, composé de grandes étendues de graminées, souvent d'un ou deux types qui créent un tapis continu, interrompu par des arbustes et des arbres épars.
            Les savanes apparaissent là où il n'y a pas assez de pluie pour soutenir une forêt tropicale, mais suffisamment pour éviter qu'elle ne devienne un désert. Il y a généralement une saison sèche et une saison des pluies dans la savane, avec des vents forts et chauds pendant la saison sèche et suffisamment de pluie pendant la saison humide pour inonder les zones basses. Quelques exemples d'habitats de savane sont les plaines d'Afrique de l'Est, les pampas d'Amérique du Sud et les forêts ouvertes du nord de l'Australie
            La savane abrite de grands troupeaux de faune herbivore et les prédateurs qui les suivent. C'est un écosystème soigneusement équilibré qui peut facilement être perturbé par les changements climatiques, un déséquilibre dans le nombre et le type de faune, et les influences humaines telles que l'agriculture et l'élevage de bétail. Le feu joue également un rôle important dans la savane, brûlant les anciennes herbes et les nouveaux semis d'arbres, laissant place aux nouvelles herbes dont dépendent les herbivores comme les gazelles."
            , 'image' => 'assets/styles/images/habitats/savane.jpg'],
            'foret-tropicale' => ['name' => 'Forêt Tropicale', 'description' => "Les forêts tropicales humides sont des écosystèmes luxuriants situés autour de l'équateur, couvrant moins de 6 % de la surface terrestre mais abritant plus de 50 % des espèces végétales et animales du monde. Elles reçoivent plus de 60 pouces de pluie par an et ont une température constante entre 20 et 28 degrés Celsius. Ces forêts sont vitales pour la biodiversité et la recherche scientifique, avec des espèces encore inconnues et des plantes possédant des propriétés médicinales, comme la pervenche utilisée contre la leucémie infantile.

Chaque jour, nous consommons des produits issus de ces forêts, comme les bananes, le chocolat, et le riz. Cependant, les forêts tropicales sont menacées par la déforestation due aux incendies, à l'exploitation forestière, et aux pratiques agricoles destructrices. Il est crucial de trouver des moyens durables de récolter leurs ressources pour préserver ces trésors biologiques pour l'avenir.", 'image' => 'assets/styles/images/habitats/foret_tropicale.jfif'],
            'ile' => ['name' => 'Île', 'description' =>"Ce que tous les écosystèmes insulaires ont en commun, c'est qu'ils sont isolés du continent et qu'ils créent des environnements spéciaux, voire uniques, avec des espèces animales et végétales qui peuvent être très différentes de leurs homologues continentaux. En fait, certaines espèces ne se trouvent que sur les îles parce qu'elles se sont développées de manière indépendante, comme le kiwi de Nouvelle-Zélande et la tortue des Galápagos des îles Galápagos. Parce qu'ils sont limités en taille et en ressources, les écosystèmes insulaires sont fragiles et facilement perturbés par les espèces introduites et l'activité humaine. Les animaux insulaires peuvent rapidement devenir en danger si leur habitat est détruit ou si leur nourriture disparaît, car ils n'ont nulle part ailleurs où aller." 
            , 'image' => 'assets/styles/images/habitats/ile.jpg'],
            'prairies' => ['name' => 'Prairies Rocheuses', 'description' => "Les prairies d'Amérique du Nord et du Sud ainsi que les steppes d'Asie et d'Australie sont des habitats de prairies qui, contrairement à la savane, subissent de plus grands changements de saison et de température : chaud en été et froid en hiver. Aussi appelés prairies tempérées, ces habitats ont évolué pendant des milliers d'années pour résister au vent, aux tempêtes, aux pluies torrentielles, aux incendies et au pâturage par de grands animaux. Une prairie a généralement des herbes plus hautes qu'une steppe; certaines parties de la prairie sèche à herbe courte des Grandes Plaines d'Amérique du Nord sont également appelées steppes.Il y a une énorme diversité de vie végétale, avec des centaines d'espèces de graminées, d'herbes, de mousses et d'autres plantes dans les prairies et les steppes. Les graminées ont des racines solides, des tiges flexibles capables de stocker des nutriments et divers degrés de tolérance à la sécheresse. Ces graminées maintiennent le fonctionnement de la prairie : lorsqu'une zone est trop pâturée ou cultivée de manière intensive pendant trop longtemps, les graminées disparaissent et la couche arable est vulnérable à l'érosion et peut être emportée par le vent. Il y a aussi des ruisseaux et des cours d'eau qui traversent ces prairies, soutenant les arbres, et des affleurements rocheux qui offrent un abri à la faune.
            Même si la prairie peut sembler désolée, c'est en réalité un habitat fertile et diversifié qui a été connu pour abriter 80 espèces différentes de mammifères et plus de 300 espèces d'oiseaux dans certains endroits."
            , 'image' => 'assets/styles/images/habitats/prairie.webp'],
            'desert' => ['name' => 'Désert', 'description' =>"Les déserts sont des endroits chauds et secs composés principalement de sable, de roches et de montagnes. En général, les déserts sont définis comme des zones où la quantité d'eau qui s'évapore dans l'air est supérieure à la quantité qui tombe au sol sous forme de pluie.
            Dans le désert, les températures peuvent atteindre jusqu'à 120 degrés Fahrenheit (50 degrés Celsius) dans certaines régions ; mais comme il n'y a pas de couverture nuageuse pour retenir la chaleur, les déserts peuvent aussi devenir très froids la nuit, avec des températures plongeant jusqu'à –4 degrés Fahrenheit (–20 degrés Celsius) à certains endroits. Les déserts peuvent également être très venteux, ce qui peut provoquer de violentes tempêtes de sable. Des exemples de déserts sont les déserts du Namib et du Kalahari en Afrique, le désert d'Arabie sur la péninsule arabique, le Grand Désert de Victoria en Australie, et les déserts de Mojave et de Sonora aux États-Unis.
            Même dans ces conditions rigoureuses et inhospitalières, peu de déserts sont complètement stériles. Il y a des plantes et des animaux qui se sont adaptés pour survivre dans cet habitat. Les plantes ont souvent des feuilles fines et résistantes ou des tiges succulentes (comme les cactus) pour stocker l'eau, et certaines restent dormantes pendant les périodes sèches, ne prenant vie que lorsqu'une des rares averses survient. Les animaux survivent dans les déserts en vivant sous terre ou en se reposant dans des terriers pendant la chaleur de la journée. Certaines créatures obtiennent l'humidité dont elles ont besoin à partir de leur nourriture, donc elles n'ont pas besoin de boire beaucoup d'eau, voire pas du tout. D'autres vivent aux abords des déserts, où il y a plus de plantes et d'abris.
            ", 'image' => 'assets/styles/images/habitats/desert.jpg'],
            'foret-temperee' => ['name' => 'Forêt Tempérée', 'description' => "Dans les forêts tempérées, les précipitations sont suffisantes pour permettre aux arbres, arbustes, fleurs, fougères et mousses de prospérer, tout en suivant le rythme des saisons : soleil et températures chaudes en été, et neige et températures froides en hiver. La plupart des forêts tempérées sont constituées d'un mélange d'arbres à feuilles caduques comme le chêne, le hêtre, l'érable, le frêne, le noisetier et le bouleau, ainsi que de quelques conifères et résineux comme le pin, le séquoia, l'épinette et le cèdre.
            La taïga est un type d'habitat forestier parfois appelé forêt boréale, mais elle est beaucoup plus froide et peut rester sous la glace et la neige pendant plus de six mois par an. Les arbres de cette forêt sont principalement des conifères et des résineux, comme l'épicéa, le pin, le sapin et le mélèze.
            Certaines zones de forêt tempérée possèdent des peuplements d'arbres très anciens, tandis que d'autres zones, affectées par des tempêtes, des sécheresses, des glissements de terrain ou des incendies, contiennent des arbres plus jeunes et des espaces plus ouverts comme des clairières ou de petites prairies. Très peu des forêts tempérées de la Terre ont été laissées intactes par l'homme, et d'immenses zones autrefois couvertes de forêts anciennes, dites « forêts primaires », sont maintenant occupées par des villes et des terres agricoles.
            Les interactions entre les arbres, les arbustes et le sous-bois des forêts tempérées et de la taïga sont complexes et dynamiques, car ils réagissent aux changements dans le sol, l'eau, les saisons et le climat. Cela crée un écosystème diversifié contenant plusieurs types de communautés forestières. Le sol riche, les litières de feuilles, les arbres tombés et la forêt vivante fournissent des habitats et des sources de nourriture pour une grande variété d'animaux et d'insectes."
            , 'image' => 'assets/styles/images/habitats/foret_temperee.jfif'],
        ];

        if (!isset($habitats[$slug])) {
            throw $this->createNotFoundException('Habitat not found');
        }

        return $this->render('habitat/habitat_name.html.twig', [
            'habitat' => $habitats[$slug],
        ]);
    }
}
