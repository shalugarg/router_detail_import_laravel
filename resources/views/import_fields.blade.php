<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
     <title>Laravel Import Excel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
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

      $('#routerDetailForm').validate(
     { 
       rules:{},
        highlight: function(element) {
          $(element).closest('tr').css('background', '#ff6347');
        },   
        unhighlight: function (element) {
            $(element).closest('tr').css({ 'background-color' : '', 'opacity' : '' });
        }      
    });
    jQuery.validator.addClassRules({
      'Sapid': {
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
        if( !$("#routerDetailForm").validate().form()){
          return false;
        }
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
                <input type="text" id="{{$header[$key]}}{{$rowno}}" name="{{$header[$key]}}{{$rowno}}" class=" {{$header[$key]}} form-control" value="{{$value}}">
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