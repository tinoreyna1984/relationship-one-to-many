<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // devuelve todos los empleados
    public function getEmployees(){
        return response()->json(Employee::all(), 200);
    }

    // devuelve un empleado por ID
    public function getEmployee($id){
        $employee = Employee::find($id);
        if(is_null($employee)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        return response()->json($employee::find($id), 200);
    }

    // agrega empleado
    public function addEmployee(Request $request){
        $this->validate($request, [
            'employee_name' => 'required|max:1000',
            'amount' => 'required',
        ]);
        $employee = Employee::create($request->all());
        return response($employee, 201);
    }

    // actualiza empleado
    public function updEmployee(Request $request, $id){
        $employee = Employee::find($id);
        if(is_null($employee)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        $employee->update($request->all());
        return response($employee, 200);
    }

    // borra empleado
    public function deleteEmployee($id){
        $employee = Employee::find($id);
        if(is_null($employee)){
            return response()->json(['msg'=>'Empleado no encontrado'],404);
        }
        $employee->delete();
        return response()->json(['msg'=>'Eliminado Correctamente'],200);
    }
}
