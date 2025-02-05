// -- Parametres de la maison -----------------------------------------------------------
// Favoris
// Récupérer les éléments des pop-ups et des boutons
const favorite = document.getElementsByName('favorite');

// Ajouter un favoris
favorite.forEach( element => {
    element.addEventListener('click', (event) => {
        // Récupérer les données
        idUser = element.getAttribute('user-id');
        idHouse = element.getAttribute('house-id');
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
    })
})

// Récupérer les éléments des pop-ups et des boutons
const setting_button = document.getElementById('house-setting'); //Bouton setting
const popup_setting = document.getElementById('popup-house-setting'); // Popup setting

// Ouvrir et fermer le pop-up setting
setting_button.addEventListener('click', (event) => {
    event.preventDefault();
    event.stopPropagation(); // Empêche la propagation au gestionnaire global
    if (popup_setting.style.display != 'flex') {
        popup_setting.style.display = 'flex';
    } // Cacher le premier pop-up
    else {
        popup_setting.style.display = 'none';
    }

});
// Fermer le popup lorsque l'utilisateur clique en dehors de celui-ci
document.addEventListener('click', (event) => {
    // Vérifier si le clic n'est ni sur le bouton ni sur le popup
    if (
        !popup_setting.contains(event.target) && // Le clic n'est pas à l'intérieur du popup
        event.target !== setting_button // Le clic n'est pas sur le bouton
    ) {
        popup_setting.style.display = 'none';
    }
});

// -- Modifier la maison ------------------------------
// Popup pour modifier
const btn_modify = document.getElementsByName('modify');
const popup_modify_myHouse = document.getElementById('popup-modify-myHouse'); // Pop-up modifier
const closePopup_cancelModify_myHouse = document.getElementById('closePopup-cancelModify-myHouse'); // Bouton annuler
const closePopup_updateModify_myHouse = document.getElementById('closePopup-updateModify-myHouse'); // bouton enregistrer
const popup_modifyDone_myCom = document.getElementById('popup-modifyDone-myCom'); // Pop-up modification effectuée
const closePopup_modifyDone_myCom = document.getElementById('closePopup-modifyDone-myCom'); // bouton ok

// Variables pour remplir les champs du pop-up modify
const houseContentModify = document.getElementById('popup-houseText');
var idHouse;
var houseContent;

// Ouvrir le premier pop-up
btn_modify.forEach( element => {
    element.addEventListener('click', () => {
        idHouse = element.getAttribute('house-id');
        // Récupérer les anciennes valeurs
        houseContent = element.getAttribute('house-content');
        // Afficher les anciennes valeurs dans le pop-up
        houseContentModify.value = houseContent;
        popup_modify_myHouse.style.display = 'flex'; // Afficher le premier pop-up
    });
})
        
// Fermer le premier pop-up
closePopup_cancelModify_myHouse.addEventListener('click', (event) => {
    event.preventDefault();
    popup_modify_myHouse.style.display = 'none'; // Cacher le premier pop-up
});
// Fermer le premier pop-up en cliquant en dehors du contenu
popup_modify_myHouse.addEventListener('click', (event) => {
    if (event.target === popup_modify_myHouse) {
        popup_modify_myHouse.style.display = 'none';
    }
});

// Ouvrir deuxieme premier pop-up
closePopup_updateModify_myHouse.addEventListener('click', () => {
    // Récupérer les nouvelles valeurs
    houseContent = houseContentModify.value;
    // Envoyer les données
    fetch('view/script/houseSettings.script.php', {
        method: 'POST',
        body: new URLSearchParams({
            'idComment': 0,
            'idHouse': idHouse,
            'houseContent': houseContent,
            'action': 'modifyHouse'
        })
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        popup_modify_myHouse.style.display = 'none';
        popup_modifyDone_myCom.style.display = 'flex'; // Afficher le premier pop-up
    })
    .catch(error => console.error('Erreur:', error));
});

// Fermer le deuxieme pop-up en cliquant en dehors du contenu
popup_modifyDone_myCom.addEventListener('click', (event) => {
    if (event.target === popup_modifyDone_myCom) {
        popup_modifyDone_myCom.style.display = 'none';
    }
});
// -- Parametres des commentaires -------------------------------------------------------

const comment_setting = document.getElementsByName('comment-setting'); //Bouton options pour colonne autres
const commentFriend_setting = document.querySelectorAll('.commentFriend-setting'); //Bouton options pour colonne amis

// Popups de signalement
const popup_signalMod_com = document.getElementById('popup-signalMod-com'); // Pop-up formulaire signalemet
const closePopup_signalMod_com = document.getElementById('closePopup-signalMod-com'); // Bouton annuler
const changeContent_signalMod_com = document.getElementById('changeContent-signalMod-com'); // Bouton envoyer
const popup_thx_signalMod_com = document.getElementById('popup-thx-signalMod-com'); // Pop-up remerciement signalement
const closePopup_thx_signalMod_com = document.getElementById('closePopup-thx-signalMod-com'); // Bouton ok

