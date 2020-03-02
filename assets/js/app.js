/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
const $ = require('jquery');
global.$ = global.JQuery = $;
import ClipboardJS from 'clipboard';
require('bootstrap');
const fa = require("fontawesome");
require('bootstrap-select');
require('bootstrap-dropdown-hover');
require('./dragndrop.js');
require('./ajax_forms');

$(document).ready(function () {
    console.log("Main js file has been launched");
    initForms();
    initClipboard();
    $('#copy-success').modal();
    $('[data-toggle="dropdown"]').bootstrapDropdownHover({
        // see next for specifications
    });
    $('[data-toggle="tooltip"]').tooltip();
    document.querySelectorAll('.custom-file-input').forEach(element => {
        element.addEventListener("change", evt => {
            var elem = evt.target;
            var label = document.querySelector('label.custom-file-label[for="'+ elem.id +'"]');
            label.innerHTML = getFilename(elem.value);
        });
    });
    /*$('.custom-file-input').on('change', function () {
        var label = document.querySelector("#user_edit > div > div > label");
    });*/
});

function getFilename(fullPath) {
    var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
    var filename = fullPath.substring(startIndex);
    if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
        filename = filename.substring(1);
    }
    return filename;
}

function initClipboard() {
    var clipboard = new ClipboardJS('.btn');
    clipboard.on('success', function (event) {
        $('#copy-info').fadeTo(2000, 500).fadeIn(500, function() {
            $("#copy-info").fadeOut(500);
        });
    });
}

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