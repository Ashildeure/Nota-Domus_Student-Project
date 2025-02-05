// Récupérer les éléments des pop-ups et des boutons
const favoritesMap = document.getElementsByName('favoriteMap');
const favoritesFilter = document.getElementsByName('favoriteFilter');
const heartsMap = document.getElementsByName('heartMap');

// Ajouter un favoris
favoritesMap.forEach(favoriteMap => {
    favoriteMap.addEventListener('click', (event) => {
        // Récupérer les données
        idUser = favoriteMap.getAttribute('user-id');
        idHouse = favoriteMap.getAttribute('house-id');
        // Envoyer les données
        fetch('view/script/favorite.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'idUser': idUser,
                'idHouse': idHouse
            })
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => console.error('Erreur:', error));
        // Colorer le coeur de la carte
        favoritesFilter.forEach(favoriteFilter => {
            if(favoriteFilter.getAttribute('house-id') === idHouse) {
                if(favoriteMap.checked === "true") {
                    favoriteFilter.checked = "false";
                } else {
                    favoriteFilter.checked = "true";
                }
            }
        })
    })
})

favoritesFilter.forEach(favoriteFilter => {
    favoriteFilter.addEventListener('click', (event) => {
        // Récupérer les données
        idUser = favoriteFilter.getAttribute('user-id');
        idHouse = favoriteFilter.getAttribute('house-id');
        // Envoyer les données
        fetch('view/script/favorite.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'idUser': idUser,
                'idHouse': idHouse
            })
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => console.error('Erreur:', error));
        // Colorer le coeur de la carte
        favoritesMap.forEach(favoriteMap => {
            if(favoriteMap.getAttribute('house-id') === idHouse) {
                if(favoriteFilter.checked === true) {
                    favoriteMap.checked = true;
                } else {
                    favoriteMap.checked = false;
                }
            }
        })
    })
})