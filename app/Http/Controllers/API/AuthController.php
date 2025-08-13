<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\EmpDet; // Assuming this model exists and is correctly configured
use App\User; // Assuming this model exists and is correctly configured
use App\AttendanceDetail; // Assuming this model exists and is correctly configured
use Illuminate\Support\Facades\Log; // Import Log facade for better error logging
use Carbon\Carbon; // For date/time manipulation

class AuthController extends Controller
{
    /**
     * Handle user login and generate API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'username' => 'required|string', // Explicitly define string type
                'password' => 'required|string', // Explicitly define string type
            ]);

            // Prepare credentials for authentication attempt
            $credentials = $request->only('username', 'password');

            // Attempt authentication
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Check if user has an associated empId
                if (empty($user->empId)) {
                    Log::warning("User ID: {$user->id} does not have an associated empId for login.");
                    return response()->json(['error' => 'Employee ID not associated with user account.'], 403);
                }

                // Generate API token
                // Ensure Passport/Sanctum is correctly set up and database tables exist
                $token = $user->createToken('Personal Access Token')->accessToken;

                // --- Optimize Database Queries ---
                // Eager load reporting user/employee if needed later to avoid N+1 queries,
                // but for single queries, `first()` is fine.
                // However, consolidating queries can reduce database roundtrips.

                // Fetch employee details in one go
                $employee = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
                                    ->select('emp_dets.id', 'emp_dets.reportingId', 'emp_dets.name', 'emp_dets.empCode', 'emp_dets.salaryScale', 
                                    'emp_dets.reportingType', 'emp_dets.profilePhoto', 'designations.name as designationName')
                                    ->where('emp_dets.id', $user->empId)
                                    ->first();

                // If employee details are not found, it's a critical error for this user
                if (!$employee) {
                    Log::error("EmpDet not found for user ID: {$user->id} with empId: {$user->empId}");
                    return response()->json(['error' => 'Employee details not found for this user.'], 404);
                }

                // Determine reporting manager name
                $repoName = 'NA'; // Default to 'NA'
                if ($employee->reportingId) { // Only query if reportingId exists
                    if ($employee->reportingType == 2 || $employee->reportingType == NULL) { // Assuming 2 means reporting to a User (admin/manager in User table)
                        $repoName = User::where('id', $employee->reportingId)->value('name');
                    } else { // Assuming other types (or NULL) mean reporting to another EmpDet
                        $repoName = EmpDet::where('id', $employee->reportingId)->value('name');
                    }
                }
                // If $repoName is null after query, it remains 'NA' from the default.

                // Fetch today's in-time
                $inTime = AttendanceDetail::where('empId', $user->empId)
                    ->whereDate('forDate', Carbon::today()) // Use Carbon for current date comparison
                    ->value('inTime');

                if ($inTime === null) {
                    $formattedInTime = 'Not Marked';
                } else {
                    // Always try to format, but handle potential invalid date string if it comes from DB
                    try {
                        $formattedInTime = Carbon::parse($inTime)->format('h:i A');
                    } catch (\Exception $e) {
                        Log::error("Failed to parse inTime '{$inTime}' for empId: {$user->empId}. Error: " . $e->getMessage());
                        $formattedInTime = 'Invalid Time'; // Fallback for bad data
                    }
                }

                // Prepare clean user response
                $userData = [
                    'id' => $user->id,
                    'empId' => $user->empId,
                    'name' => $user->name,
                    'username' => $user->username,
                    'userType' => $user->userType,
                    'email' => $user->email ?? null,
                    'empCode' => $employee->empCode,
                    'empId' => $user->empId,
                    'role' => $user->role ?? null, // Ensure 'role' column exists on User model if used
                    'inTime' => $formattedInTime, // Use the formatted time
                    'reportingAuthority' => $repoName,
                    'designationName' => $employee->designationName,
                    'avatarUrl' => $employee->profilePhoto ?? null,
                ];

                // Return structured success response
                return response()->json([
                    'status' => 200, // Explicitly 200 for success
                    'message' => 'Login successful', // Add a user-friendly message
                    'data' => [ // Consolidate main response data under 'data' key
                        'token' => $token,
                        'user' => $userData,
                        // Avoid sending the raw $employee object if it contains sensitive data not needed by frontend.
                        // If 'employee' is just a duplicate of 'userData' fields, remove it to reduce payload size.
                        // Otherwise, ensure only necessary 'employee' fields are exposed.
                        'employee_details' => [
                            'empId' => $employee->id,
                            'reportingId' => $employee->reportingId,
                            'name' => $employee->name,
                            'empCode' => $employee->empCode,
                            'salaryScale' => $employee->salaryScale,
                            'reportingType' => $employee->reportingType,
                            'profilePhoto' => $employee->profilePhoto,
                        ]
                    ]
                ]);
            } else {
                // Authentication failed
                return response()->json(['error' => 'Unauthorized', 'message' => 'Invalid username or password.'], 401);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors specifically
            Log::warning("Login validation failed: " . json_encode($e->errors()));
            return response()->json(['error' => 'Validation Failed', 'messages' => $e->errors()], 422);
        } catch (\Throwable $e) {
            // Catch any other unexpected exceptions and log them
            Log::error("Login failed for username: {$request->username}. Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal Server Error', 'message' => 'An unexpected error occurred during login.'], 500);
        }
    }

    /**
     * Example method to retrieve data (no changes needed if not affected by specific errors)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData()
    {
        // Example: You might want to protect this route with authentication middleware
        // e.g., Route::middleware('auth:api')->get('/get-data', 'API\AuthController@getData');
        try {
            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'data' => 'Your public or generic data here'
            ]);
        } catch (\Throwable $e) {
            Log::error("getData failed. Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal Server Error', 'message' => 'Could not retrieve data.'], 500);
        }
    }

    /**
     * Log out the authenticated user by revoking their token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // Ensure the user is authenticated before attempting to revoke token
            if ($request->user()) {
                $request->user()->token()->revoke(); // Revoke the token
                return response()->json(['message' => 'Successfully logged out']);
            }
            return response()->json(['message' => 'No active user session to log out.'], 400);

        } catch (\Throwable $e) {
            Log::error("Logout failed for user: " . ($request->user() ? $request->user()->id : 'N/A') . ". Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal Server Error', 'message' => 'An error occurred during logout.'], 500);
        }
    }
}