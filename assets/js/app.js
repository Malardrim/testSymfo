/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
const $ = require('jquery');
global.$ = global.JQuery = $;

require('bootstrap');
const fa = require("fontawesome");
require('bootstrap-select');
require('./dragndrop.js');
require('./ajax_forms');

$(document).ready(function () {
    console.log("Main js file has been launched");
    initForms();
    $('[data-toggle="tooltip"]').tooltip();
    document.querySelectorAll('ul').forEach(function (node) {
        node.addEventListener('click', function (elem) {
            elem.querySelectorAll('li').style.display = "none";
        });
    })
});

function initForms() {
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
}