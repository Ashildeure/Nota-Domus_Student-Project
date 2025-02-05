// Récupérer les éléments des pop-ups et des boutons
const popupNotif = document.getElementById('myPopupNotif');
const openClosePopupNotif = document.getElementsByName('openPopupNotif');
const notifs = document.querySelectorAll('[name="notif"]');
const closeNotifications = document.querySelectorAll('[name="closeNotif"]');
const addFriends = document.getElementsByName('addFriend');
let idNotif;

// Ouvrir le premier pop-up
openClosePopupNotif.forEach(element => {
    element.addEventListener('click', () => {
        if (popupNotif.style.display === 'flex') {
            popupNotif.style.display = 'none';
        } else {
            popupNotif.style.display = 'flex'; // Afficher le premier pop-up
        }
    });
    
})

// Fermer le premier pop-up en cliquant en dehors du contenu
popupNotif.addEventListener('click', (event) => {
    if (event.target === popupNotif) {
        popupNotif.style.display = 'none';
    }
});

// Fermer les notifs
closeNotifications.forEach(closeBtn => {
    closeBtn.addEventListener('click', (event) => {
        // Récupérer les données
        const idNotif = closeBtn.getAttribute('notif-id');
        console.log(idNotif);
        // Envoyer les données
        fetch('view/script/notif.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'idNotif': idNotif,
                'newFriend': false
            })
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
        })
        .catch(error => console.error('Erreur:', error));
        notifs.forEach(notif => {
            if (notif.getAttribute('notif-id') === idNotif) {
                notif.style.display = 'none';
            }
        })
    })
})

// Ajouter un ami
addFriends.forEach(element => {
    element.addEventListener('click', (event) => {
        // Récupérer les données
        idNotif = element.getAttribute('notif-id');
        // Envoyer les données
        fetch('view/script/notif.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'idNotif': idNotif,
                'newFriend': true
            })
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
        })
        .catch(error => console.error('Erreur:', error));
        notifs.forEach(notif => {
            if (notif.getAttribute('notif-id') === idNotif) {
                notif.style.display = 'none';
            }
        })
    })
})