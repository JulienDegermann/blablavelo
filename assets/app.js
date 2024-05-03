/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'bootstrap/dist/js/bootstrap.bundle';
import './styles/app.scss';

const ranges = document.querySelectorAll('.range');

const menuButton = document.querySelector('#menu-button');
const profileMenu = document.querySelector('#profile-menu')

menuButton.addEventListener('click', () => {
    profileMenu.classList.toggle('active');
})

const filterToggler = document.querySelector('#filter-toggler');
const filter = document.querySelector('#filter');
filterToggler.addEventListener('click', () => {
    filter.classList.toggle('d-none');
    filter.classList.toggle('h-100');
    filter.classList.contains('d-none') ? filterToggler.textContent = 'Afficher les filtres' : filterToggler.textContent = 'Masquer les filtres';
})


ranges.forEach(range => {
    // get min and max input of each range
    const inputMin = Array.from(range.children).find(child => child.classList.contains('min'));
    const inputMax = Array.from(range.children).find(child => child.classList.contains('max'));

    // create slider and its cursors
    const slider = document.createElement('div');
    const sliderMin = document.createElement('div');
    const sliderMax = document.createElement('div');
    slider.classList.add('slider');
    sliderMin.classList.add('slider-min');
    sliderMax.classList.add('slider-max');


    const showMin = document.createElement('div');
    const showMax = document.createElement('div');
    sliderMin.append(showMin);
    sliderMax.append(showMax);

    // for each range, create a slider with 2 cursors and set the cursors position
    range.append(slider);
    slider.append(sliderMin, sliderMax);
    sliderMin.style.left = inputMin.value / inputMin.max * 100 + '%';
    sliderMax.style.left = inputMax.value / inputMax.max * 100 + '%';



    // position x min & max of the slider + slider width
    const start = slider.getBoundingClientRect().left;
    const end = slider.getBoundingClientRect().right;
    const sliderWidth = slider.getBoundingClientRect().width;



    // move slider
    const mouseTrack = (e) => {
        // mouse position
        mouseX = e.clientX;

        // offset mouse vs left border of slider
        const xPos = (mouseX - start);

        // block the slider within the slider
        let newPos;
        if (mouseX < start) {
            newPos = start;
        }
        else if (mouseX > end) {
            newPos = end;
        }
        else {
            newPos = mouseX;
        }

        // e.target.style.left = newPos * 100 / sliderWidth + '%';

        return newPos;
    }

    // function for track mouse then move MIN cursor and set minValue to input
    const moveMin = (e) => {

        // get back newPos
        const mouseX = e.clientX

        let newPos;
        if (mouseX < start) {
            newPos = start;
        }
        else if (mouseX > end) {
            newPos = end;
        }
        else {
            newPos = mouseX;
        }

        sliderMin.style.left = (newPos - start) * 100 / sliderWidth + '%';
        const newValue = parseInt(inputMin.min) + ((newPos - start) / (end - start)) * (inputMin.max - inputMin.min);
        inputMin.value = Math.floor(newValue);
        showMin.innerText = Math.floor(inputMin.value);
    }

    // function for track mouse then move MAX cursor and set maxValue to input
    const moveMax = (e) => {

        // get back newPos
        const mouseX = e.clientX

        let newPos;
        if (mouseX < start) {
            newPos = start;
        }
        else if (mouseX > end) {
            newPos = end;
        }
        else {
            newPos = mouseX;
        }

        sliderMax.style.left = (newPos - start) * 100 / sliderWidth + '%';
        const newValue = parseInt(inputMax.min) + ((newPos - start) / (end - start)) * (inputMax.max - inputMax.min);
        inputMax.value = Math.floor(newValue);
        showMax.innerText = Math.floor(inputMax.value);
    }

    // functions to activate mousetrack on min nore max cursor press (check if works on touch)
    sliderMin.addEventListener('mousedown', () => {
        document.addEventListener('mousemove', moveMin)
    });

    sliderMax.addEventListener('mousedown', (e) => {
        newPos = document.addEventListener('mousemove', moveMax);
    });

    document.addEventListener('mouseup', () => {
        document.removeEventListener('mousemove', moveMin);
        document.removeEventListener('mousemove', moveMax);
    });
})