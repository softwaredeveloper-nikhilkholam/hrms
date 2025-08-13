<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\AaryansAppointment; // Your Appointment Model
use App\User;        // Your User Model (for participants dropdown)
use App\EmpDet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // For robust error logging

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     * Corresponds to: GET /api/appointments
     * Handled by: fetchAppointments in frontend
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // Fetch all appointments, ordered by creation date (newest first)
            // If appointments are user-specific, uncomment and adjust:
            // $appointments = auth()->user()->appointments()->orderBy('created_at', 'desc')->get();
            $appointments = AaryansAppointment::orderBy('created_at', 'desc')->get();

            return response()->json($appointments, 200);
        } catch (\Exception $e) {
            Log::error("Error in AppointmentController@index: " . $e->getMessage());
            return response()->json(['message' => 'Failed to retrieve appointments.'], 500);
        }
    }

    /**
     * Store a newly created appointment in storage.
     * Corresponds to: POST /api/appointments
     * Handled by: createAppointment in frontend
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|string|max:255',
            'priority' => 'required|string|in:low,medium,high', // Ensure valid priorities
            'agenda' => 'required|string',
            'participants' => 'nullable|array', // Can be an empty array or null
            'participants.*' => 'string|max:255', // Each item in the array must be a string
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $appointment = AaryansAppointment::create([
                'to' => $request->to,
                'priority' => $request->priority,
                'agenda' => $request->agenda,
                'participants' => $request->input('participants', []), // Default to empty array if not provided
                'status' => 'Pending', // New appointments start as 'Pending'
                // 'user_id' => auth()->id(), // Uncomment if you link appointments to users
            ]);

            return response()->json($appointment, 201); // 201 Created
        } catch (\Exception $e) {
            Log::error("Error in AppointmentController@store: " . $e->getMessage());
            return response()->json(['message' => 'Failed to create appointment.'], 500);
        }
    }

    /**
     * Display the specified appointment.
     * Corresponds to: GET /api/appointments/{id}
     * (Not explicitly called by your current service, but good for details)
     *
     * @param  \App\AaryansAppointment  $appointment // Laravel Route Model Binding
     * @return \Illuminate\Http\Response
     */
    public function show(AaryansAppointment $appointment)
    {
        // Route model binding automatically handles finding the appointment or returning 404
        return response()->json($appointment, 200);
    }

    /**
     * Update the specified appointment in storage.
     * Corresponds to: PUT/PATCH /api/appointments/{id}
     * (Can be used for general updates, including status changes)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AaryansAppointment  $appointment // Laravel Route Model Binding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AaryansAppointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'to' => 'sometimes|required|string|max:255',
            'priority' => 'sometimes|required|string|in:low,medium,high',
            'agenda' => 'sometimes|required|string',
            'participants' => 'sometimes|nullable|array',
            'participants.*' => 'string|max:255',
            'status' => 'sometimes|required|string|in:Pending,Approved,Rejected,Completed,Rescheduled',
            'mom' => 'nullable|string', // MOM can be updated here
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $appointment->update($request->all());
            return response()->json($appointment, 200);
        } catch (\Exception $e) {
            Log::error("Error in AppointmentController@update for ID {$appointment->id}: " . $e->getMessage());
            return response()->json(['message' => 'Failed to update appointment.'], 500);
        }
    }

    /**
     * Custom method to update MOM and mark appointment as completed.
     * Corresponds to: PUT /api/appointments/{id}/complete
     * (Can be called from frontend to finalize an approved appointment)
     *
     * @param Request $request
     * @param \App\AaryansAppointment $appointment
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeAppointment(Request $request, AaryansAppointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'mom' => 'required|string', // MOM is required when completing
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $appointment->update([
                'mom' => $request->mom,
                'status' => 'Completed', // Force status to Completed
            ]);
            return response()->json($appointment, 200);
        } catch (\Exception $e) {
            Log::error("Error in AppointmentController@completeAppointment for ID {$appointment->id}: " . $e->getMessage());
            return response()->json(['message' => 'Failed to complete appointment.'], 500);
        }
    }


    /**
     * Remove the specified appointment from storage.
     * Corresponds to: DELETE /api/appointments/{id}
     * Handled by: deleteAppointment in frontend
     *
     * @param  \App\AaryansAppointment  $appointment // Laravel Route Model Binding
     * @return \Illuminate\Http\Response
     */
    public function destroy(AaryansAppointment $appointment)
    {
        try {
            $appointment->delete();
            return response()->json(['message' => 'Appointment deleted successfully.'], 204); // 204 No Content
        } catch (\Exception $e) {
            Log::error("Error in AppointmentController@destroy for ID {$appointment->id}: " . $e->getMessage());
            return response()->json(['message' => 'Failed to delete appointment.'], 500);
        }
    }

    /**
     * Get dynamic list of services for the 'To' dropdown.
     * Corresponds to: GET /api/dropdowns/services
     * Handled by: fetchDynamicServices in frontend
     *
     * @return \Illuminate\Http\Response
     */
    public function getServicesDropdown()
    {
        try {
            // Replace this with fetching from a dedicated 'services' or 'departments' table
            // Example if you have a Service model:
            // $services = \App\Service::select('name as label', 'id as value')->get();

            // For demonstration, using hardcoded data
            $services = EmpDet::select('name as label', 'id as value')->where('active', 1)->orderBy('name')->get();
            // $services = [
            //     ['label' => 'Sales Department', 'value' => 'Sales Dept.'],
            //     ['label' => 'Marketing Team', 'value' => 'Marketing Team'],
            //     ['label' => 'Human Resources', 'value' => 'HR Dept.'],
            //     ['label' => 'IT Support', 'value' => 'IT Support'],
            //     ['label' => 'Finance Division', 'value' => 'Finance Div.'],
            //     ['label' => 'Product Development', 'value' => 'Prod Dev.'],
            // ];
            return response()->json($services, 200);
        } catch (\Exception $e) {
            Log::error("Error in AppointmentController@getServicesDropdown: " . $e->getMessage());
            return response()->json(['message' => 'Failed to retrieve services.'], 500);
        }
    }

    public function updateMom(Request $request, $id)
    {
        $request->validate([
            'mom' => 'required|string|max:1000',
        ]);

        $appointment = AaryansAppointment::find($id);

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found.'], 404);
        }

        $appointment->mom = $request->mom;
        $appointment->save();

        return response()->json([
            'message' => 'MOM updated successfully.',
            'data' => $appointment
        ], 200);
    }

    /**
     * Get dynamic list of participants for the 'Participants' dropdown.
     * Corresponds to: GET /api/dropdowns/participants
     * Handled by: fetchDynamicParticipants in frontend
     *
     * @return \Illuminate\Http\Response
     */
    public function getParticipantsDropdown()
    {
        try {
            // Fetch users from your 'users' table, excluding the currently authenticated user
            $participants = User::select('name as label', 'id as value')
                                  ->where('id', '!=', auth()->id()) // Exclude the current user
                                  ->get();

            // Transform value to string if DropDownPicker expects string values consistently
            $participants = $participants->map(function ($item) {
                $item['value'] = (string) $item['value'];
                return $item;
            });

            return response()->json($participants, 200);
        } catch (\Exception $e) {
            Log::error("Error in AppointmentController@getParticipantsDropdown: " . $e->getMessage());
            return response()->json(['message' => 'Failed to retrieve participants.'], 500);
        }
    }
}