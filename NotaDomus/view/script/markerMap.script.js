// Récupérer les éléments des pop-ups et des boutons
const gmap = document.getElementById('map');// carte
const markers = document.getElementsByName('marker');
const markersFilter = document.getElementsByName('markerFilter');
const svgFilter = document.getElementsByName('svgFilter');
let idHouseMap;

// Régler l'affichage


//--- Marker sur la carte
markers.forEach(marker => {
    marker.addEventListener('click', () => {
        idHouseMap = marker.getAttribute('geo-id');
        markers.forEach(marker => { // On affiche tous les autres marker
            marker.firstElementChild.style.fill = '#731702';
        });
        svgFilter.forEach(svg => {
            if (svg.getAttribute('house-id') === idHouseMap) {
                svg.firstElementChild.style.fill = 'red';
            } else {
                svg.firstElementChild.style.fill = '#731702';
            }
        })
        // Trouver la bonne div 'house' et la centrer
        houses = document.getElementById('houses');
        house = houses ? houses.querySelector(`[house-id="${idHouseMap}"]`) : null;
        if (houses && house) {
            // Calculer la position de la house par rapport à houses
            const housePosition = house.offsetTop - houses.offsetTop;
            const visibleHeight = houses.clientHeight;
            const totalHeight = houses.scrollHeight;
            // Vérifier si c'est possible de centrer la div
            if (totalHeight > visibleHeight) {
                const scrollToPosition = housePosition - visibleHeight / 2 + house.clientHeight / 2;
                // Ajuster le défilement avec un comportement fluide
                houses.scrollTo({
                    top: Math.max(0, Math.min(scrollToPosition, totalHeight - visibleHeight)),
                    behavior: 'smooth'
                });
            } else {
                houses.scrollTop = 0;
            }
        }
        marker.firstElementChild.style.fill = 'red';
    });
});

//--- Marker dans les filtre
markersFilter.forEach(markerFilter => {
    markerFilter.addEventListener('click', () => {
        idHouseMap = markerFilter.getAttribute('geo-id');
        svgFilter.forEach(svg => {
            if (svg.getAttribute('house-id') === idHouseMap) {
                svg.firstElementChild.style.fill = 'red';
            } else {
                svg.firstElementChild.style.fill = '#731702';
            }
        })
        markers.forEach(marker => {
            if (marker.getAttribute('geo-id') === idHouseMap) {
                marker.firstElementChild.style.fill = 'red';
                // change la vue de la carte
                gmap.setAttribute("center",marker.getAttribute("position"));
                gmap.setAttribute("zoom","10");
            } else {
                marker.firstElementChild.style.fill = '#731702';
            }
        });
    })
});