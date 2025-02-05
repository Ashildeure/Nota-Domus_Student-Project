//---Ajouter Ami ----------------------------------------------------------------------------------------------------------

// Récupérer les éléments des pop-ups et des boutons
const addFriend = document.getElementById('addFriend'); //Bouton
const popup_addFriend = document.getElementById('popup-addFriend'); // pop-up 1
const empty_name = document.getElementById('empty-name'); // Texte protection contre champ vide
const changeContent_addFriend = document.getElementById('changeContent-addFriend'); // Entrer pop-up1
const closePopup_addFriend = document.getElementById('closePopup-addFriend'); // Annuler pop-up1
const popup_sendFriend = document.getElementById('popup-sendFriend'); //pop-up2
const closePopup_sendFriend = document.getElementById('closePopup-sendFriend'); // Ok pop-up2
const friend_SuggestionDiv = document.getElementById('friend-Suggestion');//div qui affiche les sugestion de login
// le champ de texte à compléter
const nameFriend = document.getElementById('nameFriend');
let canChangeContent;

// Récupérer les bonnes infos
var idUser;
var loginNewFriend;

// Vérifier que les éléments sont complets
function checkForm() {
    canChangeContent = true;
    if (nameFriend.value.trim() === "") {
        canChangeContent = false;
    }
}

// Ouvrir le premier pop-up
addFriend.addEventListener('click', () => {
    idUser = addFriend.getAttribute('user-id');
    nameFriend.value = '';
    popup_addFriend.style.display = 'flex'; // Afficher le premier pop-up
});

//------Gerer la recherche d'user
// Variable pour stocker le timeout en cours
let debounceTimeout;
nameFriend.addEventListener('keydown', function (event) {
    // Annule le dernier timeout si l'utilisateur continue de taper
    // Cela évite d'exécuter la requête trop souvent
    clearTimeout(debounceTimeout);

    // Crée un nouveau timeout pour exécuter la requête après une courte pause
    debounceTimeout = setTimeout(() => {
        // Récupère la valeur actuelle du champ de saisie
        const search = nameFriend.value;

        // Envoie une requête POST au serveur avec la recherche
        fetch('view/script/friendSearch.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'search': search // Envoie la valeur de recherche
            })
        })
            .then(response => {
                // Vérifie si la réponse est valide
                if (!response.ok) {
                    throw new Error('Erreur réseau : ' + response.statusText);
                }
                return response.text(); // Convertit la réponse en texte
            })
            .then(data => {
                // Met à jour le contenu de la div avec les résultats
                friend_SuggestionDiv.innerHTML = data;

                // Ajout des gestionnaires d'événements après la mise à jour
                // je suis obliger de le faire la car avant les btn  n'etais pas cree
                const suggestionButtons = document.querySelectorAll('.login-friend-Suggestion');
                suggestionButtons.forEach(button => {
                    button.addEventListener('click', event => {
                        event.preventDefault();
                        const value = button.textContent;
                        nameFriend.value = value; // Remplit le champ input avec la valeur
                    });
                });
            })
            .catch(error => {
                // Affiche une erreur en cas de problème avec la requête
                console.error('Erreur lors de la requête :', error);
                friend_SuggestionDiv.innerHTML = '<p>Une erreur est survenue. Veuillez réessayer.</p>';
            });
    }, 300); // Définit une pause de 300 ms avant d'exécuter la requête
});

// Fermer le premier pop-up
closePopup_addFriend.addEventListener('click', () => {
    event.preventDefault();
    popup_addFriend.style.display = 'none'; // Cacher le premier pop-up
});

// Fermer le premier pop-up en cliquant en dehors du contenu
popup_addFriend.addEventListener('click', (event) => {
    if (event.target === popup_addFriend) {
        popup_addFriend.style.display = 'none';
    }
});

// Ouvrir le second pop-up
changeContent_addFriend.addEventListener('click', () => {
    event.preventDefault();
    empty_name.style.opacity = 0;
    checkForm();
    if (canChangeContent) {
        // Récupérer les informations
        loginNewFriend = nameFriend.value;
        // Envoyer les données
        fetch('view/script/friendButton.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'idUser': idUser,
                'loginFriend': loginNewFriend,
                'action': 'addFriend'
            })
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);  
            popup_addFriend.style.display = 'none';
            popup_sendFriend.style.display = 'flex';
        })
        .catch(error => console.error('Erreur:', error));
    } else {
        empty_name.style.opacity = 1;
    }
});

// Fermer le deuxime pop-up
closePopup_sendFriend.addEventListener('click', () => {
    event.preventDefault();
    popup_sendFriend.style.display = 'none'; // Cacher le premier pop-up
});

