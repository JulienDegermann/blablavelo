/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import $ from 'jquery';



// ---------------------------------------------------------------- Desktop menu
$('.profile-menu').hide();
$('.profile, .profile-menu').on('mouseenter', function () {
  $('.profile-menu').show();
});

$('.profile, .profile-menu').on('mouseleave', function () {
  $('.profile-menu').hide();
});
