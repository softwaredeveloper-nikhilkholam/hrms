<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmpDet;
use App\Holiday;
use App\Notice;
use Validator;
use Carbon\Carbon;

class DashboardsController extends Controller
{
    public function getBirthdays(Request $request)
    {
        $day = Carbon::now()->day;
        $month = Carbon::now()->month;

        $birthdays = EmpDet::whereDay('DOB', $day)
        ->whereMonth('DOB', $month)
        ->where('active', 1)
        ->select('id', 'firstName', 'lastName', 'DOB', 'profilePhoto')
        ->get();

        $data = $birthdays->map(function ($emp) {
            return [
                'id' => $emp->id,
                'name' => $emp->firstName.' '. $emp->lastName,
                'birthDate' => Carbon::parse($emp->DOB)->format('d M'),
                'photo' => $emp->profilePhoto ?? 'https://randomuser.me/api/portraits/lego/1.jpg',
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function getHolidays(Request $request)
    {
        $data = Holiday::where('forDate', '>=',Carbon::now()->format('Y-m-d'))->get();
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function getNotices(Request $request)
    {
        $data = Notice::where('fromDate', '<=', date('Y-m-d'))
            ->where('toDate', '>=', date('Y-m-d'))
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    //work-anniversaries
    public function workAnniversaries(Request $request)
    {
        $today = Carbon::now();

        // Fetch all employees and filter for today's anniversary in PHP
        $employees = EmpDet::where('active', 1)->whereNotNull('jobJoingDate')->get();

        $anniversaries = $employees->filter(function ($employee) use ($today) {
            if (!$employee->jobJoingDate) {
                return false;
            }

            // Get the anniversary date for the current year
            $anniversaryDateThisYear = Carbon::parse($employee->jobJoingDate)->setYear($today->year);
            
            // Check if this year's anniversary date is exactly today
            return $anniversaryDateThisYear->isSameDay($today);

        })->map(function ($employee) use ($today) {
            $yearsCompleted = $today->year - Carbon::parse($employee->jobJoingDate)->year;
            
            // If the anniversary for this year hasn't happened yet, it's yearsCompleted - 1
            $anniversaryDateThisYear = Carbon::parse($employee->jobJoingDate)->setYear($today->year);
            if ($anniversaryDateThisYear->isFuture()) { // This check is mostly for robustness, should be true if it's "today"
                $yearsCompleted -= 1;
            }
            // Ensure yearsCompleted is at least 1 for the first anniversary
            $yearsCompleted = max(1, $yearsCompleted);


            return [
                'id' => $employee->id,
                'employeeName' => $employee->firstName.' '.$employee->lastName,
                'ceremonyType' => 'Work Anniversary',
                'ceremonyDate' => Carbon::parse($employee->jobJoingDate)->setYear($today->year)->toDateString(), // Show this year's anniversary date
                'yearsCompleted' => $yearsCompleted,
                'photo' => $employee->profilePhoto ?? 'default_profile.jpg', // Assuming employee has a photo_path or default
            ];
        })->values()->sortBy('ceremonyDate'); // Reset keys and sort by date

        return response()->json([
            'status' => 'success',
            'data' => $anniversaries->values()->all(),
            'message' => 'Today\'s work anniversaries fetched successfully.'
        ], 200);
    }
}
