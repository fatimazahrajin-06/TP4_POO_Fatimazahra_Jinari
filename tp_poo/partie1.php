<?php

class Personne {

    // Attributs publics
    public string $nom;
    public string $prenom;

    // Attributs privés
    private string $dateNaissance;
    private string $villeNaissance;
    private string $cin;

    // Constructeur
    public function __construct(
        string $nom,
        string $prenom,
        string $dateNaissance,
        string $villeNaissance,
        string $cin
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaissance = $dateNaissance;
        $this->villeNaissance = $villeNaissance;
        $this->cin = $cin;
    }

    // Retourne le prénom et le nom
    public function getName(): string {
        return $this->prenom . " " . $this->nom;
    }

    // Calcule l'âge
    private function getAge(): int {
        $naissance = new DateTime(datetime: $this->dateNaissance);
        $aujourdhui = new DateTime();

        return $naissance->diff(targetObject: $aujourdhui)->y;
    }
}

// Programme principal
$p = new Personne(
    nom: "Hachimi",
    prenom: "Sara",
    dateNaissance: "2000-05-14",
    villeNaissance: "Marrakech",
    cin: "EE123456"
);

echo $p->getName() . PHP_EOL;
echo $p->nom . PHP_EOL;

// Expérimentation
//echo $p->cin; echo $p->dateNaissance; echo $p->getAge();
// 1. On remarque que les attributs et la méthode suivantes sont privés, Donc, depuis l’extérieur de la classe, on ne peut pas les lire ou les appeler directement.
// 3. Non, elle n’est pas inutile. Une méthode privée est souvent utilisée comme “méthode interne” à la classe, Elle peut être appelée par une autre méthode de la même classe
// <?php
// public function afficherAge(): string {
//      return $this->getAge() . " ans";}
// 4. Non, le comportement depuis l’extérieur reste le même 
?>