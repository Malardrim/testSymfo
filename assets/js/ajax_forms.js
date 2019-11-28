function inputHandler(name, elem, data, object) {
    console.log(elem.attr('type') + " -> " + name);
    if (elem.attr('type') === 'number') {
        var val = parseInt(object.value);
        if (!isNaN(val))
            data[name] = val;
    } else
        data[name] = object.value;
    return data;
}

function textareaHandler(name, elem, data, object) {
    data[name] = object.value;
    return data;
}

function ckeditorHandler(name, elem, data, object) {
    data[name] = CKEDITOR.instances[elem.attr('id')].getData();
    return data;
}

function selectHandler(name, elem, data, object) {
    if (elem.attr('multiple')) {
        if (!data[name])
            data[name] = [];
        data[name].push(object.value);
    } else {
        data[name] = object.value;
    }
    return data;
}

function getNameOfHandler(elem) {
    var func = elem.prop('nodeName').toLowerCase() + "Handler";
    var custom = elem.data('serialize_ajax_handler');
    if (custom)
        return custom;
    return func;
}

$(document).ready(function () {
    $('.ajax-form-symfo').on('submit', function (e) {
        e.preventDefault();
        $('.error-msg').remove();
        let data = {};
        $(this).serializeArray().forEach((object) => {
            var elem = $("[name='" + object.name + "']");
            var parent_name = elem.closest('form').attr('name');
            var func = getNameOfHandler(elem);
            var name = elem.attr('name').substring(parent_name.length).replace(/\[/g, '').replace(/\]/g, '');
            try {
                eval(func)(name, elem, data, object)
            } catch (e) {
                console.error(e);
            }
        });
        console.log(data);
        var pathajax = $(this).data('pathajax');
        $.ajax({
            url: pathajax,
            data: JSON.stringify(data),
            method: 'POST',
            dataType: 'json',
            beforeSend: function () {
                $(".loader").removeClass('loader').addClass('loading').addClass('disabled');
            }
        }).done(function (data) {
            $('.ajax-form-symfo').trigger('reset');
            $('#ajax_message').html(data.message);
            var item_rules = $("#item_rules");
            item_rules.append(new Option(data.name, data.id));
            item_rules.selectpicker('val', data.id);
            item_rules.selectpicker('refresh');
        }).fail(function (data) {
            $('#ajax_message').html("Rule failed to update");
            $.each(data.responseJSON, function (i, item) {
                $('#' + i).after('<span class="badge badge-danger error-msg">' + item + '</span>');
            });
        }).always(function (data) {
            $(".loading").removeClass('loading').addClass('loader').removeClass('disabled');
            $('#ajax_response').modal('show');
        });
    });
});