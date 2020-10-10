@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
    
        <p></p>
        <h1 style="text-align: center;">Employee List</h1>
       
        <p id="re-error" style="text-align: center; color: red;"> </p>
        <div class="row" style="margin-bottom: 40px">
            <div class="col-xs-2 col-xs-offset-1">
            <select name="filter" id="filter"required>
                <option value="">select filter</option>
                <option value="employee">Employee</option>
                <option value="salary">salary</option>
                <option value="designation">Designation</option>
              </select>    
            </div>
            <div class="col-xs-6">
                <div class="input-group">
                    <input type="hidden" name="search_param" value="all" id="search_param">         
                    <input type="text" class="form-control search-panel"  name="x" value="" placeholder="Search term...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" id='search'><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
            </div>
            <button type="button" id="reset"class="btn btn-success">reset</button>
        </div>
        <div class="col-md-14 ">

            <div class="panel panel-default panel-table">
              <div class="panel-heading">
                <div class="row">
                  <div class="col col-xs-6">
                  </div>
                  {{-- <div class="col col-xs-6 text-right">
                    <button type="button" onclick="location.href='/create-subscriber'" class="btn btn-sm btn-primary btn-create">Create New</button>
                  </div> --}}
                </div>
              </div>
              <div class="panel-body">
                <table class="table table-striped table-bordered table-list">
                    <thead>
                        <tr>
                 
                            <th class="hidden-xs">#sr.No</th>
                            <th>Employee Name</th>
                            <th>Designation</th>
                            <th>Salary</th>
                        </tr> 
                    </thead>
                    <tbody id='tables'>
                    </tbody>
                </table>
            
              </div>
              <div class="panel-footer">
                <div class="row" id='paginate'>
                
                </div>
              </div>
            </div>

        </div>
    </div>
</div>  
@endsection
@section('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{{Session::token()}}}'
        }
    });
    $(document).ready(function() {
      getEmpData(1);
    });
    
    function getEmpData(page){
      $.ajax({
            url: 'api/employees?page='+page,
            type: 'get',
            dataType: 'json',
            success:function(response){
                employees=response.data.data;
                data=null;
                pagination=null;
                $("#paginate").empty();
                $("#tables").empty();
                if(employees.length){
                    for(ii=0;ii < employees.length;ii++){
                        data+="<tr><td class='hidden-xs'>"+ employees[ii]['id']  +"</td><td>"+ employees[ii]['name']+"</td><td>"+ employees[ii]['designation'].name+"</td><td>"+ employees[ii]['salary'].salary+"</td></tr>";    
                    }
                    pageNextUrl='';
                    if(response.data.next_page_url!=null){
                        pageNextUrl="<li><button class='btn btn-default' onclick='getEmpData("+response.data.next_page_url.substr(-1)+")'>»</button></li>";
                    }
                    pagePrevUrl='';
                    if(response.data.prev_page_url!=null){
                        pagePrevUrl="<li><button class='btn btn-default' onclick='getEmpData("+response.data.prev_page_url.substr(-1)+")'>«</button></li>";
                    }
                    pagination="<div class='col col-xs-4'>Page "+response.data.current_page+" of "+response.data.last_page+"</div>"+
                  "<div class='col col-xs-8'><ul class='pagination hidden-xs pull-right'>"+pagePrevUrl+pageNextUrl+"</ul></div>";
                }else{
                    data="<tr><td colspan='6' style='text-align:center;'>no value found</td></tr>";
                }
               
                $("#tables").append(data);
                $("#paginate").append(pagination);
            }
        });
    }
    function pageUrl(url){
      console.log(url);
    }

    $(document).ready(function(e){
        $("#re-error").text("");
        $('#search').click(function(e) {
            e.preventDefault();
            var query = $('.search-panel').val().toLowerCase();
            var filter = $('#filter').val();
            if(filter == ''){
                $("#re-error").text("select filter first");
            }else{
                $.ajax({
                    url: 'api/search/'+filter+'?query='+query,
                    type: 'get',
                    dataType: 'json',
                    success:function(response){
                        employees=response.data.data;
                        data=null;
                        pagination=null;
                        $("#paginate").empty();
                        $("#tables").empty();
                        if(employees.length){
                            for(ii=0;ii < employees.length;ii++){
                                data+="<tr><td class='hidden-xs'>"+ employees[ii]['id']  +"</td><td>"+ employees[ii]['name']+"</td><td>"+ employees[ii]['designation'].name+"</td><td>"+ employees[ii]['salary'].salary+"</td></tr>";    
                            }
                            pageNextUrl='';
                            if(response.data.next_page_url!=null){
                                pageNextUrl="<li><button class='btn btn-default' onclick='getEmpData("+response.data.next_page_url.substr(-1)+")'>»</button></li>";
                            }
                            pagePrevUrl='';
                            if(response.data.prev_page_url!=null){
                                pagePrevUrl="<li><button class='btn btn-default' onclick='getEmpData("+response.data.prev_page_url.substr(-1)+")'>«</button></li>";
                            }
                            pagination="<div class='col col-xs-4'>Page "+response.data.current_page+" of "+response.data.last_page+"</div>"+
                        "<div class='col col-xs-8'><ul class='pagination hidden-xs pull-right'>"+pagePrevUrl+pageNextUrl+"</ul></div>";
                        }else{
                            data="<tr><td colspan='6' style='text-align:center;'>no value found</td></tr>";
                        }
                    
                        $("#tables").append(data);
                        $("#paginate").append(pagination);
                    }
                });
            }
        });
        $('#reset').click(function(e) {
            e.preventDefault();
            getEmpData(1);
        }); 
    });
</script>
@endsection