// Fermer le deuxieme pop-up en cliquant en dehors du contenu
popup_sendFriend.addEventListener('click', (event) => {
    if (event.target === popup_sendFriend) {
        popup_sendFriend.style.display = 'none';
    }
});

//---Retirer Ami ----------------------------------------------------------------------------------------------------------

// Récupérer les éléments des pop-ups et des boutons
const aFriend = document.getElementsByName('aFriend'); // On recupere le bouton ami
const removeFriend = document.getElementsByName('removeFriend'); // On recupere le bouton retirer ami associé
const popup_sureRemove = document.getElementById('popup-sureRemove'); // pop-up 1
const changeContent_sureRemove = document.getElementById('changeContent-sureRemove'); // Entrer pop-up1
const closePopup_sureRemove = document.getElementById('closePopup-sureRemove'); // Annuler pop-up1
const popup_removeFriend = document.getElementById('popup-removeFriend'); //pop-up2
const closePopup_removeFriend = document.getElementById('closePopup-removeFriend'); // Ok pop-up2

// Récupérer les données
var idFriend;

// Faire apparaitre le bouton retirer ami

aFriend.forEach((friend, index) => {
    // Trouver le bouton removeFriend associé
    const removeButton = friend.nextElementSibling; // Ajustez cette sélection en fonction de votre structure DOM

    // Ajouter un gestionnaire d'événements au clic sur aFriend
    friend.addEventListener('click', () => {
        if (removeButton.style.display != 'flex') {
            removeButton.style.display = 'flex';
        } else {
            removeButton.style.display = 'none';
        }
    });
    // Ouvrir le premier pop-up
    removeButton.addEventListener('click', () => {
        idUser = removeButton.getAttribute('user-id');
        idFriend = removeButton.getAttribute('friend-id');
        popup_sureRemove.style.display = 'flex';
    });
    //Cacher le bouton retirer ami en cliquant en dehors du contenu
    document.addEventListener('click', (event) => {
        if (!friend.contains(event.target) && !removeButton.contains(event.target)) {
            // Si le clic est en dehors de aFriend et de removeButton, on masque le bouton
            removeButton.style.display = 'none';
        }
    });
});


// Fermer le premier pop-up
closePopup_sureRemove.addEventListener('click', () => {
    event.preventDefault();
    popup_sureRemove.style.display = 'none'; // Cacher le premier pop-up

});

// Fermer le premier pop-up en cliquant en dehors du contenu
popup_sureRemove.addEventListener('click', (event) => {
    if (event.target === popup_sureRemove) {
        popup_sureRemove.style.display = 'none';
    }
});

// Ouvrir le second pop-up
changeContent_sureRemove.addEventListener('click', () => {
    event.preventDefault();
    // Envoyer les données
    fetch('view/script/friendButton.script.php', {
        method: 'POST',
        body: new URLSearchParams({
            'idUser': idUser,
            'idFriend': idFriend,
            'action': 'deleteFriend'
        })
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);  
        popup_sureRemove.style.display = 'none';
        popup_removeFriend.style.display = 'flex';
        aFriend.forEach(friend => {
            const removeButtonFriend = friend.nextElementSibling;
            if(removeButtonFriend.getAttribute('friend-id') == idFriend) {
                friend.style.display = 'none';
                removeButtonFriend.style.display = 'none';
            }
        })
    })
    .catch(error => console.error('Erreur:', error));
});

// Fermer le deuxieme pop-up
closePopup_removeFriend.addEventListener('click', () => {
    event.preventDefault();
    popup_removeFriend.style.display = 'none'; // Cacher le premier pop-up

});

// Fermer le deuxieme pop-up en cliquant en dehors du contenu
popup_removeFriend.addEventListener('click', (event) => {
    if (event.target === popup_removeFriend) {
        popup_removeFriend.style.display = 'none';

    }
});

// -- Afficher les commentaires ----------------------

// Récupérer les éléments des pop-ups et des boutons
const myCom_button = document.getElementById('myCom-button'); //Bouton Vous
const friend_button = document.getElementById('friend-button'); // Boutons Vos amis
const myComs_list = document.getElementById('myComs-list'); // Mes commentaires 
const friendsComs_list = document.getElementById('friendsComs-list'); // Commentaires des amis


// Ouvrir les coms signalés
myCom_button.addEventListener('click', () => {
    myComs_list.style.display = 'flex'; // Afficher les coms signalés
    friendsComs_list.style.display = 'none'; // Cacher les coms récents
    friend_button.style.opacity=0.5;
    myCom_button.style.opacity=1;
  });

// Ouvrir les coms récents
friend_button.addEventListener('click', () => {
    myComs_list.style.display = 'none'; // Afficher les coms récents
    friendsComs_list.style.display = 'flex'; // Cacher les coms signalés
    myCom_button.style.opacity=0.5;
    friend_button.style.opacity=1;
  });

