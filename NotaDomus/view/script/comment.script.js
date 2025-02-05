// Récupérer les éléments des pop-ups et des boutons
const popupComment = document.getElementById('myPopupComment');
const openPopupComment = document.getElementsByName('openPopupComment');
const closePopupComment = document.getElementById('closePopupComment');
const closePopupComment2 = document.getElementById('closePopupComment2');
const changeContentComment = document.getElementById('changeContentComment');
const popupContentComment = document.getElementById('popupContentComment');
const popupContentComment2 = document.getElementById('popupContentComment2');

// Récupération des éléments pour vérifier que le formulaire de proposition est remplis
const commentText = document.getElementById('commentText');
const verifstar = document.getElementById('verifContentRateComment'); // message erreur note
const verifcommentText = document.getElementById('verifContentTextComment'); // message erreur texte
let canChangeContentComment;

// Récupérer les éléments supplémentaires pour enregistrer les commentaires
let idHouse;

// Vérifier que les éléments sont complets
function checkFormComment() {
    canChangeContentComment = false;
    // Vérification des étoiles
    for (i = 1; i <= 5; i++) {
        if (document.getElementById('starComment' + i).checked) { // Si l'étoile est cochée
            canChangeContentComment = true;
            verifstar.style.opacity = 0;
        }
    }
    if (i = 6 && canChangeContentComment == false) {
        verifstar.style.opacity = 1;
    }
    // Vérification du contenu texte
    if (commentText.value.trim() === "") {
        verifcommentText.style.opacity = 1;
        canChangeContentComment = false;
    } else {
        verifcommentText.style.opacity = 0;
    }
}

// Ouvrir le premier pop-up
openPopupComment.forEach(element => {
    element.addEventListener('click', () => {
        // Enregistrer l'id de la maison
        idHouse = element.getAttribute('data-id');
        // Réinitialiser le contenu
        //// Note
        verifContentRateComment.style.opacity = 0;
        for (i = 1; i <= 5; i++) {
            document.getElementById('starComment' + i).checked = "";
        }
        //// Texte
        verifContentTextComment.style.opacity = 0;
        commentText.value = '';
        // Afficher le premier pop-up
        popupComment.style.display = 'flex';
    });
});

// Fermer le premier pop-up
closePopupComment.addEventListener('click', () => {
    event.preventDefault();
    popupComment.style.display = 'none'; // Cacher le premier pop-up
});

// Fermer le premier pop-up à partir du changement de contenu
closePopupComment2.addEventListener('click', () => {
    event.preventDefault();
    popupContentComment.style.display = '';
    popupContentComment2.style.display = 'none';
    popupComment.style.display = 'none'; // Cacher le premier pop-up
});

// Fermer le premier pop-up en cliquant en dehors du contenu
popupComment.addEventListener('click', (event) => {
    if (event.target === popupComment) {
        popupComment.style.display = 'none';
    }
});

// Changer le contenu du pop-up
changeContentComment.addEventListener('click', (event) => {
    event.preventDefault();
    checkFormComment();
    if (canChangeContentComment) {
        // Récupérer les données
        const commentText = document.getElementById('commentText').value;
        const commentRating = document.querySelector('input[name="starComment"]:checked').value;
        const idUser = document.getElementById('idUser').value;
        // Envoyer les données
        fetch('view/script/comment.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'commentText': commentText,
                'starComment': commentRating,
                'idUser': idUser,
                'idHouse': idHouse
            })
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                popupContentComment.style.display = 'none';
                popupContentComment2.style.display = 'flex';
            })
            .catch(error => console.error('Erreur:', error));
    }
});