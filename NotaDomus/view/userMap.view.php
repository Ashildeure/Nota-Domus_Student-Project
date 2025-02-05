<?php
require_once 'navBar.view.php';
require_once 'filter.view.php';
?>
<!-- Vue des utilisateurs et des visiteurs -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/design/styleMap.css">
    <link rel="icon" href="../public/design/img/logo.png">
    <title>NotaDomus</title>
</head>
<body>

<!-- Carte -->
<gmp-map id="map" class="map" center="47.2822678,2.4633453" zoom="6.4" map-id="DEMO_MAP_ID">
    <!-- Affichage des marqueurs sur la carte -->
    <?php
    $indiceMaison = 1;
    foreach ($housesFilter as $house) {
        ?>
        <!-- Début squelette marqueur -->
        <gmp-advanced-marker
                position="<?= $house->getLocation()->getLatitude() ?>,<?= $house->getLocation()->getLongitude() ?>">
            <div class="marker" name="marker" geo-id="<?= $house->getId() ?>" position="<?= $house->getLocation()->getLatitude() ?>,<?= $house->getLocation()->getLongitude() ?>"">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#731702" class="bi bi-geo-alt-fill"
                     viewBox="0 0 16 16">
                    <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                </svg>
                <div class="linkHouse">
                    <form action="?ctrl=userHouse" method="POST">
                        <input value="<?= $house->getId() ?>" name="idHouse" hidden>
                        <button type="submit"><?= $house->getName() ?></button>
                    </form>
                </div>
            </div>
        </gmp-advanced-marker>
        <!-- Fin squelette marqueur -->
        <?php
        $indiceMaison++;
    }
    ?>
</gmp-map>
<!-- Propositions -->
<input type="checkbox" id="closePH" hidden>
<div class="propositionHouse">
    <label for="closePH" class="cross-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-chevron-down"
             viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                  d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
             class="bi bi-chevron-up hidden-cross" viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                  d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708z"/>
        </svg>
    </label>
    <span class="bubble">
        <p>N'hésitez pas à nous proposer de nouvelles maisons !</p>
        <div class="triangle"></div>
    </span>
    <span>
        <button id="openPopupProposition">+ Je propose !</button>
        <img src="../public/design/img/jean-patrique.png" alt="jp" width="30%">
    </span>
</div>
<div class="popupProposition" id="myPopupProposition">
    <div class="popupContentProposition" id="popupContentProposition">
        <form method="post">
            <h2>Proposez une nouvelle maison d'illustre</h2>
            <label for="nameOh">Nom de la maison* :</label>
            <input name="nameOh" id="nameOh" type="text">
            <p class="verifContentProposition" id="verifContentNameOhProposition">Veuillez renseigner le nom de la
                maison</p>
            <label for="nameOutstanding">Nom de l'illustre* :</label>
            <input name="nameOutstanding" id="nameOutstanding" type="text">
            <p class="verifContentProposition" id="verifContentNameOutstandingProposition">Veuillez renseigner le nom de
                l'illustre</p>
            <label for="adressOh">Adresse de la maison* :</label>
            <input name="adressOh" id="adressOh" type="text">
            <p class="verifContentProposition" id="verifContentAdressOhProposition">Veuillez renseigner l'adresse de la
                maison</p>
            <label for="choice">Expliquez-nous ce choix* :</label>
            <textarea name="choice" id="choice" rows="5"></textarea>
            <p class="verifContentProposition" id="verifContentChoiceProposition">Veuillez renseigner une
                explication</p>
            <span>
                    <button id="closePopupProposition">Annuler</button>
                    <button type="button" name="send" id="changeContentProposition">Envoyer</button>
                </span>
        </form>
    </div>
    <div class="popupContentProposition2" id="popupContentProposition2">
        <p>Votre proposition a bien été envoyée</p>
        <button id="closePopupProposition2">OK</button>
    </div>
</div>
</body>
<!-- Script API -->
<script
        src="https://maps.googleapis.com/maps/api/js?key=secret&loading=async&libraries=maps,marker&v=beta"
        defer>
</script>
<!-- Script Pop-up -->
<script src="view/script/propositions.script.js"></script>
<!-- Script Affichage carte -->
<script src="view/script/markerMap.script.js"></script>
<!-- Script Favoris -->
<script src="view/script/favorite.script.js"></script>
</html>
<?php
if ($user !== null && $user->getRole() == "moderator") {
    include_once __DIR__ . '/../controller/mod.ctrl.php';
} else {
    include_once __DIR__ . '/../controller/friend.ctrl.php';
}
?>

