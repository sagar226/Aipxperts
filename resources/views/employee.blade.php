@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
    
        <p></p>
        <h1 style="text-align: center;">Employee List</h1>
       
        <p id="re-error" style="text-align: center; color: red;"> </p>
        <form method="GET" action="/search">
        <div class="row" style="margin-bottom: 40px">
            <div class="col-xs-2 col-xs-offset-1">
            <select name="filter" id="filter" required>
                <option value="">select filter</option>
                @php  $arr=['employee','salary','designation']; @endphp
                @foreach ($arr as $item)
                <option value="{{ $item }}" {{ isset($filter) ? (($item == $filter) ? "selected" : ""  ) :"" }}>{{ ucfirst($item) }}</option>
                @endforeach
                
                
              </select>    
            </div>
            <div class="col-xs-6">
                <div class="input-group">      
                    <input type="text" class="form-control search-panel"  name="query" value="{{ isset($query) ? $query : "" }}" placeholder="Search term...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" id='search'><span class="glyphicon glyphicon-search"></span></button>
                    </span>
                </div>
            </div>
        </form>
            <form method="get" action="/"><button type="submit" id="reset"class="btn btn-success">reset</button></form>
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
                    <tbody class='emp'>
                        @include('includes.emplist')
                    <tbody>
                </table>
            
              </div>
              <div class="panel-footer">
                <div class="row" id='demo'>
              </div>
            </div>

        </div>
    </div>
</div>  
@endsection
@section('script')
<script>
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getEmps(page);
                
            }
        }
    });
    $(document).ready(function() {
        $(document).on('click', '.pagination a', function (e) {
            $('.pagination li.active').removeClass('active')
            $(this).parent().toggleClass('active');

            data=$(this).attr('href').split('page=')[1];
           
            getEmps(data);
            e.preventDefault();
        });
    });
    function getEmps(page) {
        $.ajax({

            url : '?page=' + page,
            dataType: 'json',
        }).done(function (data) {

            $('.emp').html(data);
            location.hash = page;

        }).fail(function () {

            alert('Employee list not loaded');
        });
    }
 </script>   
@endsection