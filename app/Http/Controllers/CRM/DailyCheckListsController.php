<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\TaskMaster;
use App\Models\CrmCheckList;
use App\Models\AssignTaskMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyCheckListsController extends Controller
{
    public function index()
    {
        $dateColumn = 'forDate';
        $statusColumn = 'status';
        $completedValue = 'completed';
        $employeeForeignKey = 'empId';
        $employeeTable = 'emp_dets';

        $taskHistory = CrmCheckList::query()
            ->join($employeeTable, "crm_check_lists.{$employeeForeignKey}", '=', "{$employeeTable}.id")
            ->selectRaw("
                DATE(crm_check_lists.{$dateColumn}) as forDate,
                {$employeeTable}.name as employee_name,
                crm_check_lists.{$employeeForeignKey} as empId,
                COUNT(crm_check_lists.id) as total_count,
                SUM(CASE WHEN crm_check_lists.{$statusColumn} = ? THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN crm_check_lists.{$statusColumn} != ? THEN 1 ELSE 0 END) as pending_count
            ", [$completedValue, $completedValue])
            ->where('crm_check_lists.active', 1)

            // ✨ CORRECTED GROUP BY CLAUSE
            // Use the original column expressions instead of aliases
            ->groupBy(
                DB::raw("DATE(crm_check_lists.{$dateColumn})"),
                "{$employeeTable}.name",
                "crm_check_lists.{$employeeForeignKey}"
            )

            // Order by date first, then by employee name
            ->orderBy('forDate', 'DESC')
            ->orderBy('employee_name', 'ASC')
            ->paginate(25);

        return view('CRM.dailyWork.previousHistory', ['taskHistory' => $taskHistory]);
    }

    public function showDetails($empId, $date)
    {
        // 1. Validate the incoming date to ensure it's a real date
        try {
            $validatedDate = Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            abort(404, 'Invalid date provided.');
        }

        // Define column names to match your database
        $dateColumn = 'forDate';
        $employeeForeignKey = 'empId';

        // 2. Start building the database query
        $tasksQuery = CrmCheckList::query()
            ->join('task_masters', 'crm_check_lists.taskId', 'task_masters.id')
            ->select('task_masters.task', 'crm_check_lists.*')
            ->where('crm_check_lists.active', 1)
            ->whereDate($dateColumn, $validatedDate);

        // 3. If an employee ID is present in the URL, add it to the filter
        if ($empId) {
            $tasksQuery->where($employeeForeignKey, $empId);
        }

        // 4. Order the results and paginate them
       $tasks = $tasksQuery->orderBy('crm_check_lists.created_at', 'desc')
            ->paginate(50)
            ->appends(request()->query()); // ✨ FIX APPLIED HERE

        // 5. Return the view with the required data
        return view('CRM.dailyWork.showDetails', [
            'tasks' => $tasks,
            'taskDate' => $validatedDate
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->empId) {
            return redirect()->back()->with('error', 'You are not registered as an employee.');
        }

        // 1. Get all tasks assigned specifically to the logged-in user
        $assignedTasks = AssignTaskMaster::join('task_masters', 'assign_task_masters.taskId', '=', 'task_masters.id')
            ->select(
                'task_masters.task',
                'assign_task_masters.taskId'
            )
            // ✨ FIX: Add this where clause to filter by the user's empId
            ->where('assign_task_masters.empId', $user->empId)
            ->get();

        // 2. Get checklist entries already saved for today by this specific user
        $todaysEntries = CrmCheckList::whereDate('forDate', now()->today())
            // ✨ FIX: Add this where clause to filter by the user's empId
            ->where('empId', $user->empId)
            ->get()
            ->keyBy('taskId'); // Keying by taskId makes it easy to find the status/remark for a specific task

        // 3. Pass both filtered collections to the view
        return view('CRM.dailyWork.checklist')->with([
            'taskLists' => $assignedTasks,
            'todaysEntries' => $todaysEntries
        ]);
    }

    public function store(Request $request)
    {
        // 1. Define validation rules and custom messages
        $rules = [
            'tasks' => 'required|array',
            'tasks.*.taskId' => 'required|exists:task_masters,id',
            'tasks.*.status' => 'required|in:Pending,Completed,On Hold,Not Applicable',
            'tasks.*.remark' => 'nullable|string|required_if:tasks.*.status,On Hold',
        ];

        $messages = [
            'tasks.*.remark.required_if' => 'A remark is required when the status is "On Hold".',
        ];

        // 2. Validate the request
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // 3. Process and save the data
        $validatedData = $validator->validated();

        DB::beginTransaction();
        try {
            $empId = Auth::user()->empId;
            $today = now()->toDateString(); // Get today's date as 'YYYY-MM-DD'

            foreach ($validatedData['tasks'] as $taskData) {
                // Use updateOrCreate to find a record with this unique combination or create a new one.
                CrmCheckList::updateOrCreate(
                    [
                        // Keys to find the unique record:
                        'empId'   => $empId,
                        'taskId'  => $taskData['taskId'],
                        'forDate' => $today, // The specific date for the checklist entry
                    ],
                    [
                        // Values to update if the record is found, or to use for creation:
                        'status'     => $taskData['status'],
                        'remark'     => $taskData['remark'] ?? null,
                        'updated_by' => Auth::id(),
                    ]
                );
            }

            DB::commit(); // All records saved successfully

        } catch (\Exception $e) {
            DB::rollBack(); // Something went wrong, rollback all changes
            // Optional: Log the error for debugging
            // \Log::error('Checklist Store Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while saving the checklist. Please try again.');
        }

        return redirect()->route('checklist.index')->with('success', 'Checklist saved successfully!');
    }

    public function employeesList()
    {
        //$underEmployee = 
        $dateColumn = 'forDate';
        $statusColumn = 'status';
        $completedValue = 'completed';
        $employeeForeignKey = 'empId';
        $employeeTable = 'emp_dets';

        $taskHistory = CrmCheckList::query()
            ->join($employeeTable, "crm_check_lists.{$employeeForeignKey}", '=', "{$employeeTable}.id")
            ->selectRaw("
                DATE(crm_check_lists.{$dateColumn}) as forDate,
                {$employeeTable}.name as employee_name,
                crm_check_lists.{$employeeForeignKey} as empId,
                COUNT(crm_check_lists.id) as total_count,
                SUM(CASE WHEN crm_check_lists.{$statusColumn} = ? THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN crm_check_lists.{$statusColumn} != ? THEN 1 ELSE 0 END) as pending_count
            ", [$completedValue, $completedValue])
            ->where('crm_check_lists.active', 1)

            // ✨ CORRECTED GROUP BY CLAUSE
            // Use the original column expressions instead of aliases
            ->groupBy(
                DB::raw("DATE(crm_check_lists.{$dateColumn})"),
                "{$employeeTable}.name",
                "crm_check_lists.{$employeeForeignKey}"
            )

            // Order by date first, then by employee name
            ->orderBy('forDate', 'DESC')
            ->orderBy('employee_name', 'ASC')
            ->paginate(25);

        return view('CRM.dailyWork.employeesList', ['taskHistory' => $taskHistory]);
    }

    // ... other controller methods
}
