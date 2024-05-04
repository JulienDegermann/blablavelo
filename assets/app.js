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
    showMin.innerText = inputMin.value
    showMax.innerText = inputMax.value

    // for each range, create a slider with 2 cursors and set the cursors position
    range.append(slider);
    slider.append(sliderMin, sliderMax);
    sliderMin.style.left = inputMin.value / inputMin.max * 100 + '%';
    sliderMax.style.left = inputMax.value / inputMax.max * 100 + '%';

    // position x min & max of the slider + slider width
    const start = slider.getBoundingClientRect().left;
    const end = slider.getBoundingClientRect().right;
    const sliderWidth = slider.getBoundingClientRect().width;


    // function for track mouse then move MIN cursor and set minValue to input
    const moveMin = (e) => {
        // get back newPos
        const touch = e.touches
        const mouseX = e.touches ? e.touches[0].clientX : e.clientX
        const maxPos = sliderMax.getBoundingClientRect().left

        let newPos;
        if (mouseX >= maxPos) {
            console.log('un')
            newPos = maxPos;
        }
        else if (mouseX < start) {
            console.log('deux')
            newPos = start;
        }
        else if (mouseX > end) {
            console.log('trois')
            newPos = end;
        }
        else {
            console.log('quatre')
            newPos = mouseX;
        }

        console.log(
            `curseur : ${mouseX}
            start : ${start}
            end : ${end}
            newPos : ${newPos}
            width : ${sliderWidth}`
        )

        sliderMin.style.left = (newPos - start) * 100 / sliderWidth + '%';
        const newValue = parseInt(inputMin.min) + ((newPos - start) / (end - start)) * (inputMin.max - inputMin.min);
        inputMin.value = Math.floor(newValue);
        showMin.innerText = Math.floor(inputMin.value);
    }

    // function for track mouse then move MAX cursor and set maxValue to input
    const moveMax = (e) => {

        // get back newPos
        const touch = e.touches

        const mouseX = e.touches ? e.touches[0].clientX : e.clientX
        const minPos = sliderMin.getBoundingClientRect().left

        let newPos;
        if (mouseX <= minPos) {
            newPos = minPos;
        }
        else if (mouseX < start) {
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
    sliderMin.addEventListener('touchstart', () => {
        document.addEventListener('touchmove', moveMin)
    });

    sliderMax.addEventListener('mousedown', (e) => {
        document.addEventListener('mousemove', moveMax);
    });
    sliderMax.addEventListener('touchstart', (e) => {
        document.addEventListener('touchmove', moveMax);
    });

    document.addEventListener('mouseup', () => {
        document.removeEventListener('mousemove', moveMin);
        document.removeEventListener('mousemove', moveMax);
    });

    document.addEventListener('touchend', () => {
        document.removeEventListener('touchmove', moveMin);
        document.removeEventListener('touchmove', moveMax);
    });


})

// ONLY ON MOBILE : load page with filters, then "click" on hide filter (used to enable slide working)
if (window.screen.width < 1000) {
    document.addEventListener('DOMContentLoaded', () => {
        filterToggler.click()
    })
}