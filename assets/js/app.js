/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
// import '../css/app.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'js-datepicker/dist/datepicker.min.css';
/*import 'bootstrap-select/dist/css/bootstrap-select.min.css';*/
import 'select2/dist/css/select2.min.css';
import '../css/app.scss';
import "../../public/assetics/vendor/toastr.min.css";
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
//import $ from 'jquery/dist/jquery.min';
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
// Routing.generate('rep_log_list');
let flashinfo = require('./modules/Alertinfos');

import alertify from "alertifyjs";
import "select2/dist/js/select2.full.min";
/*import "bootstrap-select/dist/js/bootstrap-select.min"*/
import toastr from "../../public/assetics/vendor/toastr.min";
import Contact from "./modules/Contact";
import Dictionnairefilters from "./modules/Dictionnairefilters";
import Login from "./modules/Login";
import Register from "./modules/Register";
import "./components/Comments";
import "./components/Likeblogs";
import "./components/Likepages";
import "./components/Likeformations";
import "./components/Likedossiers";


window.alertify = alertify;
window.flashinfo = flashinfo;
window.toastr = toastr;

new Contact()
new Login(document.getElementById('loginForm'), Routing)
new Register(document.getElementById('registerForm'), Routing)
new Dictionnairefilters(document.querySelectorAll('.js-filter-dictionnaires'))

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');

$(".reveal").on('click',function() {
    let $pwd = $(".pwd");
    if ($pwd.attr('type') === 'password') {
        $pwd.attr('type', 'text');
    } else {
        $pwd.attr('type', 'password');
    }
});

/*$('.select2').select2({
    placeholder: 'Sélectionnez une option'
});*/
$('select').select2({
    placeholder: 'Sélectionnez une option'
});
