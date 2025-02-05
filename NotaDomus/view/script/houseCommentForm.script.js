// Récupération des éléments pour vérifier que le formulaire de proposition est remplis
const btnsubmitForm = document.getElementsByName('submitForm');
const commentText = document.getElementById('commentText');
const verifstar = document.getElementById('verifContentRateComment'); // message erreur note
const verifcommentText = document.getElementById('verifContentTextComment'); // message erreur texte
const body = document.getElementById('bodyHouse');
let canChangeContentComment;

// Confirmation du commentaire
const popUpComment = document.getElementById('myPopupComment');

/**
 * Vérifie que le formulaire est correctement rempli
 * @returns {boolean}
 */
function checkFormComment() {
    canChangeContentComment = false;
    // Vérification des étoiles
    for (i = 1; i <= 5; i++) {
        if (document.getElementById('star' + i).checked) { // Si l'étoile est cochée
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
    return canChangeContentComment;
}

btnsubmitForm.forEach( element => {
    element.addEventListener('click', (event) => {
        event.preventDefault();
        checkFormComment();
        if (canChangeContentComment) {
            // Récupérer les données
            const commentTextValue = document.getElementById('commentText').value;
            const commentRating = document.querySelector('input[name="star"]:checked').value;
            const idUser = document.getElementById('idUser').value;
            const idHouse = document.getElementById('idHouse').value;
            // Envoyer les données
            fetch('view/script/comment.script.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'commentText': commentTextValue,
                    'starComment': commentRating,
                    'idUser': idUser,
                    'idHouse': idHouse
                })
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                popUpComment.style.display = 'flex';
                for (i = 1; i <= 5; i++) {
                    document.getElementById('star' + i).checked = "";
                }
                commentText.value = '';
                body.style.overflow = '';
            })
            .catch(error => console.error('Erreur:', error));
        }
    });
})



