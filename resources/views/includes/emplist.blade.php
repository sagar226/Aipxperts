
@if(count($employees->toArray()['data']) > 0 )
@foreach ($employees as $employee)

    <tr>
        <td>{{ $employee->id }}</td>
        <td>{{ $employee->name }}</td>
        <td>{{ $employee->designation->name }}</td>
        <td>{{ $employee->salary->salary }}</td>
    </tr>

    
@endforeach
    <div class="panel-footer">
        <div class="row" id='demo'>
            {{ $employees->links() }}
        </div>   
    </div>
@else
<tr><td colspan='6' style='text-align:center;'>no Employees found</td></tr>
@endif
