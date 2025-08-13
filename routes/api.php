<?php

use Illuminate\Http\Request;

Route::post('/login', 'API\AuthController@login');
Route::get('/getData', 'API\AuthController@getData');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/get-birthdays', 'API\DashboardsController@getBirthdays');
    Route::get('/get-holidays', 'API\DashboardsController@getHolidays');
    Route::get('/get-notices', 'API\DashboardsController@getNotices');
    Route::get('/work-anniversaries', 'API\DashboardsController@workAnniversaries');

    Route::post('/agf-entries', 'API\AGFController@store');
    Route::get('/agf-entries', 'API\AGFController@index');
    Route::get('/agf/{id}', 'API\AGFController@deleteAGF');

    // Your Employees AGF application routes
    Route::prefix('employeesAGF')->group(function () {
        Route::get('list', 'API\AGFController@list'); // List with filters
        Route::put('{id}/approve', 'API\AGFController@approve'); // Approve
        Route::put('{id}/reject', 'API\AGFController@reject');   // Reject
    }); 

    Route::post('/exit-passes', 'API\ExitPassController@store');
    Route::get('/exit-passes', 'API\ExitPassController@index');
    Route::delete('/exit-passes/{id}', 'API\ExitPassController@deleteExitPass');
    
    Route::get('/salary-details', 'API\SalariesController@show');

    Route::get('/formsAndCircular', 'API\FormsAndCircularController@index');

    Route::POST('/attendance/attendance-report', 'API\AttendancesController@showEmployeeReport');

    Route::apiResource('appointments', 'API\AppointmentController');
    Route::put('appointments/{appointment}/complete', 'API\AppointmentController@completeAppointment');
    Route::get('dropdowns/services', 'API\AppointmentController@getServicesDropdown');
    Route::get('dropdowns/participants', 'API\AppointmentController@getParticipantsDropdown');
    Route::patch('/appointments/{id}/mom', 'API\AppointmentController@updateMom');

    Route::get('/employeeProfile', 'API\EmployeeDetailsController@employeeProfile');
    Route::get('/employees', 'API\EmployeeDetailsController@employees');

    Route::get('/attendance/{empId}', 'API\AttendancesController@getByEmp');

    Route::get('hr-tickets', 'API\HrTicketController@index');
    Route::post('hr-tickets', 'API\HrTicketController@store');
});

Route::middleware('auth:api')->post('logout', 'API\AuthController@logout');
