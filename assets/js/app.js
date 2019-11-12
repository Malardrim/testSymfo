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

$(document).ready(function () {
    console.log("haha");
    initForms();
    $('[data-toggle="tooltip"]').tooltip();
    $('.ajax-form-symfo').on('submit', function (e) {
        e.preventDefault();
        let data = {};
        var regex = /phase/;
        $(this).serializeArray().forEach((object) => {
            var name = $("[name='" + object.name + "']").attr('ajax_name');
            if (name) {
                if (name == "phases") {
                    if (!data[name])
                        data[name] = [];
                    data[name].push(object.value);
                } else
                    data[name] = object.value;
            }
        });
        data['description'] = CKEDITOR.instances.rule_description.getData();
        console.log(data);
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