// Popup pour ajouter un ami
const popup_info_addFriend_com = document.getElementById('popup-info-addFriend-com'); // Pop-up ami
const closePopup_info_addFriend_com = document.getElementById('closePopup-info-addFriend-com') // Bouton ok

// Popup pour supprimer
const popup_delete_myCom = document.getElementById('popup-delete-myCom'); // Pop-up supprimer
const closePopup_cancel_myCom = document.getElementById('closePopup-cancel-myCom'); // Bouton annuler
const closePopup_delete_myCom = document.getElementById('closePopup-delete-myCom'); // bouton supprimer
const popup_deleteDone_myCom = document.getElementById('popup-deleteDone-myCom'); // Pop-up suppression effectuée
const closePopup_deleteDone_myCom = document.getElementById('closePopup-deleteDone-myCom'); // bouton ok

// Popup pour modifier
const popup_modify_myCom = document.getElementById('popup-modify-myCom'); // Pop-up modifier
const closePopup_cancelModify_myCom = document.getElementById('closePopup-cancelModify-myCom'); // Bouton annuler
const closePopup_updateModify_myCom = document.getElementById('closePopup-updateModify-myCom'); // bouton enregistrer

//Verification modifications
const popup_commentText = document.getElementById('popup-commentText'); // champ texte modif commentaire
let canChangeContent;

// Données pour récupérer le bon commentaire
const comments = document.getElementsByName('comment');
let idComment;
let idUser;

// Vérifier que les éléments sont complets
function checkForm() {
    canChangeContent = true;
    if (popup_commentText.value.trim() === "") {
        canChangeContent = false;
    }
    ;
}

// -- Commentaires des amis ------------------------------

commentFriend_setting.forEach(button => {
    // Trouver la div '.comment_setting' associée au bouton actuel ainsi que ses boutons supprimer et signaler
    const friendComment_setting = button.nextElementSibling; // div pop-up signaler
    const friendComment_signal = friendComment_setting.querySelector('.friendComment-signal'); //Bouton signaler

    // Ajouter un gestionnaire d'événements au clic sur chaque bouton
    button.addEventListener('click', (event) => {
        event.stopPropagation();
        // Vérifier si la div associée est déjà affichée
        if (friendComment_setting.style.display === 'flex') {
            // Si la div est déjà visible, la masquer
            friendComment_setting.style.display = 'none';
        } else {
            // Sinon, l'afficher
            friendComment_setting.style.display = 'flex';
        }
    });
    // Fermer la div option en cliquant en dehors
    document.addEventListener('click', (event) => {
        if (!button.contains(event.target) && !friendComment_setting.contains(event.target)) {
            friendComment_setting.style.display = 'none'; // Masquer l'option si le clic est en dehors
        }
    });
// ------- Signaler ------------------------------------

// Ouvrir le premier pop-up
    friendComment_signal.addEventListener('click', () => {
        var content = document.getElementById('explication_report_com');
        content.value = "";
        idComment = friendComment_signal.getAttribute('comment-id');
        popup_signalMod_com.style.display = 'flex'; // Afficher le premier pop-up
    });

    // Fermer le premier pop-up
    closePopup_signalMod_com.addEventListener('click', (event) => {
        event.preventDefault();
        popup_signalMod_com.style.display = 'none'; // Cacher le premier pop-up
    });
    // Fermer le premier pop-up en cliquant en dehors du contenu
    popup_signalMod_com.addEventListener('click', (event) => {
        if (event.target === popup_signalMod_com) {
            popup_signalMod_com.style.display = 'none';
        }
    });
    // Fomulaire

    // Fermer le premier pop-up à partir du changement de contenu
    changeContent_signalMod_com.addEventListener('click', (event) => {
        event.preventDefault();
        // Récupérer la raison du signalement
        var selectElmt = document.getElementById('categorie_signalement_com');
        var choice = selectElmt.selectedIndex;
	    var categorie = selectElmt.options[choice].text;
        var content = document.getElementById('explication_report_com').value;
        idUser = document.getElementById('friendComment-signal').getAttribute('flagger-id');
        // Envoyer les données
        fetch('view/script/houseSettings.script.php', {
            method: 'POST',
            body: new URLSearchParams({
                'idComment': idComment,
                'idFlagger': idUser,
                'categorie': categorie,
                'content': content,
                'action': 'report'
            })
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            popup_thx_signalMod_com.style.display = 'flex';
            popup_signalMod_com.style.display = 'none';
        })
        .catch(error => console.error('Erreur:', error));
    });

    // Fermer le deuxieme pop-up
    closePopup_thx_signalMod_com.addEventListener('click', (event) => {
        event.preventDefault();
        popup_thx_signalMod_com.style.display = 'none'; // Cacher le premier pop-up
        friendComment_setting.style.display = 'none';
    });
    // Fermer le deuxieme pop-up en cliquant en dehors du contenu
    popup_thx_signalMod_com.addEventListener('click', (event) => {
        if (event.target === popup_thx_signalMod_com) {
            popup_thx_signalMod_com.style.display = 'none';
            friendComment_setting.style.display = 'none';
        }
    });
});

