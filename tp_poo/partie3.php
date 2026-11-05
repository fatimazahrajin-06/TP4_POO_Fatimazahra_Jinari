<?php

// ==========================================================
// Partie 3 - Surcharge de propriétés : __get, __set, __isset, __unset
// ==========================================================
// Les méthodes magiques sont appelées automatiquement par PHP lors de
// l'accès à une propriété inaccessible.
//
// - __set($name, $value) : déclenchée lors d'une écriture comme $obj->prop = "x"
// - __get($name) : déclenchée lors d'une lecture comme echo $obj->prop
// - __isset($name) : déclenchée lors d'un isset($obj->prop)
// - __unset($name) : déclenchée lors d'un unset($obj->prop)
//
// Ces méthodes permettent d'encapsuler les propriétés et de contrôler
// l'accès depuis l'extérieur de la classe.

class Personne {
    // Toutes les propriétés sont en private, donc l'extérieur ne peut pas y accéder directement.
    private string $nom = "";
    private string $prenom = "";
    private string $dateNaissance = "";

    // ------------------------------------------------------
    // __set : appelée quand on écrit une propriété inaccessible
    // ------------------------------------------------------
    // Exemple : $p->nom = "Hachimi";
    // Ici, comme $nom est private, PHP appelle __set automatiquement.
    public function __set(string $name, mixed $value): void {
        // On vérifie que la propriété demandée existe dans la classe.
        // Si elle n'existe pas, on affiche un message explicite.
        if (!property_exists($this, $name)) {
            echo "Propriété {$name} inexistante"."<br>";
            return;
        }

        // Validation de la propriété "nom" :
        // - la valeur ne doit pas être vide,
        // - et elle doit contenir au moins 2 caractères.
        if ($name === "nom") {
            $texte = trim((string) $value);

            if ($texte === "") {
                throw new InvalidArgumentException("Le nom ne peut pas être vide.");
            }

            if (strlen($texte) < 2) {
                throw new InvalidArgumentException("Le nom doit contenir au moins 2 caractères.");
            }
        }

        // Si la propriété existe et la valeur est valide, on lui affecte la valeur.
        $this->$name = $value;
    }

    // ------------------------------------------------------
    // __get : appelée quand on lit une propriété inaccessible
    // ------------------------------------------------------
    // Exemple : echo $p->nom;
    public function __get(string $name): mixed {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        echo "Propriété {$name} inexistante"."<br>";
        return null;
    }

    // ------------------------------------------------------
    // __isset : appelée lors d'un isset($obj->prop) (verifie une variable si existe et sa valeur n'est pas vide)
    // ------------------------------------------------------
    // On retourne true si la propriété existe et si sa valeur n'est pas vide.
    public function __isset(string $name): bool {
        if (!property_exists($this, $name)) {
            return false;
        }

        return $this->$name !== "";
    }

    // ------------------------------------------------------
    // __unset : appelée lors d'un unset($obj->prop)
    // ------------------------------------------------------
    // On remet la propriété à une chaîne vide pour simuler la suppression.
    public function __unset(string $name): void {
        if (property_exists($this, $name)) {
            $this->$name = "";
        } else {
            echo "Propriété {$name} inexistante"."<br>";
        }
    }
}

// ==========================================================
// Programme principal - tests de fonctionnement
// ==========================================================

$p = new Personne();

// 1) Ecriture d'une propriété private : __set est appelée.
$p->nom = "Hachimi";

// 2) Lecture de la propriété private : __get est appelée.
echo "$p->nom<br>";

// 3) isset() appelle __isset.
var_dump(isset($p->nom));

echo "--- Test de unset ---<br>";
unset($p->nom);
var_dump(isset($p->nom));

echo "--- Test de propriété inexistante ---<br>";
$p->telephone = "0600000000"; // __set est déclenché, mais la propriété n'existe pas.

echo "--- Test de validation du nom ---<br>";
try {
    $p->nom = "A"; // trop court : doit déclencher une exception
    echo "Nom accepté<br>";
} catch (InvalidArgumentException $e) {
    echo "Erreur : " . $e->getMessage() . "<br>";
}

// ==========================================================
// Réponses aux questions en commentaire
// ==========================================================
// 3.1 : Les quatre méthodes, Elles permettent de gérer
//      les accès aux propriétés privées/inexistantes proprement.
//
// 3.2 : Sans __set, si la propriété est private, PHP déclenche une erreur fatale
//      de type "Cannot access private property" lors de l'écriture $p->nom = "X".
//      Avec __set, l'écriture est interceptée par la méthode magique et on peut la traiter.
//
// 3.3 : __set peut casser l'encapsulation si on n'applique pas de validation.
//      Il faut contrôler les données avant d'affecter les propriétés.
//      Exemple : refuser une valeur vide ou trop courte, ou vérifier un type.
//
// 3.4 : La validation a été ajoutée dans __set :
//      - nom ne doit pas être vide,
//      - longueur minimum = 2 caractères,
//      - sinon on lance InvalidArgumentException.

?>
