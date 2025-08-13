<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ContactusLandPage;
use App\Department;
use App\Models\TaskMaster;
use App\Models\AssignTaskMaster;
use App\EmpDet;
use Auth;

class MasterChecklistController extends Controller
{
    public function index()
    {
        $taskMasters = TaskMaster::join('designations', 'task_masters.designationId', 'designations.id')
        ->join('departments', 'designations.departmentId', 'departments.id')
        ->join('contactus_land_pages', 'task_masters.branchId', 'contactus_land_pages.id')
        ->select('designations.name as designationName', 'departments.name as departmentName', 'contactus_land_pages.branchName','task_masters.*')
        ->where('task_masters.active', 1)
        ->get();
        return view('CRM.masters.taskSheets.list', compact('taskMasters'));
    }

    public function dlist()
    {
        $taskMasters = TaskMaster::join('designations', 'task_masters.designationId', 'designations.id')
        ->join('departments', 'designations.departmentId', 'departments.id')
        ->join('contactus_land_pages', 'task_masters.branchId', 'contactus_land_pages.id')
        ->select('designations.name as designationName', 'departments.name as departmentName', 'contactus_land_pages.branchName','task_masters.*')
        ->where('task_masters.active', 0)
        ->get();
        return view('CRM.masters.taskSheets.dlist', compact('taskMasters'));
    }

    public function create()
    {
        $departments = Department::Where('active', 1)->orderBy('name')->pluck('name', 'id');
        $branches = ContactusLandPage::Where('active', 1)->orderBy('branchName')->pluck('branchName', 'id');
        return view('CRM.masters.taskSheets.create', compact('departments','branches'));
    }

    public function store(Request $request)
    {
        $taskSheet = new TaskMaster;
        $taskSheet->designationId = $request->designationId;
        $taskSheet->branchId = $request->branchId;
        $taskSheet->task = $request->task;
        $taskSheet->updated_by = Auth::user()->username;
        $taskSheet->save();
        return redirect('/CRM/masterchecklist')->with("success","Task Updated Successfully..");
    }

    public function activeDeactiveStatus($id)
    {
        $taskSheet = TaskMaster::find($id);
        $taskSheet->active= ($taskSheet->active == 0)?1:0;
        $taskSheet->updated_by = Auth::user()->username;
        $taskSheet->save();
        return redirect('/CRM/masterchecklist')->with("success","Master Task Status Updated Successfully..");
    }

