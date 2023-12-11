/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'bootstrap/dist/js/bootstrap.bundle';
import './styles/app.scss';


// fetch for add participant


const main = document.querySelector('main');
const url = '/ride/{id}/add';
const fetchParticipant = fetch(url, { method: 'GET', body: JSON.stringify() })
  .then(response => response.json())
  .then(data => {
    main.innerHTML = '';
    
  })


