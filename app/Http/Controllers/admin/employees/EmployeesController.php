<?php

namespace App\Http\Controllers\admin\employees;
use Illuminate\Support\Facades\Log; // For logging errors
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmployeeRequest;
use App\Services\EmployeeCreationService;
use App\Imports\UsersImport;
use App\Exports\EmployeesExport;
use App\Exports\ExportNewJoinee;
use App\Department;
use App\JobApplication;
use App\UploadEmpDoc;
use App\ChangeAuthHistory;
use App\Organisation;
use App\BdayWish;
use App\CifForm;
use App\Region;
use App\City;
use App\Designation;
use App\RequiredDocument;
use App\EmpDet;
use App\ChangeTime;
use App\ContactusLandPage;
use App\ResetPasswordHistory;
use App\EmpFamilyDet; 
use App\EmpStationaryDet;
use App\EmpAssignHistory;
use App\EmpFeesConcession;
use App\UserRole;
use App\ExitProcessStatus;
use App\User;
use App\SystemAsset;
use App\OtherAsset;
use App\MobileAsset;
use App\TempEmpDet;
use App\UpdateProfileInformation;
use App\EmpHistory;
use App\LogTimeOld;
use App\EmployeeExperience;
use App\EmpVerification;
use App\EmployeeLetter;
use App\Cif3Application;
use App\EmpChangeTime;
use App\TempAssetProduct;
use App\Retention;
use App\EmpMr;
use App\Asset;
use App\TempEmployee;
use Hash;
use Auth;
use Image;
use Excel;
use DB;
use File;
use PDF;

