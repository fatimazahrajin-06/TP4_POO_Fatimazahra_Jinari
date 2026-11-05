<?php
// ---------------------------------------------
// Partie 2 : Constructeurs et destructeur
// ---------------------------------------------
// revision :
// Le constructeur (__construct) est appelé automatiquement lors de l'instanciation d'un objet avec new.
// Le destructeur (__destruct) est appelé quand l'objet n'est plus utilisé, lorsque unset() est appelé,
// ou à la fin du script si le garbage collector détruit encore l'objet.

echo "Partie 2 - Constructeurs et destructeur<br>";

echo "2.1 Constructeur avec valeurs par défaut<br>";

// Une classe peut avoir un constructeur avec des paramètres optionnels.
// Cela permet de créer des objets sans donner toutes les informations chaque fois.
// On utilise les valeurs par défaut quand l'utilisateur ne fournit pas de données.
class Voiture {
    // En PHP, on peut directement déclarer les propriétés dans la signature du constructeur.
    // C'est la notion de promotion de propriétés : on évite d'écrire les propriétés en dehors,
    // puis de les initialiser dans le corps du constructeur.
    public function __construct(
        public string $couleur = "Blanche",
        public int $vitesse = 0
    ) {
        // Le constructeur ne contient ici aucun code supplémentaire, car les propriétés sont déjà
        // déclarées et initialisées automatiquement avec les arguments passés ou les valeurs par défaut.
    }

    public function afficher(): void {
        echo "Couleur : {$this->couleur} | Vitesse : {$this->vitesse} km/h<br>";
    }
}

// 1) Aucun argument : on prend les valeurs par défaut.
$v1 = new Voiture();

// 2) On donne les deux valeurs : la couleur et la vitesse.
$v2 = new Voiture("Rouge", 50);

// 3) On donne seulement la couleur ; la vitesse garde 0 car elle a une valeur par défaut.
$v3 = new Voiture("Noire");

// 4) Ici on passe un entier dans un paramètre de type string.
// PHP tente une conversion automatique (coercition) car il n'y a pas de strict_types.
// Le nombre 120 devient la chaîne "120".
$v4 = new Voiture(120);

$v1->afficher();
$v2->afficher();
$v3->afficher();
$v4->afficher();

//Explication :
//- new Voiture("Noire") => OK : la couleur est "Noire" et la vitesse reste 0.;
//- new Voiture(120) => OK dans le mode standard PHP (coercition), car le nombre 120 est converti en chaîne "120" pour le paramètre string. Si vous ajoutez declare(strict_types=1);, cela devient une erreur de type.;

// ------------------------------------------------
// 2.2 - Le piège des deux constructeurs
// ------------------------------------------------
// En PHP, une classe ne peut avoir qu'un seul __construct().
// Déclarer deux constructeurs avec des signatures différentes ne fonctionne pas :
// PHP garde seulement la dernière définition, ou plus exactement le langage n'autorise pas cette surcharge.
// La solution consiste à utiliser un seul constructeur avec des paramètres optionnels.

echo "\n--- 2.2 Le piège des deux constructeurs ---<br>";

class Personne {
    public string $nom;
    public string $prenom;

    // Ce constructeur accepte 0, 1 ou 2 arguments.
    // Si aucun argument n'est fourni, les valeurs par défaut sont utilisées.
    // C'est pourquoi on peut créer une personne vide ou une personne complète avec un seul constructeur.
    public function __construct(string $nom = "", string $prenom = "") {
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    // Méthode statique de fabrique : il permet de créer un objet à partir d'un tableau associatif.
    // C'est utile quand les données viennent d'un formulaire, d'une base ou d'un fichier JSON.
    public static function depuisTableau(array $data): self {
        return new self(
            $data["nom"] ?? "",
            $data["prenom"] ?? ""
        );
    }

    // Le destructeur est appelé automatiquement quand l'objet est détruit.
    // Le mot-clé __CLASS__ donne le nom de la classe courante.
    public function __destruct() {
        echo "Détruire " . __CLASS__ . "<br>";
    }

    public function afficher(): void {
        echo "Personne : {$this->prenom} {$this->nom}<br>";
    }
}

// On crée une personne sans données : le constructeur reçoit des chaînes vides.
$pVide = new Personne();

// On crée une personne avec ses informations : nom et prénom sont initialisés.
$pInfo = new Personne("Martin", "Alice");

$pVide->afficher();
$pInfo->afficher();

//Explication :
//- PHP n'autorise qu'un seul __construct() pour une classe. Les deux définitions sont écrasées et la dernière remplace la précédente.
//- Solution : utiliser des paramètres optionnels (valeurs par défaut).
//- Bonus : méthode statique de fabrique 'depuisTableau' pour créer avec une structure de données.

// Ici, on montre un exemple de création à partir d'un tableau associatif.
// Cela évite de devoir passer 2 arguments individuellement dans le constructeur.
$personneDepuisTableau = Personne::depuisTableau([
    "nom" => "Dupont",
    "prenom" => "Jean"
]);
$personneDepuisTableau->afficher();

// ------------------------------------------------
// 2.3 - Le destructeur
// ------------------------------------------------
// __destruct() est appelé quand l'objet est libéré de la mémoire.
// Cela peut arriver :
// - lorsqu'on appelle unset($objet),
// - ou quand le script termine et que les objets restants sont détruits.

echo "\n--- 2.3 Le destructeur ---<br>";

class VoitureDestructible {
    // Le constructeur affiche un message quand on instancie l'objet.
    public function __construct(public string $couleur) {
        echo "Voiture {$this->couleur} créée.<br>";
    }

    // Le destructeur affiche un message quand l'objet est détruit.
    public function __destruct() {
        echo "Voiture {$this->couleur} détruite.<br>";
    }
}

echo "=== 2.3.a - avec unset ===<br>";
// On crée l'objet, puis on le supprime manuellement.
// Comme on appelle unset(), le destructeur est déclenché immédiatement.
$maVoiture = new VoitureDestructible("Blanche");
unset($maVoiture);
echo "Fin du script.<br>";

echo "\n=== 2.3.b - sans unset ===<br>";
// Ici, on ne supprime pas l'objet.
// Il reste en mémoire tant que le script continue.
// À la fin du script, PHP le détruit automatiquement.
$autreVoiture = new VoitureDestructible("Noire");
echo "Fin du script (sans unset)<br>";

echo "\n=== 2.3.c - deux objets sans unset ===<br>";
// Deux objets créés dans l'ordre : d'abord $a, puis $b.
// Quand le script se termine, PHP détruit les objets dans l'ordre inverse de création.
// C'est la logique de pile : le dernier créé est détruit en premier.
$a = new VoitureDestructible("Rouge");
$b = new VoitureDestructible("Bleue");
echo "Fin du script pour les deux objets.<br>";
