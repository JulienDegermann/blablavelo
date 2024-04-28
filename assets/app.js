/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'bootstrap/dist/js/bootstrap.bundle';
import './styles/app.scss';

const ranges = document.querySelectorAll('input[type="range"]');  

ranges.forEach(range => {
    const value = document.createElement('span');
    value.classList.add('range-value');
    value.textContent = range.value;
    range.parentElement.appendChild(value);

    range.addEventListener('input', () => {
        value.textContent = range.value;
    });
});

const menuButton = document.querySelector('#menu-button');
const profileMenu = document.querySelector('#profile-menu')

menuButton.addEventListener('click', () => {
    console.log('click');
    profileMenu.classList.toggle('active');
})

const filterToggler = document.querySelector('#filter-toggler');
const filter = document.querySelector('#filter');
filterToggler.addEventListener('click', () => {
    console.log('click');
    filter.classList.toggle('d-none');
    filter.classList.toggle('h-100');
    filter.classList.contains('d-none') ? filterToggler.textContent = 'Afficher les filtres' : filterToggler.textContent = 'Masquer les filtres';
})