<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\FormAndCircular; // Make sure this model exists and is correctly defined
use App\CircularPhoto;   // Assuming you have a model for circular_photos as well, though not strictly required for this method

class FormsAndCircularController extends Controller
{
    /**
     * Display a listing of the active forms and circulars with their associated files.
     * The files are grouped by circular.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Your original join query, which will produce duplicate rows for circulars with multiple photos
        $rawData = CircularPhoto::join('form_and_circulars', 'circular_photos.circularId', '=', 'form_and_circulars.id')
            ->select(
                'form_and_circulars.id',
                'form_and_circulars.name',
                'form_and_circulars.circularNo', // Assuming this column exists
                'form_and_circulars.status',
                'form_and_circulars.created_at',
                'form_and_circulars.updated_at',
                'circular_photos.photo' // This is the file URL
            )
            ->where('form_and_circulars.active', 1)
            ->get();

        // Group the raw data by circular ID to aggregate all photos for each circular
        $groupedData = [];
        foreach ($rawData as $row) {
            $circularId = $row->id;

            // Initialize the circular entry if it doesn't exist yet
            if (!isset($groupedData[$circularId])) {
                $groupedData[$circularId] = [
                    'id' => $row->id,
                    'name' => $row->name,
                    'circularNo' => $row->circularNo,
                    'status' => $row->status,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                    'photos' => [], // Initialize an array to hold all photo URLs
                ];
            }

            // Add the photo URL to the 'photos' array for the current circular
            // Ensure we don't add duplicates if somehow the join produced them (though pluck should handle it)
            if ($row->photo && !in_array($row->photo, $groupedData[$circularId]['photos'])) {
                $groupedData[$circularId]['photos'][] = $row->photo;
            }
        }

        // Convert the associative array back to a simple indexed array
        $transformedData = array_values($groupedData);

        return response()->json([
            'status' => 'success', // Indicate success status
            'data' => $transformedData // The actual array of documents, now with 'photos' array
        ], 200); // HTTP status code 200 for OK
    }
}