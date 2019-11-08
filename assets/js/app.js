/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
const $ = require('jquery');

global.$ = global.JQuery = $;

const fa = require("fontawesome");
require('bootstrap');
require('bootstrap-select');

//require("bootstrap/scss/bootstrap.scss");
// any CSS you require will output into a single css file (app.css in this case)
require('../css/variables.sass');
require('bootstrap-select/sass/bootstrap-select.scss');
require('@fortawesome/fontawesome-free/css/all.css');
require('../fonts/icomoon/style.css');
require('../css/app.sass');
require('../css/homepage.sass');


// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

$(document).ready(function () {
    initForms();
    $('[data-toggle="tooltip"]').tooltip();
    $('.ajax-form-symfo').on('submit', function (e) {
        e.preventDefault();
        let data = {};
        var regex = /phase/;
        $(this).serializeArray().forEach((object)=>{
            if (object.name.match(regex)){
                object.name = 'phases';
            }
            else
                data[object.name] = object.value;
        });
        data['rule_description'] = CKEDITOR.instances.rule_description.getData();
        var pathajax = $(this).data('pathajax');
        $.ajax({
            url: pathajax,
            data: JSON.stringify(data),
            method: 'POST',
            success: function (data_remote) {
                console.log(data_remote);
            },
            fail: function (data_remote) {
                console.log(data_remote);
            }
        });
    });
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