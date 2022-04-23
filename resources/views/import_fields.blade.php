<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
     <title>Laravel Import Excel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>    
    <script type="text/javascript">
    $(document).ready(function() {

        const $tableID = $('#table'); const $BTN = $('#export-btn'); const $EXPORT = $('#export');

        $tableID.on('click', '.table-remove', function() {
            $(this).parents('tr').detach();
        });

        $("#import_data").click(function() {
          //const $rows = $tableID.find('tr').not(':first');
            const $rows = $tableID.find('tr:not([id])');
            const headers = [];
            const data = [];
           const token   = $('meta[name="csrf-token"]').attr('content');
            $rows.find('th:not([id])').each(function() {
                headers.push($(this).text().toLowerCase());
            }); 
           
            const $rows_without_header = $tableID.find('tr').not(':first')
            $rows_without_header.each(function() {
                const $td = $(this).find('td:not([id])');
                const h = {};              
                headers.forEach((header, i) => {
                    h[header] = $td.eq(i).text();
                });
                data.push(h);
            });
          
            
            $.ajax({
                url: 'import_process',
                type: 'POST',
                async:true,
                data: {
                    data : data,
                    _token :token
                },
                success:function(response){
                    if(response) {
                       alert('Data Imported Successfully');
                       window.location.href = "/";
                    }
                },
                error: function(error) {
                  alert('Invalid Data');
                   // alert(error.responseJSON.errors);
                }
                
            });
            return false;
        });
            
            
        });
  </script>

<style>
        .pt-3-half {
            padding-top: 1.4rem;
        }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
<!-- <form class="form-horizontal" method="POST" action="{{ route('import_process') }}"> -->
    {{ csrf_field() }}

   
    <!-- Editable table -->
<div class="card">
  <h3 class="card-header text-center font-weight-bold text-uppercase py-4">
    Csv Data to be Imported 
  </h3>
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
  <div class="card-body">
    <div id="table" class="table-editable">
        
      <table class="table table-bordered table-responsive-md table-striped text-center">
        <thead>
          <tr>
            @foreach ($header as $key =>$row)
                <th class="text-center">{{ $row }}</th>
                <input type=hidden name="header[{{$key}}]" value="{{ $row }}">
            @endforeach
            <th id ="remove_head" class="text-center">Remove</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($csv_data as $rowno => $row)
            <tr>
            
            @foreach ($row as $key => $value)
            <!-- <input type=hidden name="csv_data[{{$rowno}}][{{$key}}]" value="{{ $value }}"> -->
            <td class="pt-3-half"  contenteditable="true">{{ $value }}
                <!-- <input type='text' name="csv_data[{{$rowno}}][{{$key}}]" value="{{ $value }}" > -->
           
            
            </td>   
            @endforeach
                <td id="remove">
                <span class="table-remove"
                    ><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 remove_button">
                    Remove
                    </button></span
                >
                </td>
            </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
<!-- Editable table -->

<!-- </form> -->

 
<button class="btn btn-primary" id="import_data">
        Import Data
    </button>  
</body>
</html>