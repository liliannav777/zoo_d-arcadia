
document.addEventListener('DOMContentLoaded', function () {
    const modals = document.querySelectorAll('.modal');
    const closeButtons = document.querySelectorAll('.close-btn');
    const overlay = document.querySelectorAll('.modal-overlay');

    document.querySelectorAll('[data-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.getAttribute('data-target');
            document.getElementById(modalId).style.display = 'block';
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', function () {
            this.closest('.modal').style.display = 'none';
        });
    });

    overlay.forEach(over => {
        over.addEventListener('click', function () {
            this.closest('.modal').style.display = 'none';
        });
    });
});

function handleClick(animalPrenom) {
    fetch(`?animalPrenom=${animalPrenom}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => console.log('Click enregistré:', data))
    .catch(error => console.error('Erreur:', error));
}

document.addEventListener('DOMContentLoaded', () => {
    const burgerMenu = document.querySelector('.burger-menu');
    const navbar = document.querySelector('.navbar');

    burgerMenu.addEventListener('click', () => {
        burgerMenu.classList.toggle('open');
        navbar.classList.toggle('open');
    });
});
