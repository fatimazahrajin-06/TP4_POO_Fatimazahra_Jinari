<?php

echo "=== Partie 5 - Sérialisation : __serialize() et __unserialize() ===<br><br>";
// avec des notes de révision.
class Utilisateur
{
    public string $nom;
    public int $age;
    private string $motDePasse;

    public function __construct(string $nom, int $age, string $motDePasse)
    {
        $this->nom = $nom;
        $this->age = $age;
        $this->motDePasse = $motDePasse;
    }

    public function __serialize(): array
    {
        return [
            'nom' => $this->nom,
            'age' => $this->age,
            // $motDePasse est volontairement exclu pour des raisons de sécurité
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->nom = $data['nom'];
        $this->age = $data['age'];
        $this->motDePasse = ""; // à réinitialiser
    }

    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }
}

// 5.1 - Exécution et observation
$u = new Utilisateur("Alice", 25, "secret123");
$serialise = serialize($u);

echo "5.1 - Chaîne sérialisée (avec __serialize()) :<br>";
echo $serialise . "<br>";

echo "Le mot de passe y figure-t-il ? " . (str_contains($serialise, "secret123") ? "Oui" : "Non") . "<br><br>";

// 5.2 - On commente __serialize() pour comparer
class UtilisateurSansSerialize
{
    public string $nom;
    public int $age;
    private string $motDePasse;

    public function __construct(string $nom, int $age, string $motDePasse)
    {
        $this->nom = $nom;
        $this->age = $age;
        $this->motDePasse = $motDePasse;
    }

    public function __unserialize(array $data): void
    {
        $this->nom = $data['nom'];
        $this->age = $data['age'];
        $this->motDePasse = "";
    }

    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }
}

$u2 = new UtilisateurSansSerialize("Bob", 30, "admin123");
$serialiseSans = serialize($u2);

echo "5.2 - Chaîne sérialisée sans __serialize() :<br>";
echo $serialiseSans . "<br>";

echo "Le mot de passe apparaît-il dans la chaîne ? " . (str_contains($serialiseSans, "admin123") ? "Oui" : "Non") . "<br>";
echo "Risque : fuite de données sensibles dans un fichier, une session ou un cookie." . "<br><br>";

// 5.3 - Vérification après unserialize()
$restaure = unserialize(serialize($u));

echo "5.3 - Valeur de \$motDePasse après unserialize() :<br>";
var_dump($restaure->getMotDePasse());

//  En vraie application, il faudrait recharger ce secret depuis une source fiable (BDD, serveur, token) ou ne pas le sérialiser du tout.

//5.4 - Réflexion sécurité :
//Jamais appeler unserialize() sur des données provenant de l'utilisateur (cookie, formulaire, URL, upload, réponse réseau...).
//Cela peut mener au PHP Object Injection : des objets malveillants sont reconstruits et peuvent exécuter du code ou provoquer des effets secondaires.
//Alternative sûre : utiliser json_encode() / json_decode() pour échanger des données structurées entre applications.
//Exemple : \$json = json_encode(['nom' => 'Alice', 'age' => 25]); \$donnees = json_decode(\$json, true);

?>