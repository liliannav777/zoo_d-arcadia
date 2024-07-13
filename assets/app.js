/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import 'bootstrap';

console.log("js fonctionnel");

document.addEventListener('DOMContentLoaded', function () {
  const animalModal = document.getElementById('animalModal');
  const closeBtn = document.querySelector('.close-btn');

  document.querySelectorAll('.animal-image').forEach(img => {
      img.addEventListener('click', function () {
          // Récupère les données de l'image cliquée
          const image = this.getAttribute('data-image');
          const prenom = this.getAttribute('data-prenom');
          const race = this.getAttribute('data-race');
          const etat = this.getAttribute('data-etat');
          const details = this.getAttribute('data-details');
          const rapportsJson = this.getAttribute('data-rapports');

          // Parse les données JSON des rapports vétérinaires
          const rapports = JSON.parse(rapportsJson);

          // Met à jour le contenu de la modal
          document.getElementById('modalImage').src = image;
          document.getElementById('animalPrenom').textContent = prenom;
          document.getElementById('animalRace').textContent = race;
          document.getElementById('animalEtat').textContent = etat;

          // Affiche les détails des rapports vétérinaires
          const rapportDetails = document.getElementById('rapportDetails');
          rapportDetails.innerHTML = ''; // Réinitialise les détails des rapports
          if (rapports.length > 0) {
              rapports.forEach(rapport => {
                  const li = document.createElement('li');
                  li.textContent = `Date: ${rapport.date}, Nourriture: ${rapport.nourriture}, Grammage: ${rapport.grammage}, Détails: ${rapport.detailsEtat || 'Aucun détail disponible'}`;
                  rapportDetails.appendChild(li);
              });
          } else {
              rapportDetails.innerHTML = '<li>Aucun rapport vétérinaire disponible.</li>';
          }

          // Affiche la modal
          animalModal.style.display = 'block';
          console.log("js fonctionnel");
      });
  });

  closeBtn.addEventListener('click', function () {
      animalModal.style.display = 'none';
  });

  document.querySelector('.modal-overlay').addEventListener('click', function () {
      animalModal.style.display = 'none';
  });
});

