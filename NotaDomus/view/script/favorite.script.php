<?php
include_once('../../model/user.class.php');
include_once('../../model/house.class.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idUser = $_POST['idUser'];
    $idHouse = $_POST['idHouse'];
    $house = House::readHouse($idHouse);
    $user = User::readUserById($idUser);
    if ($user->isInFavorite($house)) {
        $user->deleteFavorite($house);
        echo "Le favoris a été enlevé";
    } else {
        $user->addFavorite($house);
        echo "Le favoris a été ajouté";
    }
    echo "Les favoris ont été traités";
}
?>