require('./bootstrap');

function getcount(key, array) {
    var count = {};
    for (const element of array) {
        if (count[element]) {
            count[element] += 1;
        } else {
            count[element] = 1;
        }
    }
    return count[key]
}

function getduplicates() {
    var error = 0;
    var sapid_array = [];
    const $tableID = $('#table');
    const $rows = $tableID.find('tr:not([id])');
    const rows_without_header = $tableID.find('tr').not(':first');
    var data_row_count = rows_without_header.length;

    const headers = [];

    $rows.find('th:not([id])').each(function() {
        var header_value = $(this).text();
        headers.push(header_value.toLowerCase());
    });

    for (let i = 0; i < data_row_count; i++) {
        sapid_array.push($('#sapid' + i).val());
    }
    var hostname_array = [];
    for (let i = 0; i < data_row_count; i++) {
        hostname_array.push($('#hostname' + i).val());
    }
    rows_without_header.each(function(index, tr) {
        headers.forEach((header, i) => {
            if (header == 'sapid') {
                if ($('#row' + index).hasClass('duplicates')) {
                    $('#row' + index).removeClass('duplicates');
                }
                var value = $('#' + header + index).val();
                var value_count = 0;
                value_count = getcount(value, sapid_array);

                if (value_count > 1) {
                    error = 1;
                    $('#row' + index).addClass('duplicates');
                }
            }
            if (header == 'hostname') {

                var value = $('#' + header + index).val();

                var value_count = 0;
                value_count = getcount(value, hostname_array);
                if (value_count > 1) {
                    error = 1;
                    $('#row' + index).removeClass('duplicates');
                    $('#row' + index).addClass('duplicates');
                }
            }
        })
    })
    if (error) {
        return false;
    }
    return true;
}

$(document).ready(function() {

    $(".validation-error").hide();
    const $tableID = $('#table');

    // var data_error=0;
    $tableID.on('click', '.table-remove', function() {
        $(this).parents('tr').detach();
    });
    getduplicates();
    $('#routerDetailForm').validate({
        rules: {},
        highlight: function(element) {
            $(element).closest('tr').css('background', '#ff6347');
        },
        unhighlight: function(element) {
            $(element).closest('tr').css({
                'background-color': '',
                'opacity': ''
            });
        }
    });
    jQuery.validator.addClassRules({
        'sapid': {
            required: true
        },
        'hostname': {
            required: true
        },
        'loopback': {
            required: true
        },
        'macaddress': {
            required: true,
        }
    });
    $("#routerDetailForm").validate().form();
    $("#import_data").click(function() {
        if (!$("#routerDetailForm").validate().form()) {
            return false;
        }

        if (!getduplicates()) {
            return false;
        }

        var error_message = '';
        $.ajax({
            url: 'import_process',
            type: 'POST',
            async: true,
            data: $('#routerDetailForm').serialize(),
            success: function(response) {
                if (response) {
                    alert('Data Imported Successfully');
                    window.location.href = "/";
                }
            },
            error: function(data) {
                var response = $.parseJSON(data.responseText);
                $.each(response.errors, function(key, val) {
                    $.each(val, function(error_key, error_val) {
                        error_message += error_val + "<br>";
                    })
                })
                $(".validation-error").html(error_message.replace("\n", "<br>"));
                $(".validation-error").show();
            }

        });
        return false;
    });


});