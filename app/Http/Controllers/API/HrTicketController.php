<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Ticket; // Import your HRTicket model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // For authenticating users
use Illuminate\Support\Facades\Log; // For logging errors

class HrTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tickets = Ticket::where('empId', $user->empId)->orderBy('id', 'desc')->get();

        // Optional: Ensure the 'status' field is always included, even if somehow null in DB for old records.
        // This is a safety net; the migration should prevent nulls.
        $tickets->each(function ($ticket) {
            if (is_null($ticket->status)) {
                $ticket->status = 1; // Default to Pending if status is somehow null
            }
        });

        return response()->json($tickets);
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $ticket = new Ticket();
            $ticket->empId = $user->empId;
            $ticket->issueType = $request->input('issueType');
            $ticket->period = $request->input('period');
            $ticket->issue = $request->input('issue');
            $ticket->note = $request->input('note');

            // Set the initial status here (1 for Pending)
            $ticket->status = 1; // Default to Pending for new tickets

            $ticket->save();

            return response()->json($ticket, 201);
        } catch (\Exception $e) {
            Log::error('Error creating HR ticket: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e
            ]);

            return response()->json(['message' => 'Failed to create HR ticket.', 'error' => $e->getMessage()], 500);
        }
    }

    // You will also need update and destroy methods for full functionality
    public function update(Request $request, $id)
    {
        try {
            $ticket = Ticket::where('id', $id)->where('empId', Auth::user()->empId)->firstOrFail();

            $ticket->issueType = $request->input('issueType', $ticket->issueType);
            $ticket->period = $request->input('period', $ticket->period);
            $ticket->issue = $request->input('issue', $ticket->issue);
            $ticket->note = $request->input('note', $ticket->note);
            // HR can update remark and status, so include them if your frontend allows
            $ticket->remark = $request->input('remark', $ticket->remark);
            $ticket->status = $request->input('status', $ticket->status);

            $ticket->save();

            return response()->json($ticket);
        } catch (\Exception $e) {
            Log::error('Error updating HR ticket: ' . $e->getMessage(), [
                'ticket_id' => $id,
                'request_data' => $request->all(),
                'exception' => $e
            ]);
            return response()->json(['message' => 'Failed to update HR ticket.', 'error' => $e->getMessage()], 500);
        }
    } 

    public function destroy($id)
    {
        try {
            // Ensure only the owner can delete their ticket, and only if it's pending
            $ticket = Ticket::where('id', $id)
                            ->where('empId', Auth::user()->empId)
                            ->where('status', 1) // Only allow deletion if status is 1 (Pending)
                            ->firstOrFail();

            $ticket->delete();

            return response()->json(['message' => 'Ticket deleted successfully.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Ticket not found or not authorized to delete.'], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting HR ticket: ' . $e->getMessage(), [
                'ticket_id' => $id,
                'exception' => $e
            ]);
            return response()->json(['message' => 'Failed to delete HR ticket.', 'error' => $e->getMessage()], 500);
        }
    }
}