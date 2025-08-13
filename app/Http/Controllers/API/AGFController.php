<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmpApplication; // Your main application model
use App\EmpDet; // Assuming EmpDet model is used for employee details
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // For detailed error logging

class AGFController extends Controller
{
    protected function getBaseAGFQuery()
    {
        $user = Auth::user();
        $baseQuery = EmpApplication::join('emp_dets', 'emp_applications.empId', '=', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', '=', 'departments.id')
            ->leftJoin('designations', 'emp_dets.designationId', '=', 'designations.id') // Use leftJoin as designation might be nullable
            ->leftJoin('contactus_land_pages', 'emp_dets.branchId', '=', 'contactus_land_pages.id') // Use leftJoin
            ->select(
                'emp_applications.id',
                'emp_applications.type', // Ensure 'type' is selected to confirm AGF
                'emp_applications.active', // Ensure 'active' is selected
                'emp_applications.startDate',
                'emp_applications.issue',
                'emp_applications.inTime',
                'emp_applications.outTime',
                'emp_applications.dayStatus',
                'emp_applications.description',
                'emp_applications.reason',
                'emp_applications.reportingAuthorityStatus', // These are the 'status1' fields from DB
                'emp_applications.HRStatus',                   // These are the 'status2' fields from DB
                'emp_applications.accountStatus',             // This is the 'status' field from DB
                'emp_applications.reportingAuthorityApprovedBy', // approvedBy1
                'emp_applications.HRSApprovedBy',               // approvedBy2
                'emp_applications.accountApprovedBy',          // approvedBy
                'emp_applications.rejectionReason', // rejectionReason
                'emp_applications.created_at', // Important for filtering by month/year
                'emp_applications.updated_at',

                'emp_dets.name as empName',
                'emp_dets.empCode',
                'emp_dets.profilePhoto', // Profile photo for frontend
                'emp_dets.phoneNo',
                'emp_dets.username',
                'emp_dets.firmType',

                'departments.name as departmentName',
                'designations.name as designationName',
                'contactus_land_pages.branchName'
            )
            ->where('emp_applications.type', 1) // Filter for AGF applications
            ->where('emp_applications.active', 1); // Filter for active applications

        // Apply user-specific filters
        if ($user->userType == '21') { // Department Head
            $baseQuery->where('emp_dets.reportingId', $user->empId);
        } elseif ($user->userType == '11') { // Assuming HR or higher-level
            $reportingIds = EmpDet::where('reportingId', $user->empId)->pluck('id');
            $subordinatesIds = EmpDet::whereIn('reportingId', $reportingIds)->pluck('id');
            $allRelevantIds = collect([$user->empId])->merge($reportingIds)->merge($subordinatesIds)->unique()->toArray();
            
            $baseQuery->whereIn('emp_dets.id', $allRelevantIds);

            // This condition was from original code, unsure of its exact purpose but preserving
            // if ($user->userType != '601') {
            //     $baseQuery->where('emp_dets.id', '!=', $user->empId);
            // }
        } else {
            // Default for regular employees: only their own applications
            $baseQuery->where('emp_applications.empId', $user->empId);
        }

        return $baseQuery;
    }

    // Fetch all AGF entries for the logged-in user (personal list)
    public function index()
    {
        $user = Auth::user();
        $entries = EmpApplication::where('empId', $user->empId)
                               ->where('type', 1)
                               ->where('active', 1)
                               ->orderBy('startDate') // Assuming startDate is for ordering personal list
                               ->get();

        return response()->json($entries);
    }

    // Store new AGF entry
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'issue' => 'required|string|max:255',
            'inTime' => 'required|string|max:5', // e.g., "HH:MM"
            'outTime' => 'required|string|max:5',
            'day' => 'required|in:half,full', // Frontend sends 'day'
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $entry = new EmpApplication();
            $entry->type = 1; // AGF application type
            $entry->empId = $user->empId;
            $entry->startDate = $request->date;
            $entry->reason = $request->issue; // Mapping frontend 'issue' to backend 'reason'
            $entry->inTime = $request->inTime;
            $entry->outTime = $request->outTime;
            $entry->dayStatus = $request->day; // Mapping frontend 'day' to backend 'dayStatus'
            $entry->description = $request->description ?? '';
            $entry->active = 1; // Mark as active
            $entry->reportingAuthorityStatus = 0; // Default to Pending
            $entry->HRStatus = 0; // Default to Pending
            $entry->accountStatus = 0; // Default to Pending
            $entry->save();

            // Return the newly created entry, potentially with joined data if needed by frontend for immediate display
            // For simplicity, returning just the EmpApplication model's data.
            // If frontend needs joined data, you'd fetch it here after save.
            $createdEntry = EmpApplication::where('id', $entry->id)
                                          ->with(['employee.department', 'employee.designation', 'employee.branch']) // Load relationships if necessary
                                          ->first();

            // Manually format for consistent response
            $formattedCreatedEntry = [
                'id' => $createdEntry->id,
                'name' => $createdEntry->employee->name ?? null, // Assuming relationship
                'designationName' => $createdEntry->employee->designation->name ?? null,
                'empCode' => $createdEntry->employee->empCode ?? null,
                'profilePhoto' => $createdEntry->employee->profilePhoto ?? null,
                'phoneNo' => $createdEntry->employee->phoneNo ?? null,
                'departmentName' => $createdEntry->employee->department->name ?? null,
                'branchName' => $createdEntry->employee->branch->branchName ?? null,
                'appliedDate' => $createdEntry->startDate ?date('Y-m-d', strtotime($createdEntry->startDate)) : null,
                'issue' => $createdEntry->reason, // maps backend reason to frontend issue
                'inTime' => $createdEntry->inTime,
                'outTime' => $createdEntry->outTime,
                'dayStatus' => $createdEntry->dayStatus,
                'description' => $createdEntry->description,
                'reason' => $createdEntry->reason,
                'reportingAuthorityStatus' => $createdEntry->reportingAuthorityStatus,
                'HRStatus' => $createdEntry->HRStatus,
                'accountStatus' => $createdEntry->accountStatus,
                'reportingAuthorityApprovedBy' => $createdEntry->reportingAuthorityApprovedBy,
                'HRSApprovedBy' => $createdEntry->HRSApprovedBy,
                'accountApprovedBy' => $createdEntry->accountApprovedBy,
                'rejectionReason' => $createdEntry->rejectionReason,
                'created_at' => $createdEntry->created_at ? date('d-m-Y H:i', strtotime($createdEntry->created_at)) : null,
                'updated_at' => $createdEntry->updated_at ? date('d-m-Y H:i', strtotime($createdEntry->updated_at)) : null,
            ];

            return response()->json([
                'status' => 'success',
                'data' => $formattedCreatedEntry // Return formatted data
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating AGF entry: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create AGF entry.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Soft deletes an AGF entry by setting 'active' to 0.
     * Only allows deletion if reportingAuthorityStatus is 0 (Pending).
     *
     * @param  string $id The ID of the AGF entry to delete.
     * @return \Illuminate\Http\Response
     */
    public function deleteAGF(string $id)
    {
        $user = Auth::user();

        // Find the entry, ensuring ownership and AGF type
        $entry = EmpApplication::where('id', $id)
                               ->where('empId', $user->empId)
                               ->where('type', 1)
                               ->first();

        if (!$entry) {
            return response()->json([
                'status' => 'error',
                'message' => 'AGF entry not found or you do not have permission to delete it.'
            ], 404);
        }
        
        // Ensure it can only be deleted if Reporting Authority status is Pending (0)
        if ($entry->reportingAuthorityStatus != 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'AGF entry cannot be deleted unless Reporting Authority status is Pending.'
            ], 403);
        }

        try {
            $entry->active = 0; // Set active to 0 for soft delete
            $entry->save();
            return response()->json([
                'status' => 'success',
                'message' => 'AGF entry deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting AGF entry: ' . $e->getMessage(), ['id' => $id, 'exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete AGF entry.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieves AGF applications for Department Head or HR/Admin.
     * This method now returns the full query builder, ready for filtering/pagination.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getEmployeeAGFApplicationsQuery()
    {
        $user = Auth::user();
        $query = EmpApplication::join('emp_dets', 'emp_applications.empId', '=', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', '=', 'departments.id')
            ->join('designations', 'emp_dets.designationId', '=', 'designations.id') // Changed from leftJoin for consistency
            ->leftJoin('contactus_land_pages', 'emp_dets.branchId', '=', 'contactus_land_pages.id') // Branch might be nullable
            ->select(
                'emp_applications.id',
                'emp_applications.type',
                'emp_applications.active',
                'emp_applications.startDate', // Use startDate for appliedDate on frontend
                'emp_applications.issue',
                'emp_applications.inTime',
                'emp_applications.outTime',
                'emp_applications.dayStatus', // Maps to dayDetail
                'emp_applications.description',
                'emp_applications.reason',
                'emp_applications.reportingAuthorityStatus', // Status fields from DB
                'emp_applications.HRStatus',
                'emp_applications.accountStatus',
                'emp_applications.reportingAuthorityApprovedBy', // Approved By fields from DB
                'emp_applications.HRSApprovedBy',
                'emp_applications.accountApprovedBy',
                'emp_applications.rejectionReason',
                'emp_applications.created_at',
                'emp_applications.updated_at',

                'emp_dets.name as empName', // Renamed to empName for clarity
                'emp_dets.empCode',
                'emp_dets.profilePhoto',
                'emp_dets.phoneNo',
                'emp_dets.username', // Added username
                'emp_dets.firmType',

                'departments.name as departmentName',
                'designations.name as designationName',
                'contactus_land_pages.branchName' // Branch name
            )
            ->where('emp_applications.type', 1)
            ->where('emp_applications.active', 1);

        if ($user->userType == '21') { // Department Head
            $query->where('emp_dets.reportingId', $user->empId);
        } elseif ($user->userType == '11') { // HR/Admin
            $empId = $user->empId;
            $userType = $user->userType; // Ensure this is not '601' implicitly if condition below relies on it

            $users1 = EmpDet::where('reportingId', $empId)->pluck('id');
            $users2 = EmpDet::whereIn('reportingId', $users1)->pluck('id');
            $allRelevantIds = collect($users1)->merge($users2)->unique()->all();

            $query->whereIn('emp_dets.id', $allRelevantIds);
            
            // This condition was from original code, apply if still relevant
            // if ($userType != '601') {
            //     $query->where('emp_dets.id', '!=', $empId);
            // }

        } else {
            // Fallback for any other user types, maybe they see nothing or only their own?
            // This case might need specific handling if other user types shouldn't call this endpoint.
            $query->where('emp_applications.empId', $user->empId);
        }

        // Apply default date filter (last month to current month end)
        $query->where('emp_applications.startDate', '>=', date('Y-m-01', strtotime('-1 month')))
              ->where('emp_applications.startDate', '<=', date('Y-m-t'));

        return $query;
    }

    /**
     * Lists AGF applications for reporting authority/HR with filters and search.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $query = $this->getEmployeeAGFApplicationsQuery(); // Get the base query

        // Filter by Month (using created_at as per frontend, but startDate might be more logical for AGF date)
        if ($request->has('month') && $request->input('month') !== 'all') {
            $month = (int) $request->input('month');
            $query->whereMonth('emp_applications.created_at', $month); // Using created_at for filter
        }

        // Filter by Year (using created_at as per frontend)
        if ($request->has('year') && $request->input('year') !== 'all') {
            $year = (int) $request->input('year');
            $query->whereYear('emp_applications.created_at', $year); // Using created_at for filter
        }

        // Search Query
        if ($request->has('search') && !empty($request->input('search'))) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                // Ensure correct table aliases for search
                $q->where('emp_dets.name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('emp_dets.empCode', 'like', '%' . $searchTerm . '%')
                  ->orWhere('contactus_land_pages.branchName', 'like', '%' . $searchTerm . '%')
                  ->orWhere('emp_applications.issue', 'like', '%' . $searchTerm . '%') // 'issue' is frontend mapping for backend 'reason'
                  ->orWhere('emp_applications.description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Order by created_at descending (most recent first), then by status
        $applications = $query->orderBy('emp_applications.created_at', 'desc') // Corrected column name
                              ->orderBy('emp_applications.reportingAuthorityStatus', 'asc') // Order by reporting status first
                              ->get();

        // Transform data to match your React Native EmployeeAGF interface
        $formattedApplications = $applications->map(function ($app) {
            return [
                'id' => $app->id,
                'name' => $app->empName, // empName from join
                'designationName' => $app->designationName,
                'empCode' => $app->empCode,
                'profilePhoto' => $app->profilePhoto,
                'phoneNo' => $app->phoneNo,
                'departmentName' => $app->departmentName,
                'branchName' => $app->branchName,
                'appliedDate' => $app->startDate ? date('d-m-Y', strtotime($app->startDate)) : null, // Use startDate
                'issue' => $app->issue, // This is backend 'issue'
                'inTime' => $app->inTime,
                'outTime' => $app->outTime,
                'dayDetail' => $app->dayStatus, // Maps dayStatus to dayDetail
                'description' => $app->description,
                'reason' => $app->reason, // This is backend 'reason'
                'reportingAuthorityStatus' => $app->reportingAuthorityStatus, // Status fields from DB
                'HRStatus' => $app->HRStatus,
                'accountStatus' => $app->accountStatus,
                'reportingAuthorityApprovedBy' => $app->reportingAuthorityApprovedBy,
                'HRSApprovedBy' => $app->HRSApprovedBy,
                'accountApprovedBy' => $app->accountApprovedBy,
                'rejectionReason' => $app->rejectionReason,
                'created_at' => $app->created_at ? date('d-m-Y H:i', strtotime($app->created_at)) : null,
                'updated_at' => $app->updated_at ? date('d-m-Y H:i', strtotime($app->updated_at)) : null,
            ];
        });

        return response()->json($formattedApplications);
    }

    /**
     * Approve an AGF application.
     * Assumes an 'id' is passed.
     * Needs to be refined based on reporting authority/HR role and status transitions.
     *
     * @param  string $id The ID of the AGF application.
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $user = Auth::user();
        $application = EmpApplication::find($id); // Assuming EmpApplication

        if (!$application) {
            return response()->json(['message' => 'Application not found.'], 404);
        }

        // ⭐ IMPORTANT: Implement proper role-based approval logic here.
        // This is a placeholder. You need to decide:
        // 1. Who can approve (e.g., Reporting Authority, HR, Accounts)?
        // 2. Which status field they update (reportingAuthorityStatus, HRStatus, accountStatus)?
        // 3. What happens after an approval (e.g., cascades to next approval level)?

        // Example: Only Reporting Authority can set reportingAuthorityStatus
        if ($user->userType == '21' && $application->reportingAuthorityStatus == 0) { // If DH and status is pending
             $application->reportingAuthorityStatus = 1; // Approved
             $application->reportingAuthorityApprovedBy = $user->name; // Or $user->empCode
             $application->rejectionReason = null;
        } 
        // Example: If HR approves after Reporting Authority (assuming a flow)
        // else if ($user->userType == '11' && $application->HRStatus == 0 && $application->reportingAuthorityStatus == 1) {
        //     $application->HRStatus = 1;
        //     $application->HRSApprovedBy = $user->name;
        //     $application->rejectionReason = null;
        // }
        else {
            return response()->json(['message' => 'You are not authorized to approve this application or it is not in the correct status.'], 403);
        }
        
        $application->save();

        return response()->json(['message' => 'Application approved successfully.', 'data' => $application]);
    }

    /**
     * Reject an AGF application.
     * Assumes an 'id' and 'reason' are passed in the request.
     * Needs to be refined based on reporting authority/HR role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $id The ID of the AGF application.
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $application = EmpApplication::find($id); // Assuming EmpApplication

        if (!$application) {
            return response()->json(['message' => 'Application not found.'], 404);
        }

        // ⭐ IMPORTANT: Implement proper role-based rejection logic here.
        // Similar to approve, decide who can reject and which status field they update.

        // Example: Only Reporting Authority can set reportingAuthorityStatus to rejected
        if ($user->userType == '21' && $application->reportingAuthorityStatus == 0) { // If DH and status is pending
            $application->reportingAuthorityStatus = 2; // Rejected
            $application->reportingAuthorityApprovedBy = $user->name; // Record who rejected
            $application->rejectionReason = $request->input('reason');
        } 
        // Example: If HR rejects (assuming a flow)
        // else if ($user->userType == '11' && $application->HRStatus == 0) {
        //     $application->HRStatus = 2;
        //     $application->HRSApprovedBy = $user->name;
        //     $application->rejectionReason = $request->input('reason');
        // }
        else {
            return response()->json(['message' => 'You are not authorized to reject this application or it is not in the correct status.'], 403);
        }

        $application->save();

        return response()->json(['message' => 'Application rejected successfully.', 'data' => $application]);
    }
}       