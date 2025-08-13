<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmpApplication; // Your model
use Illuminate\Support\Facades\Validator;
use Auth;

class ExitPassController extends Controller
{
    // Fetch all Exit Pass entries for the logged-in user
    public function index()
    {
        $user = Auth::user();
        $entries = EmpApplication::where('empId', $user->empId)
        ->where('type', 2)
        ->where('active', 1)
        ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-6 month')))
        ->orderBy('startDate', 'desc')
        ->get();

        return response()->json([
            'status' => 'success', // Indicate success status
            'data' => $entries // The actual array of documents, now with 'photos' array
        ], 200); // HTTP status code 200 for OK
    }

    // Store new Exit Pass entry
    public function store(Request $request)
    {
        $user = Auth::user();
        $entry = new EmpApplication();
        $entry->type = 2;
        $entry->empId = $user->empId;
        $entry->startDate = $request->date;
        $entry->reason = $request->reason;
        $entry->timeout = date('H:i', strtotime($request->timeOut));
        $entry->description = $request->description ?? '';
        $entry->updated_by=$user->username;
        $entry->save();

        return response()->json([
            'status' => 'success',
            'data' => $entry
        ], 201);
    }

    public function deleteExitPass(string $id)
    {
        $user = Auth::user();

        // 1. Find the AGF entry
        $entry = EmpApplication::find($id);

        // 2. Check if entry exists and belongs to the user
        if (!$entry) {
            return response()->json([
                'status' => 'error',
                'message' => 'Exit Pass entry not found or you do not have permission to delete it.'
            ], 404); // 404 Not Found
        }
       
        if ($entry->reportingAuthorityStatus != 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'ExitPass entry cannot be deleted unless Reporting Authority status is Pending.'
            ], 403); // 403 Forbidden
        }

        // 4. Delete the entry
        try {
            $entry->updated_by=$user->username;
            $entry->active=0;
            $entry->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Exit Pass entry deleted successfully.'
            ], 200); // 200 OK or 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Exit Pass entry.',
                'details' => $e->getMessage() // For debugging, remove in production
            ], 500); // 500 Internal Server Error
        }
    }
}

