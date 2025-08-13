<?php

use Illuminate\Http\Request;

use App\Exports\stores\EODProductReportExport;
use App\Exports\stores\OutwardReportExport;
use App\Exports\stores\ProductWiseReportExport;
use App\Exports\stores\InwardReportExport;
use App\Exports\purchase\QuotationReportExport;
use App\Exports\purchase\PurchaseOrderReportExport;
use App\Exports\purchase\WorkOrderReportExport;
use App\Exports\FinalAttendanceSheetExport; // Import the new class
use Illuminate\Support\Facades\Artisan;


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    
    return "Cache, config, routes, and views cleared!";
});

Route::get('/landingPage/getBranchDetails/{name}', 'HomeController@getBranchDetails');
Route::get('/landingPage/getDataForAddEmployee', 'admin\employees\EmployeesController@getDataForAddEmployee')->name('landingPage.getDataForAddEmployee'); 
Route::post('/landingPage/storeDataForAddEmployee', 'admin\employees\EmployeesController@storeDataForAddEmployee'); 

Route::get('/test', 'HomeController@test');
Route::get('/products/printProductQR/{id}', 'storeController\ProductsController@printProductQR');
Route::group( ['middleware' => 'disablepreventback'], function()
{
    Auth::routes();   
    Route::get('/jobs', 'LandingPagesController@jobs');
    Route::get('/jobs/showJobDet/{id}', 'LandingPagesController@showJobDet');
    Route::get('/jobs/applyForm/{id}', 'LandingPagesController@applyForm')->name('applyJob');
    Route::get('/employees/cif', 'admin\employees\EmployeesController@cif');
    Route::get('/jobs/newJob/{type}', 'LandingPagesController@newJob');
    Route::get('/newJob/getDesignations/{jobSection}', 'LandingPagesController@getDesignations');
    Route::post('/jobs/applyJobApplication', 'LandingPagesController@applyJobApplication')->name('applyJobApplication'); 
    Route::post('/employees/storeCIF', 'admin\employees\EmployeesController@storeCIF'); 

    Route::get('/', 'LandingPagesController@index');
    Route::get('/login', 'Auth\LoginController@index')->name('login');
    Route::get('/testlogin', 'Auth\LoginController@testlogin')->name('testlogin');
    Route::get('forgot', 'Auth\LoginController@forgot')->name('forgot');
    Route::post('/forgotPassword', 'Auth\LoginController@forgotPassword')->name('empForgotPassword'); 
    Route::post('postLogin', 'Auth\LoginController@postLogin')->name('postLogin'); 
    Route::get('/departments/getDesignations/{departmentId}', 'admin\masters\DepartmentsController@getDesignations');

    Route::group(['middleware' => 'auth'], function()
    {
        Route::get('/admin/attendance/export', function(Request $request) {
        // Basic validation
        $request->validate([
            'month' => 'required',
            'branchId' => 'required',
        ]);
        
        $branchName = \App\ContactusLandPage::find($request->branchId)->branchName ?? 'Branch';
        $fileName = "Final-Attendance-{$branchName}-{$request->month}.xlsx";

        return Excel::download(new FinalAttendanceSheetExport($request->all()), $fileName);

    })->name('admin.attendance.export');

        Route::get('/check-notifications', function () {
            $note = \App\Notification::where('seen', false)->first();
            if ($note) {
                $note->update(['seen' => true]);
                return response()->json([
                    'status' => true,
                    'message' => $note->message
                ]);
            }
        
            return response()->json(['status' => false]);
        });
        // HRMS Application
        Route::get('/change-password', 'Auth\LoginController@changePassword')->name('change-password'); 

        Route::post('ratingUs', 'HomeController@ratingUs'); 
        Route::post('updatePass', 'HomeController@updatePass'); 
        Route::get('/commonChanges', 'HomeController@commonChanges');
        Route::post('/updateCommonChanges', 'HomeController@updateCommonChanges');

        Route::get('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('reset', 'Auth\LoginController@reset')->name('reset');

        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/empChangePassword', 'HomeController@empChangePassword');
        
        Route::get('/dashboard', 'HomeController@dashboard');
        Route::get('/payslip', 'HomeController@payslip');
        Route::get('/leave', 'HomeController@leave');
        Route::get('/changeLanguage/{type}', 'HomeController@changeLanguage');

        Route::get('/storeEmpTempData', 'admin\employees\EmployeesController@storeEmpTempData');
        
        Route::get('/notificationList', 'HomeController@notificationList');
        Route::get('/getNotificationCount', 'HomeController@getNotificationCount');
        Route::get('/getNotificationMinAgo', 'HomeController@getNotificationMinAgo');
        
        Route::get('/contactusLandPage/activate', 'ContactusLandPagesController@activate');
        Route::get('/contactusLandPage/deactivate', 'ContactusLandPagesController@deactivate');
        Route::resource('/contactusLandPage', 'ContactusLandPagesController');

        Route::get('/sliderLandPage/activate', 'SliderLandPagesController@activate');
        Route::get('/sliderLandPage/deactivate', 'SliderLandPagesController@deactivate');
        Route::resource('/sliderLandPage', 'SliderLandPagesController');

        Route::get('/aboutusLandPage/activate', 'AboutsLandPagesController@activate');
        Route::get('/aboutusLandPage/activate', 'AboutsLandPagesController@activate');
        Route::get('/aboutusLandPage/deactivate', 'AboutsLandPagesController@deactivate');
        Route::resource('/aboutusLandPage', 'AboutsLandPagesController');

        Route::get('/multiLogin/login', 'HomeController@multiLogin');
        Route::get('/multiLogin/setMultiLogin', 'HomeController@setMultiLogin');

        Route::get('/businesslogoLandPage/activate', 'BusinessLogoLandPagesController@activate');
        Route::get('/businesslogoLandPage/deactivate', 'BusinessLogoLandPagesController@deactivate');
        Route::resource('/businesslogoLandPage', 'BusinessLogoLandPagesController');

        Route::get('/funFactsCtLandPage/activate', 'FunFactCtLandPagesController@activate');
        Route::get('/funFactsCtLandPage/deactivate', 'FunFactCtLandPagesController@deactivate');
        Route::resource('/funFactsCtLandPage', 'FunFactCtLandPagesController');

        Route::get('/vedioLandPage/activate', 'VedioLandPagesController@activate');
        Route::get('/vedioLandPage/deactivate', 'VedioLandPagesController@deactivate');
        Route::resource('/vedioLandPage', 'VedioLandPagesController');

        Route::get('/ourTeamLandPage/activate', 'OurTeamLandPagesController@activate');
        Route::get('/ourTeamLandPage/deactivate', 'OurTeamLandPagesController@deactivate');
        Route::resource('/ourTeamLandPage', 'OurTeamLandPagesController');

        Route::get('/socialMediaLandPage/activate', 'MediaLandPagesController@activate');
        Route::get('/socialMediaLandPage/deactivate', 'MediaLandPagesController@deactivate');
        Route::resource('/socialMediaLandPage', 'MediaLandPagesController');

        // Admin Panel Routes
        Route::get('/dashboards/getCities/{regionId}', 'HomeController@getCities');
        
        Route::get('/branches/{id}/activate', 'admin\masters\BranchesController@activate');
        Route::get('/branches/{id}/deactivate', 'admin\masters\BranchesController@deactivate');
        Route::get('/branches/dlist', 'admin\masters\BranchesController@dlist');
        Route::resource('/branches', 'admin\masters\BranchesController');
        
        Route::get('/departments/getReporting/{sectionId}', 'admin\masters\DepartmentsController@getReporting');
        Route::get('/departments/getDepartments/{sectionId}', 'admin\masters\DepartmentsController@getDepartments');
        Route::get('/departments/{id}/activate', 'admin\masters\DepartmentsController@activate');
        Route::get('/departments/{id}/deactivate', 'admin\masters\DepartmentsController@deactivate');
        Route::get('/departments/dlist', 'admin\masters\DepartmentsController@dlist');
        Route::resource('/departments', 'admin\masters\DepartmentsController');

        Route::get('/designations/{active}/excel', 'admin\masters\DesignationsController@excel');
        Route::get('/designations/{id}/activate', 'admin\masters\DesignationsController@activate');
        Route::get('/designations/{id}/deactivate', 'admin\masters\DesignationsController@deactivate');
        Route::get('/designations/dlist', 'admin\masters\DesignationsController@dlist');
        Route::resource('/designations', 'admin\masters\DesignationsController');

        Route::get('/requiredDocuments/{id}/activate', 'admin\masters\RequiredDocumentsController@activate');
        Route::get('/requiredDocuments/{id}/deactivate', 'admin\masters\RequiredDocumentsController@deactivate');
        Route::get('/requiredDocuments/dlist', 'admin\masters\RequiredDocumentsController@dlist');
        Route::resource('/requiredDocuments', 'admin\masters\RequiredDocumentsController');
        
        Route::get('/appointments/exportPDF/{requestTo}/{priority}/{status}/{forDate}', 'admin\AppointmentsController@exportPDF');
        Route::get('/appointments/exportExcel', 'admin\AppointmentsController@exportExcel');
       
        Route::get('/appointments/getLocations', 'admin\AppointmentsController@getLocations');
        Route::get('/appointments/changeStatus', 'admin\AppointmentsController@changeStatus');
        Route::get('/appointments/{id}/rejects', 'admin\AppointmentsController@rejects');
        Route::get('/appointments/requestList', 'admin\AppointmentsController@requestList');
        Route::get('/appointments/search', 'admin\AppointmentsController@search');
        Route::resource('/appointments', 'admin\AppointmentsController');       

        Route::get('/notices/deletedList', 'admin\NoticesController@deletedList');
        Route::get('/notices/dlist', 'admin\NoticesController@dlist');
        Route::get('/notices/{id}/activate', 'admin\NoticesController@activate');
        Route::get('/notices/{id}/deactivate', 'admin\NoticesController@deactivate');
        Route::get('/notices/edit', 'admin\NoticesController@edit');
        Route::resource('/notices', 'admin\NoticesController'); 
        

        
        Route::resource('/resourceRequest', 'admin\ResourceRequestController');   
        
        Route::get('/leavePaymentPolicy/{id}/toggleActive', 'admin\LeavePaymentPolicyController@toggleActive');
        Route::get('/leavePaymentPolicy/dlist', 'admin\LeavePaymentPolicyController@dlist')->name('leavePaymentPolicy.dlist');
        Route::resource('leavePaymentPolicy', 'admin\LeavePaymentPolicyController');

       
        Route::get('/employees/getBranch/{organisationId}', 'admin\employees\EmployeesController@getBranch');
        Route::get('/employees/getEmployeeDetails/{empCode}', 'admin\employees\EmployeesController@getEmployeeDetails');
        Route::get('/employees/addCandidateToEmployee/{candidateId}', 'admin\employees\EmployeesController@addCandidateToEmployee');
        Route::get('/employees/exportFeesConcession/{active}', 'admin\employees\EmployeesController@exportFeesConcession');
        Route::get('/employees/cancelFeesConcession/{id}', 'admin\employees\EmployeesController@cancelFeesConcession');
        Route::get('/employees/showFeesConcession/{id}', 'admin\employees\EmployeesController@showFeesConcession');
        Route::get('/employees/addFeesConcession', 'admin\employees\EmployeesController@addFeesConcession');
        Route::get('/employees/dFeesConcession', 'admin\employees\EmployeesController@dFeesConcession');
        Route::get('/employees/feesConcession', 'admin\employees\EmployeesController@feesConcession');
        Route::get('/employees/addConcession', 'admin\employees\EmployeesController@addConcession');
        Route::get('/employees/getReportingDesignations/{id}', 'admin\employees\EmployeesController@getReportingDesignations');
        Route::get('/employees/downloadCif/{id}', 'admin\employees\EmployeesController@downloadCif');
        Route::get('/employees/{id}/profileInfo', 'admin\employees\EmployeesController@profileInfo');
        Route::get('/employees/exportempExcel/{search}/{active}/{section}', 'admin\employees\EmployeesController@exportempExcel');
        Route::get('/employees/bdayWish/{id}', 'admin\employees\EmployeesController@bdayWish');
        Route::get('/employees/getLastEmpCode/{firmType}', 'admin\employees\EmployeesController@getLastEmpCode');
        Route::get('/employees/teachingEmps', 'admin\employees\EmployeesController@teachingEmps');
        Route::get('/employees/inActiveTeachingEmps', 'admin\employees\EmployeesController@inActiveTeachingEmps');
        Route::get('/employees/nonTeachingEmps', 'admin\employees\EmployeesController@nonTeachingEmps');
        Route::get('/employees/inActiveNonTeachingEmps', 'admin\employees\EmployeesController@inActiveNonTeachingEmps');
        Route::get('/employees/deactive/nonTeachingEmps', 'admin\employees\EmployeesController@deactiveNonTeachingEmps');
        Route::get('/employees/deactive/teachingEmps', 'admin\employees\EmployeesController@deactiveTeachingEmps');
        Route::get('/employees/editTimeChangeRequest/{id}', 'admin\employees\EmployeesController@editTimeChangeRequest');
        Route::get('/employees/changeTimeRequestList', 'admin\employees\EmployeesController@changeTimeRequestList');
        Route::get('/employees/changeTimeRequestListCompleted', 'admin\employees\EmployeesController@changeTimeRequestListCompleted');
        Route::get('/employees/changeTimeRequest', 'admin\employees\EmployeesController@changeTimeRequest');
        Route::get('/employees/changeTime', 'admin\employees\EmployeesController@changeTime');
        Route::get('/employees/changeTimeList', 'admin\employees\EmployeesController@changeTimeList');
        Route::get('/employees/removeUploadDocs/{id}', 'admin\employees\EmployeesController@removeUploadDocs');
        Route::get('/employees/UpdateInTime', 'admin\employees\EmployeesController@UpdateInTime');
        Route::get('/employees/appointmentPerson', 'admin\employees\EmployeesController@appointmentPerson');


        Route::get('/employees/profileRequestList', 'admin\employees\EmployeesController@profileRequestList');
        Route::get('/employees/profileAddRequestList', 'admin\employees\EmployeesController@profileAddRequestList');
        Route::get('/employees/viewProfile/{id}', 'admin\employees\EmployeesController@viewProfile');
        Route::get('/employees/getValidCode/{empId}/{empCode}', 'admin\employees\EmployeesController@getValidCode');
        Route::get('/employees/getReportings/{id}', 'admin\employees\EmployeesController@getReportings');
        Route::get('/employees/{id}/resetPassword', 'admin\employees\EmployeesController@resetPassword');
        Route::get('/employees/search', 'admin\employees\EmployeesController@search');
        Route::get('/employees/getRequiredDocuments/{departmentId}/{designationId}', 'admin\employees\EmployeesController@getRequiredDocuments');
        Route::get('/employees/getEditRequiredDocuments/{empId}', 'admin\employees\EmployeesController@getEditRequiredDocuments');
        Route::get('/employees/uploadEmpExcel', 'admin\employees\EmployeesController@uploadEmpExcel');
        Route::get('/employees/activate', 'admin\employees\EmployeesController@activate');
        Route::get('/employees/deactivate', 'admin\employees\EmployeesController@deactivate');
        Route::get('/employees/dlist', 'admin\employees\EmployeesController@dlist');
        Route::get('/employees/leftEmployeeList', 'admin\employees\EmployeesController@leftEmployeeList');
        Route::get('/employees/changAuthority', 'admin\employees\EmployeesController@changAuthority');
        Route::resource('/employees', 'admin\employees\EmployeesController');
        Route::post('/employees/uploadExcel', 'admin\employees\EmployeesController@uploadExcel');
        Route::post('/employees/oldEmps/updateProfileInfo', 'admin\employees\EmployeesController@updateProfileInfo');
        Route::post('/employees/updateChangeTime', 'admin\employees\EmployeesController@updateChangeTime');
        Route::POST('/employees/updateChangAuthority', 'admin\employees\EmployeesController@updateChangAuthority');
        Route::POST('/employees/storeFeesConcession', 'admin\employees\EmployeesController@storeFeesConcession');
        Route::POST('/employees/saveInOutTime', 'admin\employees\EmployeesController@saveInOutTime');
        Route::POST('/employees/saveAppointmentPerson', 'admin\employees\EmployeesController@saveAppointmentPerson');
        Route::POST('/employees/updateChangeTimeRequest', 'admin\employees\EmployeesController@updateChangeTimeRequest');


        Route::get('/assets/searchAsset', 'admin\masters\AssetsController@searchAsset');
        Route::get('/assets/getAssetDetails/{id}/{type}', 'admin\masters\AssetsController@getAssetDetails');
        Route::get('/assets/{type}/{id}/activate', 'admin\masters\AssetsController@activate');
        Route::get('/assets/{type}/{id}/deactivate', 'admin\masters\AssetsController@deactivate');
        Route::get('/assets/dlist', 'admin\masters\AssetsController@dlist');
        Route::get('/assets/{type}/{id}/editAsset', 'admin\masters\AssetsController@editAsset');
        Route::get('/assets/{type}/{id}/showAsset', 'admin\masters\AssetsController@showAsset');
        Route::resource('/assets', 'admin\masters\AssetsController');
   
        Route::get('/events/{type}/{id}/activate', 'EventsController@activate');
        Route::get('/events/{type}/{id}/deactivate', 'EventsController@deactivate');
        Route::get('/events/dlist', 'EventsController@dlist');
        Route::resource('/events', 'EventsController');

        Route::get('/employeeLetters/concernList', 'admin\EmployeeLettersController@concernList');
        Route::get('/employeeLetters/concernCreate', 'admin\EmployeeLettersController@concernCreate');
        Route::get('/employeeLetters/concernView/{id}', 'admin\EmployeeLettersController@concernView');
        Route::post('/employeeLetters/concernStore', 'admin\EmployeeLettersController@concernStore');

        Route::get('/employeeLetters/getInternalBranchTransferLetter', 'admin\EmployeeLettersController@getInternalBranchTransferLetter');
        Route::get('/employeeLetters/viewInternalBranchTransferLetter/{id}', 'admin\EmployeeLettersController@viewInternalBranchTransferLetter');

        Route::get('/employeeLetters/getInternalDepartmentTransferLetter', 'admin\EmployeeLettersController@getInternalDepartmentTransferLetter');
        Route::get('/employeeLetters/viewInternalDepartmentTransferLetter/{id}', 'admin\EmployeeLettersController@viewInternalDepartmentTransferLetter');

        Route::get('/employeeLetters/getPromotionLetter', 'admin\EmployeeLettersController@getPromotionLetter');
        Route::get('/employeeLetters/viewPromotionLetter/{id}', 'admin\EmployeeLettersController@viewPromotionLetter');

        Route::get('/employeeLetters/list/{letterType}', 'admin\EmployeeLettersController@list');
        Route::get('/employeeLetters/getOfferLetter', 'admin\EmployeeLettersController@getOfferLetter');
        Route::get('/employeeLetters/viewOfferLetter/{id}', 'admin\EmployeeLettersController@viewOfferLetter');
        Route::get('/employeeLetters/getAgreement', 'admin\EmployeeLettersController@getAgreement');
        Route::get('/employeeLetters/viewAgreement/{id}', 'admin\EmployeeLettersController@viewAgreement');
        Route::get('/employeeLetters/getAppointmentLetter', 'admin\EmployeeLettersController@getAppointmentLetter');
        Route::get('/employeeLetters/viewAppointmentLetter/{id}/{flag}', 'admin\EmployeeLettersController@viewAppointmentLetter');
        Route::get('/employeeLetters/viewWarningLetter/{id}', 'admin\EmployeeLettersController@viewWarningLetter');
        Route::get('/employeeLetters/viewExperienceLetter/{id}', 'admin\EmployeeLettersController@viewExperienceLetter');

        Route::get('/employeeLetters/getWarningLetter', 'admin\EmployeeLettersController@getWarningLetter');
        Route::get('/employeeLetters/getExperienceLetter', 'admin\EmployeeLettersController@getExperienceLetter');
        Route::post('/employeeLetters/generateExperience', 'admin\EmployeeLettersController@generateExperience');

        Route::post('/employeeLetters/generateInternalDepartmentTransferLetter', 'admin\EmployeeLettersController@generateInternalDepartmentTransferLetter');
        Route::post('/employeeLetters/generateInternalBranchTransferLetter', 'admin\EmployeeLettersController@generateInternalBranchTransferLetter');
        Route::post('/employeeLetters/generateOfferLetter', 'admin\EmployeeLettersController@generateOfferLetter');
        Route::post('/employeeLetters/generateAppointment', 'admin\EmployeeLettersController@generateAppointment');
        Route::post('/employeeLetters/generateWarningLetter', 'admin\EmployeeLettersController@generateWarningLetter');
        Route::post('/employeeLetters/generatePromotionLetter', 'admin\EmployeeLettersController@generatePromotionLetter');

        Route::post('/employeeLetters/generateAgreement', 'admin\EmployeeLettersController@generateAgreement');
        Route::post('/employeeLetters/saveAgreementLetter', 'admin\EmployeeLettersController@saveAgreementLetter');
          
        Route::get('/formsCirculars/employeeList', 'admin\FormAndCircularsController@employeeList');
        Route::get('/formsCirculars/{id}/activate', 'admin\FormAndCircularsController@activate');
        Route::get('/formsCirculars/{id}/deactivate', 'admin\FormAndCircularsController@deactivate');
        Route::get('/formsCirculars/dlist', 'admin\FormAndCircularsController@dlist');
        Route::resource('/formsCirculars', 'admin\FormAndCircularsController');
       
        Route::get('/holidays/holidayList', 'admin\HolidaysController@holidayList');
        Route::get('/holidays/{id}/activate', 'admin\HolidaysController@activate');
        Route::get('/holidays/{id}/deactivate', 'admin\HolidaysController@deactivate');
        Route::get('/holidays/dlist', 'admin\HolidaysController@dlist');
        Route::get('/holidays/uploadList', 'admin\HolidaysController@uploadList');
        Route::resource('/holidays', 'admin\HolidaysController');
        Route::post('/holidays/uploadHolidayList', 'admin\HolidaysController@uploadHolidayList');

        Route::get('/signFiles/{id}/activate', 'admin\SignatureFilesController@activate');
        Route::get('/signFiles/{id}/deactivate', 'admin\SignatureFilesController@deactivate');
        Route::get('/signFiles/dlist', 'admin\SignatureFilesController@dlist');
        Route::resource('/signFiles', 'admin\SignatureFilesController');

        Route::get('/letterHeads/{id}/activate', 'admin\LetterHeadsController@activate');
        Route::get('/letterHeads/{id}/deactivate', 'admin\LetterHeadsController@deactivate');
        Route::get('/letterHeads/dlist', 'admin\LetterHeadsController@dlist');
        Route::resource('/letterHeads', 'admin\LetterHeadsController');
        
        Route::get('/empApplication/taApplication/{id}/{forDate}/exportPdf', 'admin\employees\EmpApplicationsController@exportPdf');
        Route::get('/empApplications/{empId}/{forDate}/{type}/viewMore', 'admin\employees\EmpApplicationsController@viewMore');
        Route::get('/empApplications/deleteApplication/{id}/{month}', 'admin\employees\EmpApplicationsController@deleteApplication');
        Route::get('/empApplications/applyApplication/{type}', 'admin\employees\EmpApplicationsController@applyApplication');
        Route::get('/empApplications/applyTAllow', 'admin\employees\EmpApplicationsController@applyTAllow');
        Route::get('/empApplications/applicationList', 'admin\employees\EmpApplicationsController@applicationList');
        Route::get('/empApplications/changeStatus', 'admin\employees\EmpApplicationsController@changeStatus');
        Route::get('/empApplications/{id}/activate', 'admin\employees\EmpApplicationsController@activate');
        Route::get('/empApplications/{id}/deactivate', 'admin\employees\EmpApplicationsController@deactivate');
        Route::get('/empApplications/dlist', 'admin\employees\EmpApplicationsController@dlist');

        Route::get('/empApplications/empExtraWorking', 'admin\employees\EmpApplicationsController@empExtraWorking');

        Route::get('/empApplications/empAGFList', 'admin\employees\EmpApplicationsController@empAGFList');
        Route::get('/empApplications/AGFList', 'admin\employees\EmpApplicationsController@AGFList');
        Route::get('/empApplications/{empId}/{forDate}/{type}/AGFShow', 'admin\employees\EmpApplicationsController@AGFShow');
        Route::get('/empApplications/changeAGFStatus', 'admin\employees\EmpApplicationsController@changeAGFStatus');
        Route::get('/empApplications/{month}/{type}/{branchId}/{departmentId}/{designationId}/exportAGFExcel', 'admin\employees\EmpApplicationsController@exportAGFExcel');

        Route::get('/empApplications/empExitPassList', 'admin\employees\EmpApplicationsController@empExitPassList');
        Route::get('/empApplications/exitPassList', 'admin\employees\EmpApplicationsController@exitPassList');
        Route::get('/empApplications/{month}/{type}/exportExitPassExcel', 'admin\employees\EmpApplicationsController@exportExitPassExcel');
        Route::get('/empApplications/{empId}/{forDate}/{type}/exitPassShow', 'admin\employees\EmpApplicationsController@exitPassShow');
        Route::get('/empApplications/changeExitPassStatus', 'admin\employees\EmpApplicationsController@changeExitPassStatus');
        
        Route::get('/empApplications/empTravellingAllownaceList', 'admin\employees\EmpApplicationsController@empTravellingAllownaceList');
        Route::get('/empApplications/travellingTranspList', 'admin\employees\EmpApplicationsController@travellingTranspList');
        Route::get('/empApplications/{month}/{type}/exportTravellingAllowExcel', 'admin\employees\EmpApplicationsController@exportTravellingAllowExcel');
        Route::get('/empApplications/{empId}/{forDate}/{type}/travellingTranspShow', 'admin\employees\EmpApplicationsController@travellingTranspShow');
        Route::get('/empApplications/changeTravellingTranspStatus', 'admin\employees\EmpApplicationsController@changeTravellingTranspStatus');
        Route::get('/empApplications/{flag}/{empId}/{month}/printTravellingAllow', 'admin\employees\EmpApplicationsController@printTravellingAllow');
        Route::get('/travellingAllowance/{empId}/{month}/exportPdfTA', 'admin\employees\EmpApplicationsController@exportPdfTA');
        
        Route::get('/empApplications/empLeaveList', 'admin\employees\EmpApplicationsController@empLeaveList');
        Route::get('/empApplications/leaveList', 'admin\employees\EmpApplicationsController@leaveList');
        Route::get('/empApplications/{month}/{type}/exportLeaveExcel', 'admin\employees\EmpApplicationsController@exportLeaveExcel');
        Route::get('/empApplications/{empId}/{forDate}/{type}/leaveShow', 'admin\employees\EmpApplicationsController@leaveShow');
        Route::get('/empApplications/changeLeaveStatus', 'admin\employees\EmpApplicationsController@changeLeaveStatus');

        Route::get('/empApplications/compOffApplication', 'admin\employees\EmpApplicationsController@compOffApplication');
        Route::get('/empApplications/applyCompoff', 'admin\employees\EmpApplicationsController@applyCompoff');
        Route::get('/empApplications/compdList', 'admin\employees\EmpApplicationsController@compdList');
        Route::get('/empApplications/concessionList', 'admin\employees\EmpApplicationsController@concessionList');
        Route::get('/empApplications/applyConcession', 'admin\employees\EmpApplicationsController@applyConcession');
        Route::get('/empApplications/dConcessionList', 'admin\employees\EmpApplicationsController@dConcessionList');


        Route::get('/creativeIdeas/list', 'admin\CreativeIdeasController@index');
        Route::get('/creativeIdeas/create', 'admin\CreativeIdeasController@create');
        Route::get('/creativeIdeas/dList', 'admin\CreativeIdeasController@dList');


        Route::get('/Projects/list', 'admin\developer\ProjectsController@index');
        Route::get('/Projects/create', 'admin\developer\ProjectsController@create');
        Route::get('/Projects/dList', 'admin\developer\ProjectsController@dList');
        Route::get('/Projects/show', 'admin\developer\ProjectsController@show');


        Route::get('/Module/list', 'admin\developer\ModuleController@index');
        Route::get('/Module/create', 'admin\developer\ModuleController@create');
        Route::get('/Module/dList', 'admin\developer\ModuleController@dList');
        Route::get('/Module/show', 'admin\developer\ModuleController@show');


        Route::get('/subModule/list', 'admin\developer\SubModuleController@index');
        Route::get('/subModule/create', 'admin\developer\SubModuleController@create');
        Route::get('/subModule/dList', 'admin\developer\SubModuleController@dList');
        Route::get('/subModule/show', 'admin\developer\SubModuleController@show');


        Route::get('/developer/list', 'admin\developer\DeveloperController@index');
        Route::get('/developer/create', 'admin\developer\DeveloperController@create');
        Route::get('/developer/dList', 'admin\developer\DeveloperController@dList');
        Route::get('/developer/show', 'admin\developer\DeveloperController@show');



        Route::get('/projectCredentials/list', 'admin\developer\ProjectCreController@index');
        Route::get('/projectCredentials/create', 'admin\developer\ProjectCreController@create');
        Route::get('/projectCredentials/dList', 'admin\developer\ProjectCreController@dList');
        Route::get('/projectCredentials/show', 'admin\developer\ProjectCreController@show');









        Route::get('/empApplications/{month}/{type}/applicationPdfListView', 'admin\employees\EmpApplicationsController@applicationPdfListView');
            
        Route::resource('/empApplications', 'admin\employees\EmpApplicationsController');
        Route::post('/empApplications/updateApplicatinStatus', 'admin\employees\EmpApplicationsController@updateApplicatinStatus');
        Route::post('/empApplications/updateExtraWorking', 'admin\employees\EmpApplicationsController@updateExtraWorking');
        Route::post('/empApplications/updateAttendAGF', 'admin\employees\EmpApplicationsController@updateAttendAGF');

        // employee Recruitement
        Route::get('/candidateApplication/uploadOldEntries', 'admin\recruitments\JobApplicationsController@uploadOldEntries');
        Route::get('/candidateApplication/{id}/addEmployee', 'admin\employees\EmployeesController@addEmployee');
        Route::post('/candidateApplication/upload', 'admin\recruitments\JobApplicationsController@upload');

        Route::get('/candidateApplication/list', 'admin\recruitments\JobApplicationsController@list');
        Route::get('/candidateApplication/editForm/{id}', 'admin\recruitments\JobApplicationsController@editForm');
        Route::get('/candidateApplication/show/{id}', 'admin\recruitments\JobApplicationsController@show');
        Route::get('/candidateApplication/{id}/{round}/showToAssign', 'admin\recruitments\JobApplicationsController@showToAssign');
        Route::get('/candidateApplication/updateAssignTo', 'admin\recruitments\JobApplicationsController@updateAssignTo');
        Route::get('/candidateApplication/updateStatus', 'admin\recruitments\JobApplicationsController@updateStatus');
        
        Route::get('/jobApplications/verificationList', 'admin\recruitments\JobApplicationsController@verificationList');
        Route::get('/jobApplications/verifiedList', 'admin\recruitments\JobApplicationsController@verifiedList');
        Route::get('/jobApplications/createVerification', 'admin\recruitments\JobApplicationsController@createVerification');
        Route::get('/jobApplications/showDetails/{id}/{flag}', 'admin\recruitments\JobApplicationsController@showDetails');
        Route::get('/jobApplications/checkUser/{username}/{password}', 'admin\recruitments\JobApplicationsController@checkUser');
        Route::post('/jobApplications/storeVerification', 'admin\recruitments\JobApplicationsController@storeVerification');
        Route::post('/jobApplications/updateJobApplication', 'admin\recruitments\JobApplicationsController@updateJobApplication');

        Route::get('/jobApplications/walkinList', 'admin\recruitments\JobApplicationsController@walkinList');
        Route::get('/jobApplications/walkinCreate', 'admin\recruitments\JobApplicationsController@walkinCreate');
        Route::get('/jobApplications/walkinEdit/{id}', 'admin\recruitments\JobApplicationsController@walkinEdit');
        Route::get('/getBioMetricStatus', 'HomeController@getBioMetricStatus');
        
        Route::get('/jobApplications/walkinShow/{id}', 'admin\recruitments\JobApplicationsController@walkinShow');
       
        Route::get('/jobApplications/interviewDList', 'admin\recruitments\JobApplicationsController@interviewDList');
        Route::get('/jobApplications/interviewDCreate', 'admin\recruitments\JobApplicationsController@interviewDCreate');
        Route::get('/jobApplications/interviewDEdit/{id}', 'admin\recruitments\JobApplicationsController@interviewDEdit');
        Route::get('/jobApplications/interviewDShow/{id}', 'admin\recruitments\JobApplicationsController@interviewDShow');
      
        Route::post('/jobApplications/walkinStore', 'admin\recruitments\JobApplicationsController@walkinStore');
        Route::post('/jobApplications/walkinUpdate', 'admin\recruitments\JobApplicationsController@walkinUpdate');
        Route::post('/jobApplications/interviewDStore', 'admin\recruitments\JobApplicationsController@interviewDStore');
        Route::post('/jobApplications/interviewDUpdate', 'admin\recruitments\JobApplicationsController@interviewDUpdate');

        Route::get('/jobApplications/printList/{fromDate}/{toDate}', 'admin\recruitments\JobApplicationsController@printList');
        Route::get('/jobApplications/recruiteIdList', 'admin\recruitments\JobApplicationsController@recruiteIdDList');
        Route::get('/jobApplications/recruiteIdCreate', 'admin\recruitments\JobApplicationsController@recruiteIdCreate');
        Route::get('/jobApplications/recruiteIdEdit', 'admin\recruitments\JobApplicationsController@recruiteIdEdit');
        Route::get('/jobApplications/recruiteIdShow/{id}', 'admin\recruitments\JobApplicationsController@recruiteIdShow');
        Route::post('/jobApplications/recruiteIdStore', 'admin\recruitments\JobApplicationsController@recruiteIdStore');
        Route::post('/jobApplications/recruiteIdUpdate', 'admin\recruitments\JobApplicationsController@recruiteIdUpdate');

        Route::get('/jobApplications/applicationPrint/{id}', 'admin\recruitments\JobApplicationsController@applicationPrint');
        Route::get('/jobApplications/printCIF/{id}', 'admin\recruitments\JobApplicationsController@printCIF');
        
        Route::get('/empCif/walkinJobApplication', 'admin\recruitments\EmpJobsController@walkinJobApplication');
        Route::get('/empCif/interviewDriveJobApplication', 'admin\recruitments\EmpJobsController@interviewDriveJobApplication');
        Route::get('/empCif/recruitementJobApplication', 'admin\recruitments\EmpJobsController@recruitementJobApplication');
        Route::get('/empCif/showJobApplication/{id}', 'admin\recruitments\EmpJobsController@showJobApplication');
        Route::post('/empCif/storeJobApplication', 'admin\recruitments\EmpJobsController@storeJobApplication');
        
        Route::get('/empJobs/vacancy', 'admin\recruitments\EmpJobsController@vacancy');
        Route::get('/empJobs/{id}/activate', 'admin\recruitments\EmpJobsController@activate');
        Route::get('/empJobs/{id}/deactivate', 'admin\recruitments\EmpJobsController@deactivate');
        Route::get('/empJobs/dlist', 'admin\recruitments\EmpJobsController@dlist');
        Route::resource('/empJobs', 'admin\recruitments\EmpJobsController');

        //branch to branch distance
        Route::get('/branchDists/{id}/activate', 'admin\masters\BranchDistancesController@activate');
        Route::get('/branchDists/{id}/deactivate', 'admin\masters\BranchDistancesController@deactivate');
        Route::get('/branchDists/dlist', 'admin\masters\BranchDistancesController@dlist');
        Route::resource('/branchDists', 'admin\masters\BranchDistancesController');

        //employee advance
        Route::get('/empAttendances/confirmSheetList', 'admin\EmpAttendancesController@confirmSheetList')->name('finalSheet.confirmSheetList');
        Route::get('/employeeAttendance/getDepartments/{section}', 'admin\EmpAttendancesController@getDepartments');
        Route::get('/empAttendances/salaryHoldList', 'admin\EmpAttendancesController@salaryHoldList');
        Route::get('/empAttendances/salaryHoldDList', 'admin\EmpAttendancesController@salaryHoldDList');
        Route::get('/empAttendances/{id}/editSalaryHoldDetail', 'admin\EmpAttendancesController@editSalaryHoldDetail');
        Route::get('/empAttendances/{id}/activeOrDeactiveSalaryStatus', 'admin\EmpAttendancesController@activeOrDeactiveSalaryStatus');
        Route::get('/empAttendances/searchSalaryHold', 'admin\EmpAttendancesController@searchSalaryHold');
        Route::get('/empAdvRs/{id}/activate', 'admin\employees\EmpAdvRsController@activate');
        Route::get('/empAdvRs/{id}/deactivate', 'admin\employees\EmpAdvRsController@deactivate');
        Route::get('/empAdvRs/dlist', 'admin\employees\EmpAdvRsController@dlist');
        Route::resource('/empAdvRs', 'admin\employees\EmpAdvRsController');
        Route::get('/empPayroll/salaryReport', 'admin\EmpAttendancesController@salaryReport');
        Route::get('/empAttendances/changeDays/{empId}/{month}/{totDays}', 'admin\EmpAttendancesController@changeDays');
        
        //employee Debits
        Route::get('/empDebits/{id}/activate', 'admin\employees\EmpDebitsController@activate');
        Route::get('/empDebits/{id}/deactivate', 'admin\employees\EmpDebitsController@deactivate');
        Route::get('/empDebits/{month}/exportExcel', 'admin\employees\EmpDebitsController@exportExcel');
        Route::get('/empDebits/dlist', 'admin\employees\EmpDebitsController@dlist');
        Route::resource('/empDebits', 'admin\employees\EmpDebitsController');
        
        Route::get('/empPayroll/salarySlip', 'admin\EmpAttendancesController@salarySlip');
        Route::get('/empPayroll/raiseReqSalarySlip', 'admin\EmpAttendancesController@raiseReqSalarySlip');
        
        Route::get('/empPayroll/form16', 'admin\EmpAttendancesController@form16');
        Route::get('/empPayroll/raiseReqForm16', 'admin\EmpAttendancesController@raiseReqForm16');

        Route::get('/empPayroll/salaryCertificate', 'admin\EmpAttendancesController@salaryCertificate');
        Route::get('/empPayroll/raiseReqSalaryCertificate', 'admin\EmpAttendancesController@raiseReqSalaryCertificate');
       
        
        
        Route::post('/empPayroll/updateRaiseReqSalarySlip', 'admin\EmpAttendancesController@updateRaiseReqSalarySlip');
        Route::post('/empPayroll/updateRaiseReqForm16', 'admin\EmpAttendancesController@updateRaiseReqForm16');
        Route::post('/empPayroll/updateRaiseReqSalaryCertificate', 'admin\EmpAttendancesController@updateRaiseReqSalaryCertificate');
        Route::post('/empPayroll/updatecChangeDays', 'admin\EmpAttendancesController@updatecChangeDays');
        
        Route::get('/empAttendances/getPuntchTime/{forDate}/{empCode}', 'admin\EmpAttendancesController@getPuntchTime');
        Route::get('/empAttendances/generateAttendance', 'admin\EmpAttendancesController@generateAttendance');
        Route::get('/empAttendances/uploadEmpExcel', 'admin\EmpAttendancesController@uploadEmpExcel');

        Route::get('/empAttendances/finalAttendanceSheet', 'admin\EmpAttendancesController@finalAttendanceSheet')->name('admin.finalAttendanceSheet');
        Route::get('/empAttendances/finalAttendanceSheetList', 'admin\EmpAttendancesController@finalAttendanceSheetList');
        Route::get('/empAttendances/exportExcel', 'admin\EmpAttendancesController@exportExcel');
        Route::get('/empAttendances/exportPDF/{empCode}/{branchId}/{departmentId}/{month}', 'admin\EmpAttendancesController@exportPDF');
        Route::get('/empAttendances/uploadAttendanceSheet', 'admin\EmpAttendancesController@uploadAttendanceSheet');
        Route::get('/empAttendances/search', 'admin\EmpAttendancesController@search')->name('admin.searchAttendance');
        Route::get('/empAttendances/dlist', 'admin\EmpAttendancesController@dlist');
        Route::get('/empAttendances/empPolicy', 'admin\EmpAttendancesController@empPolicy');

        Route::get('/empAttendances/editFinalAttendanceSheet/{empId}/{forMonth}', 'admin\EmpAttendancesController@editFinalAttendanceSheet')->name('admin.attendance.editFinalAttendanceSheet');

        Route::resource('/empAttendances', 'admin\EmpAttendancesController');
        Route::post('/empAttendances/uploadExcel', 'admin\EmpAttendancesController@uploadExcel');
        Route::post('/empAttendances/updateEmpPolicy', 'admin\EmpAttendancesController@updateEmpPolicy');
        Route::post('/admin/attendance/update-final-days', [EmpAttendancesController::class, 'updateFinalDays'])->name('admin.attendance.updateFinalDays');
        Route::post('/empAttendances/confirmation', 'admin\EmpAttendancesController@confirmation')->name('admin.attendance.confirmation');
        Route::post('/attendance/process-and-save', 'admin\EmpAttendancesController@processAndSaveAttendance')->name('admin.attendance.process-and-save');

        Route::post('/empAttendances/updateFinalAttendance', 'admin\EmpAttendancesController@updateFinalAttendance')->name('admin.attendance.updateFinalAttendance');
        Route::post('/empAttendances/updateAttendAGF', 'admin\EmpAttendancesController@updateAttendAGF');
        Route::post('/empAttendances/updateSalaryStatus', 'admin\EmpAttendancesController@updateSalaryStatus');
        
        Route::get('/hrPolicies/listWFH', 'admin\HrPoliciesController@listWFH');
        Route::get('/hrPolicies/createWFH', 'admin\HrPoliciesController@createWFH');
        Route::get('/hrPolicies/{id}/editWFH', 'admin\HrPoliciesController@editWFH');
        Route::get('/hrPolicies/{id}/showWFH', 'admin\HrPoliciesController@showWFH');
        Route::get('/hrPolicies/{id}/removeResignation', 'admin\HrPoliciesController@removeResignation');
        
        Route::get('/exitProces/exportExcel/{type}', 'admin\HrPoliciesController@exportExcel');
        Route::get('/exitProcess/{id}/printNDC', 'admin\HrPoliciesController@printNDC')->name('exit.printNDC');
        Route::get('/exitProces/deleteResignation/{id}', 'admin\HrPoliciesController@deleteResignation');
      
        Route::get('/exitProces/employeeResignation', 'admin\HrPoliciesController@employeeResignation');
        Route::get('/exitProces/apply', 'admin\HrPoliciesController@apply');
        Route::get('/exitProces/view/{id}', 'admin\HrPoliciesController@view');
        Route::get('/exitProces/standardProcess', 'admin\HrPoliciesController@standardProcess');
        Route::get('/exitProces/archieveStandardProcess', 'admin\HrPoliciesController@archieveStandardProcess');
        Route::get('/exitProces/terminationProcess', 'admin\HrPoliciesController@terminationProcess');
        Route::get('/exitProces/sabiticalProcess', 'admin\HrPoliciesController@sabiticalProcess');
        Route::get('/exitProces/abscondingProcess', 'admin\HrPoliciesController@abscondingProcess');
        Route::get('/exitProces/search', 'admin\HrPoliciesController@search');
        Route::get('/hrPolicies/trainingDevelopment', 'admin\HrPoliciesController@calender');
        Route::resource('/hrPolicies', 'admin\HrPoliciesController');
        Route::post('/exitProces/storeExitProcess', 'admin\HrPoliciesController@storeExitProcess');
        Route::post('/hrPolicies/storeResignation', 'admin\HrPoliciesController@storeResignation');
        
        Route::post('/hrPolicies/storeWFH', 'admin\HrPoliciesController@storeWFH');
        Route::post('/hrPolicies/updateWFH', 'admin\HrPoliciesController@updateWFH');

        //employees 
        Route::get('/tickets/downloadSalaryCertificate/{empId}/{startMonth}/{endMonth}', 'admin\TicketsController@downloadSalaryCertificate');
        Route::get('/tickets/exportExcel/{year}/{type}', 'admin\TicketsController@exportExcel');
        Route::get('/tickets/raiseTicket/{flag}', 'admin\TicketsController@raiseTicket');
        Route::get('/tickets/{id}/changeStatus', 'admin\TicketsController@changeStatus');
        Route::get('/tickets/list', 'admin\TicketsController@list');
        Route::get('/tickets/deactivate/{id}', 'admin\TicketsController@deactivate');
        Route::get('/tickets/allTickets', 'admin\TicketsController@allTickets');
        Route::get('/tickets/archiveTicketList', 'admin\TicketsController@archiveTicketList');
        Route::resource('/tickets', 'admin\TicketsController');
        
        Route::post('/tickets/updateStatus', 'admin\TicketsController@updateStatus');
        Route::get('/userAllocations/{id}/deactivate', 'admin\UserAllocationsController@deactivate');
        Route::get('/userAllocations/{id}/activate', 'admin\UserAllocationsController@activate');
        Route::get('/userAllocations/search', 'admin\UserAllocationsController@search');
        Route::get('/changeLogin/{type}', 'admin\UserAllocationsController@changeLogin');
        Route::resource('/userAllocations', 'admin\UserAllocationsController');
        Route::post('/userAllocations/updateMenu', 'admin\UserAllocationsController@updateMenu');

        //Aprisal Routes
        Route::get('/apprisal/exportExcel/{year}', 'admin\AppraisalsController@exportExcel');
        Route::resource('/apprisal', 'admin\AppraisalsController');

        //account Department
        Route::get('/accounts/retention', 'admin\AccountsController@retention');
        Route::get('/accounts/getAdvancePeriod/{month}', 'admin\AccountsController@getAdvancePeriod');
        Route::get('/empAttendances/exportSalarySlip/{month}', 'admin\EmpAttendancesController@exportSalarySlip');
        Route::get('/accounts', 'admin\AccountsController@index');
        Route::get('/accounts/viewMRReport/{id}', 'admin\AccountsController@viewMRReport');
        Route::get('/accounts/salarySheet', 'admin\AccountsController@salarySheet');
        Route::get('/accounts/uploadMR', 'admin\AccountsController@uploadMR');
        Route::get('/accounts/uploadSalarySheet', 'admin\AccountsController@uploadSalarySheet');
        Route::get('/accounts/exportSalarySheet/{organisation}/{branch}/{section}/{salaryType}/{month}', 'admin\AccountsController@exportSalarySheet');
        Route::get('/accounts/exportBankDetails/{organisation}/{branch}/{section}/{salaryType}/{month}', 'admin\AccountsController@exportBankDetails');
        Route::post('/accounts/updateSalarySheet', 'admin\AccountsController@updateSalarySheet');
        Route::post('/accounts/updateUploadMR', 'admin\AccountsController@updateUploadMR');
        Route::POST('/accounts/uploadRetention', 'admin\AccountsController@uploadRetention');
        
        //reports
        
        Route::get('/reports/arrearsExportReport', 'admin\ReportsController@arrearsExportReport')->name('reports.arrearsExportReport');
        Route::get('/reports/arrearsReport', 'admin\ReportsController@arrearsReport')->name('reports.arrearsReport');
        Route::get('/reports/attendanceToExport', 'admin\ReportsController@exportAttendanceToExcel')->name('exportAttendanceToExcel');
        Route::get('/reports/exportPaidLeaveReport', 'admin\ReportsController@exportPaidLeaveReport')->name('reports.exportPaidLeaveReport');
        Route::get('/reports/paidLeaveReport', 'admin\ReportsController@paidLeaveReport');
        Route::get('/reports/logTimeReport', 'admin\ReportsController@logTimeReport');
        Route::get('/reports/empHistory', 'admin\employees\EmployeesController@empHistory');
        Route::get('/reports/newJoinee', 'admin\employees\EmployeesController@newJoinee');
        Route::get('/reports/exportNewJoinee/{startDate}/{endDate}', 'admin\employees\EmployeesController@exportNewJoinee');
        Route::get('/reports/getNDCHistory', 'admin\ReportsController@getNDCHistory');
        Route::get('/reports/getContractReport', 'admin\ReportsController@getContractReport');
        Route::get('/reports/getContractReportExcel', 'admin\ReportsController@getContractReportExcel');
        Route::get('/reports/{empId}/{forDate}/{type}/applicationPdfView', 'admin\ReportsController@applicationPdfView');
        Route::get('/reports/attendanceReport', 'admin\ReportsController@attendanceReport')->name('reports.attendanceReport');
        Route::get('/reports/searchAttendance', 'admin\ReportsController@searchAttendance');
        Route::get('/reports/pendingInfo', 'admin\ReportsController@pendingInfo');
        Route::get('/reports/AGFReport', 'admin\ReportsController@AGFReport');
        Route::get('/reports/PDFAGFReport/{branchId}/{departmentId}/{month}', 'admin\ReportsController@PDFAGFReport');
        Route::get('/reports/excelAGFReport/{branchId}/{departmentId}/{month}/{type}', 'admin\ReportsController@excelAGFReport');
        Route::get('/reports/{empId}/{forDate}/{type}/viewMore', 'admin\ReportsController@viewMore');
        Route::get('/reports/exitPassReport', 'admin\ReportsController@exitPassReport');
        Route::get('/reports/PDFExitPassReport/{branchId}/{departmentId}/{fromDate}/{toDate}', 'admin\ReportsController@PDFExitPassReport');
        Route::get('/reports/excelExitPassReport/{branchId}/{departmentId}/{fromDate}/{toDate}', 'admin\ReportsController@excelExitPassReport');
        Route::get('/reports/leaveReport', 'admin\ReportsController@leaveReport');
        Route::get('/reports/PDFLeaveReport/{branchId}/{departmentId}/{fromDate}/{toDate}', 'admin\ReportsController@PDFLeaveReport');
        Route::get('/reports/excelLeaveReport/{branchId}/{departmentId}/{fromDate}/{toDate}', 'admin\ReportsController@excelLeaveReport');
        Route::get('/reports/travellingAllowReport', 'admin\ReportsController@travellingAllowReport');
        Route::get('/reports/PDFTravellingAllowReport/{branchId}/{departmentId}/{fromDate}/{toDate}', 'admin\ReportsController@PDFTravellingAllowReport');
        Route::get('/reports/excelTravellingAllowReport/{branchId}/{departmentId}/{fromDate}/{toDate}', 'admin\ReportsController@excelTravellingAllowReport');
        Route::get('/reports/retentionReport', 'admin\ReportsController@retentionReport');
        Route::get('/reports/exportRetentionReport/{branchId}', 'admin\ReportsController@exportRetentionReport');
        Route::get('/reports/extraWorkingReport', 'admin\ReportsController@extraWorkingReport');
        Route::get('/reports/extraWorkingReportDet/{empId}/{fromDate}/{endDate}', 'admin\ReportsController@extraWorkingReportDet');
        Route::get('/reports/PDFExtraWorkingReport/{branchId}/{departmentId}/{fromDate}/{toDate}', 'admin\ReportsController@PDFExtraWorkingReport');
        Route::get('/reports/excelExtraWorkingReport/{branchId}/{departmentId}/{fromDate}/{toDate}', 'admin\ReportsController@excelExtraWorkingReport');
        Route::get('/reports/showStatusReport/{id}/{type}', 'admin\ReportsController@showStatusReport');
        Route::get('/reports/recruitementReport', 'admin\ReportsController@recruitementReport');
        Route::get('/reports/recruitementReport/{page}/{applicationType}/{status}/{fromDate}/{toDate}/pdfRecruitementReport', 'admin\ReportsController@pdfRecruitementReport');
        Route::get('/reports/recruitementReport/{page}/{applicationType}/{status}/{fromDate}/{toDate}/excelRecruitementReport', 'admin\ReportsController@excelRecruitementReport');

        // Route::group(['middleware' => 'superAdminMiddleware'], function()
        // {
            Route::get('/selectApplication/{type}', 'HomeController@selectApplication');
            // Route::get('/storeHome', 'HomeController@storeHome')->name('storeHome');
            Route::get('/quotation/{id}/printQuotation', 'storeController\PurchaseTransactions@printQuotation');
            Route::get('/quotation/getProducts/{id}', 'storeController\PurchaseTransactions@getProducts');

            Route::get('/vendor/list', 'storeController\VendorsController@list');
            Route::get('/quotation/list', 'storeController\PurchaseTransactions@list');
            Route::get('/quotation/saveList', 'storeController\PurchaseTransactions@saveList');

        // });

         Route::group(['middleware' => 'itDepartmentMiddleware'], function()
         {
                Route::get('/landingPage', 'HomeController@landingPage');
         });
        // Route::group(['middleware' => 'storeMiddleware'], function()
        // {
            
            Route::get('/produdcts/printQRCode/{productId}', 'storeController\ProductsController@printQRCode');
            Route::get('/produdcts/getLastProductCode', 'storeController\ProductsController@getLastProductCode');
            Route::get('/product/productList', 'storeController\ProductsController@productList');
            Route::get('/product/productDetails/{name}', 'storeController\ProductsController@productDetails');
            Route::get('/products/getProductLists/{name}', 'storeController\ProductsController@getProductLists');
            Route::get('/produdcts/exportExcelSheet/{search}/{active}', 'storeController\ProductsController@exportExcelSheet');
            
            Route::get('/commonPage', 'HomeController@commonPage')->name('commonPage');
            Route::get('/storeHome', 'HomeController@storeHome')->name('storeHome');

            Route::get('/categories/categoryList', 'storeController\CategoriesController@categoryList');
            Route::get('/categories/getSubCategory/{id}', 'storeController\CategoriesController@getSubCategory');
            Route::get('/categories/getSubCategoryStore/{id}', 'storeController\CategoriesController@getSubCategoryStore');
            Route::get('/categories/getQuotationSubCategory/{id}', 'storeController\CategoriesController@getQuotationSubCategory');
            Route::get('/categories/exportToExcel/{active}', 'storeController\CategoriesController@exportToExcel');

            Route::get('/category/{id}/activate', 'storeController\CategoriesController@activate');
            Route::get('/category/{id}/deactivate', 'storeController\CategoriesController@deactivate');
            Route::get('/category/search', 'storeController\CategoriesController@search');
            Route::get('/category/dlist', 'storeController\CategoriesController@dlist');
            Route::resource('/category', 'storeController\CategoriesController');

            Route::get('/subCategory/{id}/activate', 'storeController\SubCategoriesController@activate');
            Route::get('/subCategory/{id}/deactivate', 'storeController\SubCategoriesController@deactivate');
            Route::get('/subCategory/search', 'storeController\SubCategoriesController@search');
            Route::get('/subCategory/dlist', 'storeController\SubCategoriesController@dlist');
            Route::get('/subCategories/subCategoryList/{name}', 'storeController\SubCategoriesController@subCategoryList');    

            Route::resource('/subCategory', 'storeController\SubCategoriesController');

            Route::get('/hall/{id}/activate', 'storeController\HallsController@activate');
            Route::get('/hall/{id}/deactivate', 'storeController\HallsController@deactivate');
            Route::get('/hall/search', 'storeController\HallsController@search');
            Route::get('/hall/dlist', 'storeController\HallsController@dlist');
            Route::resource('/hall', 'storeController\HallsController');

            Route::get('/unit/{id}/activate', 'storeController\UnitsController@activate');
            Route::get('/unit/{id}/deactivate', 'storeController\UnitsController@deactivate');
            Route::get('/unit/search', 'storeController\UnitsController@search');
            Route::get('/unit/dlist', 'storeController\UnitsController@dlist');
            Route::resource('/unit', 'storeController\UnitsController');

            Route::get('/rack/{id}/{active}/printPDF', 'storeController\RacksController@printPDF');
            Route::get('/rack/{id}/activate', 'storeController\RacksController@activate');
            Route::get('/rack/{id}/deactivate', 'storeController\RacksController@deactivate');
            Route::get('/rack/search', 'storeController\RacksController@search');
            Route::get('/rack/dlist', 'storeController\RacksController@dlist');
            Route::resource('/rack', 'storeController\RacksController');

            Route::get('/shelf/{id}/activate', 'storeController\ShelfsController@activate');
            Route::get('/shelf/{id}/deactivate', 'storeController\ShelfsController@deactivate');
            Route::get('/shelf/search', 'storeController\ShelfsController@search');
            Route::get('/shelf/getRacks/{id}', 'storeController\ShelfsController@getRacks');
            Route::get('/shelf/dlist', 'storeController\ShelfsController@dlist');
            Route::resource('/shelf', 'storeController\ShelfsController');

            Route::get('/products/getInOutProductList/{categoryId}/{subCategory}', 'storeController\ProductsController@getInOutProductList');
            Route::get('/product/{id}/activate', 'storeController\ProductsController@activate');
            Route::get('/product/{id}/deactivate', 'storeController\ProductsController@deactivate');
            Route::get('/product/search', 'storeController\ProductsController@search');
            Route::get('/product/getShelfs/{id}', 'storeController\ProductsController@getShelfs');
            Route::get('/product/dlist', 'storeController\ProductsController@dlist');
            Route::get('/products/getProductDetails/{name}', 'storeController\ProductsController@getProductDetails');
            
            Route::get('/product/changeOpeningStock/{id}', 'storeController\ProductsController@changeOpeningStock');
            Route::get('/product/searchProduct', 'storeController\ProductsController@searchProduct');
            Route::resource('/product', 'storeController\ProductsController');
            Route::post('/product/generateQRCodes', 'storeController\ProductsController@generateQRCodes');
            Route::post('/product/updateOpeningStock', 'storeController\ProductsController@updateOpeningStock');

            Route::get('/storeUser/checkUsername/{username}', 'storeController\StoreUsersController@checkUsername');
            Route::get('/storeUser/getEmployee/{empCode}', 'storeController\StoreUsersController@getEmployee');
            Route::get('/storeUser/{id}/activate', 'storeController\StoreUsersController@activate');
            Route::get('/storeUser/{id}/deactivate', 'storeController\StoreUsersController@deactivate');
            Route::get('/storeUser/dlist', 'storeController\StoreUsersController@dlist');
            Route::resource('/storeUser', 'storeController\StoreUsersController');

            Route::get('/scrapCategory/{id}/activate', 'storeController\ScrapCategoriesController@activate');
            Route::get('/scrapCategory/{id}/deactivate', 'storeController\ScrapCategoriesController@deactivate');
            Route::get('/scrapCategory/dlist', 'storeController\ScrapCategoriesController@dlist');
            Route::resource('/scrapCategory', 'storeController\ScrapCategoriesController');

            Route::resource('/scraps', 'storeController\ScrapsController');

        // });

        // Route::group(['middleware' => 'purchaseMiddleware'], function()
        // {
            Route::get('/inwardGRNs/getGRN/{id}', 'storeController\InwardGrnsController@getGRN');
            Route::resource('/inwardGRNs', 'storeController\InwardGrnsController');

            Route::get('/inwards/productReturnView/{id}', 'storeController\InwardsController@productReturnView');
            Route::get('/inwards/productReturnList', 'storeController\InwardsController@productReturnList');
            Route::get('/inwards/productReturn', 'storeController\InwardsController@productReturn');

            Route::get('/inwards/printInward/{id}', 'storeController\InwardsController@printInward');
            Route::get('/inwards/getVAddress/{id}', 'storeController\InwardsController@getVAddress');
            Route::resource('/inwards', 'storeController\InwardsController');
            Route::POST('/inwards/productReturnStore', 'storeController\InwardsController@productReturnStore');

            Route::get('/outwards/{id}/products', 'storeController\OutwardController@getProducts');
            Route::get('/outwards/oldList', 'storeController\OutwardsController@oldList');
            Route::get('/outwards/productReturn/{id}', 'storeController\OutwardsController@productReturn');
            Route::get('/outwards/printOutward/{id}', 'storeController\OutwardsController@printOutward');
            Route::get('/outwards/showDetails/{id}', 'storeController\OutwardsController@showDetails');
            Route::get('/outwards/getRequisition/{id}', 'storeController\OutwardsController@getRequisition');
            Route::get('/outwards/getVAddress/{id}', 'storeController\OutwardsController@getVAddress');
            Route::resource('/outwards', 'storeController\OutwardsController');
            Route::POST('/outwards/updatedDeliveryHistory', 'storeController\OutwardsController@updatedDeliveryHistory');
            Route::POST('/outwards/updateReturnProduct', 'storeController\OutwardsController@updateReturnProduct');
            

            Route::get('/purchaseHome', 'HomeController@purchaseHome')->name('purchaseHome');

            Route::get('/vendor/{id}/exportExcel', 'storeController\VendorsController@exportExcel');
            Route::get('/vendor/{id}/getVendorDetails', 'storeController\VendorsController@getVendorDetails');
            Route::get('/vendor/{id}/activate', 'storeController\VendorsController@activate');
            Route::get('/vendor/{id}/deactivate', 'storeController\VendorsController@deactivate');
            Route::get('/vendor/dlist', 'storeController\VendorsController@dlist');
            Route::resource('/vendor', 'storeController\VendorsController');

            Route::get('/payments/edit/{id}', 'storeController\PurchaseTransactions@edit');
            Route::get('/payments/{id}/rejectPayment', 'storeController\PurchaseTransactions@rejectPayment');
            Route::get('/payments/POPayment', 'storeController\PurchaseTransactions@POPayment');
            Route::get('/payments/POPaidPayments', 'storeController\PurchaseTransactions@POPaidPayments');

            Route::get('/purchaseOrder/purchasedSuccessfully/{id}', 'storeController\PurchaseTransactions@purchasedSuccessfully');
            Route::get('/purchaseOrder/productList', 'storeController\PurchaseTransactions@productList');
            Route::get('/purchaseOrder/completedProductList', 'storeController\PurchaseTransactions@completedProductList');
            Route::get('/purchaseOrder/purchaseOrderList', 'storeController\PurchaseTransactions@purchaseOrderList');
            Route::get('/purchaseOrder/paidPurchaseOrderList', 'storeController\PurchaseTransactions@paidPurchaseOrderList');
            Route::get('/purchaseOrder/viewPO/{id}', 'storeController\PurchaseTransactions@viewPO');
            Route::get('/purchaseOrder/viewPONumber/{poNumber}', 'storeController\PurchaseTransactions@viewPONumber');
            Route::get('/purchaseOrder/printPO/{id}', 'storeController\PurchaseTransactions@printPO');

            
            Route::get('/quotation/{commQuotId}/editQuotation', 'storeController\PurchaseTransactions@editQuotation');

            Route::get('/quotation/{id}/printQuotation', 'storeController\PurchaseTransactions@printQuotation');
            Route::get('/quotation/{id}/activate', 'storeController\PurchaseTransactions@activate');
            Route::get('/quotation/{id}/deactivate', 'storeController\PurchaseTransactions@deactivate');
            Route::get('/quotation/rejectedQuotationList', 'storeController\PurchaseTransactions@rejectedQuotationList');
            Route::get('/quotation/quotationList', 'storeController\PurchaseTransactions@list');
            Route::get('/quotation/approvedQuotationList', 'storeController\PurchaseTransactions@approvedQuotationList');
            Route::resource('/quotation', 'storeController\PurchaseTransactions');
            Route::post('/quotation/generateQuot', 'storeController\PurchaseTransactions@generateQuot');
            Route::post('/quotation/approveQuotation', 'storeController\PurchaseTransactions@approveQuotation');
            Route::post('/purchaseOrder/updateProducts', 'storeController\PurchaseTransactions@updateProducts');
            Route::post('/payments/updatePayment', 'storeController\PurchaseTransactions@updatePayment');

            Route::get('/WOPayments/{forMonth}/{status}/{typeOfCompany}/exportWOPayments', 'storeController\WorkOrdersController@exportWOPayments');
            Route::get('/WOPayments/edit/{id}', 'storeController\WorkOrdersController@edit');
            Route::get('/WOPayments/{id}/rejectPayment', 'storeController\WorkOrdersController@rejectPayment');
            Route::get('/WOPayments/WOPayment', 'storeController\WorkOrdersController@WOPayment');
            Route::get('/WOPayments/WOPaidPayments', 'storeController\WorkOrdersController@WOPaidPayments');
            Route::get('/WOPayments/WOHoldPayments', 'storeController\WorkOrdersController@WOHoldPayments');
            Route::get('/WOPayments/WORejectedPayments', 'storeController\WorkOrdersController@WORejectedPayments');
            
            Route::get('/workOrder/{vendorId}/{riasedBy}/{status}/exportWorkOrders', 'storeController\WorkOrdersController@exportWorkOrders');
            Route::get('/workOrder/{id}/deactivate', 'storeController\WorkOrdersController@deactivate');
            Route::get('/workOrder/printWO/{id}', 'storeController\WorkOrdersController@printWO');
            Route::get('/workOrder/viewWorkOrder/{woNumber}', 'storeController\WorkOrdersController@viewWorkOrder');
            Route::get('/workOrder/rejectedOrderList', 'storeController\WorkOrdersController@rejectedOrderList');
            Route::get('/workOrder/approvedOrderList', 'storeController\WorkOrdersController@approvedOrderList');
            Route::resource('/workOrder', 'storeController\WorkOrdersController');
            Route::post('/workOrder/generateWorkOrder', 'storeController\WorkOrdersController@generateWorkOrder');
            Route::post('/workOrder/approveWorkOrder', 'storeController\WorkOrdersController@approveWorkOrder');
            Route::post('/WOPayments/updatePayment', 'storeController\WorkOrdersController@updatePayment');

            Route::get('/assetProducts/outwardList', 'storeController\AssetProductsController@outwardList');
            Route::get('/assetProducts/searchAssetProduct', 'storeController\AssetProductsController@searchAssetProduct');
            Route::get('/assetProducts/{id}/activate', 'storeController\AssetProductsController@activate');
            Route::get('/assetProducts/{id}/deactivate', 'storeController\AssetProductsController@deactivate');
            Route::get('/assetProducts/dlist', 'storeController\AssetProductsController@dlist');
            Route::get('/assetProducts/getProductDetails/{productId}', 'storeController\AssetProductsController@getProductDetails');
            Route::resource('/assetProducts', 'storeController\AssetProductsController');
            Route::post('/assetProducts/generateQRCodes', 'storeController\AssetProductsController@generateQRCodes');

            Route::get('/subStores/inward', 'storeController\SubStoresController@inward');
            Route::get('/subStores/inwList', 'storeController\SubStoresController@inwList');
            Route::get('/subStores/inwdList', 'storeController\SubStoresController@inwdList');
            Route::get('/subStores/dlist', 'storeController\SubStoresController@dlist');
            Route::get('/subStores/getProducts/{id}', 'storeController\SubStoresController@getProducts');
            Route::get('/subStores/getInOutProductList/{categoryId}/{subCategory}', 'storeController\SubStoresController@getInOutProductList');
            Route::resource('/subStores', 'storeController\SubStoresController');
            Route::post('/subStores/storeOutward', 'storeController\SubStoresController@storeOutward');
            Route::post('/subStores/storeInward', 'storeController\SubStoresController@storeInward');

            Route::get('/requisitions/reqProductReturnView/{id}', 'storeController\RequisitionsController@reqProductReturnView');
            Route::get('/requisitions/reqProductReturnList', 'storeController\RequisitionsController@reqProductReturnList');
            Route::POST('/requisitions/updateReqProductReturn', 'storeController\RequisitionsController@updateReqProductReturn');
            
            Route::get('/requisitions/deactivatePurchaseRequisition/{id}', 'storeController\RequisitionsController@deactivatePurchaseRequisition')->name('deactivatePurchaseRequisition');
            Route::get('/requisitions/getDepartment/{id}', 'storeController\RequisitionsController@getDepartment')->name('getDepartment');
            Route::get('/requisitions/masterProductList', 'storeController\RequisitionsController@masterProductList')->name('masterProductList');
            Route::get('/requisitions/purchaseRequisitionView/{id}/{type}', 'storeController\RequisitionsController@purchaseRequisitionView')->name('purchaseRequisitionView');
            Route::get('/requisitions/approvedPurchaseRequisitionList', 'storeController\RequisitionsController@approvedPurchaseRequisitionList')->name('approvedPurchaseRequisitionList');
            Route::get('/requisitions/completedPurchaseRequisitionList', 'storeController\RequisitionsController@completedPurchaseRequisitionList')->name('completedPurchaseRequisitionList');
            Route::get('/requisitions/rejectedPurchaseRequisitionList', 'storeController\RequisitionsController@rejectedPurchaseRequisitionList')->name('rejectedPurchaseRequisitionList');
            Route::get('/requisitions/purchaseRequisitionList', 'storeController\RequisitionsController@purchaseRequisitionList')->name('purchaseRequisitionList');
            Route::get('/requisitions/raisePurchaseRequisition', 'storeController\RequisitionsController@raisePurchaseRequisition')->name('raisePurchaseRequisition');
            Route::get('/requisitions/printRequisition/{id}', 'storeController\RequisitionsController@printRequisition')->name('printRequisition');
            Route::get('/requisitions', 'storeController\RequisitionsController@index')->name('requisitions');
            Route::get('/getBranchStock', 'storeController\RequisitionsController@getBranchStock')->name('getBranchStock');
            Route::get('/requisitions/raiseRequisition', 'storeController\RequisitionsController@create')->name('raiseRequisition');
            Route::get('/requisitions/completedReqList', 'storeController\RequisitionsController@completedReqList')->name('completedReqList');
            Route::get('/requisitions/oldEventReqList', 'storeController\RequisitionsController@oldEventReqList')->name('oldEventReqList');
            Route::get('/requisitions/{id}/deactivate', 'storeController\RequisitionsController@deactivate');
            Route::resource('/requisitions', 'storeController\RequisitionsController');
            Route::post('/requisitions/store', 'storeController\RequisitionsController@store');
            Route::post('/requisitions/storePurchaseRequisition', 'storeController\RequisitionsController@storePurchaseRequisition');
            Route::post('/requisitions/approvePurchaseProduct', 'storeController\RequisitionsController@approvePurchaseProduct');
            Route::POST('/requisitions/updateReqProductReturn', 'storeController\RequisitionsController@updateReqProductReturn');
            
            Route::get('/eventRequisitions/printRequisition/{id}', 'storeController\EventRequisitionsController@printRequisition')->name('printRequisition');
            Route::get('/eventRequisitions', 'storeController\EventRequisitionsController@index')->name('requisitions');
            Route::get('/eventRequisitions/raiseRequisition', 'storeController\EventRequisitionsController@create')->name('raiseRequisition');
            Route::get('/eventRequisitions/completedReqList', 'storeController\EventRequisitionsController@completedReqList')->name('completedReqList');
            Route::get('/eventRequisitions/rejectedList', 'storeController\EventRequisitionsController@rejectedList')->name('rejectedList');
            Route::get('/eventRequisitions/{id}/deactivate', 'storeController\EventRequisitionsController@deactivate');
            Route::resource('/eventRequisitions', 'storeController\EventRequisitionsController');

            Route::get('/repaires/rejectedList', 'storeController\RepairesController@rejectedList')->name('repaires.rejectedList');
            Route::get('/repaires/completedList', 'storeController\RepairesController@completedList')->name('repaires.completedList');
            Route::resource('/repaires', 'storeController\RepairesController');
            // store reports
            Route::get('/reports/openingStocExportToExcel/{startDate}/{endDate}/{productId}', 'storeController\ReportsController@openingStocExportToExcel')->name('reports.openingStocExportToExcel');
            Route::get('/reports/openingStockReport', 'storeController\ReportsController@openingStockReport')->name('openingStockReport');

            Route::get('/reports/EODProductReport', 'storeController\ReportsController@EODProductReport')->name('EODProductReport');
            Route::get('/reports/EODProductReportExport', function (Request $request) {
                $productId = $request->query('productId'); // or $request->get('productId')
                $forDate = $request->query('forDate'); // or $request->get('forDate')
                $pageNumber = $request->query('pageNumber'); // or $request->get('pageNumber')
                return Excel::download(new EODProductReportExport($productId, $forDate, $pageNumber), 'EODProductReport.xlsx');
            })->name('export.EODProductReportExport');

            Route::get('/reports/outwardReport', 'storeController\ReportsController@outwardReport')->name('outwardReport');
            Route::get('/reports/outwardReportExport', function (Request $request) {
                $forMonth = $request->query('forMonth'); // or $request->get('forDate')
                return Excel::download(new OutwardReportExport($forMonth), 'OutwardReport.xlsx');
            })->name('export.outwardReportExport');

            Route::get('/reports/productWiseReport', 'storeController\ReportsController@productWiseReport')->name('productWiseReport');
            Route::get('/reports/productWiseReportExport', function (Request $request) {
                $forMonth = $request->query('forMonth'); // or $request->get('forDate')
                $productId = $request->query('productId'); // or $request->get('productId')
                return Excel::download(new ProductWiseReportExport($forMonth, $productId), 'ProductWiseReport.xlsx');
            })->name('export.productWiseReportExport');

            Route::get('/reports/inwardReport', 'storeController\ReportsController@inwardReport')->name('reports.inwardReport');
            Route::get('/reports/inwardReportExport', function (Request $request) {
                $forMonth = $request->query('forMonth'); // or $request->get('forDate')
                return Excel::download(new InwardReportExport($forMonth), 'InwardReport.xlsx');
            })->name('export.inwardReportExport');

            // purchase reports
            Route::get('/reports/quotationReport', 'storeController\ReportsController@quotationReport')->name('quotationReport');
            Route::get('/reports/quotationReportExport', function (Request $request) {
                $forMonth = $request->query('forMonth'); // or $request->get('forMonth')
                return Excel::download(new QuotationReportExport($forMonth), 'QuotationReport.xlsx');
            })->name('export.quotationReportExport');

            Route::get('/reports/purchaseOrderReport', 'storeController\ReportsController@purchaseOrderReport')->name('purchaseOrderReport');
            Route::get('/reports/purchaseOrderReportExport', function (Request $request) {
                $forMonth = $request->query('forMonth'); // or $request->get('forMonth')
                return Excel::download(new PurchaseOrderReportExport($forMonth), 'PurchaseOrderReport.xlsx');
            })->name('export.purchaseOrderReportExport');

            Route::get('/reports/workOrderReport', 'storeController\ReportsController@workOrderReport')->name('workOrderReport');
            Route::get('/reports/workOrderReportExport', function (Request $request) {
                $forMonth = $request->query('forMonth'); // or $request->get('forMonth')
                return Excel::download(new WorkOrderReportExport($forMonth), 'WorkOrderReport.xlsx');
            })->name('export.workOrderReportExport');

            Route::get('/reports/quotationRejectReport', 'storeController\ReportsController@quotationRejectReport')->name('reports.quotationRejectReport');
            Route::get('/reports/quotationRejectReportExport', function (Request $request) {
                $forMonth = $request->query('forMonth'); // or $request->get('forMonth')
                return Excel::download(new WorkOrderReportExport($forMonth), 'QuotationRejectReport.xlsx');
            })->name('export.quotationRejectReportExport');

            Route::get('/reports/vendorWiseReport', 'storeController\ReportsController@vendorWiseReport')->name('vendorWiseReport');

            Route::get('/reports/getVendors', 'storeController\ReportsController@getVendors')->name('getVendors');
            Route::get('/reports/vendorReport', 'storeController\ReportsController@vendorReport')->name('vendorReport');
            Route::get('/reports/branchWiseRequisitionReport', 'storeController\ReportsController@branchWiseRequisitionReport')->name('branchWiseRequisitionReport');
            Route::get('/reports/branchWiseRequisitionCountReport', 'storeController\ReportsController@branchWiseRequisitionCountReport')->name('branchWiseRequisitionCountReport');
            
            Route::get('/payments/{forMonth}/{status}/{typeOfCompanyId}/exportPOPayments', 'storeController\PaymentsController@exportPOPayments');
            Route::get('/payments/POPaidPaymentList', 'storeController\PaymentsController@POPaidPaymentList');
            Route::get('/payments/POUnpaidPaymentList', 'storeController\PaymentsController@POUnpaidPaymentList');
            Route::get('/payments/PORejectedPaymentList', 'storeController\PaymentsController@PORejectedPaymentList');
            Route::get('/payments/POHoldPaymentList', 'storeController\PaymentsController@POHoldPaymentList');
            Route::get('/payments/POPaymentShow/{id}', 'storeController\PaymentsController@POPaymentShow');          
            Route::get('/payments/{id}/POPaymentEdit', 'storeController\PaymentsController@POPaymentEdit');          
            Route::post('/payments/POPaymentUpdate', 'storeController\PaymentsController@POPaymentUpdate');

            Route::get('/products/getVehicleFuelDetails/{id}', 'storeController\FuelFilledSystemsController@getVehicleFuelDetails');
            Route::get('/fuelSystems/getVehicleFuelDetails/{id}', 'storeController\FuelFilledSystemsController@getVehicleFuelDetails');
            Route::get('/fuelSystems/fuelVehicleEntry/{id}', 'storeController\FuelFilledSystemsController@fuelVehicleEntry');
            Route::get('/fuelSystems/fuelVehicleDetails/{id}', 'storeController\FuelFilledSystemsController@fuelVehicleDetails');
            Route::get('/fuelSystems/exportExcelSheet/{id}', 'storeController\FuelFilledSystemsController@exportExcelSheet');
            Route::get('/fuelSystems/{id}/activeDeactivateFuelEntry', 'storeController\FuelFilledSystemsController@activeDeactivateFuelEntry');
            Route::get('/fuelSystems/{id}/deleteVehicleEntry', 'storeController\FuelFilledSystemsController@deleteVehicleEntry');
            Route::get('/fuelSystems/generateQuotation/{id}', 'storeController\FuelFilledSystemsController@generateQuotation');
            Route::get('/fuelSystems/dlist', 'storeController\FuelFilledSystemsController@dlist');
            Route::resource('/fuelSystems', 'storeController\FuelFilledSystemsController');
            Route::post('/fuelSystems/storeFuelVehicleEntry', 'storeController\FuelFilledSystemsController@storeFuelVehicleEntry');
            Route::post('/fuelSystems/storeQuotation', 'storeController\FuelFilledSystemsController@storeQuotation');

            Route::get('/audits/auditViewProductList', 'storeController\AuditsController@auditViewProductList');
            Route::get('/audits/auditProductEntry', 'storeController\AuditsController@auditProductEntry');
            Route::resource('/audits', 'storeController\AuditsController'); //  
        // });

        Route::group(['middleware' => 'CRM', 'prefix' => 'CRM'], function() {
            Route::get('/masterchecklist/activeDeactiveStatus/{id}', 'CRM\MasterChecklistController@activeDeactiveStatus');
            Route::get('/masterchecklist/dlist', 'CRM\MasterChecklistController@dlist');
            Route::resource('/masterchecklist', 'CRM\MasterChecklistController');

            Route::get('/assignTaskSheet/assignActiveDeactiveStatus/{id}', 'CRM\MasterChecklistController@assignActiveDeactiveStatus');
            Route::get('/assignTaskSheet/assignTaskList', 'CRM\MasterChecklistController@assignTaskList');
            Route::get('/assignTaskSheet/assignDeactiveTaskList', 'CRM\MasterChecklistController@assignDeactiveTaskList');
            Route::get('/assignTaskSheet/assignTask', 'CRM\MasterChecklistController@assignTask');
            Route::post('/assignTaskSheet/updateAssignTask', 'CRM\MasterChecklistController@updateAssignTask');

            Route::get('/dailyTaskList', 'CRM\DailyCheckListsController@index')->name('checklist.index');
            Route::get('/dailyTaskList/employees', 'CRM\DailyCheckListsController@employeesList')->name('checklist.employeesList');
            Route::get('/dailyTaskList/create', 'CRM\DailyCheckListsController@create')->name('checklist.create');
            Route::get('/dailyTaskList/showDetails/{id}/{forDate}', 'CRM\DailyCheckListsController@showDetails')->name('checklist.showDetails');

            Route::get('/dailyTaskList/show/{id}', 'CRM\DailyCheckListsController@show')->name('checklist.show');
            Route::post('/dailyTaskList/store', 'CRM\DailyCheckListsController@store')->name('checklist.store');

             Route::get('/extraTask/raiseTask','CRM\ExtraWorksController@raiseTask')->name('extraTask.raiseTask');
             Route::get('/extraTask/requestList','CRM\ExtraWorksController@requestList')->name('extraTask.requestList');
             Route::get('/extraTask/dTaskList','CRM\ExtraWorksController@dTaskList')->name('extraTask.dTaskList');
             Route::get('/extraTask/assignedTask','CRM\ExtraWorksController@assignedTask')->name('extraTask.assignedTask');
             Route::get('/extraTask/rescheduleTask','CRM\ExtraWorksController@rescheduleTask')->name('extraTask.rescheduleTask');

        });
    });

    Route::group(['middleware' => ['auth', 'software.dev']], function () {
        Route::get('/developer/deleteEmployeeAccessOnlyDeveloper/{id}', 'admin\employees\EmployeesController@deleteEmployeeAccessOnlyDeveloper');
    });


});