    // assign Task to employee using employee Code
    public function assignTaskList()
    {
        $assignSheets = AssignTaskMaster::join('emp_dets', 'assign_task_masters.empId', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('task_masters', 'assign_task_masters.taskId', 'task_masters.id')
        ->join('users', 'assign_task_masters.updated_by', 'users.id')
        ->select('emp_dets.name as empName','contactus_land_pages.branchName', 'departments.name as departmentName',
        'designations.name as designationName', 'task_masters.task', 'assign_task_masters.*', 'users.name as username')
        ->where('assign_task_masters.status', 1)
        ->get();
        return view('CRM.masters.taskAssign.list', compact('assignSheets'));
    }

    public function assignDeactiveTaskList()
    {
        $assignSheets = AssignTaskMaster::join('emp_dets', 'assign_task_masters.empId', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('task_masters', 'assign_task_masters.taskId', 'task_masters.id')
        ->select('emp_dets.name as empName','contactus_land_pages.branchName', 'departments.name as departmentName',
        'designations.name as designationName', 'task_masters.task', 'assign_task_masters.*')
        ->where('assign_task_masters.status', 0)
        ->get();
        return view('CRM.masters.taskAssign.dlist', compact('assignSheets'));
    }
    
    public function assignTask()
    {
        $user = Auth::user();

        // Prepare a variable to hold the collection of employees from the query
        $employeeQueryResults = collect();
        $taskList = collect();

        // Case 1: Handle Admin/Super Users
        if (in_array($user->userType, ['501', '00', '51'])) {
            // CHANGED: Using join() to fetch all data in a single query
            $employeeQueryResults = EmpDet::query()
                ->join('departments', 'emp_dets.departmentId', '=', 'departments.id')
                ->join('designations', 'emp_dets.designationId', '=', 'designations.id')
                ->select(
                    'emp_dets.id',
                    'emp_dets.name',
                    'emp_dets.empCode',
                    'emp_dets.branchId',      // Select IDs for task filtering
                    'emp_dets.designationId', // Select IDs for task filtering
                    'departments.name as department_name',
                    'designations.name as designation_name'
                )
                ->where('emp_dets.active', 1)
                ->whereNull('emp_dets.lastDate')
                ->orderBy('emp_dets.name')
                ->get();

            $taskList = TaskMaster::where('active', 1)->orderBy('task')->pluck('task', 'id');

        } else { // Case 2: Handle other users
            $empId = $user->empId;

            if (empty($empId)) {
                return view('CRM.masters.taskAssign.create', ['employees' => [], 'taskList' => []]);
            }
            
            // This part remains the same: find the subordinate IDs
            $subordinateIds = collect();
            if ($user->userType == '21') {
                $subordinateIds = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
            }
            if ($user->userType == '11') {
                $directReports = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                $indirectReports = EmpDet::whereIn('reportingId', $directReports)->where('active',1)->pluck('id');
                $subordinateIds = $directReports->merge($indirectReports);
            }
            
            if ($subordinateIds->isEmpty()) {
                return view('CRM.masters.taskAssign.create', ['employees' => [], 'taskList' => []]);
            }
            
            // CHANGED: Using join() for the subordinates
            $employeeQueryResults = EmpDet::query()
                ->whereIn('emp_dets.id', $subordinateIds) // Filter by subordinates
                ->join('departments', 'emp_dets.departmentId', '=', 'departments.id')
                ->join('designations', 'emp_dets.designationId', '=', 'designations.id')
                ->select(
                    'emp_dets.id',
                    'emp_dets.name',
                    'emp_dets.empCode',
                    'emp_dets.branchId',
                    'emp_dets.designationId',
                    'departments.name as department_name',
                    'designations.name as designation_name'
                )
                ->where('emp_dets.active', 1)
                ->whereNull('emp_dets.lastDate')
                ->orderBy('emp_dets.name')
                ->get();

            // Filter tasks based on the results
            $designationIds = $employeeQueryResults->pluck('designationId')->unique();
            $branchIds = $employeeQueryResults->pluck('branchId')->unique();
            $taskList = TaskMaster::whereIn('designationId', $designationIds)
                                ->whereIn('branchId', $branchIds)
                                ->where('active', 1)
                                ->pluck('task', 'id');
        }

        // NEW: Loop through the query results to build the display text
        // The logic is the same, but it uses the aliased columns from the SELECT statement
        $employees = $employeeQueryResults->mapWithKeys(function ($emp) {
            $displayText = "{$emp->name} ({$emp->empCode}) - {$emp->department_name} - {$emp->designation_name}";
            return [$emp->id => $displayText];
        });

        return view('CRM.masters.taskAssign.create', compact('employees', 'taskList'));
    }

    public function updateAssignTask(Request $request)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            // Ensure taskId is provided and exists in the 'task_masters' table
            'taskId' => 'required|exists:task_masters,id', 
            
            // Ensure empId is provided and exists in the 'emp_dets' table
            'empId' => 'required|exists:emp_dets,id',
        ]);

        // 2. Check if this exact task is already assigned to the employee
        $existingAssignment = AssignTaskMaster::where('taskId', $validatedData['taskId'])
                                            ->where('empId', $validatedData['empId'])
                                            ->first();

        if ($existingAssignment) {
            // If it already exists, redirect back with a warning message
            return redirect()->back()
                            ->with('warning', 'This task is already assigned to the selected employee.');
        }

        // 3. Create the new assignment using Mass Assignment (cleaner and safer)
        try {
            AssignTaskMaster::create([
                'taskId' => $validatedData['taskId'],
                'empId' => $validatedData['empId'],
                'created_by' => Auth::id(), // Use created_by for new records and store user's ID
                'updated_by' => Auth::id(), // You can set updated_by on creation as well
            ]);

        } catch (\Exception $e) {
            // If something goes wrong during save, redirect back with an error
            return redirect()->back()
                            ->with('error', 'Failed to assign task. Please try again.')
                            ->withInput();
        }

        // 4. Redirect to the list page with a success message
        return redirect('/CRM/assignTaskSheet/assignTaskList')
                    ->with('success', 'Task assigned to employee successfully.');
    }

    public function assignActiveDeactiveStatus($id)
    {
        $assignSheet = AssignTaskMaster::find($id);
        $assignSheet->status= ($taskSheet->status == 0)?1:0;
        $assignSheet->updated_by = Auth::user()->username;
        $assignSheet->save();
        return redirect('/CRM/assignTaskSheets')->with("success","Assigned Task Status Updated Successfully..");
    }
}