// -- Commentaires des autres ------------------------------

comment_setting.forEach(button => {
    // Trouver la div '.comment_setting' associée au bouton actuel ainsi que ses boutons supprimer et signaler

    const myComment_setting = button.nextElementSibling; // div pop-up mon commentaire ou other commentaire
    const myComment_btn1 = myComment_setting.firstElementChild; // Bouton supprimer ou signaler
    const myComment_btn2 = myComment_btn1.nextElementSibling; // Bouton modifier ou ajouter ami

    // Ajouter un gestionnaire d'événements au clic sur chaque bouton
    button.addEventListener('click', (event) => {
        event.stopPropagation();

        // Vérifier si la div associée est déjà affichée
        if (myComment_setting.style.display === 'flex') {
            // Si la div est déjà visible, la masquer
            myComment_setting.style.display = 'none';
        } else {
            // Sinon, l'afficher
            myComment_setting.style.display = 'flex';
        }
    });

    // Fermer la div option en cliquant en dehors
    document.addEventListener('click', (event) => {
        if (!button.contains(event.target) && !myComment_setting.contains(event.target)) {
            myComment_setting.style.display = 'none'; // Masquer l'option si le clic est en dehors
        }
    });

    if(myComment_setting.classList.contains("otherComment-setting")){
        // -------Signaler ------------------------------------
        // Ouvrir le premier pop-up
        myComment_btn1.addEventListener('click', () => {
            var content = document.getElementById('explication_report_com');
            content.value = "";
            idComment = myComment_btn1.getAttribute('comment-id');
            popup_signalMod_com.style.display = 'flex'; // Afficher le premier pop-up
        });

        // Fermer le premier pop-up
        closePopup_signalMod_com.addEventListener('click', (event) => {
            event.preventDefault();
            popup_signalMod_com.style.display = 'none'; // Cacher le premier pop-up
        });
        // Fermer le premier pop-up en cliquant en dehors du contenu
        popup_signalMod_com.addEventListener('click', (event) => {
            if (event.target === popup_signalMod_com) {
                popup_signalMod_com.style.display = 'none';
            }
        });
        // Fomulaire

        // Fermer le premier pop-up à partir du changement de contenu
        changeContent_signalMod_com.addEventListener('click', (event) => {
            event.preventDefault();
            // Récupérer la raison du signalement
	        var selectElmt = document.getElementById('categorie_signalement_com');
            var choice = selectElmt.selectedIndex;
	        var categorie = selectElmt.options[choice].text;
            var content = document.getElementById('explication_report_com').value;
            idUser = document.getElementById('otherComment-signal').getAttribute('flagger-id');
            // Envoyer les données
            fetch('view/script/houseSettings.script.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'idComment': idComment,
                    'idFlagger': idUser,
                    'categorie': categorie,
                    'content': content,
                    'action': 'report'
                })
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                popup_thx_signalMod_com.style.display = 'flex';
                popup_signalMod_com.style.display = 'none';
            })
            .catch(error => console.error('Erreur:', error));
        });

        // Fermer le deuxieme pop-up
        closePopup_thx_signalMod_com.addEventListener('click', (event) => {
            event.preventDefault();
            popup_thx_signalMod_com.style.display = 'none'; // Cacher le premier pop-up
            myComment_setting.style.display = 'none';
        });
        // Fermer le deuxieme pop-up en cliquant en dehors du contenu
        popup_thx_signalMod_com.addEventListener('click', (event) => {
            if (event.target === popup_thx_signalMod_com) {
                popup_thx_signalMod_com.style.display = 'none';
                myComment_setting.style.display = 'none';
            }
        });

        // -------Ajouter en ami ------------------------------------

        // Ouvrir le pop-up de confirmation
        myComment_btn2.addEventListener('click', () => {
            // Récupérer les informations
            idComment = myComment_btn2.getAttribute('comment-id');
            idUser = myComment_btn2.getAttribute('user-id');
            // Envoyer les données
            fetch('view/script/houseSettings.script.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'idComment': idComment,
                    'idUser': idUser,
                    'action': 'addFriend'
                })
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);  
                popup_info_addFriend_com.style.display = 'flex'; // Afficher le pop-up de confirmation
            })
            .catch(error => console.error('Erreur:', error));
        })
        // Fermer le pop-up
        closePopup_info_addFriend_com.addEventListener('click', (event) => {
            event.preventDefault();
            popup_info_addFriend_com.style.display = 'none'; // Cacher le premier pop-up
        });
        // Fermer le pop-up en cliquant en dehors du contenu
        popup_info_addFriend_com.addEventListener('click', (event) => {
            if (event.target === popup_info_addFriend_com) {
                popup_info_addFriend_com.style.display = 'none';
            }
        });

    }else{
        // -------Supprimer ------------------------------------

        // Ouvrir le premier pop-up
        myComment_btn1.addEventListener('click', () => {
            idComment = myComment_btn1.getAttribute('comment-id');
            popup_delete_myCom.style.display = 'flex'; // Afficher le premier pop-up
        });

        // Fermer le premier pop-up
        closePopup_cancel_myCom.addEventListener('click', (event) => {
            event.preventDefault();
            popup_delete_myCom.style.display = 'none'; // Cacher le premier pop-up
        });
        // Fermer le premier pop-up en cliquant en dehors du contenu
        popup_delete_myCom.addEventListener('click', (event) => {
            if (event.target === popup_delete_myCom) {
                popup_delete_myCom.style.display = 'none';
            }
        });
        // Ouvrir deuxieme premier pop-up
        closePopup_delete_myCom.addEventListener('click', () => {
            // Envoyer les données
            fetch('view/script/houseSettings.script.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'idComment': idComment,
                    'action': 'delete'
                })
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);  
                comments.forEach(comment => {
                    if(comment.getAttribute('comment-id') === idComment) { // cacher le commentaire si il a été supprimé
                        comment.style.display = 'none';
                    }
                })
                popup_delete_myCom.style.display = 'none';
                popup_deleteDone_myCom.style.display = 'flex'; // Afficher le premier pop-up
            })
            .catch(error => console.error('Erreur:', error));
        });

        // Fermer le deuxieme pop-up
        closePopup_deleteDone_myCom.addEventListener('click', (event) => {
            event.preventDefault();
            popup_deleteDone_myCom.style.display = 'none'; // Cacher le premier pop-up
        });
        // Fermer le deuxieme pop-up en cliquant en dehors du contenu
        popup_deleteDone_myCom.addEventListener('click', (event) => {
            if (event.target === popup_deleteDone_myCom) {
                popup_deleteDone_myCom.style.display = 'none';
            }
        });

        // -------Modifier ------------------------------------
        // Variables pour remplir les champs du pop-up modify
        const commentContentModify = document.getElementById('popup-commentText');
        var commentContent;
        var commentRate;

        // Ouvrir le premier pop-up
        myComment_btn2.addEventListener('click', () => {
            idComment = myComment_btn2.getAttribute('comment-id');
            // Récupérer les anciennes valeurs
            commentContent = myComment_btn2.getAttribute('comment-content');
            commentRate = myComment_btn2.getAttribute('comment-rate');
            const commentRateInt = Math.floor(parseFloat(commentRate));
            // Afficher les anciennes valeurs dans le pop-up
            commentContentModify.value = commentContent;
            const commentRateModify = document.getElementById('starModify'+commentRateInt); // on va chercher la bonne étoile
            commentRateModify.checked = true;
            popup_modify_myCom.style.display = 'flex'; // Afficher le premier pop-up
        });

        // Fermer le premier pop-up
        closePopup_cancelModify_myCom.addEventListener('click', (event) => {
            event.preventDefault();
            popup_modify_myCom.style.display = 'none'; // Cacher le premier pop-up
        });
        // Fermer le premier pop-up en cliquant en dehors du contenu
        popup_modify_myCom.addEventListener('click', (event) => {
            if (event.target === popup_modify_myCom) {
                popup_modify_myCom.style.display = 'none';
            }
        });
        // Ouvrir deuxieme premier pop-up
        closePopup_updateModify_myCom.addEventListener('click', () => {
            // Récupérer les nouvelles valeurs
            commentContent = commentContentModify.value;
            commentRate = document.querySelector('input[name="starModify"]:checked').value;
            // Envoyer les données
            fetch('view/script/houseSettings.script.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'idComment': idComment,
                    'commentContent': commentContent,
                    'commentRate': commentRate,
                    'action': 'modify'
                })
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                popup_modify_myCom.style.display = 'none';
                popup_modifyDone_myCom.style.display = 'flex'; // Afficher le premier pop-up
            })
            .catch(error => console.error('Erreur:', error));
        });

        // Fermer le deuxieme pop-up en cliquant en dehors du contenu
        popup_modifyDone_myCom.addEventListener('click', (event) => {
            if (event.target === popup_modifyDone_myCom) {
                popup_modifyDone_myCom.style.display = 'none';
            }
        });
    }

});


  

