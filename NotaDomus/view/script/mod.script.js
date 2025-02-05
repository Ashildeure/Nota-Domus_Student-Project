// Récupérer les éléments des pop-ups et des boutons
const signal_button = document.getElementById('signal-button'); //Bouton signalements
const recent_button = document.getElementById('recent-button'); // Boutons recents
const coms_signal = document.getElementById('coms-signal'); // Commentaires signalés
const coms_recent = document.getElementById('coms-recent'); // Commentaires récents

// Récupérer les éléments des pop-ups et des boutons
const popup_removeMod = document.getElementById('popup-removeMod'); // Pop-up supprimer
const closePopup_removeMod = document.getElementById('closePopup-removeMod'); // Boutons ok supprimer


// Ouvrir les coms signalés
signal_button.addEventListener('click', () => {
    coms_signal.style.display = 'flex'; // Afficher les coms signalés
    coms_recent.style.display = 'none'; // Cacher les coms récents
    recent_button.style.opacity = 0.5;
    signal_button.style.opacity = 1;
});

// Ouvrir les coms récents
recent_button.addEventListener('click', () => {
    coms_signal.style.display = 'none'; // Afficher les coms récents
    coms_recent.style.display = 'flex'; // Cacher les coms signalés
    signal_button.style.opacity = 0.5;
    recent_button.style.opacity = 1;
});

// --------------Pop-up ----------------------------------------------------------------------------------
// ------- Option --------------------------------------

const dots_buttons = document.querySelectorAll('[name="dots-button"]'); // Sélectionner tous les boutons

dots_buttons.forEach((button, index) => {
    // Trouver la div '.option' associée au bouton actuel aisni que ses boutons supprimer et signaler
    const optionDiv = button.nextElementSibling;
    const removeButton = optionDiv.querySelector('.remove-button');
    // const reportButton = optionDiv.querySelector('.report-button');


    // Ajouter un gestionnaire d'événements au clic sur chaque bouton
    button.addEventListener('click', (event) => {

        // Vérifier si la div associée est déjà affichée
        if (optionDiv.style.display === 'flex') {
            // Si la div est déjà visible, la masquer
            optionDiv.style.display = 'none';
        } else {
            // Sinon, l'afficher
            optionDiv.style.display = 'flex';
        }
    });
    // ----------------Supprimer ----------------------------------

    // Fermer la div option en cliquant en dehors
    document.addEventListener('click', (event) => {
        if (!button.contains(event.target) && !optionDiv.contains(event.target)) {
            optionDiv.style.display = 'none'; // Masquer l'option si le clic est en dehors
        }
    });
    // Ouvrir le premier pop-up
    removeButton.addEventListener('click', () => {
        popup_removeMod.style.display = 'flex'; // Afficher le premier pop-up
    });

    // Fermer le premier pop-up
    closePopup_removeMod.addEventListener('click', (event) => {
    event.preventDefault(); // Empêcher l'action par défaut
    popup_removeMod.style.display = 'none'; // Cacher le premier pop-up
        document.dispatchEvent(new Event('readyToSubmit'));

    });

    // Fermer le premier pop-up en cliquant en dehors du contenu
    popup_removeMod.addEventListener('click', (event) => {
    if (event.target === popup_removeMod) {
        popup_removeMod.style.display = 'none';
        document.dispatchEvent(new Event('readyToSubmit'));

    }
    });
});


// Sélectionner tous les boutons ayant la classe "remove-button"
document.querySelectorAll('.remove-button').forEach(function (button) {
    button.addEventListener('click', function () {
        // Créer dynamiquement un formulaire
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?ctrl=userMap';

        // Créer un champ caché avec la valeur à supprimer
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'commentToBeDeleted'; // Nom du paramètre envoyé
        input.value = this.getAttribute('data-value'); // Valeur à supprimer

        // Ajouter le champ caché au formulaire
        form.appendChild(input);

        // Ajouter le formulaire au document (dans le body, mais il ne sera pas visible)
        document.body.appendChild(form);

        // Attendre un autre événement avant de soumettre le formulaire
        document.addEventListener('readyToSubmit', function () {
            // Soumettre le formulaire lorsque l'événement personnalisé se produit
            form.submit();
            // Supprimer le formulaire après soumission
            document.body.removeChild(form);
        });
    });
});
document.querySelectorAll('.see-button').forEach(function (button) {
    button.addEventListener('click', function () {
        const valueButton = this.getAttribute('data-value');

        // Créer un formulaire dynamique
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?ctrl=userHouse'; // URL cible

        // Ajouter un champ caché avec la valeur à envoyer
        const hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.name = 'idHouse';
        hiddenField.value = valueButton;

        // Ajouter le champ caché au formulaire
        form.appendChild(hiddenField);

        // Ajouter le formulaire au document et le soumettre
        document.body.appendChild(form);
        form.submit();
    });
});