class EmployeesController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeCreationService $employeeService)
    {
        $this->employeeService = $employeeService;
    }
    public function index()
    {  
        $empId = Auth::user()->empId;
        $uId = Auth::user()->id;
        $userType = Auth::user()->userType;

        if($empId != '')
        {
            if($userType == '11')
            {
                $users1 = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                $users2 = EmpDet::whereIn('reportingId', $users1)->where('active', 1)->pluck('id');

                $collection = collect($users1);
                $merged = $collection->merge($users2);
                $users = $merged->all();
            }

            if($userType == '21')
            {
                $users = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
            }
        }

        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.firmType','emp_dets.phoneNo','emp_dets.feesConcession', 
        'emp_dets.name', 'emp_dets.username', 'emp_dets.empCode','contactus_land_pages.branchName',
        'departments.name as departmentName','designations.name as designationName');

        if($userType == '601')
        {
            $employees = $employees->where('emp_dets.reportingId', $uId)
            ->where('emp_dets.active', 1)
            ->get();
            return view('admin.employees.list')->with(['employees'=>$employees]);
        }

        if($userType == '101')
        {
            $employees = $employees->whereIn('departments.name', ['Security Department'])
            ->where('emp_dets.active', 1)
            ->get();
            return view('admin.employees.list')->with(['employees'=>$employees]);
        }

        if($empId != '')
            $employees=$employees->whereIn('emp_dets.id', $users);
        
        $employees=$employees->where('emp_dets.active', 1);
        if($empId != '')
            $employees=$employees->orderBy('departments.name');
        else
            $employees=$employees->orderBy('emp_dets.empCode');

        $employees=$employees->get();
      
        return view('admin.employees.list')->with(['employees'=>$employees]);
    }

    public function dlist()
    {
        $empId = Auth::user()->empId;
        $uId = Auth::user()->id;
       return $userType = Auth::user()->userType;
        
        if($empId != '')
        {
            if($userType == '11')
            {
                $users1 = EmpDet::where('reportingId', $empId)->pluck('id');
                $users2 = EmpDet::whereIn('reportingId', $users1)->pluck('id');

                $collection = collect($users1);
                $merged = $collection->merge($users2);
                $users = $merged->all();
            }

            if($userType == '21')
            {
                $users = EmpDet::where('reportingId', $empId)->pluck('id');
            }

        }

        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.firmType','emp_dets.phoneNo',
        'emp_dets.name','emp_dets.empCode','contactus_land_pages.branchName',
        'departments.name as departmentName','designations.name as designationName');

        if($userType == '601')
        {
            $employees = $employees->where('emp_dets.reportingId', $uId)
            ->where('emp_dets.active', 0)
            ->get();
            return view('admin.employees.dlist')->with(['employees'=>$employees]);
        }

        if($userType == '101')
        {
            $employees = $employees->whereIn('departments.name', ['Security Department'])
            ->where('emp_dets.active', 0)
            ->get();
            return view('admin.employees.dlist')->with(['employees'=>$employees]);
        }
        
        if($empId != '')
            $employees=$employees->whereIn('emp_dets.id', $users);
        
        $employees=$employees->where('emp_dets.active', 0);
        if($empId != '')
        {
            $employees=$employees->orderBy('departments.name');
        }
        else
        {
            $employees=$employees->orderBy('emp_dets.empCode');
        }

        $employees=$employees->paginate(10);
      
        return view('admin.employees.dlist')->with(['employees'=>$employees]);
    }

    public function create(Request $request)
    {    
        $searchAadharCardNo = $request->searchAadharCardNo;  
        if($searchAadharCardNo != '')
        {     
            $foundEmp = EmpDet::where('AADHARNo', $searchAadharCardNo)->first();
            if($foundEmp)
            {
                return redirect('employees/'.$foundEmp->id)->with("success","This Employee is already added in the System");
            }

            $empFamilyDet=[];
            $application = CifForm::where('AADHARNo', $searchAadharCardNo)->first();
            if($application)
            {
                $empFamilyDet = EmpFamilyDet::where('empId', $application->id)->where('type', 2)->get();
            }
            else
            {
                $records = DB::table('temp_employees')
                    ->whereRaw("JSON_SEARCH(form_data, 'all', ?) IS NOT NULL", [$searchAadharCardNo])
                    ->get();

                if($records->isNotEmpty())
                {
                    $records = $records->map(function ($item) {
                        $item->form_data = json_decode($item->form_data); // removed `true` to get object
                        return $item;
                    });

                    $application = $records[0]->form_data;
                }
            }
            
            $report1 = collect(EmpDet::select('name', 'id', 'empCode')->where('userRoleId', '!=', '5')->orderBy('name')->whereActive(1)->get());
            $report2 = collect(User::select('name', 'id', 'empId as empCode')->whereIn('userType', [201,301,401,501,601])->orderBy('name')->whereActive(1)->get());
        
            $empReportings = $report1->merge($report2);
            $empReportings=$empReportings->sortBy('name');  
   
            $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
            $departments = Department::whereActive(1)->pluck('name', 'id');
            $designations = Designation::whereActive(1)->pluck('name', 'id');
            $userRoles = UserRole::whereIn('userType', ['11','21','31'])->whereActive(1)->pluck('name','id');

            $organisations = Organisation::where('active', 1)->pluck('name', 'id');
            $buddyNames = EmpDet::where('active', 1)->orderBy('name')->pluck('name','id');
            return view('admin.employees.masters.create')->with(['organisations'=>$organisations,'empFamilyDet'=>$empFamilyDet,'application'=>$application,'userRoles'=>$userRoles, 'departments'=>$departments, 'designations'=>$designations,
            'searchAadharCardNo'=>$searchAadharCardNo,'empReportings'=>$empReportings,'branches'=>$branches,'buddyNames'=>$buddyNames]);
        }
        else
        {
            return view('admin.employees.masters.create')->with(['searchAadharCardNo'=>$searchAadharCardNo]);
        }       
    }

    public function getBranch($organisationId)
    {
        $organisation = Organisation::where('id', $organisationId)->first();
        $branchIds = explode(',', $organisation->branchIds);
        return ContactusLandPage::whereIn('id', $branchIds)->where('active', 1)->orderBy('shortName')->get(['shortName', 'id']);
    }

    public function addCandidateToEmployee($candidateId)
    {
        $application = Cif3Application::where('id', $candidateId)->first();
        $userRoles = UserRole::whereIn('userType', ['11','21','31'])->whereActive(1)->pluck('name','id');
        $departments = Department::whereActive(1)->pluck('name', 'id');
        $designations = Designation::whereActive(1)->pluck('name', 'id');
        $searchAadharCardNo='000000000000';

        $report1 = collect(EmpDet::select('name', 'id', 'empCode')->where('userRoleId', '!=', '5')->orderBy('name')->whereActive(1)->get());
        $report2 = collect(User::select('name', 'id', 'empId as empCode')->whereIn('userType', [201,301,401,501,601])->orderBy('name')->whereActive(1)->get());
    
        $empReportings = $report1->merge($report2);
        $empReportings=$empReportings->sortBy('name');  

        $branches = ContactusLandPage::whereActive(1)->get(['branchName', 'id']);
        $buddyNames = EmpDet::where('active', 1)->orderBy('name')->pluck('name','id');
       
        $empId=0;
       
        return view('admin.employees.create')->with(['application'=>$application,'userRoles'=>$userRoles, 'departments'=>$departments,
         'designations'=>$designations, 'searchAadharCardNo'=>$searchAadharCardNo,'empReportings'=>$empReportings,'branches'=>$branches,'buddyNames'=>$buddyNames]);
    }

    public function cif(Request $request)
    {
        $branches = ContactusLandPage::whereActive(1)->get(['branchName', 'id']);
        $states = Region::whereActive(1)->pluck('name', 'id');
        $userRoles = UserRole::whereIn('userType', ['11','21','31'])->whereActive(1)->pluck('name','id');
        return view('admin.employees.cif')->with(['userRoles'=>$userRoles,'states'=>$states, 'branches'=>$branches]);
    }

    public function storeCIF(Request $request)
    {
        $jobJoingDate = ($request->empJobJoingDate == '')?'':date('Y-m-d', strtotime($request->empJobJoingDate));
        $DOB = date('Y-m-d', strtotime($request->DOB));

        $employee = new CifForm();
        $employee->name = ucwords($request->empName);
        if(!empty($request->file('profPhoto')))
        {
            $originalImage= $request->file('profPhoto');
            $Image = $request->PANNo.'.'.$originalImage->getClientOriginalExtension();
            $image = Image::make($originalImage);
            $originalPath =  public_path()."/admin/images/empDocs/";
            $image->resize(500,500);
            $image->save($originalPath.$Image);
            $employee->profilePhoto = $Image;
        }

        $employee->phoneNo = $request->phoneNo;
        $employee->whatsappNo = $request->whatsappNo;
        $employee->DOB = $DOB;
        $employee->gender = $request->gender;
        $employee->cast = $request->cast;
        $employee->type = $request->type;
        $employee->branchId  = $request->branchId;
        $employee->jobJoingDate = $jobJoingDate;
        $employee->startTime = $request->jobStartTime;
        $employee->endTime = $request->jobEndTime;
        $employee->reference = $request->reference;
        $employee->maritalStatus = $request->maritalStatus;

        $employee->salaryScale = $request->salaryScale;
        $employee->PANNo = $request->PANNo;

        $employee->email = $request->personalEmail;
        $employee->bankName = $request->bankName;
        $employee->bankAccountNo = $request->bankAccountNo;
        $employee->bankIFSCCode = $request->bankIFSCNo;

        $employee->qualification = $request->qualification;
        $employee->AADHARNo = $request->aadhaarCardNo;
        $employee->teachingSubject = $request->teachingSubject;
        $employee->instagramId = $request->instagramId;
        $employee->twitterId = $request->twitterId;
        $employee->facebookId = $request->facebookId;

        $employee->presentAddress = $request->presentAddress;
        $employee->presentRegionId = $request->presentStateId;
        $employee->presentCityId = $request->presentCityId;
        $employee->presentPINCode = $request->presentPINCode;

        $employee->permanentAddress = $request->comPermanentAddress;
        $employee->permanentRegionId  = $request->permanentStateId;
        $employee->permanentCityId = $request->permanentCityId;
        $employee->permanentPINCode = $request->comPermanentPINNo;

        $employee->workingStatus = $request->workDet;
        $employee->experName = $request->experName;
        $employee->experDesignation  = $request->experDesignation;
        $employee->experLastSalary = $request->experLastSalary;
        $employee->experDuration = $request->experDuration;
        $employee->experJobDesc = $request->experJobDesc;
        $employee->experReasonLeaving = $request->experReasonLeaving;
        $employee->experCompanyCont = $request->experCompanyCont;

        if($employee->save())
        {
            if(isset($request->familyName))
            {
                $familyRowCount = count($request->familyName);
                if($familyRowCount != 0)
                {
                    $names = $request->familyName;
                    $ages = $request->familyAge;
                    $relations = $request->familyRelation;
                    $occupations = $request->familyOccupation;
                    $contactNos = $request->familyContactNo;

                    for($i=0; $i<$familyRowCount; $i++)
                    {
                        $empFamilyDet = new EmpFamilyDet;
                        $empFamilyDet->empId = $employee->id;
                        $empFamilyDet->name=$names[$i];
                        $empFamilyDet->age=$ages[$i];
                        $empFamilyDet->relation=$relations[$i];
                        $empFamilyDet->occupation=$occupations[$i];
                        $empFamilyDet->contactNo=$contactNos[$i];
                        $empFamilyDet->type=2;
                        $empFamilyDet->save();
                    }
                }
            }
            return redirect('/')->with("success","CIF Form Updated Successfully...");
        }
    }

    // public function store(Request $request)
    // {
    //     // return $request->all();
    //     $userType = Auth::user()->userType;
    //     if($userType != '51')
    //         return redirect()->back()->withInput()->with("error","You don't have access this page, only HR Can add New Employee");

    //     $jobJoingDate = ($request->empJobJoingDate == '')?'':date('Y-m-d', strtotime($request->empJobJoingDate));
    //     $DOB = date('Y-m-d', strtotime($request->DOB));
    //     $firmType = $request->firmType;

    //     $request->name = ucwords($request->name);
    //     if($firmType == 1)
    //     {
    //         $lastEmpCode = EmpDet::where('firmType', $firmType)
    //         ->whereNotIn('empCode', [4414, 4413, 4412, 4006, 4005,4004,4003,4002,4001])
    //         ->orderBy('empCode', 'desc')
    //         ->value('empCode');
    //     }
    //     else
    //     {
    //         $lastEmpCode = EmpDet::where('firmType', $firmType)
    //         ->orderBy('empCode', 'desc')
    //         ->value('empCode');
    //     }

    //     if($lastEmpCode == '')
    //     { 
    //         if($firmType == 1)
    //             $empCode = 1;

    //         if($firmType == 2)
    //             $empCode = 10001;

    //         if($firmType == 3)
    //             $empCode = 40001;

    //         if($firmType == 4)
    //             $empCode = 30001;

    //         if($firmType == 5)
    //             $empCode = 20000;
            
    //         if($firmType == 6)
    //             $empCode = 50000;

    //         if($firmType == 7) // ARW
    //             $empCode = 70000;

    //     }
    //     else
    //     {
    //         $empCode = $lastEmpCode+1;
    //     }

    //     if(EmpDet::where('empCode', $empCode)->count())
    //         return redirect()->back()->withInput()->with("error","Employee Code already Exist...");

    //     $employee = new EmpDet();
    //     $employee->empCode = $empCode;
    //     $employee->recruitementNo = $request->candidateId;
    //     $employee->firstName = $request->firstName;
    //     $employee->middleName = $request->middleName;
    //     $employee->lastName = $request->lastName;
    //     $employee->name = $employee->firstName.' '.$employee->middleName.' '.$employee->lastName;
    //     if(!empty($request->file('profilePhoto')))
    //     {
    //         $originalImage= $request->file('profilePhoto');
    //         $Image = $request->PANNo.'.'.$originalImage->getClientOriginalExtension();
    //         $image = Image::make($originalImage);
    //         $originalPath =  public_path()."/admin/profilePhotos/";
    //         $image->resize(400,500);
    //         $image->save($originalPath.$Image);
    //         $employee->profilePhoto = $Image;
    //     }
        
    //     $employee->verifyStatus = $request->verifyStatus;
    //     $employee->idCardStatus = $request->idCardStatus;
    //     $employee->gender = $request->gender;
    //     $employee->region = $request->region;
    //     $employee->cast = $request->cast;
    //     $employee->type = $request->type;
    //     $employee->DOB = $DOB;
    //     $employee->maritalStatus = $request->maritalStatus;
    //     $employee->phoneNo = $request->phoneNo;
    //     $employee->whatsappNo = $request->whatsappNo;
    //     $employee->email = $request->email;
    //     $employee->presentAddress = $request->presentAddress;
    //     $employee->permanentAddress = $request->permanentAddress;
    //     $employee->qualification = $request->qualification;
    //     $employee->workingStatus = $request->workDet;

    //     $employee->branchId  = $request->branchId;
    //     $employee->organisation=$request->organisation;
    //     $employee->departmentId  = $request->departmentId;
    //     $employee->designationId  = $request->designationId;
    //     $employee->teachingSubject  = $request->teachingSubject;
    //     $employee->salaryScale = $request->salaryScale;        
       
    //     $employee->buddyName = $request->buddyName;
    //     $employee->jobJoingDate = $jobJoingDate;
    //     $employee->shift = $request->shift;
    //     $employee->startTime = date('H:i:s', strtotime($request->jobStartTime));
    //     $employee->endTime = date('H:i:s', strtotime($request->jobEndTime));
    //     $employee->contractStartDate = $request->contractStartDate;
    //     $employee->contractEndDate = $request->contractEndDate;

    //     $employee->AADHARNo = $request->aadhaarCardNo;
    //     $employee->PANNo = $request->PANNo;
    //     $employee->bankName = $request->bankName;
    //     $employee->branchName = $request->bankBranch;
    //     $employee->bankAccountName = $request->bankAccountName;
    //     $employee->bankAccountNo = $request->bankAccountNo;
    //     $employee->bankIFSCCode = $request->bankIFSCCode;
    //     $employee->pfNumber = $request->pfNumber;
    //     $employee->uIdNumber = $request->uIdNumber;
    //     $employee->reference = $request->reference;
    //     $employee->instagramId = $request->instagramId;
    //     $employee->twitterId = $request->twitterId;
    //     $employee->facebookId = $request->facebookId;
    //     $employee->workingStatus = $request->workingStatus;    
    //     $employee->attendanceStatus = 0;    
          
    //     $employee->reportingId  = $request->reportingId;
    //     if(EmpDet::where('id', $request->reportingId)->count())
    //         $employee->reportingType  = 1;
    //     else
    //         $employee->reportingType  = 2;

    //     $employee->firmType = $request->firmType;
        
    //     if($employee->firmType == 1)
    //         $useName = 'AWS';
    //     elseif($employee->firmType == 2)
    //         $useName = 'ADF';
    //     elseif($employee->firmType == 3)
    //         $useName = 'YB';
    //     elseif($employee->firmType == 4)
    //         $useName = 'SNAYRAA';
    //     elseif($employee->firmType == 6)
    //         $useName = 'AE';
    //     else
    //         $useName = 'AFS';

    //     $employee->username = $useName.$employee->empCode;
    //     $employee->newUser = 1;
    //     $employee->attendanceType = $request->attendanceType;
    //     $employee->userRoleId  = $request->userRoleId;
    //     $employee->added_by = Auth::user()->username;
    //     $employee->updated_by = Auth::user()->username;

    //     $letter = new EmployeeLetter;
    //     $letter->designationId=$employee->designationId;
    //     $letter->branchId=$employee->branchId;
    //     $letter->organisation=$employee->organisation;
    //     $letter->fromDate=$employee->contractStartDate;
    //     $letter->toDate=$employee->contractEndDate;
    //     $letter->salary=$employee->salaryScale;
    //     $letter->aPeriod='Probation Period';            
    //     $letter->forDate=date('Y-m-d');
    //     $letter->letterType=2;
    //     $letter->updated_by=Auth::user()->username;

    //    DB::beginTransaction();

    //    try 
    //    {

    //         if($employee->save())
    //         {
    //             if($employee->salaryScale)
    //             {
    //                 $fromMonth = $request->deductionFromMonth;
    //                 $toMonth = date('Y-m', strtotime('+5 months', strtotime($request->deductionFromMonth)));
    //                 for($i=$fromMonth; $i<=$toMonth; $i++)
    //                 {
    //                     $retention = Retention::where('empId', $employee->id)->first();
    //                     if(!$retention)
    //                     {
    //                         $retention = new Retention;
    //                         $retention->empId = $employee->id;
    //                     }

    //                     $retention->retentionAmount = $request->retentionAmountPerMonth;
    //                     $retention->month = $i;
    //                     $retention->remark = 'Retention for the month of '.date('M-Y', strtotime($i));
    //                     $retention->updated_by =Auth::user()->username;
    //                     $retention->save();
    //                 }
                    
    //             }
                
    //             $letter->empId=$employee->id;
    //             $letter->save();

    //             if($request->emergencyName1 != '' && $request->emergencyRelation1 != '')
    //             {
    //                 $empFamily1 = new EmpFamilyDet;
    //                 $empFamily1->empId = $employee->id;
    //                 $empFamily1->name = $request->emergencyName1;
    //                 $empFamily1->relation = $request->emergencyRelation1;
    //                 $empFamily1->occupation = $request->emergencyPlace1;
    //                 $empFamily1->contactNo = $request->emergencyContactNo1;
    //                 $empFamily1->updated_by = Auth::user()->username;
    //                 $empFamily1->save();
    //             }
        
    //             if($request->emergencyName2 != '' && $request->emergencyRelation2 != '')
    //             {
    //                 $empFamily2 = new EmpFamilyDet;
    //                 $empFamily2->empId = $employee->id;
    //                 $empFamily2->name = $request->emergencyName2;
    //                 $empFamily2->relation = $request->emergencyRelation2;
    //                 $empFamily2->occupation = $request->emergencyPlace2;
    //                 $empFamily2->contactNo = $request->emergencyContactNo2;
    //                 $empFamily2->updated_by = Auth::user()->username;
    //                 $empFamily2->save();
    //             }  

    //             if($request->workingStatus == 2)
    //             {
    //                 for($i=0; $i<5; $i++)
    //                 {
    //                     if($request->experName[$i] != '' && $request->experDesignation[$i] != '')
    //                     {
                            
    //                         $empExperience = EmployeeExperience::where('experName', $request->experName[$i])->where('experDesignation',$request->experDesignation[$i])->first();
    //                         if(!$empExperience)
    //                             $empExperience = new EmployeeExperience;

    //                         $empExperience->empId = $employee->id;
    //                         $empExperience->experName = $request->experName[$i];
    //                         $empExperience->experDesignation  = $request->experDesignation[$i];
    //                         $empExperience->experFromDuration = $request->experFromDuration[$i];
    //                         $empExperience->experToDuration = $request->experToDuration[$i];
    //                         $empExperience->experLastSalary = $request->experLastSalary[$i];
    //                         $empExperience->experJobDesc = $request->experJobDesc[$i];
    //                         $empExperience->experReasonLeaving = $request->experReasonLeaving[$i];
    //                         $empExperience->experReportingAuth = $request->experReportingAuth[$i];
    //                         $empExperience->experReportingDesignation = $request->experReportingDesignation[$i];
    //                         $empExperience->experCompanyCont = $request->experCompanyCont[$i];
    //                         $empExperience->updated_by = Auth::user()->username;
    //                         $empExperience->save();
    //                     } 
    //                 }
    //             }               
      
                
    //             $user = new User();
    //             $user->name = $employee->name;
    //             $user->username = $employee->username;
    //             $user->email = $employee->email;
    //             $user->password = Hash::make('Welcome@1');
    //             $user->empId = $employee->id;
    //             $user->newUser = 1;
    //             $user->transAllowed =  $request->transAllowed;
    //             $user->userRoleId =  $request->userRoleId;
    //             $user->userType = UserRole::where('id',$request->userRoleId)->value('userType');
    //             $user->updated_by = Auth::user()->username;
    //             if($user->save())
    //             {
                    
    //                 if(!empty($request->file('uploadAddharCard')))
    //                 {
    //                     $originalFile= $request->file('uploadAddharCard');
    //                     $fileName = date('dmhis').'AC_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 1)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 1;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 if(!empty($request->file('uploadPanCard')))
    //                 {
    //                     $originalFile= $request->file('uploadPanCard');
    //                     $fileName = date('dmhis').'PC_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 2)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 2;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }
            
    //                 if(!empty($request->file('uploadTestimonials10th')))
    //                 {
    //                     $originalFile= $request->file('uploadTestimonials10th');
    //                     $fileName = date('dmhis').'10th_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 3)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 3;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 if(!empty($request->file('uploadTestimonials12th')))
    //                 {
    //                     $originalFile= $request->file('uploadTestimonials12th');
    //                     $fileName = date('dmhis').'12th_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 10)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 10;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 if(!empty($request->file('uploadTestimonialsGrad')))
    //                 {
    //                     $originalFile= $request->file('uploadTestimonialsGrad');
    //                     $fileName = date('dmhis').'Grad_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 11)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 11;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 if(!empty($request->file('uploadTestimonialsPostGrad')))
    //                 {
    //                     $originalFile= $request->file('uploadTestimonialsPostGrad');
    //                     $fileName = date('dmhis').'PostGrad_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 12)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 12;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }
                    
    //                 if(!empty($request->file('uploadTestimonialsOtherEducation')))
    //                 {
    //                     $originalFile= $request->file('uploadTestimonialsOtherEducation');
    //                     $fileName = date('dmhis').'OtherEducation_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 14)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 14;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 if(!empty($request->file('uploadDrivingLicense')))
    //                 {
    //                     $originalFile= $request->file('uploadDrivingLicense');
    //                     $fileName = date('dmhis').'DL_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 4)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 4;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 if(!empty($request->file('uploadRtoBatch')))
    //                 {
    //                     $originalFile= $request->file('uploadRtoBatch');
    //                     $fileName = date('dmhis').'RB_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 5)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 5;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 if(!empty($request->file('uploadElectricityBill')))
    //                 {
    //                     $originalFile= $request->file('uploadElectricityBill');
    //                     $fileName = date('dmhis').'RB_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 6)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 6;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 if(!empty($request->file('uploadBankDetails')))
    //                 {
    //                     $originalFile= $request->file('uploadBankDetails');
    //                     $fileName = date('dmhis').'BD_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 7)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 7;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 if(!empty($request->file('uploadEmployeeContract')))
    //                 {
    //                     $originalFile= $request->file('uploadEmployeeContract');
    //                     $fileName = date('dmhis').'EC_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 8)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 8;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 if(!empty($request->file('uploadTestimonialsOther')))
    //                 {
    //                     $originalFile= $request->file('uploadTestimonialsOther');
    //                     $fileName = date('dmhis').'OtherEducation_.'.$originalFile->extension();  
    //                     $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                        
    //                     UploadEmpDoc::where('empId', $employee->id)->where('type', 13)->update(['active'=>'0']);

    //                     $uploadDoc = new UploadEmpDoc;
    //                     $uploadDoc->empId = $employee->id;
    //                     $uploadDoc->empCode = $empCode;
    //                     $uploadDoc->fileName = $fileName;
    //                     $uploadDoc->type = 13;
    //                     $uploadDoc->updated_by = Auth::user()->username;
    //                     $uploadDoc->save();
    //                 }

    //                 DB::commit();
    //                 $sms = "New ".$employee->username." Employee Added successfully..";
    //                 return redirect('/employees')->with("success",$sms);
    //             }
    //         }
    //    } 
    //    catch (\Exception $e) 
    //    {
    //        DB::rollback();
    //        return redirect()->back()->withInput()->with("error","something went wrong : ".$e->getMessage());
    //    }
       
    // }

    public function store(StoreEmployeeRequest $request)
    {
        // Authorization & Validation are now handled automatically by StoreEmployeeRequest.
        $validatedData = $request->validated();

        DB::beginTransaction();
        try {
            // 1. Delegate the core logic of creating the employee to the service class.
            $employee = $this->employeeService->createEmployee($validatedData);

            // 2. Handle all file uploads after the employee record exists.
            $this->handleFileUploads($request, $employee);
            
            DB::commit();
            
            return redirect('/employees/nonTeachingEmps')->with("success", "Employee {$employee->username} was added successfully.");

        } catch (\Exception $e) {
            DB::rollback();
            // Log the actual error for easier debugging.
            \Log::error('Employee Creation Failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with("error", "Something went wrong during employee creation. Please check the logs.");
        }
    }

    /**
     * Handles all file uploads for the new employee.
     *
     * @param Request $request
     * @param EmpDet $employee
     */
    private function handleFileUploads(Request $request, EmpDet $employee)
    {
        // Handle Profile Photo separately due to its unique naming convention.
        if ($request->hasFile('profilePhoto')) {
            $image = Image::make($request->file('profilePhoto'))->resize(400, 500);
            $imageName = $request->PANNo . '.' . $request->file('profilePhoto')->getClientOriginalExtension();
            $image->save(public_path('admin/profilePhotos/') . $imageName);
            $employee->update(['profilePhoto' => $imageName]);
        }
        
        // A map for all other documents to avoid repetition.
        $documentMap = [
            'uploadAddharCard' => ['type' => 1, 'prefix' => 'AC_'],
            'uploadPanCard' => ['type' => 2, 'prefix' => 'PC_'],
            'uploadTestimonials10th' => ['type' => 3, 'prefix' => '10th_'],
            'uploadDrivingLicense' => ['type' => 4, 'prefix' => 'DL_'],
            'uploadRtoBatch' => ['type' => 5, 'prefix' => 'RB_'],
            'uploadElectricityBill' => ['type' => 6, 'prefix' => 'EB_'],
            'uploadBankDetails' => ['type' => 7, 'prefix' => 'BD_'],
            'uploadEmployeeContract' => ['type' => 8, 'prefix' => 'EC_'],
            'uploadTestimonials12th' => ['type' => 10, 'prefix' => '12th_'],
            'uploadTestimonialsGrad' => ['type' => 11, 'prefix' => 'Grad_'],
            'uploadTestimonialsPostGrad' => ['type' => 12, 'prefix' => 'PostGrad_'],
            'uploadTestimonialsOther' => ['type' => 13, 'prefix' => 'Other_'],
            'uploadTestimonialsOtherEducation' => ['type' => 14, 'prefix' => 'OtherEdu_'],
        ];

        foreach ($documentMap as $inputName => $details) {
            $this->uploadEmployeeDocument($request, $inputName, $employee, $details['type'], $details['prefix']);
        }
    }

    /**
     * Reusable helper method to upload a single document.
     */
    private function uploadEmployeeDocument(Request $request, string $inputName, EmpDet $employee, int $docType, string $prefix)
    {
        if (!$request->hasFile($inputName)) {
            return;
        }

        $file = $request->file($inputName);
        $fileName = date('dmYHis') . $prefix . $employee->id . '.' . $file->extension();
        $file->move(public_path('admin/images/empDocs/' . $employee->empCode . "/"), $fileName);

        // Deactivate old documents of the same type before creating a new one.
        UploadEmpDoc::where('empId', $employee->id)->where('type', $docType)->update(['active' => '0']);

        UploadEmpDoc::create([
            'empId' => $employee->id,
            'empCode' => $employee->empCode,
            'fileName' => $fileName,
            'type' => $docType,
            'updated_by' => Auth::user()->username,
        ]);
    }

    // access only for developer
    public function deleteEmployeeAccessOnlyDeveloper($empId)
    {
        $empDet = EmpDet::find($empId);
        DB::beginTransaction();
        try 
        {
            if($empDet)
            {
                $emloyeeLetters = EmployeeLetter::where('empId', $empId)->get();
                foreach($emloyeeLetters as $letter)
                {
                    $letter->delete();
                }

                $retentions = Retention::where('empId', $empId)->get();
                foreach($retentions as $retention)
                {
                    $retention->delete();
                }

                $empFamilyDets = EmpFamilyDet::where('empId', $empId)->get();
                foreach($empFamilyDets as $empFamilyDet)
                {
                    $empFamilyDet->delete();
                }

                $employeeExperiences = EmployeeExperience::where('empId', $empId)->get();
                foreach($employeeExperiences as $employeeExperience)
                {
                    $employeeExperience->delete();
                }

                User::where('empId', $empId)->delete();
                
                $uploadEmpDocs = UploadEmpDoc::where('empId', $empId)->get();
                foreach($uploadEmpDocs as $uploadEmpDoc)
                {
                    $uploadEmpDoc->delete();
                }
                $empDet->delete();

                DB::commit();
                $sms = "Employee Deleted Permantly successfully..";
                return redirect('/home')->with("success",$sms);
            }
        }
        catch (\Exception $e) 
        {
            DB::rollback();
            return redirect()->back()->withInput()->with("error","something went wrong : ".$e->getMessage());
        }
    }

    public function getLastEmpCode($firmType)
    {
        $userType = Auth::user()->userType;
        if($userType != '51')
            return redirect()->back()->withInput()->with("error","You don't have access this page, only HR Can add New Employee");

        if($firmType == 1)
        {
            return $lastEmpCode = EmpDet::where('firmType', $firmType)
            ->whereNotIn('empCode', [4414, 4413,4412])
            ->orderBy('empCode', 'desc')
            ->value('empCode');
        }
        else
        {
            $lastEmpCode = EmpDet::where('firmType', $firmType)
            ->orderBy('empCode', 'desc')
            ->value('empCode');
            if($lastEmpCode == '')
                $lastEmpCode = 0;

            return $lastEmpCode;
                
        }
    }
   
    public function show($id)
    {
        $employee = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.*', 'emp_dets.branchName as bankBranchName','contactus_land_pages.branchName', 'departments.section', 
        'departments.name as departmentName', 'designations.name as designationName')
        ->where('emp_dets.id', $id)
        ->first();

        $buddyNames = EmpDet::where('active', 1)->orderBy('name')->pluck('name','id');

        $buddyName='';
        if($employee->buddyName != '')
            $buddyName = EmpDet::where('id', $employee->buddyName)->value('name');

        if(!$employee)
            return redirect()->back()->withInput()->with("error","Employee Not Found");

        if($employee->presentRegionId != '')
            $employee['regionName']=Region::where('id', $employee->presentRegionId)->value('name');
        else
            $employee['regionName']='';

        if($employee->presentCityId != '')
            $employee['cityName']=City::where('id', $employee->presentCityId)->value('name');
        else
            $employee['cityName']='';

        $empDet = EmpDet::select('reportingId','reportingType','empCode', 'firmType', 'departmentId')->where('id', $id)->first();
           
        if($empDet->reportingType == 2)
            $repoName = User::where('id', $empDet->reportingId)->value('name');
        else
            $repoName = EmpDet::where('id', $empDet->reportingId)->value('name');

        $empFamilyDet = EmpFamilyDet::where('empId', $id)->get();
        $empExperiences = EmployeeExperience::where('empId', $id)->get();        

        $systemHistories1 = EmpAssignHistory::join('system_assets', 'emp_assign_histories.assignId', 'system_assets.id')
        ->select('system_assets.*', 'emp_assign_histories.type', 'emp_assign_histories.forDate')
        ->where('emp_assign_histories.type', 1)
        ->where('emp_assign_histories.empId', $id)
        ->where('emp_assign_histories.active', 1)
        ->get();

        $systemHistories2 = EmpAssignHistory::join('system_assets', 'emp_assign_histories.assignId', 'system_assets.id')
        ->select('system_assets.*', 'emp_assign_histories.type', 'emp_assign_histories.forDate')
        ->where('emp_assign_histories.type', 5)
        ->where('emp_assign_histories.empId', $id)
        ->where('emp_assign_histories.active', 1)
        ->get();

        $mobileHistories = EmpAssignHistory::join('mobile_assets', 'emp_assign_histories.assignId', 'mobile_assets.id')
        ->select('mobile_assets.*','emp_assign_histories.forDate')
        ->where('emp_assign_histories.type', 2)
        ->where('emp_assign_histories.empId', $id)
        ->where('emp_assign_histories.active', 1)
        ->get();

        $otherHistories = EmpAssignHistory::join('other_assets', 'emp_assign_histories.assignId', 'other_assets.id')
        ->select('other_assets.*','emp_assign_histories.type','emp_assign_histories.forDate')
        ->whereIn('emp_assign_histories.type', [3,4,6])
        ->where('emp_assign_histories.empId', $id)
        ->where('emp_assign_histories.active', 1)
        ->get();

        $empStationaryDet = EmpStationaryDet::where('empId', $id)->first();
        $feesConcession=EmpFeesConcession::where('empId', $id)->orderBy('id', 'desc')->where('active', 1)->first();

        $branches = ContactusLandPage::whereActive(1)->pluck('branchName','id');
        $departments = Department::whereActive(1)->pluck('name', 'id');
        $designations = Designation::whereActive(1)->pluck('name', 'id');
        $states = Region::whereActive(1)->pluck('name', 'id');
        $cities = City::whereActive(1)->pluck('name', 'id');
        
        $laptops = SystemAsset::where('type', 1)->whereActive(1)->orderBy('MACId')->pluck('MACId', 'id');
        $desktops = SystemAsset::where('type', 2)->whereActive(1)->orderBy('MACId')->pluck('MACId', 'id');
        $mobiles = MobileAsset::whereActive(1)->orderBy('modelNumber')->pluck('modelNumber', 'id');
        
        $simcards = OtherAsset::where('assetType', 1)->whereActive(1)->orderBy('mobNumber')->pluck('mobNumber', 'id');
        $pendAssets = OtherAsset::where('assetType', 2)->whereActive(1)->orderBy('storeageSize')->pluck('storeageSize', 'id');
        $hardDAssets = OtherAsset::where('assetType', 3)->whereActive(1)->orderBy('storeageSize')->pluck('storeageSize', 'id');
        $uniforms = [];
        
        $userRoles = UserRole::whereIn('userType', ['11','21','31'])->whereActive(1)->orderBy('userType')->pluck('name','id');
        $changedPassword = User::where('empId', $id)->value('newUser');

        $docs1 = UploadEmpDoc::where('empId', $id)->where('type', 1)->where('active', 1)->first();
        $docs2 = UploadEmpDoc::where('empId', $id)->where('type', 2)->where('active', 1)->first();
        $docs3 = UploadEmpDoc::where('empId', $id)->where('type', 3)->where('active', 1)->first();
        $docs4 = UploadEmpDoc::where('empId', $id)->where('type', 4)->where('active', 1)->first();
        $docs5 = UploadEmpDoc::where('empId', $id)->where('type', 5)->where('active', 1)->first();
        $docs6 = UploadEmpDoc::where('empId', $id)->where('type', 6)->where('active', 1)->first();
        $docs7 = UploadEmpDoc::where('empId', $id)->where('type', 7)->where('active', 1)->first();
        $docs8 = UploadEmpDoc::where('empId', $id)->where('type', 8)->where('active', 1)->first();
        $docs9 = UploadEmpDoc::where('empId', $id)->where('type', 9)->where('active', 1)->first();
        $docs10 = UploadEmpDoc::where('empId', $id)->where('type', 10)->where('active', 1)->first();
        $docs11 = UploadEmpDoc::where('empId', $id)->where('type', 11)->where('active', 1)->first();
        $docs12 = UploadEmpDoc::where('empId', $id)->where('type', 12)->where('active', 1)->first();
        $docs13 = UploadEmpDoc::where('empId', $id)->where('type', 13)->where('active', 1)->first();
        $docs14 = UploadEmpDoc::where('empId', $id)->where('type', 14)->where('active', 1)->first();
        $aggrementDoc = EmployeeLetter::where('empId', $id)->where('letterType', 3)->orderBy('id', 'desc')->first();

        $report1 = collect(EmpDet::select('name', 'id', 'empCode')->where('userRoleId', '!=', '5')->orderBy('name')->whereActive(1)->get());
        $report2 = collect(User::select('name', 'id', 'empId as empCode')->whereIn('userType', [201,301,401,501,601])->orderBy('name')->whereActive(1)->get());
 
        $empReportings = $report1->merge($report2);
        $empReportings=$empReportings->sortBy('name');    
        
        $repoDesignation='';
        $empUser = EmpDet::where('id', $employee->reportingId)->first();
        if(!$empUser)
            $empUser = User::where('id', $employee->reportingId)->first();

        if($empUser)
            $repoDesignation = Designation::where('id', $empUser->designationId)->value('name');

        $empVerfication = EmpVerification::where('verificationRemark5', $employee->AADHARNo)->first();
      
        $letters = EmployeeLetter::where('empId', $id)->orderBy('id', 'desc')->get();
        $empUser = User::where('empId', $employee->id)->whereIn('userType', ['11', '21', '31'])->first();
        return view('admin.employees.masters.show')->with(['letters'=>$letters, 'employee'=>$employee,'cities'=>$cities,'feesConcession'=>$feesConcession,
        'empFamilyDet'=>$empFamilyDet,'departments'=>$departments,'buddyName'=>$buddyName,'empReportings'=>$empReportings,'aggrementDoc'=>$aggrementDoc,
        'docs1'=>$docs1,'docs2'=>$docs2,'docs3'=>$docs3,'docs4'=>$docs4,'docs5'=>$docs5,'docs6'=>$docs6,'repoDesignation'=>$repoDesignation,
        'docs7'=>$docs7,'docs8'=>$docs8,'docs9'=>$docs9,'docs10'=>$docs10,'docs11'=>$docs11,'docs12'=>$docs12,'docs13'=>$docs13,'docs14'=>$docs14,'buddyNames'=>$buddyNames,'empExperiences'=>$empExperiences,
        'changedPassword'=>$changedPassword,'designations'=>$designations,'states'=>$states,'laptops'=>$laptops,'branches'=>$branches,
        'desktops'=>$desktops,'mobiles'=>$mobiles,'simcards'=>$simcards,'uniforms'=>$uniforms,'userRoles'=>$userRoles,'empUser'=>$empUser,'empVerfication'=>$empVerfication,
        'repoName'=>$repoName, 'systemHistories2'=>$systemHistories2, 'systemHistories1'=>$systemHistories1, 'mobileHistories'=>$mobileHistories, 'otherHistories'=>$otherHistories]);
   
    }

    public function downloadCif($id)
    {
        $employee = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.*', 'contactus_land_pages.branchName', 'departments.section', 
        'departments.name as departmentName', 'designations.name as designationName')
        ->where('emp_dets.id', $id)
        ->first();

        $presentRegion = Region::Where('id', $employee->presentRegionId)->value('name');
        $presentCity = City::Where('id', $employee->presentCityId)->value('name');

        $permanentRegion = Region::Where('id', $employee->permanentRegionId)->value('name');
        $permanentCity = City::Where('id', $employee->permanentCityId)->value('name');

        $docs1 = UploadEmpDoc::where('empId', $id)->where('type', 1)->where('active', 1)->count();
        $docs2 = UploadEmpDoc::where('empId', $id)->where('type', 2)->where('active', 1)->count();
        $docs3 = UploadEmpDoc::where('empId', $id)->where('type', 3)->where('active', 1)->count();
        $docs4 = UploadEmpDoc::where('empId', $id)->where('type', 4)->where('active', 1)->count();
        $docs5 = UploadEmpDoc::where('empId', $id)->where('type', 5)->where('active', 1)->count();
        $docs6 = UploadEmpDoc::where('empId', $id)->where('type', 6)->where('active', 1)->count();
        $docs7 = UploadEmpDoc::where('empId', $id)->where('type', 7)->where('active', 1)->count();
        $docs8 = UploadEmpDoc::where('empId', $id)->where('type', 8)->where('active', 1)->count();
        $docs9 = UploadEmpDoc::where('empId', $id)->where('type', 9)->where('active', 1)->count();

        $pdf = PDF::loadView('admin.pdfs.cif',compact('employee','docs1','docs2','docs3','docs4','docs5','docs6','docs7','docs8','docs9','presentRegion','presentCity','permanentRegion','permanentCity'));
        return $pdf->stream('CIF');
    }
  
    public function edit($id)
    {
        // // 1. Find the main employee record
        // $employee = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        // ->select('emp_dets.*', 'departments.section')
        // ->where('emp_dets.id', $id)
        // ->first();

        // $user = User::where('empId', $employee->id)->first();

        // // 2. Manually fetch related data since no relationships are used
        // $employee_experiences = EmployeeExperience::where('empId', $id)->get();
        // $employee_family_details = EmpFamilyDet::where('empId', $id)->get();
        // $employee_documents = UploadEmpDoc::where('empId', $id)->get();

        // // 3. Load all the data required for the form's dropdowns
        // $userRoles = UserRole::orderBy('name')->pluck('name', 'id');
        // $organisations = Organisation::orderBy('shortName')->pluck('shortName', 'id');
        // $branches = ContactusLandPage::orderBy('branchName')->pluck('branchName', 'id');
        // $departments = Department::orderBy('name')->pluck('name', 'id');
        // $designations = Designation::where('departmentId', $employee->departmentId)->orderBy('name')->pluck('name', 'id');
        // $allEmployees = EmpDet::where('id', '!=', $id)->orderBy('name')->get(['name', 'id', 'empCode']);
        // $empReportings = $allEmployees;
        // $buddyNames =  EmpDet::where('id', '!=', $id)->orderBy('name')->pluck('name', 'id');

        // // 4. Return the view, passing ALL data to it
        // return view('admin.employees.masters.edit', compact(
        //     'employee', 
        //     'employee_experiences',         // Pass experiences
        //     'employee_family_details',    // Pass family details
        //     'employee_documents',         // Pass documents
        //     'userRoles', 
        //     'organisations',
        //     'branches',
        //     'departments',
        //     'empReportings',
        //     'designations',
        //     'buddyNames',
        //     'user'
        // ));
        $userType = Auth::user()->userType;
        // if($userType != '51' && $userType != '61')
        //     return redirect()->back()->withInput()->with("error","You don't have access this page, only HR Can add /Edit Employee");

        if($userType == '31' || $userType == '11')
            $userType = Auth::user()->deptUserType;
            
        $employee = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->select('emp_dets.*', 'departments.section','designations.name as designationName')
        ->where('emp_dets.id', $id)
        ->first();

        $organisations = Organisation::orderBy('shortName')->pluck('shortName', 'id');

        if($userType == '51') // HR Department
        {
            $docs1 = UploadEmpDoc::where('empId', $id)->where('type', 1)->where('active', 1)->first();
            $docs2 = UploadEmpDoc::where('empId', $id)->where('type', 2)->where('active', 1)->first();
            $docs3 = UploadEmpDoc::where('empId', $id)->where('type', 3)->where('active', 1)->first();
            $docs4 = UploadEmpDoc::where('empId', $id)->where('type', 4)->where('active', 1)->first();
            $docs5 = UploadEmpDoc::where('empId', $id)->where('type', 5)->where('active', 1)->first();
            $docs6 = UploadEmpDoc::where('empId', $id)->where('type', 6)->where('active', 1)->first();
            $docs7 = UploadEmpDoc::where('empId', $id)->where('type', 7)->where('active', 1)->first();
            $docs8 = UploadEmpDoc::where('empId', $id)->where('type', 8)->where('active', 1)->first();
            $docs9 = UploadEmpDoc::where('empId', $id)->where('type', 9)->where('active', 1)->first();
            
            $docs10 = UploadEmpDoc::where('empId', $id)->where('type', 10)->where('active', 1)->first();
            $docs11 = UploadEmpDoc::where('empId', $id)->where('type', 11)->where('active', 1)->first();
            $docs12 = UploadEmpDoc::where('empId', $id)->where('type', 12)->where('active', 1)->first();
            $docs13 = UploadEmpDoc::where('empId', $id)->where('type', 13)->where('active', 1)->first();
            $docs14 = UploadEmpDoc::where('empId', $id)->where('type', 14)->where('active', 1)->first();
    
            $employee['transAllowed']=User::where('empId', $employee->id)->value('transAllowed');

            $empFamilyDet = EmpFamilyDet::where('empId', $id)->get();
            $empExperiences = EmployeeExperience::where('empId', $id)->get();

            $report1 = collect(EmpDet::select('name', 'id', 'empCode')->where('userRoleId', '!=', '5')->orderBy('name')->whereActive(1)->get());
            $report2 = collect(User::select('name', 'id', 'empId as empCode')->whereIn('userType', [201,301,401,501,601])->orderBy('name')->whereActive(1)->get());
     
            $empReportings = $report1->merge($report2);
            $empReportings=$empReportings->sortBy('name');    
           
            $branches = ContactusLandPage::whereActive(1)->pluck('branchName','id');
            $feesBranches = ContactusLandPage::whereActive(1)->pluck('shortName','id');
            $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
            $designations = Designation::whereActive(1)->orderBy('name')->pluck('name', 'id');
            $buddyNames = EmpDet::where('active', 1)->orderBy('name')->pluck('name','id');
            $userRoles = UserRole::whereIn('userType', ['11','21','31'])->whereActive(1)->orderBy('userType')->pluck('name','id');

            $desRepoAuth = '';
            $empUser = EmpDet::where('id', $employee->reportingId)->first();
            if(!$empUser)
            {
                $empUser = User::where('id', $employee->reportingId)->first();
            }
    
            if($empUser)
            {
                $desRepoAuth= Designation::where('id', $empUser->designationId)->value('name');
            }
          
            return view('admin.employees.hrEdit')->with(['employee'=>$employee,'feesBranches'=>$feesBranches,'organisations'=>$organisations,
            'empFamilyDet'=>$empFamilyDet,'departments'=>$departments,'empExperiences'=>$empExperiences,'buddyNames'=>$buddyNames,
            'docs1'=>$docs1,'docs2'=>$docs2,'docs3'=>$docs3,'docs4'=>$docs4,'docs5'=>$docs5,'docs6'=>$docs6,'desRepoAuth'=>$desRepoAuth,
            'docs7'=>$docs7,'docs8'=>$docs8,'docs9'=>$docs9,'docs10'=>$docs10,'docs11'=>$docs11,'docs12'=>$docs12,'docs13'=>$docs13,'docs14'=>$docs14,
            'designations'=>$designations,'empReportings'=>$empReportings,'branches'=>$branches,'userRoles'=>$userRoles]);
        }

        //Account Department
        if($userType == '61')
        {
            return view('admin.employees.accountEdit')->with(['employee'=>$employee]);
        }

        // ERP Department
        if($userType == '81')
        {
            $feesConcession=EmpFeesConcession::where('empId', $id)->orderBy('id', 'desc')->where('active', 1)->first();
            $branches = ContactusLandPage::whereActive(1)->pluck('shortName','id');
            return view('admin.employees.erpEdit')->with(['branches'=>$branches,'employee'=>$employee,'feesConcession'=>$feesConcession]);
        }

        // Store Department
        if($userType == '91')
        {
            $empStationaryDet = EmpStationaryDet::where('empId', $id)->first();
            $uniforms = Asset::where('assetCategory', 4)->whereActive(1)->orderBy('uniformName')->pluck('uniformName', 'id');

            return view('admin.employees.storeEdit')->with(['empStationaryDet'=>$empStationaryDet,'uniforms'=>$uniforms,'employee'=>$employee]);
        }

        // IT Department
        if($userType == '71')
        {
            $systemHistories = EmpAssignHistory::join('system_assets', 'emp_assign_histories.assignId', 'system_assets.id')
            ->select('system_assets.id', 'system_assets.MACId', 'emp_assign_histories.type', 'emp_assign_histories.forDate')
            ->whereIn('emp_assign_histories.type', [1, 5])
            ->where('emp_assign_histories.empId', $id)
            ->where('emp_assign_histories.active', 1)
            ->get();

            $mobileHistories = EmpAssignHistory::join('mobile_assets', 'emp_assign_histories.assignId', 'mobile_assets.id')
            ->select('mobile_assets.id', 'mobile_assets.companyName', 'mobile_assets.modelNumber','mobile_assets.IMEI1','emp_assign_histories.forDate')
            ->where('emp_assign_histories.type', 2)
            ->where('emp_assign_histories.empId', $id)
            ->where('emp_assign_histories.active', 1)
            ->get();

            $otherHistories = EmpAssignHistory::join('other_assets', 'emp_assign_histories.assignId', 'other_assets.id')
            ->select('other_assets.id', 'other_assets.operatComName','emp_assign_histories.type',
             'other_assets.mobNumber','other_assets.storeageSize','other_assets.extraMat','emp_assign_histories.forDate')
            ->whereIn('emp_assign_histories.type', [3,4,6])
            ->where('emp_assign_histories.empId', $id)
            ->where('emp_assign_histories.active', 1)
            ->get();
            
            $laptops = SystemAsset::where('status', '!=',1)->where('type', 1)->whereActive(1)->orderBy('MACId')->pluck('MACId', 'id');
            $desktops = SystemAsset::where('status', '!=',1)->where('type', 2)->whereActive(1)->orderBy('MACId')->pluck('MACId', 'id');
            $mobiles = MobileAsset::where('status', '!=',1)->whereActive(1)->orderBy('modelNumber')->pluck('modelNumber', 'id');
            
            $simcards = OtherAsset::where('status', '!=',1)->where('assetType', 1)->whereActive(1)->orderBy('mobNumber')->pluck('mobNumber', 'id');
            $pendAssets = OtherAsset::where('status', '!=',1)->where('assetType', 2)->whereActive(1)->orderBy('storeageSize')->pluck('storeageSize', 'id');
            $hardDAssets = OtherAsset::where('status', '!=',1)->where('assetType', 3)->whereActive(1)->orderBy('storeageSize')->pluck('storeageSize', 'id');

            return view('admin.employees.itEdit')->with(['employee'=>$employee, 'laptops'=>$laptops,'mobiles'=>$mobiles,
            'hardDAssets'=>$hardDAssets,'pendAssets'=>$pendAssets,'desktops'=>$desktops,
            'simcards'=>$simcards, 'systemHistories'=>$systemHistories, 'otherHistories'=>$otherHistories, 'mobileHistories'=>$mobileHistories]);
        }
    }

    public function getReportingDesignations($id)
    {
        $designationId = EmpDet::where('id', $id)->where('active', 1)->value('designationId');
        if(!$designationId)
        {
            $designationId = User::where('id', $id)->value('designationId');
        }

        return Designation::where('id', $designationId)->value('name');
    }

    public function update(Request $request, $id)
    {
        $userType = Auth::user()->userType;
         if($userType != '51' && $userType != '61' && $userType == '81' && $userType == '71' && $userType == '91')
            return redirect()->back()->withInput()->with("error","You don't have access this page, only HR Can add /Edit Employee");

        $employee = EmpDet::find($id);
        $user = Auth::user();

        // ERP Department
        if($userType == '81')
        {
            $employee->feesConcession = $request->feesConcetion;
            $employee->updated_by = Auth::user()->username;
            if($employee->save())
            {
                if($request->feesConcetion == 'Yes')
                {
                    $feesConcession = EmpFeesConcession::where('empId', $id)->where('acadmicYear', $request->acadmicYear)->first();
                    if(!$feesConcession )
                        $feesConcession = new EmpFeesConcession;

                    $feesConcession->empId = $id;
                    $feesConcession->acadmicYear = $request->acadmicYear;
                    $feesConcession->studentName = $request->studentName;
                    $feesConcession->branchId = $request->branchId;
                    $feesConcession->classSection = $request->classSection;
                    $feesConcession->category = $request->category;
                    $feesConcession->tuitionInst1 = $request->tuitionInst1;
                    $feesConcession->tuitionFees1 = $request->tuitionFees1;
                    $feesConcession->tuitionInst2 = $request->tuitionInst2;
                    $feesConcession->tuitionFees2 = $request->tuitionFees2;
                    $feesConcession->tuitionInst3 = $request->tuitionInst3;
                    $feesConcession->tuitionFees3 = $request->tuitionFees3;
                    $feesConcession->worksheetInst1 = $request->worksheetInst1;
                    $feesConcession->worksheetFees1 = $request->worksheetFees1;
                    $feesConcession->worksheetInst2 = $request->worksheetInst2;
                    $feesConcession->worksheetFees2 = $request->worksheetFees2;
                    $feesConcession->worksheetInst3 = $request->worksheetInst3;
                    $feesConcession->worksheetFees3 = $request->worksheetFees3;
                    $feesConcession->transportInst1 = $request->transportInst1;
                    $feesConcession->transportInst2 = $request->transportInst2;
                    $feesConcession->transportFees2 = $request->transportFees2;
                    $feesConcession->transportInst3 = $request->transportInst3;
                    $feesConcession->transportFees3 = $request->transportFees3;
                    $feesConcession->gpsInst1 = $request->gpsInst1;
                    $feesConcession->gpsCharge1 = $request->gpsCharge1;
                    $feesConcession->gpsInst2 = $request->gpsInst2;
                    $feesConcession->gpsCharge2 = $request->gpsCharge2;
                    $feesConcession->gpsInst3 = $request->gpsInst3;
                    $feesConcession->gpsCharge3 = $request->gpsCharge3;
                    $feesConcession->type = 1;
                    $feesConcession->updated_by = Auth::user()->username;                   
                    $feesConcession->save();

                }
                return redirect('/employees')->with("success","Employee's Fees Concession Details Updated successfully..");
            }
        }

        //IT Department
        if($userType == '71')
        {
            if($request->laptopStatus == '1')
            {
                $empLaptop = new EmpAsset;
                $empLaptop->empId = $id;
                $empLaptop->type = 1;
                $empLaptop->count = $request->count;
                $empLaptop->remark = $request->remark;
                $empLaptop->save();
            }

            if($request->desktopStatus == '1')
            {
                $empLaptop = new EmpAsset;
                $empLaptop->empId = $id;
                $empLaptop->type = 2;
                $empLaptop->count = $request->count;
                $empLaptop->remark = $request->remark;
                $empLaptop->save();
            }

            if($request->mobileStatus == '1')
            {
                $empLaptop = new EmpAsset;
                $empLaptop->empId = $id;
                $empLaptop->type = 3;
                $empLaptop->count = $request->count;
                $empLaptop->remark = $request->remark;
                $empLaptop->save();
            }

            if($request->simcardStatus == '1')
            {
                $empLaptop = new EmpAsset;
                $empLaptop->empId = $id;
                $empLaptop->type = 4;
                $empLaptop->count = $request->count;
                $empLaptop->remark = $request->remark;
                $empLaptop->save();
            }

            if($request->pendriveStatus == '1')
            {
                $empLaptop = new EmpAsset;
                $empLaptop->empId = $id;
                $empLaptop->type = 5;
                $empLaptop->count = $request->count;
                $empLaptop->remark = $request->remark;
                $empLaptop->save();
            }

            if($request->hardDiskStatus == '1')
            {
                $empLaptop = new EmpAsset;
                $empLaptop->empId = $id;
                $empLaptop->type = 6;
                $empLaptop->count = $request->count;
                $empLaptop->remark = $request->remark;
                $empLaptop->save();
            }

            return redirect('/employees')->with("success","Asset assigned to Employee successfully..");
        }

        //Account Department
        if($userType == '61')
        { 
            $employee->salaryScale=$request->salaryScale;
            $employee->organisation=$request->organisation;
            $employee->retentionAmount=$request->retention;
            $employee->bankName=$request->bankName;
            $employee->branchName=$request->branchName;
            $employee->bankAccountNo=$request->bankAccountNo;
            $employee->bankIFSCCode=$request->bankIFSCCode;
            $employee->PF=$request->PF;
            $employee->PT=$request->PT;
            $employee->ESIC=$request->ESIC;
            $employee->MLWF=$request->MLWF;
            $employee->TDS=$request->TDS;
            $employee->updated_by=Auth::user()->username;
            $employee->save();
            $section = Department::where('id', $employee->departmentId)->value('section');

            if($section == 'Teaching')
                return redirect('/employees/teachingEmps')->with("success","Employee Details Updated successfully..");
            else    
                return redirect('/employees/nonTeachingEmps')->with("success","Employee Details Updated successfully..");

        }
   
        // HR Department
        if($userType == '51')
        {
            $employee->idCardStatus = $request->idCardStatus;
            $employee->verifyStatus = $request->verifyStatus;
            $employee->firstName = $request->firstName;
            $employee->middleName = $request->middleName;
            $employee->lastName = $request->lastName;
            $employee->name = $employee->firstName.' '.$employee->middleName.' '.$employee->lastName;
            if(!empty($request->file('profilePhoto')))
            {
                if($employee->profilePhoto != '')
                {
                    $oldImage = base_path('public/admin/profilePhotos/').$employee->profilePhoto;

                    if (File::exists($oldImage))  // unlink or remove previous image from folder
                    {
                        unlink($oldImage);
                    }
                }

                $originalImage= $request->file('profilePhoto');
                $Image = $request->PANNo.'.'.$originalImage->getClientOriginalExtension();
                $image = Image::make($originalImage);
                $originalPath =  public_path()."/admin/profilePhotos/";
                $image->resize(400,500);
                $image->save($originalPath.$Image);
                $employee->profilePhoto = $Image;
            }

            $empCode = $employee->empCode;
            $DOB = date('Y-m-d', strtotime($request->DOB));
            $employee->gender = $request->gender;
            $employee->region = $request->region;
            $employee->cast = $request->cast;
            $employee->type = $request->type;
            $employee->DOB = date('Y-m-d', strtotime($request->DOB));
            $employee->maritalStatus = $request->maritalStatus;
            $employee->phoneNo = $request->phoneNo;
            $employee->whatsappNo = $request->whatsappNo;
            $employee->email = $request->email;
            $employee->presentAddress = $request->presentAddress;
            $employee->permanentAddress = $request->permanentAddress;
            $employee->qualification = $request->qualification;
            $employee->workingStatus = $request->workDet;

            $employee->branchId  = $request->branchId;
            $employee->organisationId  = $request->organisation;
            $employee->organisation  = Organisation::where('id', $request->organisation)->value('name');
            $employee->departmentId  = $request->departmentId;
            $employee->designationId  = $request->designationId;
            $employee->teachingSubject  = $request->teachingSubject;
            $employee->reportingId  = $request->reportingId;
            $employee->reportingType  = $request->reportingIdType;
            $employee->buddyName = $request->buddyName;
            $employee->jobJoingDate = $request->jobJoingDate;
            $employee->shift = $request->shift;
            $employee->startTime = date('H:i:s', strtotime($request->jobStartTime));
            $employee->endTime = date('H:i:s', strtotime($request->jobEndTime));
            $employee->contractStartDate = $request->contractStartDate;
            $employee->contractEndDate = $request->contractEndDate;

            $employee->AADHARNo = $request->aadhaarCardNo;
            $employee->PANNo = $request->PANNo;
            $employee->bankName = $request->bankName;
            $employee->branchName = $request->branchName;
            $employee->bankAccountName = $request->bankAccountName;
            $employee->bankAccountNo = $request->bankAccountNo;
            $employee->bankIFSCCode = $request->bankIFSCCode;
            $employee->pfNumber = $request->pfNumber;
            $employee->uIdNumber = $request->uIdNumber;
            $employee->reference = $request->reference;
            $employee->instagramId = $request->instagramId;
            $employee->twitterId = $request->twitterId;
            $employee->facebookId = $request->facebookId;
            $employee->workingStatus = $request->workingStatus;

            if($request->emergencyName1 != '' && $request->emergencyRelation1 != '')
            {
                $empFamily1 = EmpFamilyDet::where('empId', $id)->where('name', $request->emergencyName1)->first(); 
                if(!$empFamily1)
                    $empFamily1 = new EmpFamilyDet;
               
                $empFamily1->empId = $id;
                $empFamily1->name = $request->emergencyName1;
                $empFamily1->relation = $request->emergencyRelation1;
                $empFamily1->occupation = $request->emergencyPlace1;
                $empFamily1->contactNo = $request->emergencyContactNo1;
                $empFamily1->updated_by = Auth::user()->username;
                $empFamily1->save();
            }

            if($request->emergencyName2 != '' && $request->emergencyRelation2 != '')
            {
                $empFamily2 = EmpFamilyDet::where('empId', $id)->where('name', $request->emergencyName2)->first(); 
                if(!$empFamily2)
                    $empFamily2 = new EmpFamilyDet;

                $empFamily2->empId = $id;
                $empFamily2->name = $request->emergencyName2;
                $empFamily2->relation = $request->emergencyRelation2;
                $empFamily2->occupation = $request->emergencyPlace2;
                $empFamily2->contactNo = $request->emergencyContactNo2;
                $empFamily2->updated_by = Auth::user()->username;
                $empFamily2->save();
            }        
        
            $employeeOlUserRoleId  = $employee->userRoleId;
            $employee->userRoleId  = $request->userRoleId;
            $employee->added_by = Auth::user()->username;
            $employee->updated_by = Auth::user()->username;
    
            DB::beginTransaction();
    
            try 
            {
                if($employee->save())
                {
                    if($request->workingStatus == 2)
                    {
                        for($i=0; $i<5; $i++)
                        {
                            if($request->experName[$i] != '' && $request->experDesignation[$i] != '')
                            {
                                
                                $empExperience = EmployeeExperience::where('experName', $request->experName[$i])->where('experDesignation', $request->experDesignation[$i])->first();
                                if(!$empExperience)
                                    $empExperience = new EmployeeExperience;

                                $empExperience->empId = $employee->id;
                                $empExperience->experName = $request->experName[$i];
                                $empExperience->experDesignation  = $request->experDesignation[$i];
                                $empExperience->experFromDuration = $request->experFromDuration[$i];
                                $empExperience->experToDuration = $request->experToDuration[$i];
                                $empExperience->experLastSalary = $request->experLastSalary[$i];
                                $empExperience->experJobDesc = $request->experJobDesc[$i];
                                $empExperience->experReasonLeaving = $request->experReasonLeaving[$i];
                                $empExperience->experReportingAuth = $request->experReportingAuth[$i];
                                $empExperience->experReportingDesignation = $request->experReportingDesignation[$i];
                                $empExperience->experCompanyCont = $request->experCompanyCont[$i];
                                $empExperience->updated_by = Auth::user()->username;
                                $empExperience->save();
                                
                            } 
                        }
                    }  

                    $exitProcess = ExitProcessStatus::where('empId', $employee->id)->orderBy('id', 'desc')->first();
                    if($exitProcess)
                    {
                        if($employee->reportingType == 2 || $employee->reportingType == '')
                        {
                            $exitProcess->reportingAuthId =  User::where('empId', $employee->reportingId)
                                    ->whereIn('userType', [11, 21, 31])
                                    ->value('id');
                            if($exitProcess->reportingAuthId == '')
                                $exitProcess->reportingAuthId =  User::where('id', $employee->reportingId)->value('id');

                        }
                        else
                        {
                            $exitProcess->reportingAuthId =  User::where('id', $employee->reportingId)->value('id');
                        }

                        $exitProcess->save();
                    }
                    
                    $user = User::where('empId', $employee->id)->where('userRoleId', $employeeOlUserRoleId)->first();
                    $user->name = $employee->name;
                    $user->username = $employee->username;
                    $user->email = $employee->email;                    
                 
                    $user->transAllowed =  $request->transAllowed;
                    $user->userRoleId =  $request->userRoleId;
                    $user->userType = UserRole::where('id', $request->userRoleId)->value('userType');
                    $user->updated_by = Auth::user()->username;
                    if($user->save())
                    {
                        
                        if(!empty($request->file('uploadAddharCard')))
                        {
                            $originalFile= $request->file('uploadAddharCard');
                            $fileName = date('dmhis').'AC_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 1)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 1;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        if(!empty($request->file('uploadPanCard')))
                        {
                            $originalFile= $request->file('uploadPanCard');
                            $fileName = date('dmhis').'PC_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 2)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 2;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }
                
                        if(!empty($request->file('uploadTestimonials10th')))
                        {
                            $originalFile= $request->file('uploadTestimonials10th');
                            $fileName = date('dmhis').'10th_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 3)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 3;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        if(!empty($request->file('uploadTestimonials12th')))
                        {
                            $originalFile= $request->file('uploadTestimonials12th');
                            $fileName = date('dmhis').'12th_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 10)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 10;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        if(!empty($request->file('uploadTestimonialsGrad')))
                        {
                            $originalFile= $request->file('uploadTestimonialsGrad');
                            $fileName = date('dmhis').'Grad_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 11)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 11;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        if(!empty($request->file('uploadTestimonialsPostGrad')))
                        {
                            $originalFile= $request->file('uploadTestimonialsPostGrad');
                            $fileName = date('dmhis').'PostGrad_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 12)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 12;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }
                        
                        if(!empty($request->file('uploadTestimonialsOtherEducation')))
                        {
                            $originalFile= $request->file('uploadTestimonialsOtherEducation');
                            $fileName = date('dmhis').'OtherEducation.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 14)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 14;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        if(!empty($request->file('uploadDrivingLicense')))
                        {
                            $originalFile= $request->file('uploadDrivingLicense');
                            $fileName = date('dmhis').'DL_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 4)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 4;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        if(!empty($request->file('uploadRtoBatch')))
                        {
                            $originalFile= $request->file('uploadRtoBatch');
                            $fileName = date('dmhis').'RB_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 5)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 5;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        if(!empty($request->file('uploadElectricityBill')))
                        {
                            $originalFile= $request->file('uploadElectricityBill');
                            $fileName = date('dmhis').'RB_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 6)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 6;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        if(!empty($request->file('uploadBankDetails')))
                        {
                            $originalFile= $request->file('uploadBankDetails');
                            $fileName = date('dmhis').'BD_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 7)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 7;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        if(!empty($request->file('uploadEmployeeContract')))
                        {
                            $originalFile= $request->file('uploadEmployeeContract');
                            $fileName = date('dmhis').'EC_.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 8)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 8;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        if(!empty($request->file('uploadTestimonialsOther')))
                        {
                            $originalFile= $request->file('uploadTestimonialsOther');
                            $fileName = date('dmhis').'Other.'.$originalFile->extension();  
                            $originalFile->move(public_path('admin/images/empDocs/'.$empCode."/"), $fileName);
                            
                            UploadEmpDoc::where('empId', $employee->id)->where('type', 13)->update(['active'=>'0']);

                            $uploadDoc = new UploadEmpDoc;
                            $uploadDoc->empId = $employee->id;
                            $uploadDoc->empCode = $empCode;
                            $uploadDoc->fileName = $fileName;
                            $uploadDoc->type = 13;
                            $uploadDoc->updated_by = Auth::user()->username;
                            $uploadDoc->save();
                        }

                        DB::commit();
                        $sms = "New ".$employee->username." Employee Added successfully..";
                        return redirect('/employees')->with("success",$sms);
                    }
                }
            } 
            catch (\Exception $e) 
            {
                DB::rollback();
                return redirect()->back()->withInput()->with("error","something went wrong : ".$e->getMessage());
            }
        
        }
    }
    public function removeUploadDocs($id)
    {
        UploadEmpDoc::where('id', $id)->update(['active'=>0]);  
        return redirect()->back()->with("success","Document Removed Successfully...");
    }

    public function uploadEmpExcel()
    {
        $branches = ContactusLandPage::Where('active', 1)->pluck('branchName', 'id');
        return view('admin.employees.uploadExcel')->with(['branches'=>$branches]);
    }

    public function uploadExcel(Request $request)
    {
       //3524
       Excel::import(new UsersImport, $request->file('excelFile'));
        $temps = TempAssetProduct::all();
         
        foreach($temps as $tps)
        {
            $emp = EmpDet::where('empCode', $tps->code)->first();
            if($emp)
            {
                $emp->oldSalary=$tps->oldsalary;
                $emp->salaryScale=$tps->newsalary;
                if($emp->save())
                {
                    $empMr = EmpMr::where('empId', $emp->id)->first();
                    if($empMr)
                    {
                        $empMr->grossSalary=$tps->newsalary;
                        $empMr->save();
                    }
                }
            }
            else
            {
                echo $tps->code.'----------';
            }
            
        }

        return 'ddd';

     //   return $employee = EmpDet::where('branchId', 11)->where('active', 1)->pluck('empCode');
      //   TempEmpDet::truncate();
      //  ManualAttendance::truncate();
       // AttendanceLog::truncate();

          
        //  $temps = Appraisal::where('hikeRs', '!=', 0)->get();
        //  foreach($temps as $temp)
        //  {
        //     $appraisals = Appraisal::find($temp->id);
        //     $appraisals->percentage = ($appraisals->finalRs - $appraisals->oldSalary) / $appraisals->oldSalary * 100;
        //     $appraisals->save();
        //  }

        //  return 'asdf';
            
            // // $working=$tp = 0;
            // // $attend = [];
            // // $month = date('M', strtotime($request->month));
            // // $year = date('Y', strtotime($request->month));
            
            // foreach($temps as $temp)
            // {
            //     $employee = EmpDet::where('empCode', $temp->code)->first();
            //     if($employee)
            //     {
            //         $designation = Designation::where('name', $temp->designation)->where('active', 1)->first();
            //         $employee->name = $temp->name;
            //         $employee->branchId = $temp->branch;

            //         if($designation)
            //         {
            //             $employee->designationId = $designation->id;
            //             $employee->departmentId = $designation->departmentId;
            //         }
            //         $id = EmpDet::where('empCode', $temp->repempcode)->value('id');
            //         if($id)
            //         {
            //             $employee->reportingId = $id;
            //             $employee->reportingType = 1;
            //         }
            //         else
            //         {
            //             if($id == 1)
            //             {
            //                 $employee->reportingId = $id;
            //                 $employee->reportingType = 2;
            //             }                 
            //         }

            //         $employee->startTime = date('H:i', strtotime($temp->intime));
            //         $employee->endTime = date('H:i', strtotime($temp->outtime));                           
            //         $employee->save();
            //     }
            // }

            return 'asd';

            //     // $category = StoreCategory::where('name', $temp->category)->first();
            //     // if(!$category)
            //     // {
            //     //     $category=new StoreCategory;
            //     //     $category->name=$temp->category; 
            //     //     $category->save(); 
            //     // } 

            //     // $subCategory = StoreSubCategory::where('name', $temp->subcategory)->first();
            //     // if(!$subCategory)
            //     // {
            //     //     $subCategory=new StoreSubCategory;
            //     //     $subCategory->categoryId=StoreCategory::where('name', $temp->category)->value('id');
            //     //     $subCategory->name=$temp->subcategory; 
            //     //     $subCategory->save(); 
            //     // } 
            //     // $categoryId = StoreCategory::where('name', $temp->category)->value('id');
            //     // $subCategoryId = StoreSubCategory::where('categoryId', $categoryId)->where('name', $temp->subcategory)->value('id');

            //     // $product = StoreProduct::where('categoryId', $categoryId)->where('subCategoryId', $subCategoryId)->where('name', $temp->name)->first();
            //     // if(!$product)
            //     // {
            //     //     $product = new StoreProduct;
            //     //     $product->categoryId = $categoryId;
            //     //     $product->subCategoryId = $subCategoryId;
            //     //     $product->name = $temp->name;          
            //     //     $product->updated_by='Super Admin';
            //     //     $product->save();
            //     // }
            // }

            return 'asdf';

            // $temps = TempEmpDet::all();
            // foreach($temps as $temp)
            // {
            //     if($temp->newNo != '')
            //     {
            //         $validUser = EmpDet::select('id')
            //         ->where('empCode', $temp->newNo)
            //         ->first();
            //         if($validUser)
            //         {
            //             $userInTime = TempEmpDet::where('id', $temp->id)->orderBy('id','asc')->first();
            //             $userOutTime = TempEmpDet::where('id', '>', $userInTime->id)->orderBy('id','asc')->first();
            //             $userTotTime = TempEmpDet::where('id', '>', $userOutTime->id)->orderBy('id','asc')->first();
            //             $userDay = TempEmpDet::where('id', '<', $userInTime->id)->orderBy('id','desc')->first();
            
            //             for($i=1; $i<=31; $i++)
            //             {
            //                 $attLog = new ManualAttendance;
            //                 $attLog->empCode = $temp->newNo;

            //                 $agf=0;
            //                 if($i >= 1 && $i <= 9)
            //                 {
            //                     $date = 't'.$i;
            //                     $forDate = date('Y-m-d', strtotime($year.'-'.$month.'-0'.$i));
            //                 }
            //                 else
            //                 {
            //                     $date = 't'.$i;
            //                     $forDate = date('Y-m-d', strtotime($year.'-'.$month.'-'.$i));
            //                 }

            //                 $day = 't'.$i;

            //                 $attLog->dayStatus = $userDay->$day;
            //                 $attLog->inTime = $userDay->$day;
            //                 $attLog->outTime = $userDay->$day;
                        
            //                 if($userInTime->$day == '')
            //                     $attLog->inTime = 0;
            //                 else
            //                 {
            //                     $attLog->inTime = $forDate.' '.$userInTime->$day;

            //                     $attLog1 = AttendanceLog::where('employeeCode', $attLog->empCode)
            //                     ->where('logDateTime', date('Y-m-d H:i:s', strtotime($attLog->inTime)))
            //                     ->first();
            //                     if(!$attLog1)
            //                         $attLog1 = new AttendanceLog;

            //                     $attLog1->employeeCode=$attLog->empCode;
            //                     $attLog1->logDateTime=date('Y-m-d H:i:s', strtotime($attLog->inTime));
            //                     $attLog1->logDate=date('Y-m-d', strtotime($attLog->inTime));
            //                     $attLog1->logTime=date('H:i:s', strtotime($attLog->inTime));
            //                     $attLog1->save();
            //                 }

            //                 if($userOutTime->$day == '')
            //                     $attLog->outTime=0;
            //                 else
            //                 {
            //                     $attLog->outTime=$forDate.' '.$userOutTime->$day;
            //                     $attLog2 = AttendanceLog::where('employeeCode', $attLog->empCode)
            //                     ->where('logDateTime', date('Y-m-d H:i:s', strtotime($attLog->outTime)))
            //                     ->first();
            //                     if(!$attLog2)
            //                         $attLog2 = new AttendanceLog;

            //                     $attLog2->employeeCode=$attLog->empCode;
            //                     $attLog2->logDateTime=date('Y-m-d H:i:s', strtotime($attLog->outTime));
            //                     $attLog2->logDate=date('Y-m-d', strtotime($attLog->outTime));
            //                     $attLog2->logTime=date('H:i:s', strtotime($attLog->outTime));
            //                     $attLog2->save();
            //                 }

            //                 if($userTotTime->$day == "00:00")
            //                     $attLog->workingHr = 0;
            //                 else
            //                     $attLog->workingHr = $userTotTime->$day;

            //                 if($i == '31')
            //                 {
            //                     $attLog->present = $userDay->present;
            //                     $attLog->absent = $userDay->absent;
            //                     $attLog->total = $userDay->total;
            //                 }

            //                 $attLog->save();
            //             }
            //         }
            //     }
            // }

        return back()->with('success', 'Excel Data Imported Successfully.');
    }

    public function getRequiredDocuments($departmentId, $designationId) 
    {
        return RequiredDocument::where('designationId', $designationId)
        ->where('departmentId', $departmentId)
        ->where('active', 1)
        ->get();
    }

    public function storeEmpTempData(Request $data)
    {
        $tempData = TempEmpDet::all();
        foreach($tempData as $data)
        {
            $empCode = str_replace('AWS', '', $data->empcode);
            $found = EmpDet::where('empCode', $empCode)->count();
            if($found == 0)
            {
                $employee = new EmpDet();
                $employee->empCode = $empCode;
                $employee->name = $data->name;
                
                $employee->phoneNo = $data->phoneno;
                $employee->DOB = date('Y-m-d', strtotime($data->birthday));
                $employee->gender = $data->gender;
                $employee->cast = $data->cast;
                $employee->type = $data->type;

                $employee->jobJoingDate = ($data->jobjoingdate == '')?'':date('Y-m-d', strtotime($data->jobjoingdate));
                $employee->startTime = '09:00 AM';
                $employee->endTime = '05:00 PM';
                $employee->maritalStatus = $data->maritalstatus;

                $employee->dateOfRetirement = $data->dateofleaving;
                $employee->salaryScale = $data->salary;
                $employee->PANNo = $data->pancardno;

                $employee->email = $data->email;
                $employee->bankName = $data->bankname;
                $employee->bankAccountNo = $data->accountno;
                $employee->bankIFSCCode = $data->ifsccode;

                $employee->qualification = $data->qualification;
                $employee->AADHARNo = $data->aadharno;
                $employee->teachingSubject = $data->teachingsubject;
                $employee->feesConcession = 1;

               

                $employee->permanentPINCode = $data->perpincode;

                $employee->workingStatus = 1;
            
                $employee->username = 'AWS'.$employee->empCode;
                $employee->userRoleId  = 5;
                $employee->updated_by = Auth::user()->username;
                if($employee->save())
                {
                    $user = new User();
                    $user->name = $employee->name;
                    $user->username = $employee->username;
                    $user->email = $employee->email;
                    $user->password = Hash::make('welcome');
                    $user->empId = $employee->id;
                    $user->userRoleId =  5;
                    $user->userType = '31';
                    $user->updated_by = Auth::user()->username;
                    $user->save();
                }
            }
               
        }
        return redirect('/employees')->with("success","Employee Added successfully..");
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $active = $request->active;
        $section = $request->section;
        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.phoneNo', 'emp_dets.email','emp_dets.profilePhoto',
        'emp_dets.firmType','emp_dets.name','emp_dets.username','emp_dets.empCode','emp_dets.gender',
        'contactus_land_pages.branchName','departments.name as departmentName',
        'designations.name as designationName')
        ->where('emp_dets.active', $active)
        ->where('departments.section', $section)
        ->where(function($query) use ($search)
        {
            $query->Where('emp_dets.name', 'LIKE', '%' . $search . '%')   
            ->orWhere('designations.name', 'LIKE', '%' . $search . '%')      
            ->orWhere('contactus_land_pages.branchName', 'LIKE', '%' . $search . '%')          
            ->orWhere('departments.name', 'LIKE', '%' . $search . '%')          
            ->orWhere('emp_dets.empCode', 'LIKE', '%' . $search . '%')
            ->orWhere('emp_dets.username', 'LIKE', '%' . $search . '%')
            ->orWhere('emp_dets.phoneNo', 'LIKE', '%' . $search . '%');
        })->orderBy('emp_dets.empCode')
        ->get();

        if($active==0)
        {
            $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->select('emp_dets.id','emp_dets.phoneNo', 'emp_dets.email',
            'emp_dets.firmType','emp_dets.name','emp_dets.username','emp_dets.empCode',
            'departments.name as departmentName')
            ->where('emp_dets.active', $active)
            ->where('departments.section', $section)
            ->where(function($query) use ($search)
            {
                $query->Where('emp_dets.name', 'LIKE', '%' . $search . '%')   
                ->orWhere('departments.name', 'LIKE', '%' . $search . '%')          
                ->orWhere('emp_dets.empCode', 'LIKE', '%' . $search . '%')
                ->orWhere('emp_dets.username', 'LIKE', '%' . $search . '%')
                ->orWhere('emp_dets.phoneNo', 'LIKE', '%' . $search . '%');
            })->orderBy('emp_dets.empCode')->get();

            if($section=="Teaching")
                return view('admin.employees.deactiveTeachingList')->with(['employees'=>$employees, 'search'=>$search])->withInput($request->all());
            else    
                return view('admin.employees.deactiveNonTeachingList')->with(['employees'=>$employees, 'search'=>$search])->withInput($request->all());

        }
        elseif($active==1)
        {
            if($section=="Teaching")
                return view('admin.employees.activeTeachingList')->with(['employees'=>$employees, 'search'=>$search])->withInput($request->all());
            else    
                return view('admin.employees.activeNonTeachingList')->with(['employees'=>$employees, 'search'=>$search])->withInput($request->all());

        }
        else
        {
            if($section=="Teaching")
                return view('admin.employees.inActiveTeachingList')->with(['employees'=>$employees, 'search'=>$search])->withInput($request->all());
            else    
                return view('admin.employees.inActiveNonTeaching')->with(['employees'=>$employees, 'search'=>$search])->withInput($request->all());

        }         
    }

    public function activate(Request $request)
    {
        $id=$request->id;
        $reason=$request->reason;
        $section=$request->section;
        EmpDet::where('id', $id)->update(['reason'=>$reason, 'active'=>1, 'updated_by'=>Auth::user()->username]);
        User::where('empId', $id)->whereIn('userType', [11,21,31])->update(['active'=>1, 'updated_by'=>Auth::user()->username]);

        if($section == "Teaching")
            return redirect('/employees/teachingEmps')->with("success","Employee Activated successfully..");
        else
            return redirect('/employees/nonTeachingEmps')->with("success","Employee Activated successfully..");
    }

    public function deactivate(Request $request)
    {
        $id=$request->id;
        $reason=$request->reason;
        $section=$request->section;
        $lastDay=$request->lastDay;

        $empDet = EmpDet::find($id);
        if($empDet)
        {
            $empDet->reason = $reason;
            $empDet->lastDate = $lastDay;
            
            $empDet->updated_by = Auth::user()->username;
            if($empDet->save())
            {
                $user = User::where('empId', $id)->first();
                if($user)
                {
                    $user->active=0;
                    $user->updated_by = Auth::user()->username;
                    $user->save();
                    
                }
            }
            return redirect()->back()->withInput()->with("success","Employee In-Activate successfully..");
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Employee not found....");
        }     
    }

    public function resetPassword($id)
    {
        $empDet = EmpDet::find($id);
        if($empDet)
        {
            $user = User::where('empId', $empDet->id)->first();
            $user->newUser = 1;
            $user->password = Hash::make('Welcome@1');
            $user->updated_by= Auth::user()->username;
            if($user->save())
            {
                $resetHistory = new ResetPasswordHistory;
                $resetHistory->empId = $empDet->id;
                $resetHistory->newPassword = 'Welcome@1';
                $resetHistory->updated_by = Auth::user()->username;
                if($resetHistory->save())
                {
                    return redirect('/employees')->with("success", $empDet->name." Password reset successfully..");        
                }
            }
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Employee not found....");
        }
    }

    public function getReportings($id)
    {
        $temp1 = EmpDet::whereActive(1)->whereIn('userRoleId', [11, 3])->orderBy('name')->get(['name', 'id']);
        $temp2 = User::whereActive(1)->whereIn('userType', [00, 301, 201, 401, 501, 601])->orderBy('name')->get(['name', 'id']);
    }   

    public function deactiveNonTeachingEmps()
    {
        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.id','emp_dets.firmType','emp_dets.phoneNo', 'emp_dets.reason',
        'emp_dets.name','emp_dets.username','emp_dets.empCode', 'departments.name as departmentName')
        ->where('departments.section', 'Non Teaching')
        ->where('emp_dets.active', 0)
        ->orderBy('emp_dets.empCode')
        ->get();
              
        return view('admin.employees.masters.deactiveNonTeachingList')->with(['employees'=>$employees]);
    }
  
    public function inActiveNonTeachingEmps()
    {
        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.firmType','emp_dets.phoneNo', 'emp_dets.email','emp_dets.profilePhoto',
        'emp_dets.name','emp_dets.username','emp_dets.salaryScale','emp_dets.empCode','emp_dets.gender','contactus_land_pages.branchName',
        'departments.name as departmentName','designations.name as designationName')
        ->where('departments.section', 'non teaching')
        ->where('emp_dets.active', 0)
        ->orderBy('emp_dets.empCode')
        ->get();
        return view('admin.employees.inActiveNonTeaching')->with(['employees'=>$employees]);
    }

     public function nonTeachingEmps()
    {
        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.*','contactus_land_pages.branchName',
        'departments.name as departmentName','designations.name as designationName')
        ->where('departments.section', 'Non Teaching')
        ->where('emp_dets.active', 1)
        ->where('emp_dets.lastDate', null)
        ->orderBy('emp_dets.empCode')
        ->get();       
        return view('admin.employees.masters.activeNonTeachingList')->with(['employees'=>$employees]);
    }

    public function leftEmployeeList()
    {
        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.*','contactus_land_pages.branchName',
        'departments.name as departmentName','designations.name as designationName')
        ->where('emp_dets.active', 1)
        ->where('emp_dets.lastDate', '!=', null)
        ->orderBy('emp_dets.empCode')
        ->get();       
        return view('admin.employees.masters.leftEmployees')->with(['employees'=>$employees]);
    }

    public function teachingEmps()
    {
        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.*','contactus_land_pages.branchName',
        'departments.name as departmentName','designations.name as designationName')
        ->where('departments.section', 'Teaching')
        ->where('emp_dets.lastDate', null)
        ->where('emp_dets.active', 1)
        ->orderBy('emp_dets.empCode')
        ->get();
      
        return view('admin.employees.masters.activeTeachingList')->with(['employees'=>$employees]);   
    }

    public function deactiveTeachingEmps()
    {
        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.id','emp_dets.firmType','emp_dets.phoneNo','departments.name as departmentName','emp_dets.reason',
        'emp_dets.name','emp_dets.username','emp_dets.empCode')
        ->where('departments.section', 'Teaching')
        ->where('emp_dets.active', 0)
        ->orderBy('emp_dets.empCode')
        ->get();
              
        return view('admin.employees.masters.deactiveTeachingList')->with(['employees'=>$employees]);
    }

    public function inActiveTeachingEmps()
    {
        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.id','emp_dets.firmType','emp_dets.phoneNo', 
        'emp_dets.name','emp_dets.username','emp_dets.salaryScale','emp_dets.empCode','contactus_land_pages.branchName',
        'departments.name as departmentName','designations.name as designationName')
        ->where('departments.section', 'Teaching')
        ->where('emp_dets.active', 0)
        ->orderBy('emp_dets.empCode')
        ->get();
      
        return view('admin.employees.masters.inActiveTeachingList')->with(['employees'=>$employees]);   
    }

    public function bdayWish($id)
    {
        $bday = new BdayWish;
        $bday->empId = Auth::user()->empId;
        $bday->toEmpId = $id;
        $bday->forDate = date('Y-m-d');
        $bday->updated_by = Auth::user()->username;
        $bday->active = 1;
        $bday->save();
        return redirect('/home')->with("success","Thanks For Wishes");
    }

    public function profileInfo($empId)
    {
        $employee = EmpDet::find($empId);
        $states = Region::whereActive(1)->pluck('name', 'id');
        $cities = City::whereActive(1)->pluck('name', 'id');
        $empFamilyDet = EmpFamilyDet::where('empId', $empId)->get();
        return view('admin.employees.profileUpdate')->with(['empFamilyDet'=>$empFamilyDet,'states'=>$states,'cities'=>$cities,'employee'=>$employee]);
    }
    
    public function updateProfileInfo(Request $request)
    {
        $employee = EmpDet::find($request->empId);
        $employee->idCardStatus = $request->idCardStatus;
        $employee->updated_by = Auth::user()->username;
        $employee->save();
        return redirect('/home')->with("success","Profile Updated successfully..");
    }

    public function getValidCode($empId, $empCode)
    {
        return $empDet = EmpDet::select('name', 'id')->where('empCode', $empCode)->first();
    }

    public function profileRequestList()
    {
        $employees = UpdateProfileInformation::join('emp_dets', 'update_profile_information.empId', 'emp_dets.id')
        ->select('update_profile_information.id', 'emp_dets.name', 'emp_dets.empCode')
        ->where('update_profile_information.active', 1)
        ->get();

        return view('admin.employees.requestList')->with(['employees'=>$employees]);
    }

    public function viewProfile($id)
    {
        $employee = UpdateProfileInformation::join('emp_dets', 'update_profile_information.empId', 'emp_dets.id')
        ->select('update_profile_information.*', 'emp_dets.name', 'emp_dets.empCode')
        ->where('update_profile_information.id', $id)
        ->first();
        $states = Region::whereActive(1)->pluck('name', 'id');
        $cities = City::whereActive(1)->pluck('name', 'id');
        $empFamilyDet = EmpFamilyDet::where('empId', $id)->where('active', 0)->get();

        return view('admin.employees.viewProfile')->with(['states'=>$states, 'cities'=>$cities, 
        'empFamilyDet'=>$empFamilyDet, 'employee'=>$employee]);
    }

    public function changeTimeList(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        if($startDate == '' && $endDate == '')
        {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        }

        $changeTimes = EmpChangeTime::join('emp_dets', 'emp_change_times.empId', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.name', 'designations.name as designationName', 'contactus_land_pages.branchName', 
        'departments.name as departmentName','emp_change_times.*')
        ->whereDate('emp_change_times.startDate', '>=', $startDate)
        ->whereDate('emp_change_times.startDate', '<=', $endDate)
        ->orderBy('emp_change_times.status')
        ->orderBy('emp_change_times.updated_at', 'desc')
        ->get();

        return view('admin.employees.changeTimeList', compact('startDate', 'endDate','changeTimes'));
    }

    public function changeTime(Request $request)
    {   
        $employeeCode= $request->employeeCode;
        $branchId= $request->branchId;
        $departmentId= $request->departmentId;
        $designationId= $request->designationId;

        $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
        $departments = Department::whereActive(1)->orderBy('name')->pluck('name', 'id');
        $designations = Designation::whereActive(1)->orderBy('name')->pluck('name', 'id');
        
        if($employeeCode != '' || $branchId != '' || $departmentId != '' || $designationId != '')
        {
            $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('emp_dets.empCode','emp_dets.name', 'departments.name as departmentName','contactus_land_pages.branchName',
            'designations.name as designationName', 'emp_dets.jobJoingDate', 'emp_dets.departmentId', 'emp_dets.branchId', 
            'emp_dets.startTime','emp_dets.endTime', 'departments.section', 'emp_dets.designationId');
            
            if(!empty($employeeCode))
                $employees=$employees->where('emp_dets.empCode', $employeeCode);

            if($branchId != NULL)
                $employees=$employees->where('emp_dets.branchId', $branchId);

            if($designationId != NULL)
                $employees=$employees->where('emp_dets.designationId', $designationId);

            if($departmentId != NULL)
                $employees=$employees->where('emp_dets.departmentId', $departmentId);

            $employees = $employees->where('emp_dets.active', 1)->orderBy('emp_dets.empCode')->get();
            return view('admin.employees.changeTime', compact('employeeCode', 'branchId', 'designationId', 'departmentId', 'employees', 'departments', 'designaitons','branches'));

        }
        else
        {
            return view('admin.employees.changeTime')->with(['branches'=>$branches, 'departments'=>$departments, 'designations'=>$designations]);
        }
    }  

    public function updateChangeTime(Request $request)
    {
        $rows = count($request->empCode);
        if($rows != 0)
        {
            DB::beginTransaction();
            try 
            {
                $changeTime = new ChangeTime;
                $changeTime->employeeCode=$request->searchEmployeeCode;
                $changeTime->branchId=$request->searchBranchId;
                $changeTime->departmentId=$request->searchDepartmentId;
                $changeTime->designationId=$request->searchDesignationId;
                $changeTime->masters=$request->masters;
                $changeTime->fromDate=$request->fromDate;
                $changeTime->toDate=$request->toDate;
                $changeTime->fromTime=$request->fromTime;
                $changeTime->toTime=$request->toTime;
                $changeTime->remarks=$request->remarks;
                $changeTime->updated_by = Auth::user()->username;

                if($changeTime->save())
                {
                    for($i=0; $i < $rows; $i++)
                    {
                        $tempEmployee = EmpDet::where('empCode', $request->empCode[$i])->first();
                        if($request->masters == 1)
                        {
                            EmpDet::where('empCode', $request->empCode[$i])->update(['startTime'=>$request->fromTime, 'endTime'=>$request->toTime, 'updated_by'=>Auth::user()->username]);
                        }

                        $empChangeTime = new EmpChangeTime;
                        $empChangeTime->changeTimeId=$changeTime->id;
                        $empChangeTime->empId=$tempEmployee->id;
                        $empChangeTime->empCode=$tempEmployee->empCode;
                        $empChangeTime->startDate=$changeTime->fromDate;
                        $empChangeTime->endDate=$changeTime->toDate;
                        if($request->masters == 2)
                        {
                            $empChangeTime->inTime=date('H:i:s', strtotime($tempEmployee->startTime)); 
                            $empChangeTime->outTime=date('H:i:s', strtotime($tempEmployee->endTime));  
                        }
                        else
                        {
                            $empChangeTime->inTime=date('H:i:s', strtotime($request->fromTime)); 
                            $empChangeTime->outTime=date('H:i:s', strtotime($request->toTime)); 
                        }

                        $empChangeTime->updated_by=Auth::user()->username;
                        $empChangeTime->save();   
                    }

                    DB::commit();
                    return redirect('/employees/changeTimeList')->with("success","Record Updated Successfully, time will change in some time...");
                }
            } 
            catch (\Exception $e) 
            {
                DB::rollBack();
                Log::error('Failed to update employee time change: ' . $e->getMessage());
                return redirect()->back()->withInput()->with("error", "An unexpected error occurred while saving the data. Please try again.");
            }
        }
        else
        {
            return redirect()->back()->withInput()->with("error","No employee found based on your selected criteria."); 
        }
    }

    public function exportempExcel($search, $active, $section)
    {
        $fileName = 'EmpoloyeeList.xlsx';
        return Excel::download(new EmployeesExport($search, $active, $section), $fileName);
    }

    public function UpdateInTime(Request $request)
    {   
        return view('admin.attendance.UpdateInTime');
    }  
    
    public function saveInOutTime(Request $request)
    {
        if($request->fromTime == NULL && $request->toTime == NULL)
        {
            return redirect()->back()->withInput()->with("error","Please add In time or Out time...."); 
        }

        if($employee = EmpDet::where('empCode', $request->employeeCode)->first())
        {
            if($request->fromTime != NULL)
            {
                $logTime1 = new LogTimeOld;
                $logTime1->EmployeeCode=$request->employeeCode;
                $logTime1->LogDateTime=date('Y-m-d H:i:s', strtotime($request->forDate.' '.$request->fromTime));
                $logTime1->DeviceSerialNumber='Manually';
                $logTime1->save();
            }

            if($request->toTime != NULL)
            {
                $logTime2 = new LogTimeOld;
                $logTime2->EmployeeCode=$request->employeeCode;
                $logTime2->LogDateTime=date('Y-m-d H:i:s', strtotime($request->forDate.' '.$request->toTime));
                $logTime2->DeviceSerialNumber='Manually';
                $logTime2->save();
            }

            $changeTime = new ChangeTime;
            $changeTime->empId = $employee->id;  
            $changeTime->shift = $request->shift;
            $changeTime->fromDate = $request->forDate;
            $changeTime->toDate = $request->forDate;
            $changeTime->inTime = date('H:i:s', strtotime($request->fromTime));
            $changeTime->outTime = date('H:i:s', strtotime($request->toTime));
            $changeTime->remark = $request->remark;
            $changeTime->updated_by = Auth::user()->username;
            if($changeTime->save())
            {
                $empChangeTime = new EmpChangeTime; 
                $empChangeTime->empCode = $employee->empCode;  
                $empChangeTime->empId = $employee->id;  
                $empChangeTime->startDate = $request->forDate;
                $empChangeTime->endDate = $request->forDate;
                $empChangeTime->inTime = date('H:i:s', strtotime($request->fromTime));
                $empChangeTime->outTime = date('H:i:s', strtotime($request->toTime));
                $empChangeTime->updated_by = Auth::user()->username;
                $empChangeTime->save();
            } 
            return redirect('/employees/UpdateInTime')->with("success","Record Updated Successfully...");
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Employee not found..."); 
        }
    }

    public function appointmentPerson(Request $request)
    {  
        $MDUsers = User::join('emp_dets', 'users.empId', 'emp_dets.id')->select('emp_dets.empCode', 'emp_dets.name')->where('users.appointStatus', 3)->get();
        $CEOUsers = User::join('emp_dets', 'users.empId', 'emp_dets.id')->select('emp_dets.empCode', 'emp_dets.name')->where('users.appointStatus', 4)->get();
        $COOUsers = User::join('emp_dets', 'users.empId', 'emp_dets.id')->select('emp_dets.empCode', 'emp_dets.name')->where('users.appointStatus', 5)->get();
        return view('admin.employees.appointmentPerson')->with(['MDUsers'=>$MDUsers,'CEOUsers'=>$CEOUsers,'COOUsers'=>$COOUsers]);
    }  

    public function saveAppointmentPerson (Request $request)
    {  
        if($request->AWSMDEmpCode1 != null)
        {
            $empDet = EmpDet::where('empCode', $request->AWSMDEmpCode1)->first();
            if($empDet)
            {
                $user = User::where('empId', $empDet->id)->whereIn('userType', [21,31])->first();
                if($user)
                {
                    $user->appointStatus = 3;
                    $user->save();
                }
            }
        }

        if($request->AWSMDEmpCode1 == 0)
        {
            $user = User::where('appointStatus', 3)
            ->orderBy('id')
            ->first();
            if($user)
            {
                $user->appointStatus = 0;
                $user->save();
            }
        }       

        if($request->AWSMDEmpCode2 != null)
        {
            $empDet = EmpDet::where('empCode', $request->AWSMDEmpCode2)->first();
            if($empDet)
            {
                $user = User::where('empId', $empDet->id)->whereIn('userType', [21,31])->first();
                if($user)
                {
                    $user->appointStatus = 3;
                    $user->save();
                }
            }
        }

        if($request->AWSMDEmpCode2 == 0)
        {
            if(User::where('appointStatus', 3)->count() == 2)
            {
                $user = User::where('appointStatus', 3)
                ->orderBy('id','desc')
                ->first();
                if($user)
                {
                    $user->appointStatus = 0;
                    $user->save();
                }
            }
        }

        if($request->AWSCEOEmpCode1 != null)
        {
            $empDet = EmpDet::where('empCode', $request->AWSCEOEmpCode1)->first();
            if($empDet)
            {
                $user = User::where('empId', $empDet->id)->whereIn('userType', [21,31])->first();
                if($user)
                {
                    $user->appointStatus = 4;
                    $user->save();
                }
            }
        }

        if($request->AWSCEOEmpCode1 == 0)
        {
            $user = User::where('appointStatus', 4)
            ->orderBy('id')
            ->first();
            if($user)
            {
                $user->appointStatus = 0;
                $user->save();
            }
        }

        if($request->AWSCEOEmpCode2 == 0)
        {
            if(User::where('appointStatus', 4)->count() == 2)
            {
                $user = User::where('appointStatus', 4)
                ->orderBy('id','desc')
                ->first();
                if($user)
                {
                    $user->appointStatus = 0;
                    $user->save();
                }
            }
        }

        if($request->AWSCEOEmpCode2 != null)
        {
            $empDet = EmpDet::where('empCode', $request->AWSCEOEmpCode2)->first();
            if($empDet)
            {
                $user = User::where('empId', $empDet->id)->whereIn('userType', [21,31])->first();
                if($user)
                {
                    $user->appointStatus = 4;
                    $user->save();
                }
            }
        }

        if($request->AWSCOOEmpCode1 != null)
        {
            $empDet = EmpDet::where('empCode', $request->AWSCOOEmpCode1)->first();
            if($empDet)
            {
                $user = User::where('empId', $empDet->id)->whereIn('userType', [21,31])->first();
                if($user)
                {
                    $user->appointStatus = 5;
                    $user->save();
                }
            }
        }

        if($request->AWSCOOEmpCode2 != null)
        {
            $empDet = EmpDet::where('empCode', $request->AWSCOOEmpCode2)->first();
            if($empDet)
            {
                $user = User::where('empId', $empDet->id)->whereIn('userType', [21,31])->first();
                if($user)
                {
                    $user->appointStatus = 5;
                    $user->save();
                }
            }
        }

        if($request->AWSCOOEmpCode1 == 0)
        {
            $user = User::where('appointStatus', 5)
            ->orderBy('id')
            ->first();
            if($user)
            {
                $user->appointStatus = 0;
                $user->save();
            }
        }

        if($request->AWSCOOEmpCode2 == 0)
        {
            if(User::where('appointStatus', 5)->count() == 2)
            {
                $user = User::where('appointStatus', 5)
                ->orderBy('id','desc')
                ->first();
                if($user)
                {
                    $user->appointStatus = 0;
                    $user->save();
                }
            }
        }

        return redirect('/employees/appointmentPerson')->with("success","Record Updated Successfully...");
    }  
  
    public function addEmployee($jobId)
    {
        $application = JobApplication::find($jobId);
        $report1 = collect(EmpDet::select('name', 'id', 'empCode')->where('userRoleId', '!=', '5')->orderBy('name')->whereActive(1)->get());
        $report2 = collect(User::select('name', 'id', 'empId as empCode')->whereIn('userType', [201,301,401,501,601])->orderBy('name')->whereActive(1)->get());
    
        $empReportings = $report1->merge($report2);
        $empReportings=$empReportings->sortBy('name');    

        $branches = ContactusLandPage::whereActive(1)->get(['branchName', 'id']);
        $departments = Department::whereActive(1)->pluck('name', 'id');
        $designations = Designation::whereActive(1)->pluck('name', 'id');
        $states = Region::whereActive(1)->pluck('name', 'id');
        $userRoles = UserRole::whereIn('userType', ['11','21','31'])->whereActive(1)->pluck('name','id');
        $empId=0;
        $searchAadharCardNo=1;
        return view('admin.employees.create')->with(['application'=>$application,'userRoles'=>$userRoles, 'departments'=>$departments, 'designations'=>$designations,
        'states'=>$states, 'searchAadharCardNo'=>$searchAadharCardNo,'empReportings'=>$empReportings,'branches'=>$branches]);
    }

    public function newJoinee(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        if($startDate == '' && $endDate == '')
        {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        }
        else
        {
            $startDate = date('Y-m-d', strtotime($request->startDate));
            $endDate = date('Y-m-d', strtotime($request->endDate));
        }

        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->join('designations', 'emp_dets.designationId', 'designations.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select('emp_dets.name', 'emp_dets.jobJoingDate','emp_dets.DOB','emp_dets.email',
        'emp_dets.phoneNo','emp_dets.bankName','emp_dets.bankName','contactus_land_pages.branchName as branch',
        'emp_dets.bankAccountNo','emp_dets.bankIFSCCode','emp_dets.empCode','emp_dets.salaryScale', 'emp_dets.firmType',
        'departments.name as departmentName','designations.name as designationName')
        ->where('emp_dets.active', 1)
        ->where('emp_dets.jobJoingDate', '>=', $startDate)
        ->where('emp_dets.jobJoingDate', '<=', $endDate)
        ->get();

        return view('admin.employees.newJoinee')->with(['employees'=>$employees,'startDate'=>$startDate,'endDate'=>$endDate]);
    }

    public function exportNewJoinee($startDate, $endDate)
    {
        $fileName = 'NewJoineeEmployees.xlsx';
        return Excel::download(new ExportNewJoinee($startDate, $endDate), $fileName);
    }

    public function changAuthority(Request $request)
    {
        $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->pluck('branchName', 'id');
        $from = $request->from; 
        $to = $request->to; 
        $branchId = $request->branchId; 
        if($from == '' || $to == '')
        {
            return view('admin.employees.changeAuthority')->with(['branches'=>$branches]);
        }
        else
        {
            $fromUser = User::where('username', $from)->first();
            if(!$fromUser)
                return redirect()->back()->withInput()->with("error","Reporting Authority From not found...");

            $toUser = User::where('username', $to)->first();
            if(!$toUser)
                return redirect()->back()->withInput()->with("error","Reporting Authority To not found...");

            $employees = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('emp_dets.id','emp_dets.empCode', 'emp_dets.name', 'emp_dets.firmType', 'designations.name as designationName',
            'departments.name as departmentName', 'contactus_land_pages.branchName');

            if($fromUser->empId == '')
            {
                if($branchId == '')
                    $employees = $employees->where('reportingId', $fromUser->id)->get();
                else
                    $employees = $employees->where('branchId', $branchId)->where('reportingId', $fromUser->id)->where('reportingType', 2)->get();

                $reportingType=2;  
                $uId =  $fromUser->id;            
            }
            else
            {
                if($branchId == '')
                    $employees = $employees->where('reportingId', $fromUser->empId)->get();
                else
                    $employees = $employees->where('branchId', $branchId)->where('reportingId', $fromUser->empId)->where('reportingType', 1)->get();

                $reportingType=1;
                $uId =  $fromUser->empId;          
            }

            $fromUser = $fromUser->name;
            $toUser = User::where('username', $to)->value('name');

            return view('admin.employees.changeAuthority')->with(['fromUser'=>$fromUser,'toUser'=>$toUser,'branchId'=>$branchId, 'from'=>$from, 'to'=>$to, 'branches'=>$branches, 'employees'=>$employees]);
        }
    }

    public function updateChangAuthority(Request $request)
    {
        $empList = $request->option1;
        $updateTo = $request->updateTo;

        $toUser = User::where('username', $updateTo)->first();
        if($toUser->empId == '')
            EmpDet::whereIn('id', $empList)->update(['reportingType'=>'2', 'reportingId'=>$toUser->id]);
        else
            EmpDet::whereIn('id', $empList)->update(['reportingType'=>'1', 'reportingId'=>$toUser->empId]);

        $history = new ChangeAuthHistory;
        $history->fromAuth=$request->updateFrom;
        $history->toAuth=$request->updateTo;
        $history->branchId=$request->updateBranchId;
        $history->updated_by=Auth::user()->username;
        $history->save();

        return redirect('/employees/changAuthority')->with("success","Updated Successfully..");

    }

    public function empHistory(Request $request)
    {
        $empCode = $request->empCode;
        if($empCode == '')
            return view('admin.reports.empHistory')->with(['empCode'=>$empCode]);
        else
        {
            $histories = EmpHistory::join('emp_dets', 'emp_histories.empId', 'emp_dets.id')
            ->select('emp_dets.empCode', 'emp_dets.name as empName','emp_histories.*')
            ->where('emp_dets.empCode', $empCode)
            ->paginate(10);
        }

        return view('admin.reports.empHistory')->with(['histories'=>$histories, 'empCode'=>$empCode]);
    }

    public function feesConcession(Request $request)
    {
        $feesConcessions=EmpFeesConcession::join('contactus_land_pages', 'emp_fees_concessions.branchId', 'contactus_land_pages.id')
        ->select('emp_fees_concessions.*', 'contactus_land_pages.branchName')
        ->whereNull('emp_fees_concessions.empId')
        ->orderBy('emp_fees_concessions.id', 'desc')
        ->where('emp_fees_concessions.active', 1)
        ->get();

        return view('admin.otherFeesConcession.list')->with(['feesConcessions'=>$feesConcessions]);
    }

    public function dFeesConcession(Request $request)
    {
        $feesConcessions=EmpFeesConcession::join('contactus_land_pages', 'emp_fees_concessions.branchId', 'contactus_land_pages.id')
        ->select('emp_fees_concessions.*', 'contactus_land_pages.branchName')
        ->whereNull('emp_fees_concessions.empId')
        ->orderBy('emp_fees_concessions.id', 'desc')
        ->where('emp_fees_concessions.active', 0)
        ->get();

        return view('admin.otherFeesConcession.dlist')->with(['feesConcessions'=>$feesConcessions]);
    }

    public function addFeesConcession()
    {
        $branches = ContactusLandPage::whereActive(1)->pluck('shortName','id');
        return view('admin.otherFeesConcession.add')->with(['branches'=>$branches]);
    }

    public function storeFeesConcession(Request $request)
    {
       $feesConcession = EmpFeesConcession::whereNull('empId')
       ->where('branchId',  $request->branchId)
       ->where('classSection',  $request->classSection)
       ->where('studentName',  $request->studentName)
       ->where('acadmicYear', $request->acadmicYear)
       ->first();
        if(!$feesConcession )
            $feesConcession = new EmpFeesConcession;

        $feesConcession->acadmicYear = $request->acadmicYear;
        $feesConcession->studentName = $request->studentName;
        $feesConcession->branchId = $request->branchId;
        $feesConcession->classSection = $request->classSection;
        $feesConcession->category = $request->category;
        $feesConcession->tuitionInst1 = $request->tuitionInst1;
        $feesConcession->tuitionFees1 = $request->tuitionFees1;
        $feesConcession->tuitionInst2 = $request->tuitionInst2;
        $feesConcession->tuitionFees2 = $request->tuitionFees2;
        $feesConcession->tuitionInst3 = $request->tuitionInst3;
        $feesConcession->tuitionFees3 = $request->tuitionFees3;
        $feesConcession->worksheetInst1 = $request->worksheetInst1;
        $feesConcession->worksheetFees1 = $request->worksheetFees1;
        $feesConcession->worksheetInst2 = $request->worksheetInst2;
        $feesConcession->worksheetFees2 = $request->worksheetFees2;
        $feesConcession->worksheetInst3 = $request->worksheetInst3;
        $feesConcession->worksheetFees3 = $request->worksheetFees3;
        $feesConcession->transportInst1 = $request->transportInst1;
        $feesConcession->transportInst2 = $request->transportInst2;
        $feesConcession->transportFees2 = $request->transportFees2;
        $feesConcession->transportInst3 = $request->transportInst3;
        $feesConcession->transportFees3 = $request->transportFees3;
        $feesConcession->gpsInst1 = $request->gpsInst1;
        $feesConcession->gpsCharge1 = $request->gpsCharge1;
        $feesConcession->gpsInst2 = $request->gpsInst2;
        $feesConcession->gpsCharge2 = $request->gpsCharge2;
        $feesConcession->gpsInst3 = $request->gpsInst3;
        $feesConcession->gpsCharge3 = $request->gpsCharge3;
        $feesConcession->type = 1;
        $feesConcession->updated_by = Auth::user()->username;                   
        $feesConcession->save();
        return redirect('/employees/feesConcession')->with("success","Fees Concession Details Updated successfully..");
    }

    public function showFeesConcession($id)
    {
        $feesConcession=EmpFeesConcession::find($id);
        $branches = ContactusLandPage::whereActive(1)->pluck('shortName','id');
        return view('admin.otherFeesConcession.show')->with(['feesConcession'=>$feesConcession,'branches'=>$branches]);
    }

    public function cancelFeesConcession($id)
    {
        $feesConcession = EmpFeesConcession::find($id);
        if($feesConcession->active==0)
            $feesConcession->active=1;
        else
            $feesConcession->active=0;

        $feesConcession->updated_by = Auth::user()->username;          
        $feesConcession->save();      
        
        if($feesConcession->active==0)
            return redirect('/employees/dFeesConcession')->with("success","Fees Concession Cancel successfully..");

        if($feesConcession->active==1)
            return redirect('/employees/feesConcession')->with("success","Fees Concession Cancel successfully..");
        
    }

    public function exportFeesConcession($active)
    {
        return $active;
    }

    public function changeTimeRequestList(Request $request)
    {
        $empId = Auth::user()->empId;
        $uId = Auth::user()->id;
        $userType = Auth::user()->userType;
        if($userType == '51')
            $requestList = ChangeTime::all(); 
        else  
            $requestList = ChangeTime::where('requestRaisedBy', $uId)->where('status', 2)->get();  

        return view('admin.employeeChangeTime.changeTimeRequestList', compact('requestList', 'userType'));
    }

    public function changeTimeRequestListCompleted(Request $request)
    {
        $empId = Auth::user()->empId;
        $uId = Auth::user()->id;
        $userType = Auth::user()->userType;
        if($userType == '51')
            $requestList = ChangeTime::all(); 
        else  
            $requestList = ChangeTime::where('requestRaisedBy', $uId)->where('status', 2)->get();  

        return view('admin.employeeChangeTime.changeTimeRequestList', compact('requestList', 'userType'));
    }

    public function editTimeChangeRequest($id)
    {
        $row = ChangeTime::find($id);

       return $employees = EmpChangeTime::from('emp_change_times as ect')
            ->join('emp_dets as ed', 'ect.empId', '=', 'ed.id')
            ->join('departments as d', 'ed.departmentId', '=', 'd.id')
            ->join('designations as des', 'ed.designationId', '=', 'des.id')
            ->join('contactus_land_pages as br', 'ed.branchId', '=', 'br.id')
            ->select(
                'ect.*',
                'ed.name',
                'ed.empCode',
                'br.branchName',
                'd.name as departmentName',
                'des.name as designationName'
            )
            ->where('ect.changeTimeId', $id)
            ->where('ed.active', 1)
            ->get();

        return view('admin.employeeChangeTime.editTimeRequest', compact('row', 'employees'));
    }

    public function changeTimeRequest(Request $request)
    {
        $uId = Auth::user()->id;
        $empId = Auth::user()->empId;
        $userType = Auth::user()->userType;
        $users = [];

        $designationId = $request->designationId;
        $branchId = $request->branchId;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        if ($empId != '') {
            if ($userType == '11') {
                // Get direct and second-level reports
                $users1 = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                $users2 = EmpDet::whereIn('reportingId', $users1)->where('active', 1)->pluck('id');
                $users = $users1->merge($users2)->unique()->values()->all();
            } elseif ($userType == '21') {
                // Only direct reports
                $users = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id')->all();
            }
        }
        else
        {
            $users = EmpDet::where('reportingId', $uId)->pluck('id')->all();
        }

        // Get unique designation IDs
        $designationIds = EmpDet::where('active', 1)
            ->whereIn('id', $users)
            ->pluck('designationId')
            ->unique()
            ->values()
            ->all();

        // Fetch designations with names
        $designations = Designation::join('departments', 'designations.departmentId', '=', 'departments.id')
        ->whereIn('designations.id', $designationIds)
        ->where('designations.active', 1)
        ->orderBy('departments.name')
        ->orderBy('designations.name')
        ->selectRaw('CONCAT(departments.name, " - ", designations.name) as departmentDesignation, designations.id')
        ->pluck('departmentDesignation', 'designations.id');

            
        $brachIds = EmpDet::where('active', 1)
        ->whereIn('id', $users)
        ->pluck('branchId')
        ->unique()
        ->values()
        ->all();

        // Fetch branch with names
        $branches = ContactusLandPage::whereIn('id', $brachIds)
        ->where('active', 1)
        ->orderBy('branchName')
        ->pluck('branchName', 'id');

        // Get employees
        $employees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select(
                'emp_dets.id','emp_dets.name', 'emp_dets.empCode','emp_dets.startTime','emp_dets.endTime',
                'contactus_land_pages.branchName',
                'departments.name as departmentName','designations.name as designationName'
            )
            ->whereIn('emp_dets.id', $users)
            ->where('emp_dets.active', 1);

            if($branchId != '')
                $employees = $employees->where('emp_dets.branchId', $branchId);
            if($designationId != '')
                $employees = $employees->where('emp_dets.designationId', $designationId);

        $employees = $employees->get();

        return view('admin.employeeChangeTime.changeTimeRequest', compact('startDate', 'endDate', 'branchId', 'designationId','employees', 'designationIds','branches','designations'));
    }

    public function updateChangeTimeRequest(Request $request)
    {
        return $request->all();
        $changeTime = new ChangeTime();
        $changeTime->branchId = $request->branchId;
        $changeTime->departmentId = Designation::where('id', $request->designationId)->first()->departmentId;
        $changeTime->designationId = $request->designationId;
        $changeTime->masters = $request->masters;
        $changeTime->fromDate = $request->startDate;
        $changeTime->toDate = $request->endDate;
        $changeTime->fromTime = $request->startTime;
        $changeTime->toTime = $request->endTime;
        $changeTime->remarks = $request->remarks;
        $changeTime->status = 2;
        $changeTime->requestRaisedBy = $uId;
        $changeTime->updated_by = Auth::user()->username;
        if($changeTime->save())
        {
            $empChangeTime = EmpChangeTime::where('empId', $request->empId)
            ->where('startDate', $request->startDate)
            ->where('endDate', $request->endDate)
            ->first();

            if(!$empChangeTime)
                $empChangeTime = new EmpChangeTime();
          
            $empChangeTime->empId = $request->empId;
            $empChangeTime->empCode = EmpDet::where('id', $request->empId)->value('empCode');
            $empChangeTime->changeTimeId = $changeTime->id;
            $empChangeTime->startDate = $changeTime->fromDate;
            $empChangeTime->endDate = $changeTime->toDate;
            $empChangeTime->inTime = $changeTime->fromTime;
            $empChangeTime->outTime = $changeTime->toTime;
            $empChangeTime->status = 2;
            $empChangeTime->updated_by = Auth::user()->username;
            $empChangeTime->save();
            return redirect('/employees/changeTimeRequestList')->with("success","Time Change Request send to HR Department successfully..");
        }
    }

    public function getDataForAddEmployee()
    {
        $branches = ContactusLandPage::whereActive(1)->get(['branchName', 'id']);
        $departments = Department::whereActive(1)->pluck('name', 'id');
        $designations = Designation::whereActive(1)->pluck('name', 'id');
        $userRoles = UserRole::whereIn('userType', ['11','21','31'])->whereActive(1)->pluck('name','id');
        $buddyNames = EmpDet::where('active', 1)->orderBy('name')->pluck('name','id');
        return view('admin.employees.getDataForAddEmployee', compact('designations', 'departments', 'branches','userRoles','buddyNames'));
    }

    public function storeDataForAddEmployee(Request $request)
    {
        try {
            // Example: Saving some data
            $data = $request->except('_token');

            $employee = new TempEmployee();
            $employee->form_data = $data;
            $employee->save();
    
            // Flash a success message
            return redirect()->route('landingPage.getDataForAddEmployee')->with('status', 'success');
        } catch (\Exception $e) {
            // Flash an error message
            return redirect()->route('landingPage.getDataForAddEmployee')->with('status', 'error');
        }

        
        return redirect('/employees/changeTimeRequestList')->with("success","Information saved and forwarded to HR");
      
    }

    public function profileAddRequestList()
    {
        $tempEmployees = TempEmployee::all();
        return view('admin.employees.ArwEmployeeUpdationRequest', compact('tempEmployees'));
    }

    public function viewTempEmployeeData($id)
    {
        $tempEmployee = TempEmployee::find($id);
        return view('admin.employees.viewRequestDetails', compact('tempEmployee'));
    }

    public function getEmployeeDetails($empCode)
    {   
        return EmpDet::where('empCode', $empCode)->value('name');
    }
}




