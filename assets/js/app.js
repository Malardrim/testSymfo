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
        var pathajax = $(this).data('pathajax');
        $.ajax({
            url: pathajax,
            data: JSON.stringify(data),
            method: 'POST',
            dataType: 'json',
        }).done(function (data) {
            $('.ajax-form-symfo').trigger('reset');
            $('#ajax_message').html(data.message);
            var item_rules = $("#item_rules");
            item_rules.append(new Option(data.name, data.id));
            item_rules.selectpicker('val', data.id);
            item_rules.selectpicker('refresh');
        }).fail(function (data) {
            $('#ajax_message').html(data.responseJSON);
        }).always(function (data) {
            $('#newRuleModal').modal('hide');
            $('#ajax_response').modal('show');
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