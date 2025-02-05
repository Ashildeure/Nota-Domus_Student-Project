// Récupérer les éléments des pop-ups et des boutons
const bodyHouse = document.getElementById('bodyHouse');
const popupDonate = document.getElementById('myPopupDonate');
const openPopupDonate = document.getElementById('openPopupDonate');
const closePopupDonate = document.getElementById('closePopupDonate');

// Ouvrir le premier pop-up
openPopupDonate.addEventListener('click', () => {
    bodyHouse.style.overflow = 'hidden';
    popupDonate.style.display = 'flex'; // Afficher le premier pop-up
});

// Fermer le premier pop-up
closePopupDonate.addEventListener('click', () => {
    event.preventDefault();
    bodyHouse.style.overflow = '';
    popupDonate.style.display = 'none'; // Cacher le premier pop-up
});

// Fermer le premier pop-up en cliquant en dehors du contenu
popupDonate.addEventListener('click', (event) => {
    if (event.target === popupDonate) {
        popupDonate.style.display = 'none';
        bodyHouse.style.overflow = '';
    }
});