<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Laravel Import Excel</title>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $(".validation-error").hide();
        const $tableID = $('#table');

        // var data_error=0;
        $tableID.on('click', '.table-remove', function() {
            $(this).parents('tr').detach();
        });
        const headers = [];
        const $rows = $tableID.find('tr:not([id])');
        $rows.find('th:not([id])').each(function() {
            headers.push($(this).text());
        });

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
        const rows_without_header = $tableID.find('tr').not(':first');
        var data_row_count = rows_without_header.length;
        var sapid_array = [];

        function getduplicates() {
            var error = 0;
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
                        var value = $('#' + header + index).val();
                        var value_count = 0;
                        value_count = getcount(value, sapid_array);
                        if (value_count > 1) {
                            error = 1;
                            $('#row' + index).removeClass('duplicates');
                            $('#row' + index).addClass('duplicates');
                            $()
                        }
                    }
                    if (header == 'hostname') {
                        var value = $('#' + header + index).val();

                        var value_count = 0;
                        value_count = getcount(value, hostname_array);
                        console.log('#row' + index);
                        if (value_count > 1) {
                            error = 1;
                            $('#row' + index).removeClass('duplicates');
                            $('#row' + index).addClass('duplicates');
                        }
                    }
                })
            })
            if (!error) {
                return false;
            }
        }
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
                required: true
            }
        });
        $("#routerDetailForm").validate().form();
        $("#import_data").click(function() {
            if (!$("#routerDetailForm").validate().form()) {
                return false;
            }

            if (!getduplicates()) {
                alert('bI');
                return false;
            }
            alert('HI');
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
    </script>

    <style>
    .duplicates {
        background: gray !important;
    }

    .pt-3-half {
        padding-top: 1.4rem;
    }

    .error {
        background: red;
    }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    <form class="form-horizontal" method="POST" name="routerDetailForm" id="routerDetailForm">
        {{ csrf_field() }}


        <!-- Editable table -->
        <div class="card">
            <h3 class="card-header text-center font-weight-bold text-uppercase py-4">
                Csv Data to be Imported
            </h3>
            <!-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif -->
            <div class="alert alert-danger validation-error">
            </div>
            <div class="card-body">
                <div id="table" class="table-editable">

                    <table class="table table-bordered table-responsive-md table-striped text-center">
                        <thead>
                            <tr>
                                @foreach ($header as $key =>$row)
                                <th class="text-center">{{ ucfirst($row) }}</th>
                                @endforeach
                                <th id="remove_head" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($csv_data as $rowno => $row)
                            <tr id="row{{+$rowno}}">

                                @foreach ($row as $key => $value)
                                <td class="pt-3-half" contenteditable="true">
                                    <input type="text" id="{{strtolower($header[$key])}}{{$rowno}}"
                                        name="{{strtolower($header[$key])}}{{$rowno}}"
                                        class=" {{strtolower($header[$key])}} form-control" value="{{$value}}">
                                </td>
                                @endforeach
                                <td id="remove">
                                    <span class="table-remove"><button type="button"
                                            class="btn btn-danger btn-rounded btn-sm my-0 remove_button">
                                            Remove
                                        </button></span>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Editable table -->
    </form>
    <button class="btn btn-primary" id="import_data">
        Import Data
    </button>
</body>

</html>