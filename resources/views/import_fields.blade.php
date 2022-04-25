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
      $(".validation-error").hide();
      $(".alert-success").hide();
      const $tableID = $('#table');
      // const headers = [];
      // var data_error=0;
      // const hostname_regex="/^(http(s)?\/\/:)?(www\.)?[a-zA-Z\-]{3,}(\.(com|net|org))?$/";

      $tableID.on('click', '.table-remove', function() {
          $(this).parents('tr').detach();
      });
      // const $rows = $tableID.find('tr:not([id])');
      // $rows.find('th:not([id])').each(function() {
      //       headers.push($(this).text().toLowerCase());
      //   }); 
      // const $rows_without_header = $tableID.find('tr').not(':first');
      // $rows_without_header.each(function() {
      //   const $td = $(this).find('td:not([id])');          
      //   headers.forEach((header, i) => {
      //     const value=$td.eq(i).text();
      //     if(value == ''){
      //       $(this).css('background', 'red');
      //     }
      //   });
      // });
        $("#import_data").click(function() {
          // const data = [];
          const token   = $('meta[name="csrf-token"]').attr('content');
          
           
          // console.log($rows_without_header);
            // $rows_without_header.each(function() {
            //     const $td = $(this).find('td:not([id])');
            //     const h = {};              
            //     headers.forEach((header, i) => {
            //     const value=$td.eq(i).text();
            //     if(value == '' ){
            //       data_error=1;
            //         $(this).css('background', 'red');
            //     }
            //     if(header == 'hostname' && !hostname_regex.test(value) ){
            //       data_error=1;
            //         $(this).css('background', 'red');
            //     }
            //       h[header] = value;
            //     });
            //     data.push(h);
            // });
            // if(data_error){
            //   return false;
            // }
            var error_message='';
            $.ajax({
                url: 'import_process',
                type: 'POST',
                async:true,
                data: $('#routerDetailForm').serialize(),
                success:function(response){
                     if(response) {
                        alert('Data Imported Successfully');
                        window.location.href = "/";
                     }
                },
                error: function(data) {
                  var response = $.parseJSON(data.responseText);
                  $.each(response.errors, function(key, val) {
                    $.each(val, function(error_key, error_val) {
                      error_message+=error_val+"<br>";
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
        .pt-3-half {
            padding-top: 1.4rem;
        }
        .emptyrow {
          background:red;
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
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
      <div class="alert alert-danger validation-error">
      </div>
  <div class="card-body">
    <div id="table" class="table-editable">
        
      <table class="table table-bordered table-responsive-md table-striped text-center">
        <thead>
          <tr>
            @foreach ($header as $key =>$row)
                <th class="text-center">{{ $row }}</th>
            @endforeach
            <th id ="remove_head" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
        
        @foreach ($csv_data as $rowno => $row)
            <tr>
            
            @foreach ($row as $key => $value)
              <td class="pt-3-half"  contenteditable="true">
                <input type="text" id="name" name="{{$header[$key]}}{{$rowno}}" class="form-control" value="{{$value}}">
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

      </form>

 
<button class="btn btn-primary" id="import_data">
        Import Data
    </button>  
</body>
</html>