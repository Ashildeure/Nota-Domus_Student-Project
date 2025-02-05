<?php
//--- gestion pour l'affichage sans warning
error_reporting(E_ALL & ~E_WARNING); // Affiche tout sauf les warnings
ini_set('display_errors', 1);

//----Import
require_once __DIR__ . '/../../model/house.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search = $_POST['search'] ?? '';
    $housesSugests = House::readLike($search);
    // Génération du HTML des suggestions
    if (!empty($housesSugests)) {
        foreach ($housesSugests as $houseSugest) {
            echo '<button class="name-House-Suggestion">' . htmlspecialchars($houseSugest->getName()) . '</button>';
        }
    } else {
        echo '<p>Aucune suggestion trouvée.</p>';
    }
} else {
    echo '<p>Méthode non autorisée.</p>';
}

