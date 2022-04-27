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
    <script src="{{asset('js/app.js')}}"></script>
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