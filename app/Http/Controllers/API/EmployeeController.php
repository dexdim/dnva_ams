<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Inventory\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends BaseController
{
    protected $employee = '';
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(Employee $employee)
    {
        $this->middleware('auth:api');
        $this->employee = $employee;
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = $this->employee->paginate(10);
        return $this->sendResponse($employees, 'Employee list');
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $employees = $this->employee->all();
        return $this->sendResponse($employees, 'Employee list');
    }


    /**
     * Store a newly created resource in storage.
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(EmployeeRequest $request)
    {
        $employees = $this->employee->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'dept' => $request->get('dept'),
        ]);
        return $this->sendResponse($employees, 'Employee created successfully');
    }

    /**
     * Update the resource in storage
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $employees = $this->employee->findOrFail($id);
        $employees->update($request->all());
        return $this->sendResponse($employees, 'Employee information has been updated');
    }

    /**
     * Remove the specified category.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $employees = $this->employee->findOrFail($id);
        $employees->delete();
        return $this->sendResponse($employees, 'Employee has been deleted');
    }
}
