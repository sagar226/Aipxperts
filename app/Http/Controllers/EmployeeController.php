<?php

namespace App\Http\Controllers;

use App\Designation;
use App\Employee;
use App\Salary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class EmployeeController extends Controller
{
    
       /**
     * create employee for application
     *
     * @var array
     */
    public function createEmployee(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'salary' => 'required|numeric',
            'designation' => 'required',
        ]);       

        if($validator->fails()) return $this->validationError($validator);

        try{
            DB::beginTransaction();
            $designation=Designation::create([
                'name' => $request->designation
            ]);
            $employee=Employee::create([
                'name' => $request->name,
                'designation_id' => $designation->id
            ]);
            Salary::create([
                'salary' => $request->salary,
                'employee_id' => $employee->id
            ]);
            DB::commit();
            return $this->successResponse("Employee create successfully");
        }catch(Exception $e){
            return $this->errorResponse($e->getMessage());
        }

    }

    /**
     * get the list of employees data.(only for data entry purpose)
     *
     * @var integer
     */
    public function getEmployees($item=2,Request $request){
        
        $employees=Employee::with('designation','salary')->paginate($item);
        if ($request->ajax()) {
            return Response::json(View::make('includes.emplist', array('employees' => $employees))->render());
        }

        return View::make('employee', array('employees' => $employees));
    }

    /**
     * filter employee with salary and designation
     *
     * @var integer
     */
    public function getSearchResult(Request $request,$filter,$item=2){
        $filter=$request['filter'];
        $validator = Validator::make(['filter' => $filter],[
            'filter'=>'required|in:salary,employee,designation'
        ]);       

        if($validator->fails()) return $this->validationError($validator);
       
        $searchString=$request['query'];
    
        if($filter=='salary'){

            $employees = Employee::with('designation','salary')->whereHas($filter, function ($query) use ($searchString){
                  $query->where('salaries.salary', 'like', $searchString.'%');
            })->paginate($item);
            
        }else if($filter=='designation') {

            $employees = Employee::with('designation','salary')->whereHas($filter, function ($query) use ($searchString){
                  $query->where('designations.name', 'like', $searchString.'%');
            })->paginate($item);

        }else{
            $employees = Employee::with('designation','salary')->where('name', 'like',$searchString.'%')->paginate($item); 
        }
        if ($request->ajax()) {
            return Response::json(View::make('includes.emplist', array('employees' => $employees,'query'=> $searchString,'filter' => $filter))->render());
        }
        return View::make('employee', array('employees' => $employees,'query'=> $searchString,'filter' => $filter));

    }
}
