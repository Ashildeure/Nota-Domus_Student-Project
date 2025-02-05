const thumbsUp = Array.from(document.getElementsByClassName('btnUp'));
const thumbsDown = Array.from(document.getElementsByClassName('btnDown'));

thumbsUp.forEach(thumbUp => {
    thumbUp.addEventListener('click', (event) => {
        // TODO: Supprimer cette ligne si les likes ont des soucis en front-end :
        Array.from(document.querySelector('[for="' + thumbUp.id + '"]').children)[1].textContent++;
        // Récupérer les données
        idComment = thumbUp.getAttribute('comment-id');
        // Envoyer les données
        fetch('view/script/like.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'idComment': idComment,
                'thumbUp': true
            })
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => console.error('Erreur:', error));
        // notifs.forEach(notif => {
        //     if (notif.getAttribute('notif-id') === idNotif) {
        //         const likeCountElement = notif.querySelector('.like-count');
        //         if (likeCountElement) {
        //             const currentLikes = parseInt(likeCountElement.textContent, 10) || 0;
        //             likeCountElement.textContent = currentLikes + 1;
        //         }
        //     }
        // });
    })
})

thumbsDown.forEach(thumbDown => {
    thumbDown.addEventListener('click', (event) => {
        // TODO: Supprimer cette ligne si les dislikes ont des soucis en front-end :
        Array.from(document.querySelector('[for="' + thumbDown.id + '"]').children)[1].textContent++;
        // Récupérer les données
        idComment = thumbDown.getAttribute('comment-id');
        // Envoyer les données
        fetch('view/script/like.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'idComment': idComment,
                'thumbUp': false
            })
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => console.error('Erreur:', error));
        // notifs.forEach(notif => {
        //     if (notif.getAttribute('notif-id') === idNotif) {
        //         const likeCountElement = notif.querySelector('.like-count');
        //         if (likeCountElement) {
        //             const currentLikes = parseInt(likeCountElement.textContent, 10) || 0;
        //             likeCountElement.textContent = currentLikes + 1;
        //         }
        //     }
        // });
    })
})