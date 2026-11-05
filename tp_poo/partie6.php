
<?php

// ==================================================
// PARTIE 6 : HERITAGE EN PHP
// ==================================================

// 6.1 - DECOUVERTE AVEC ANIMAL ET CHIEN

// Classe mère
class Animal {

    // Méthode accessible par les classes filles
    public function manger(): void {
        echo "Je mange." . "<br>";
    }
}

// Classe Chien hérite de Animal
class Chien extends Animal {

    // Méthode propre à Chien
    public function aboyer(): void {
        echo "Wouf !" . "<br>";
    }
}

// Création d'un objet Chien
$c = new Chien();

// Appel de la méthode héritée
$c->manger();

// Appel de la méthode propre à Chien
$c->aboyer();


// Classe Chat hérite également de Animal
class Chat extends Animal {

    // Méthode propre à Chat
    public function miauler(): void {
        echo "Miaou !" . "<br>";
    }
}

$chat = new Chat();

$chat->manger();
$chat->miauler();

// 6.2 - PARENT:: ET REDEFINITION DE METHODE

// Classe mère Personne
class Personne {

    // Constructeur avec propriété protected
    public function __construct(
        protected string $nom
    ) {}

    // Méthode de présentation
    public function presentation(): string {
        return "Je suis " . $this->nom;
    }
}

// Classe fille Etudiant
class Etudiant extends Personne {

    // Constructeur de la classe fille
    public function __construct(
        string $nom,
        private string $filiere
    ) {

        // Appel du constructeur de la classe mère
        parent::__construct($nom);
    }

    // Redéfinition de presentation()
    public function presentation(): string {

        // Réutilisation de la méthode du parent
        return parent::presentation()
            . ", étudiant en " . $this->filiere;
    }
}

$e = new Etudiant("Ahmed", "Informatique");

echo $e->presentation() . "<br>";


// 6.3 - FINAL ET READONLY

class Base {

    // final empêche la redéfinition de cette méthode
    final public function afficher(): void {
        echo "Méthode finale" . "<br>";
    }
}

// final empêche l'héritage de cette classe
final class Outil {

    public const VERSION = "1.0";
}

class Produit {

    // readonly : propriété non modifiable après initialisation
    public function __construct(
        public readonly string $reference
    ) {}
}

$p = new Produit("REF-001");

// Affichage de la référence
echo $p->reference . "<br>";

// Les instructions suivantes provoqueraient des erreurs :
// class Fille extends Base {
//     public function afficher(): void {}
// } **En PHP interdit de la redéfinir dans une classe fille si elle est déclarée final dans la classe mère**.
//
// **Héritage d’une classe finale :**
// class SousOutil extends Outil {} 
//
// $p->reference = "REF-002"; **En PHP, une propriété readonly ne peut pas être modifiée après son initialisation dans le constructeur. Toute tentative de modification après l'initialisation générera une erreur fatale.**



// 6.4 - EXERCICE PRINCIPAL : ETUDIANT EXTENDS PERSONNE


// Classe mère Personne
class PersonneComplete {

    // Attributs protégés accessibles par les classes filles
    protected string $nom;
    protected string $prenom;

    // Attributs privés
    private int $age;
    private string $dateNaissance;

    // Constructeur
    public function __construct(
        string $nom,
        string $prenom,
        int $age,
        string $dateNaissance
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->age = $age;
        $this->dateNaissance = $dateNaissance;
    }

    // Accesseur de l'âge
    protected function getAge(): int {
        return $this->age;
    }

    // Méthode magique pour transformer l'objet en texte
    public function __toString(): string {
        return $this->prenom . " "
            . $this->nom . " ("
            . $this->getAge() . " ans)";
    }
}


// Classe Etudiant hérite de PersonneComplete
class EtudiantComplet extends PersonneComplete {

    // Nouvel attribut privé
    private string $cne;

    // Constructeur de la classe fille
    public function __construct(
        string $nom,
        string $prenom,
        int $age,
        string $dateNaissance,
        string $cne
    ) {

        // Initialiser les attributs de la classe mère
        parent::__construct(
            $nom,
            $prenom,
            $age,
            $dateNaissance
        );

        // Initialiser l'attribut propre à Etudiant
        $this->cne = $cne;
    }

    // Accesseur du CNE
    public function getCne(): string {
        return $this->cne;
    }

    // Redéfinition de __toString()
    public function __toString(): string {

        // Réutiliser l'affichage de PersonneComplete
        // et ajouter le CNE
        return parent::__toString()
            . " | CNE : " . $this->getCne();
    }
}


// CREATION DE 5 ETUDIANTS

// Tableau vide
$etudiants = [];

// Ajout des étudiants
$etudiants[] = new EtudiantComplet(
    "Jinari", "Fatima", 20, "2006-01-15", "CNE001"
);

$etudiants[] = new EtudiantComplet(
    "Amrani", "Sara", 21, "2005-03-12", "CNE002"
);

$etudiants[] = new EtudiantComplet(
    "Alaoui", "Ahmed", 22, "2004-06-20", "CNE003"
);

$etudiants[] = new EtudiantComplet(
    "Karimi", "Youssef", 20, "2006-02-10", "CNE004"
);

$etudiants[] = new EtudiantComplet(
    "Bennani", "Salma", 19, "2007-05-08", "CNE005"
);



// AFFICHAGE AVEC FOREACH

echo "<br>=== Liste des étudiants ===<br>";

foreach ($etudiants as $etudiant) {

    // Appelle automatiquement __toString()
    echo $etudiant . "<br>";
}

?>
