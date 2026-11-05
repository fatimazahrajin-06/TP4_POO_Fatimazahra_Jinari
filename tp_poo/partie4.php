<?php
// TP POO - Partie 4 : Méthodes magiques __toString() avec revision de cours
// ------------------------------------------------------------
class Personne {
    public string $nom;
    public string $prenom;
    private string $dateNaissance;

    public function __construct(string $nom, string $prenom, string $dateNaissance) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaissance = $dateNaissance;
    }

    private function getAge(): int {
        $naissance = new DateTime($this->dateNaissance);
        $aujourdhui = new DateTime();

        return $naissance->diff($aujourdhui)->y;
    }

    public function __toString(): string { //__toString() est une méthode magique qui permet de transformer un objet en chaîne de caractères (string)
        return $this->prenom . " " . $this->nom . " (" . $this->getAge() . " ans)";
    }
}

$p = new Personne("Hachimi", "Sara", "2000-05-14");

echo $p . "<br>";

// 4.1 - Pourquoi getAge() private fonctionne dans __toString() ?
// Parce que __toString() est une méthode de la même classe.
// En PHP, les membres privés sont accessibles depuis l'intérieur de la classe,
// même si on appelle la méthode depuis l'extérieur de l'objet.
// L'accès privé est bloqué pour le code externe, pas pour le code interne.

// 4.2 - Si __toString() retournait un entier au lieu d'une chaîne ?
// En PHP standard (sans strict_types=1), l'entier est converti automatiquement
// en chaîne lors de l'affichage :
$bad = new class {
    public function __toString(): string {
        return 42;
    }
};

echo $bad . "<br>";

// Avec declare(strict_types=1);, PHP lancerait une TypeError :
// "Return value must be of type string, int returned"

// 4.3 - Intérêt de __toString() par rapport à getName() :
// 1) Permet d'afficher directement un objet dans echo, print, concaténation, etc.
// 2) Utile pour le logging / debug / génération de messages sans devoir appeler
//    une méthode spécifique à chaque fois.
// Exemple : echo $personne; // affiche "Sara Hachimi (26 ans)"
