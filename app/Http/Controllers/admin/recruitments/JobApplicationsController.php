<?php

namespace App\Http\Controllers\admin\recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\RecruitmentImport;
use App\ContactusLandPage;
use App\SignatureFile;
use App\JobApplication;
use App\Department;
use App\Designation;
use App\User;
use App\EmpDet;
use App\Interview;
use App\EmpVerification;
use App\CifApplication;
use App\Cif2Application;
use App\Cif3Application;
use App\EmpFamilyDet;
use App\EmployeeExperience;
use Auth;
use Excel;
use PDF;
use Hash;
use DB;
use Image;

class JobApplicationsController extends Controller
{
    public function list(Request $request)
    { 
        $userType = Auth::user()->userType;
        $userId = Auth::user()->id;
        $empId = Auth::user()->empId;
    
        if($userType == '51')
        {
            $startDate = $request->startDate;
            $endDate = $request->endDate;
            if($request->startDate == '' || $request->endDate == '')
            {
                $startDate = date('Y-m-d', strtotime('-6 days'));
                $endDate = date('Y-m-d');
            }
            else
            {
                $startDate = date('Y-m-d', strtotime($request->startDate));
                $endDate = date('Y-m-d', strtotime($request->endDate));
            }

            $applications = Cif3Application::join('designations', 'cif3_applications.designationId', 'designations.id')
            ->select('designations.name as jobPosition', 'cif3_applications.*')
            ->where('cif3_applications.active', 1)
            ->whereDate('cif3_applications.created_at','>=', $startDate)
            ->whereDate('cif3_applications.created_at','<=', $endDate)
            ->orderByRaw('FIELD(cif3_applications.appStatus,"Pending","Selected","CBC","Rejected")')
            ->get();
            return view('admin.recruitments.jobApplications.list')->with(['startDate'=>$startDate,'endDate'=>$endDate,'applications'=>$applications]); 
        }
        else
        {

            $applications = Interview::join('cif3_applications', 'interviews.candidateId', 'cif3_applications.id')
            ->join('designations', 'cif3_applications.designationId', 'designations.id')
            ->select('designations.name as jobPosition','cif3_applications.*')
            ->where('cif3_applications.active', 1)
            ->where('interviews.assignTo', $userId)
            ->orderByRaw('FIELD(cif3_applications.appStatus, "Pending", "Selected", "CBC", "Rejected")')
            ->get();

            // $applications = Cif3Application::join('designations', 'cif3_applications.designationId', 'designations.id')
            // ->join('users', 'cif3_applications.assignTo', 'users.id')
            // ->select('designations.name as jobPosition','cif3_applications.*')
            // ->where('cif3_applications.active', 1)
            // ->where('assignTo', $userId)
            // ->orderByRaw('FIELD(cif3_applications.appStatus, "Pending", "Selected", "CBC", "Rejected")')
            // ->paginate(100);

            return view('admin.recruitments.jobApplications.list')->with(['applications'=>$applications]); 
        }

    }

    public function show($id)
    {
        $application = Cif3Application::join('designations', 'cif3_applications.designationId', 'designations.id')
        ->select('designations.name as jobPosition','cif3_applications.*')
        ->where('cif3_applications.id', $id)
        ->first();
        
        $interview1 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 1)
        ->where('interviews.active', 1)
        ->first();

        $interview2 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 2)
        ->where('interviews.active', 1)
        ->first();

        $interview3 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 3)
        ->where('interviews.active', 1)
        ->first();

        $interview4 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 4)
        ->where('interviews.active', 1)
        ->first();

        $signFiles = SignatureFile::whereActive(1)->pluck('name','id');
        $departments = Department::where('active', 1)->pluck('name','id');
        $designations = Designation::where('active', 1)->pluck('name','id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'branchName');

        $report1 = collect(EmpDet::select('name', 'id', 'empCode')->where('userRoleId', '!=', '5')->orderBy('name')->whereActive(1)->get());
        $report2 = collect(User::select('name', 'id', 'empId as empCode')->whereIn('userType', [201,301,401,501,601])->orderBy('name')->whereActive(1)->get());
    
        $empReportings = $report1->merge($report2);
        $empReportings=$empReportings->sortBy('name');  


        return view('admin.recruitments.jobApplications.show')->with(['empReportings'=>$empReportings,'application'=>$application,'interview1'=>$interview1,
        'signFiles'=>$signFiles,'interview2'=>$interview2, 'interview3'=>$interview3,'interview4'=>$interview4,'departments'=>$departments, 'designations'=>$designations, 'branches'=>$branches]); 
    }

    public function editForm($id)
    {
        $application = Cif3Application::join('designations', 'cif3_applications.designationId', 'designations.id')
        ->select('designations.name as jobPosition','cif3_applications.*')
        ->where('cif3_applications.id', $id)
        ->first();
        
        $interview1 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 1)
        ->where('interviews.active', 1)
        ->first();

        $interview2 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 2)
        ->where('interviews.active', 1)
        ->first();

        $interview3 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 3)
        ->where('interviews.active', 1)
        ->first();

        $interview4 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 4)
        ->where('interviews.active', 1)
        ->first();

        $signFiles = SignatureFile::whereActive(1)->pluck('name','id');
        $departments = Department::where('active', 1)->pluck('name','id');
        $designations = Designation::where('active', 1)->pluck('name','id');
        $branches = ContactusLandPage::where('active', 1)->orderBy('branchName')->pluck('branchName', 'branchName');

        $report1 = collect(EmpDet::select('name', 'id', 'empCode')->where('userRoleId', '!=', '5')->orderBy('name')->whereActive(1)->get());
        $report2 = collect(User::select('name', 'id', 'empId as empCode')->whereIn('userType', [201,301,401,501,601])->orderBy('name')->whereActive(1)->get());
    
        $empReportings = $report1->merge($report2);
        $empReportings=$empReportings->sortBy('name');  


        return view('admin.recruitments.jobApplications.editForm')->with(['empReportings'=>$empReportings,'application'=>$application,'interview1'=>$interview1,
        'signFiles'=>$signFiles,'interview2'=>$interview2, 'interview3'=>$interview3,'interview4'=>$interview4,'departments'=>$departments, 'designations'=>$designations, 'branches'=>$branches]); 
    }

    public function updateJobApplication(Request $request)
    {
        $job = Cif3Application::find($request->id);
        if(!$job)
        {
            return redirect()->back()->withInput()->with("error","Application Not found...");
        }
        $job->section=$request->section;
        $job->departmentId=Designation::where('id', $request->designationId)->value('departmentId');
        $job->designationId=$request->designationId;
        $job->firstName=$request->firstName;
        $job->middleName=$request->middleName;
        $job->lastName=$request->lastName;
        $job->motherName=$request->motherName;
        $job->adharFirstName=$request->adharFirstName;
        $job->adharMiddleName=$request->adharMiddleName;
        $job->adharLastName=$request->adharLastName;
        $job->DOB=$request->DOB;
        $job->gender=$request->gender;
        $job->religion=$request->religion;
        $job->caste=$request->caste;
        $job->category=$request->category;
        $job->maritalStatus=$request->maritalStatus;
        $job->presentAddress=$request->presentAddress;
        $job->permanentAddress=$request->permanentAddress;
        $job->mobileNo=$request->mobileNo;
        $job->whatsMobileNo=$request->whatsMobileNo;
        $job->email=$request->email;
        $job->facebook=$request->facebook;
        $job->instagram=$request->instagram;
        $job->linkedIn=$request->linkedIn;
        $job->twitter=$request->twitter;
        $job->youTube=$request->youTube;
        $job->googlePlus=$request->googlePlus;
        $job->english=$request->english;
        $job->hindi=$request->hindi;
        $job->marathi=$request->marathi;
        $job->otherLanguage=$request->otherLanguage;
        $job->emergencePersonName=$request->emergencePersonName;
        $job->emergenceRelation=$request->emergenceRelation;
        $job->emergenceMob=$request->emergenceMob; 
        $job->updated_by=$request->firstName.' '.$request->lastName; 
        $job->currentStatus=0; 
        $job->advSource=$request->advSource;
        $job->refName=$request->refName;
        $job->refContactNo=$request->refContactNo;
        $job->degree1=$request->degree1;
        $job->board1=$request->board1;
        $job->passingYear1=$request->passingYear1;
        $job->percent1=$request->percent1;
        $job->degree2=$request->degree2;
        $job->board2=$request->board2;
        $job->passingYear2=$request->passingYear2;
        $job->percent2=$request->percent2;
        $job->degree3=$request->degree3;
        $job->board3=$request->board3;
        $job->passingYear3=$request->passingYear3;
        $job->percent3=$request->percent3;
        $job->degree4=$request->degree4;
        $job->board4=$request->board4;
        $job->passingYear4=$request->passingYear4;
        $job->percent4=$request->percent4;
        $job->degree5=$request->degree5;
        $job->board5=$request->board5;
        $job->passingYear5=$request->passingYear5;
        $job->percent5=$request->percent5;
        $job->degree6=$request->degree6;
        $job->board6=$request->board6;
        $job->passingYear6=$request->passingYear6;
        $job->percent6=$request->percent6;
        $job->overallComputerProficiency=$request->overallComputerProficiency;
        $job->microsoftOffice=$request->microsoftOffice;
        $job->specialEducation=$request->specialEducation;

        $job->workExperienceDetails=$request->workExperienceDetails;
        $job->experience=$request->experience;

        $job->organisation1=$request->organisation1;
        $job->exp1=$request->exp1;
        $job->from1=$request->from1;
        $job->to1=$request->to1;
        $job->post1=$request->post1;
        $job->std1=$request->std1;
        $job->reason1=$request->reason1;

        $job->organisation2=$request->organisation2;
        $job->exp2=$request->exp2;
        $job->from2=$request->from2;
        $job->to2=$request->to2;
        $job->post2=$request->post2;
        $job->std2=$request->std2;
        $job->reason2=$request->reason2;

        $job->organisation3=$request->organisation3;
        $job->exp3=$request->exp3;
        $job->from3=$request->from3;
        $job->to3=$request->to3;
        $job->post3=$request->post3;
        $job->std3=$request->std3;
        $job->reason3=$request->reason3;

        $job->organisation4=$request->organisation4;
        $job->exp4=$request->exp4;
        $job->from4=$request->from4;
        $job->to4=$request->to4;
        $job->post4=$request->post4;
        $job->std4=$request->std4;
        $job->reason4=$request->reason4;


        $job->refOrganization1=$request->refOrganization1;
        $job->refrepoAuth1=$request->refrepoAuth1;
        $job->refRepoAuthPost1=$request->refRepoAuthPost1;
        $job->refContctNo1=$request->refContctNo1;
        $job->refEmail1=$request->refEmail1;

        $job->refOrganization2=$request->refOrganization2;
        $job->refrepoAuth2=$request->refrepoAuth2;
        $job->refRepoAuthPost2=$request->refRepoAuthPost2;
        $job->refContctNo2=$request->refContctNo2;
        $job->refEmail2=$request->refEmail2;
        $job->lastSalary=$request->lastSalary;
        $job->expectedSalary=$request->expectedSalary;
        

        $job->strenghths=$request->strenghths;
        $job->hobbies=$request->hobbies;
        $job->extraCurricular=$request->extraCurricular;
        $job->medicalPrevious=$request->medicalPrevious;
        $job->medicalCurrent=$request->medicalCurrent;
        $job->bloodGp=$request->bloodGp;

        $job->prevAppliedFor=$request->prevAppliedFor;
        $job->appliedForMonth=$request->appliedForMonth;
        $job->appliedForPost=$request->appliedForPost;

        $job->exEmployee=$request->exEmployee;      

        $job->currentStatus=0;
        $job->updated_by=Auth::user()->username; 


        try
        {
            DB::beginTransaction();
          
            if(!empty($request->file('profilePhoto')))
            {
                $fileName = $request->mobileNo.date('Ymdhi').'.'.$request->profilePhoto->extension();  
                $request->profilePhoto->move(public_path('admin/images/recPhotos/'), $fileName);
                $job->profilePhoto = $fileName;
            }

            if(!empty($request->file('resume')))
            {
                $fileName = $request->mobileNo.date('Ymdhi').'.'.$request->resume->extension();  
                $request->resume->move(public_path('admin/candidatesDocuments/'), $fileName);
                $job->resume = $fileName;
            }  

            // return $job;
            $job->save();
            DB::commit();

        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return redirect()->back()->withInput()->with("error","there is Some issue.....");
        }
        
        return redirect()->back()->with("success","Application Form Updated Successfully....");
    }

    public function printList($fromDate, $toDate)
    {
        $applications = Cif3Application::join('designations', 'cif3_applications.designationId', 'designations.id')
        ->select('designations.name as jobPosition','cif3_applications.*')
        ->whereDate('cif3_applications.created_at', '>=', $fromDate)
        ->whereDate('cif3_applications.created_at', '<=', $toDate)
        ->where('cif3_applications.appType', 0)
        ->where('cif3_applications.status', 1)
        ->orderByRaw('FIELD(cif3_applications.appStatus,null,"Selected","CBC","Rejected")')
        ->get();

        $temp = collect($applications);
        $total = $temp->count();
        $selected = $temp->where('appStatus', 'Selected')->count();
        $cBC = $temp->where('appStatus', 'CBC')->count();
        $rejected = $temp->where('appStatus', 'Rejected')->count();
        $pending = $temp->where('appStatus', 'null')->count();

        $jobs = Cif3Application::join('designations', 'cif3_applications.designationId', 'designations.id')
        ->select('designations.name as jobPosition', 'cif3_applications.designationId', 
        DB::raw('count(cif3_applications.id) as count'))
        ->whereDate('cif3_applications.created_at', '>=', $fromDate)
        ->whereDate('cif3_applications.created_at', '<=', $toDate)
        ->where('cif3_applications.appType', 0)
        ->where('cif3_applications.status', 1)
        ->groupBy('designations.name','cif3_applications.designationId')
        ->orderBy('cif3_applications.designationId')
        ->get();
        foreach($jobs as $job)
        {
            $job['pending'] = Cif3Application::whereDate('cif3_applications.created_at', '>=', $fromDate)
            ->whereDate('cif3_applications.created_at', '<=', $toDate)
            ->where('cif3_applications.appType', 0)
            ->where('cif3_applications.status', 1)
            ->where('cif3_applications.appStatus', null)
            ->where('cif3_applications.designationId', $job->designationId)
            ->count(); 

            $job['selected'] = Cif3Application::whereDate('cif3_applications.created_at', '>=', $fromDate)
            ->whereDate('cif3_applications.created_at', '<=', $toDate)
            ->where('cif3_applications.appType', 0)
            ->where('cif3_applications.status', 1)
            ->where('cif3_applications.appStatus', 'Selected')
            ->where('cif3_applications.designationId', $job->designationId)
            ->count(); 

            $job['cBC'] = Cif3Application::whereDate('cif3_applications.created_at', '>=', $fromDate)
            ->whereDate('cif3_applications.created_at', '<=', $toDate)
            ->where('cif3_applications.appType', 0)
            ->where('cif3_applications.status', 1)
            ->where('cif3_applications.appStatus', 'CBC')
            ->where('cif3_applications.designationId', $job->designationId)
            ->count(); 

            $job['rejected'] = Cif3Application::whereDate('cif3_applications.created_at', '>=', $fromDate)
            ->whereDate('cif3_applications.created_at', '<=', $toDate)
            ->where('cif3_applications.appType', 0)
            ->where('cif3_applications.status', 1)
            ->where('cif3_applications.appStatus', 'Rejected')
            ->where('cif3_applications.designationId', $job->designationId)
            ->count(); 
        }

        $references = Cif3Application::select('advSource', DB::raw('count(id) as count'))
        ->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate)
        ->where('appType', 0)
        ->where('status', 1)
        ->groupBy('advSource')
        ->orderBy('advSource')
        ->get();

        $references = collect($references);
        $totalReferences = $references->sum('count');

        $takenBys = Interview::join('cif3_applications', 'interviews.candidateId', 'cif3_applications.id')
        ->join('users', 'interviews.assignTo', 'users.id')
        ->select('users.name', DB::raw('count(interviews.id) as count'))
        ->whereDate('cif3_applications.created_at', '>=', $fromDate)
        ->whereDate('cif3_applications.created_at', '<=', $toDate)
        ->where('cif3_applications.appType', 0)
        ->where('cif3_applications.status', 1)
        ->groupBy('users.name')
        ->orderBy('users.name')
        ->get();

        $takenBys = collect($takenBys);
        $totalTakenBys = $takenBys->sum('count');

        if($applications)
        {
            foreach($applications as $application)
            {
                $application['interview1'] = Interview::join('users', 'interviews.assignTo', 'users.id')
                ->select('interviews.*','users.name')
                ->where('interviews.candidateId', $application->id)
                ->where('interviews.round', 1)
                ->where('interviews.active', 1)
                ->first();

                $application['interview2'] = Interview::join('users', 'interviews.assignTo', 'users.id')
                ->select('interviews.*','users.name')
                ->where('interviews.candidateId', $application->id)
                ->where('interviews.round', 2)
                ->where('interviews.active', 1)
                ->first();

                $application['interview3'] = Interview::join('users', 'interviews.assignTo', 'users.id')
                ->select('interviews.*','users.name')
                ->where('interviews.candidateId', $application->id)
                ->where('interviews.round', 3)
                ->where('interviews.active', 1)
                ->first();

                $application['interview4'] = Interview::join('users', 'interviews.assignTo', 'users.id')
                ->select('interviews.*','users.name')
                ->where('interviews.candidateId', $application->id)
                ->where('interviews.round', 4)
                ->where('interviews.active', 1)
                ->first();
            }
        }

        $interviews1 = Interview::join('cif3_applications', 'interviews.candidateId', 'cif3_applications.id')
        ->join('designations', 'cif3_applications.designationId', 'designations.id')
        ->join('users', 'cif3_applications.assignTo', 'users.id')
        ->select('users.name as updatedBy','designations.name as designationName', 'cif3_applications.email', 'cif3_applications.firstName', 'cif3_applications.middleName'
        , 'cif3_applications.lastName', 'cif3_applications.mobileNo', 'cif3_applications.id as recNo',
        'interviews.*')
        ->whereDate('cif3_applications.created_at', '>=', $fromDate)
        ->whereDate('cif3_applications.created_at', '<=', $toDate)
        ->where('cif3_applications.appType', 0)
        ->where('cif3_applications.status', 1)
        ->where('interviews.round', 1)
        ->orderByRaw('FIELD(cif3_applications.appStatus,"Selected","CBC","Rejected")')
        ->get();

        $interviews2 = Interview::join('cif3_applications', 'interviews.candidateId', 'cif3_applications.id')
        ->join('designations', 'cif3_applications.designationId', 'designations.id')
        ->join('users', 'cif3_applications.assignTo', 'users.id')
        ->select('users.name as updatedBy','designations.name as designationName', 'cif3_applications.email','cif3_applications.firstName', 'cif3_applications.middleName'
        , 'cif3_applications.lastName', 'cif3_applications.mobileNo', 'cif3_applications.id as recNo',
        'interviews.*')
        ->whereDate('cif3_applications.created_at', '>=', $fromDate)
        ->whereDate('cif3_applications.created_at', '<=', $toDate)
        ->where('cif3_applications.appType', 0)
        ->where('cif3_applications.status', 1)
        ->where('interviews.round', 2)
        ->orderByRaw('FIELD(cif3_applications.appStatus,"Selected","CBC","Rejected")')
        ->get();

        $interviews3 = Interview::join('cif3_applications', 'interviews.candidateId', 'cif3_applications.id')
        ->join('designations', 'cif3_applications.designationId', 'designations.id')
        ->join('users', 'cif3_applications.assignTo', 'users.id')
        ->select('users.name as updatedBy','designations.name as designationName', 'cif3_applications.email','cif3_applications.firstName', 'cif3_applications.middleName'
        , 'cif3_applications.lastName', 'cif3_applications.mobileNo', 'cif3_applications.id as recNo',
        'interviews.*')
        ->whereDate('cif3_applications.created_at', '>=', $fromDate)
        ->whereDate('cif3_applications.created_at', '<=', $toDate)
        ->where('cif3_applications.appType', 0)
        ->where('cif3_applications.status', 1)
        ->where('interviews.round', 3)
        ->orderByRaw('FIELD(cif3_applications.appStatus,"Selected","CBC","Rejected")')
        ->get();

        $interviews4 = Interview::join('cif3_applications', 'interviews.candidateId', 'cif3_applications.id')
        ->join('designations', 'cif3_applications.designationId', 'designations.id')
        ->join('users', 'cif3_applications.assignTo', 'users.id')
        ->select('users.name as updatedBy','designations.name as designationName', 'cif3_applications.email','cif3_applications.firstName', 'cif3_applications.middleName'
        , 'cif3_applications.lastName', 'cif3_applications.mobileNo', 'cif3_applications.id as recNo','interviews.*')
        ->whereDate('cif3_applications.created_at', '>=', $fromDate)
        ->whereDate('cif3_applications.created_at', '<=', $toDate)
        ->where('cif3_applications.appType', 0)
        ->where('cif3_applications.status', 1)
        ->where('interviews.round', 4)
        ->orderByRaw('FIELD(cif3_applications.appStatus,"Selected","CBC","Rejected")')
        ->get();

        return view('admin.recruitments.jobApplications.printAppSummary')->with(['applications'=>$applications,
        'fromDate'=>$fromDate,'toDate'=>$toDate, 'total'=>$total,'selected'=>$selected,
        'cBC'=>$cBC,'rejected'=>$rejected, 'pending'=>$pending, 'jobs'=>$jobs,
        'interviews1'=>$interviews1, 'interviews2'=>$interviews2, 'interviews3'=>$interviews3,
        'interviews4'=>$interviews4, 'references'=>$references, 'takenBys'=>$takenBys,
        'totalReferences'=>$totalReferences, 'totalTakenBys'=>$totalTakenBys]);
    }

    public function exportList($fromDate, $toDate)
    {
        $fileName = 'CandidateList '.date('M-Y').'.xlsx';
        return Excel::download(new AGFApplicationsExport($fromDate, $toDate), $fileName);
    }

    public function walkinList()
    {
        $applications = Cif3Application::join('departments', 'cif3_applications.departmentId', 'departments.id')
        ->join('designations', 'cif3_applications.designationId', 'designations.id')
        ->select('cif3_applications.*', 'departments.name as departmentName', 'designations.name as designationName')
        ->where('cif3_applications.appType', 1)
        ->where('cif3_applications.status', 1)
        ->get();

        return view('admin.recruitments.jobApplications.walkins.list')->with(['applications'=>$applications]); 
    }

    public function walkinCreate()
    {
        return view('admin.recruitments.jobApplications.walkins.create');
    }
    
    public function walkinStore(Request $request)
    {
        $application = new JobApplication;
        
        $application->section=$request->sectionId;
        $application->departmentId=$request->empDepartmentId;
        $application->designationId=$request->empDesignationId;
        $application->forDate=$request->forDate;
        $application->firstName=$request->firstName;
        $application->middleName=$request->middleName;
        $application->lastName=$request->lastName;
        $application->mobileNo=$request->mobileNo;
        $application->motherName=$request->motherName;
        $application->fatherName=$request->fatherName;
        $application->maritalStatus=$request->maritalStatus;
        $application->language=$request->language;
        $application->board10Th=$request->board10Th;
        $application->yearPass10Th=$request->yearPass10Th;
        $application->percent10Th=$request->percent10Th;
        $application->board12Th=$request->board12Th;
        $application->yearPass12Th=$request->yearPass12Th;
        $application->percent12Th=$request->percent12Th;
        $application->boardGrad=$request->boardGrad;
        $application->yearPassGrad=$request->yearPassGrad;
        $application->percentGrad=$request->percentGrad;
        $application->boardPostG=$request->boardPostG;
        $application->yearPassPostG=$request->yearPassPostG;
        $application->percentPostG=$request->percentPostG;
        $application->totalWorkExp=$request->totalWorkExp;
        $application->organisation1=$request->organisation1;
        $application->exp1=$request->exp1;
        $application->respon1=$request->respon1;
        $application->reasonLeav1=$request->reasonLeav1;
        $application->organisation2=$request->organisation2;
        $application->exp2=$request->exp2;
        $application->respon2=$request->respon2;
        $application->reasonLeav2=$request->reasonLeav2;
        $application->organisation3=$request->organisation3;
        $application->exp3=$request->exp3;
        $application->respon3=$request->respon3;
        $application->reasonLeav3=$request->reasonLeav3;
        $application->yourStrenghths=$request->yourStrenghths;
        $application->hobbies=$request->hobbies;
        $application->appStatus=$request->appStatus;
        $application->appType=$request->appType;
        $application->updated_by=Auth::user()->username;
        if($application->save())
        {
            $application = Cif3Application::find($application->id);
            $application->refRecr = 'WlK'.date('dmy',strtotime($request->forDate)).$application->id;
            $application->save();
        }

        return redirect('/jobApplications/walkinList')->with("success","Application save Successfully.");
    }

    public function walkinEdit($id)
    {
        $application = Cif3Application::find($id);
        $departments=Department::where('section', $application->section)->whereActive(1)->pluck('name', 'id');
        $designations=Designation::where('departmentId', $application->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.recruitments.jobApplications.walkins.edit')->with(['application'=>$application, 'departments'=>$departments, 'designations'=>$designations]); 
    }

    public function walkinUpdate(Request $request)
    {
        $application = Cif3Application::find($request->id);
        $application->section=$request->sectionId;
        $application->departmentId=$request->empDepartmentId;
        $application->designationId=$request->empDesignationId;
        $application->forDate=$request->forDate;
        $application->firstName=$request->firstName;
        $application->middleName=$request->middleName;
        $application->lastName=$request->lastName;
        $application->mobileNo=$request->mobileNo;
        $application->motherName=$request->motherName;
        $application->fatherName=$request->fatherName;
        $application->maritalStatus=$request->maritalStatus;
        $application->language=$request->language;
        $application->board10Th=$request->board10Th;
        $application->yearPass10Th=$request->yearPass10Th;
        $application->percent10Th=$request->percent10Th;
        $application->board12Th=$request->board12Th;
        $application->yearPass12Th=$request->yearPass12Th;
        $application->percent12Th=$request->percent12Th;
        $application->boardGrad=$request->boardGrad;
        $application->yearPassGrad=$request->yearPassGrad;
        $application->percentGrad=$request->percentGrad;
        $application->boardPostG=$request->boardPostG;
        $application->yearPassPostG=$request->yearPassPostG;
        $application->percentPostG=$request->percentPostG;
        $application->totalWorkExp=$request->totalWorkExp;
        $application->organisation1=$request->organisation1;
        $application->exp1=$request->exp1;
        $application->respon1=$request->respon1;
        $application->reasonLeav1=$request->reasonLeav1;
        $application->organisation2=$request->organisation2;
        $application->exp2=$request->exp2;
        $application->respon2=$request->respon2;
        $application->reasonLeav2=$request->reasonLeav2;
        $application->organisation3=$request->organisation3;
        $application->exp3=$request->exp3;
        $application->respon3=$request->respon3;
        $application->reasonLeav3=$request->reasonLeav3;
        $application->yourStrenghths=$request->yourStrenghths;
        $application->hobbies=$request->hobbies;
        $application->appStatus=$request->appStatus;
        $application->appType=$request->appType;
        $application->updated_by=Auth::user()->username;
        if($application->save())
        {
            $application = Cif3Application::find($application->id);
            $application->refRecr = 'WlK'.date('dmy',strtotime($request->forDate)).$application->id;
            $application->save();
        }

        return redirect('/jobApplications/walkinList')->with("success","Application Update Successfully.");
    }

    public function walkinShow($id)
    {
        $application = Cif3Application::find($id);
        $departments=Department::where('section', $application->section)->whereActive(1)->pluck('name', 'id');
        $designations=Designation::where('departmentId', $application->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.recruitments.jobApplications.walkins.show')->with(['application'=>$application, 'departments'=>$departments, 'designations'=>$designations]); 
    }

    // Interview Drive
    public function interviewDList()
    {
        $applications = Cif3Application::join('departments', 'cif3_applications.departmentId', 'departments.id')
        ->join('designations', 'cif3_applications.designationId', 'designations.id')
        ->select('cif3_applications.*', 'departments.name as departmentName', 'designations.name as designationName')
        ->where('cif3_applications.appType', 2)
        ->get();

        return view('admin.recruitments.jobApplications.interviewDrives.list')->with(['applications'=>$applications]); 
    }

    public function interviewDCreate()
    {
        return view('admin.recruitments.jobApplications.interviewDrives.create');
    }
    
    public function interviewDStore(Request $request)
    {
        $application = new JobApplication;
        $application->section=$request->sectionId;
        $application->departmentId=$request->empDepartmentId;
        $application->designationId=$request->empDesignationId;
        $application->forDate=$request->forDate;
        $application->firstName=$request->firstName;
        $application->middleName=$request->middleName;
        $application->lastName=$request->lastName;
        $application->mobileNo=$request->mobileNo;
        $application->motherName=$request->motherName;
        $application->fatherName=$request->fatherName;
        $application->maritalStatus=$request->maritalStatus;
        $application->language=$request->language;
        $application->board10Th=$request->board10Th;
        $application->yearPass10Th=$request->yearPass10Th;
        $application->percent10Th=$request->percent10Th;
        $application->board12Th=$request->board12Th;
        $application->yearPass12Th=$request->yearPass12Th;
        $application->percent12Th=$request->percent12Th;
        $application->boardGrad=$request->boardGrad;
        $application->yearPassGrad=$request->yearPassGrad;
        $application->percentGrad=$request->percentGrad;
        $application->boardPostG=$request->boardPostG;
        $application->yearPassPostG=$request->yearPassPostG;
        $application->percentPostG=$request->percentPostG;
        $application->totalWorkExp=$request->totalWorkExp;
        $application->organisation1=$request->organisation1;
        $application->exp1=$request->exp1;
        $application->respon1=$request->respon1;
        $application->reasonLeav1=$request->reasonLeav1;
        $application->organisation2=$request->organisation2;
        $application->exp2=$request->exp2;
        $application->respon2=$request->respon2;
        $application->reasonLeav2=$request->reasonLeav2;
        $application->organisation3=$request->organisation3;
        $application->exp3=$request->exp3;
        $application->respon3=$request->respon3;
        $application->reasonLeav3=$request->reasonLeav3;
        $application->yourStrenghths=$request->yourStrenghths;
        $application->hobbies=$request->hobbies;
        $application->startDate=$request->startDate;
        $application->endDate=$request->endDate;
        $application->appStatus=$request->appStatus;
        $application->appType=$request->appType;
        $application->updated_by=Auth::user()->username;
        if($application->save())
        {
            $application = Cif3Application::find($application->id);
            $application->refRecr = 'INDR'.date('dmy',strtotime($request->forDate)).$application->id;
            $application->save();
        }

        return redirect('/jobApplications/interviewDList')->with("success","Application save Successfully.");
    }

    public function interviewDEdit($id)
    {
        $application = Cif3Application::find($id);
        $departments=Department::where('section', $application->section)->whereActive(1)->pluck('name', 'id');
        $designations=Designation::where('departmentId', $application->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.recruitments.jobApplications.interviewDrives.edit')->with(['application'=>$application, 'departments'=>$departments, 'designations'=>$designations]); 
    }

    public function  interviewDUpdate(Request $request)
    {
        $application = Cif3Application::find($request->id);
        $application->section=$request->sectionId;
        $application->departmentId=$request->empDepartmentId;
        $application->designationId=$request->empDesignationId;
        $application->forDate=$request->forDate;
        $application->firstName=$request->firstName;
        $application->middleName=$request->middleName;
        $application->lastName=$request->lastName;
        $application->mobileNo=$request->mobileNo;
        $application->motherName=$request->motherName;
        $application->fatherName=$request->fatherName;
        $application->maritalStatus=$request->maritalStatus;
        $application->language=$request->language;
        $application->board10Th=$request->board10Th;
        $application->yearPass10Th=$request->yearPass10Th;
        $application->percent10Th=$request->percent10Th;
        $application->board12Th=$request->board12Th;
        $application->yearPass12Th=$request->yearPass12Th;
        $application->percent12Th=$request->percent12Th;
        $application->boardGrad=$request->boardGrad;
        $application->yearPassGrad=$request->yearPassGrad;
        $application->percentGrad=$request->percentGrad;
        $application->boardPostG=$request->boardPostG;
        $application->yearPassPostG=$request->yearPassPostG;
        $application->percentPostG=$request->percentPostG;
        $application->totalWorkExp=$request->totalWorkExp;
        $application->organisation1=$request->organisation1;
        $application->exp1=$request->exp1;
        $application->respon1=$request->respon1;
        $application->reasonLeav1=$request->reasonLeav1;
        $application->organisation2=$request->organisation2;
        $application->exp2=$request->exp2;
        $application->respon2=$request->respon2;
        $application->reasonLeav2=$request->reasonLeav2;
        $application->organisation3=$request->organisation3;
        $application->exp3=$request->exp3;
        $application->respon3=$request->respon3;
        $application->reasonLeav3=$request->reasonLeav3;
        $application->yourStrenghths=$request->yourStrenghths;
        $application->hobbies=$request->hobbies;
        $application->appStatus=$request->appStatus;
        $application->appType=$request->appType;
        $application->startDate=$request->startDate;
        $application->endDate=$request->endDate;
        $application->updated_by=Auth::user()->username;
        if($application->save())
        {
            $application = Cif3Application::find($application->id);
            $application->refRecr = 'INDR'.date('dmy',strtotime($request->forDate)).$application->id;
            $application->save();
        }

        return redirect('/jobApplications/interviewDList')->with("success","Application Update Successfully.");
    }

    public function interviewDShow($id)
    {
        $application = Cif3Application::find($id);
        $departments=Department::where('section', $application->section)->whereActive(1)->pluck('name', 'id');
        $designations=Designation::where('departmentId', $application->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.recruitments.jobApplications.interviewDrives.show')->with(['application'=>$application, 'departments'=>$departments, 'designations'=>$designations]); 
    }

    public function uploadOldEntries()
    {
        return view('admin.recruitments.jobApplications.uploadOldEntry');
    }

    public function upload(Request $request)
    {
        Excel::import(new RecruitmentImport, $request->file('excelFile'));
        return redirect('/candidateApplication/uploadOldEntries')->with("success","upload Successfully.");
    }

    public function verificationList()
    {
        $candidates = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.id','emp_dets.name', 'emp_dets.phoneNo', 'emp_dets.jobJoingDate', 'departments.section')
        ->where('emp_dets.verifyStatus', 0)
        ->get();
        
        // $candidates = Cif3Application::join('departments', 'cif3_applications.departmentId', 'departments.id')
        // ->join('designations', 'cif3_applications.designationId', 'designations.id')
        // ->select('departments.name as departmentName', 'designations.name as designationName', 
        // 'departments.section', 'cif3_applications.firstName','cif3_applications.middleName','cif3_applications.lastName',
        // 'cif3_applications.id','cif3_applications.mobileNo','cif3_applications.updated_at')
        // ->where('cif3_applications.round', 4)
        // ->where('cif3_applications.appStatus', 'Selected')
        // ->where('cif3_applications.verifyStatus', 'Pending')
        // ->orderBy('cif3_applications.updated_at')
        // ->get();

        return view('admin.verifications.list')->with(['candidates'=>$candidates]);
    }

    public function verifiedList()
    {
        // $verifieds = EmpVerification::join('cif3_applications', 'emp_verifications.id', 'cif3_applications.id')
        // ->select('cif3_applications.forDate','cif3_applications.firstName','cif3_applications.middleName','cif3_applications.lastName',
        // 'cif3_applications.section','cif3_applications.mobileNo','emp_verifications.status','emp_verifications.jobAppId')
        // ->where('emp_verifications.active', 1)
        // ->get();

        $verifieds = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
        ->select('emp_dets.id','emp_dets.name', 'emp_dets.phoneNo', 'emp_dets.jobJoingDate', 'departments.section','emp_dets.verifyStatus')
        ->where('emp_dets.verifyStatus', 0)
        ->get();

        return view('admin.verifications.verifiedList')->with(['verifieds'=>$verifieds]);
    }
    public function checkUser($username, $password)
    {
        $user = User::where('username', $username)->where('active', 1)->first();
        if($user)
        {
            if(Hash::check($password, $user->password))
                return 1;
            else
                return 2;
        }
        else
        {
            return 2;
        }
    }

    public function showDetails($id, $flag)
    {
        if($flag == 1)
        {
           $employee = EmpDet::find($id);
           $application = Cif3Application::where('firstName', $employee->firstName)
           ->where('lastName', $employee->lastName)
           ->where('DOB', $employee->DOB)
           ->first();
           
            if($application)
            {
                $branches = ContactusLandPage::whereActive(1)->pluck('branchName','id');
                $departments = Department::whereActive(1)->pluck('name', 'id');
                $designations = Designation::whereActive(1)->orderBy('name')->pluck('name', 'id');
                $empVerfication = EmpVerification::where('jobAppId', $id)->first();
                $empFamilyDet =$empExperiences=[];
            }
            else
            {
                $branches = ContactusLandPage::whereActive(1)->pluck('branchName','id');
                $departments = Department::whereActive(1)->pluck('name', 'id');
                $designations = Designation::whereActive(1)->orderBy('name')->pluck('name', 'id');
                $empVerfication = EmpVerification::where('jobAppId', $id)->first();
                $empFamilyDet = EmpFamilyDet::where('empId', $id)->get();
                $empExperiences = EmployeeExperience::where('empId', $id)->get();
            }
            
        }
        else
        {
           $empVerfication = EmpVerification::where('jobAppId', $id)->first();
            if(!$empVerfication)
                return redirect()->back()->withInput()->with("error","Application not found.....");

            $application = Cif3Application::where('id',$empVerfication->jobAppId)->first();
            $employee = EmpDet::find($id);
            $departments = Department::where('section', $application->section)->whereActive(1)->pluck('name', 'id');
            $designations = Designation::where('departmentId', $application->departmentId)->whereActive(1)->pluck('name', 'id');
        }
        return view('admin.verifications.showDetails')->with(['empFamilyDet'=>$empFamilyDet,'empExperiences'=>$empExperiences,'branches'=>$branches,'employee'=>$employee, 'empVerfication'=>$empVerfication, 'flag'=>$flag, 'application'=>$application, 'departments'=>$departments, 'designations'=>$designations]); 
    }

    public function storeVerification(Request $request)
    {
        $empVerfication = EmpVerification::where('jobAppId', $request->id)->first();
        if(!$empVerfication)
            $empVerfication = new EmpVerification;

        $empVerfication->jobAppId = $request->id;
        $empVerfication->verificationStatus5 = $request->verificationStatus5;
        $empVerfication->verificationRemark5 = $request->verificationRemark5;
        $empVerfication->verificationStatus1 = $request->verificationStatus1;
        $empVerfication->verificationRemark1 = $request->verificationRemark1;
        $empVerfication->verificationStatus2 = $request->verificationStatus2;
        $empVerfication->verificationRemark2 = $request->verificationRemark2;
        $empVerfication->verificationStatus3 = $request->verificationStatus3;
        $empVerfication->verificationRemark3 = $request->verificationRemark3;
        $empVerfication->verificationStatus4 = $request->verificationStatus4;
        $empVerfication->verificationRemark4 = $request->verificationRemark4;
        $empVerfication->remarks = $request->remarks;
        
        if(isset($request->reject))
            $empVerfication->status = "Rejected";

        if(isset($request->verified))
            $empVerfication->status = "Verified";

        if(isset($request->hold))
            $empVerfication->status = "CBC";

        $empVerfication->updated_by = Auth::user()->username;
        if($empVerfication->save())
        {
            $application = Cif3Application::find($request->cifId); 
            if($application)
            {
                $application->verifyStatus=$empVerfication->status;
                $application->updated_by=Auth::user()->username;
                $application->save();
            }
            
            $employee = EmpDet::find($request->id);
            $employee->verifyStatus = 1;
            $employee->updated_by=Auth::user()->username;
            $employee->save();

            return redirect('/jobApplications/verificationList')->with("success","Verfication Status Updated Successfully.");
        }
    }

    public function showToAssign($id, $round)
    {
        $application = Cif3Application::join('designations', 'cif3_applications.designationId', 'designations.id')
        ->select('designations.name as jobPosition','cif3_applications.*')
        ->where('cif3_applications.id', $id)
        ->first();

        $interview1 = Interview::where('candidateId', $id)
        ->where('round', $round)
        ->where('active', 1)
        ->first();

        $departments=Department::where('section', $application->section)->whereActive(1)->pluck('name', 'id');
        $designations=Designation::where('departmentId', $application->departmentId)->whereActive(1)->pluck('name', 'id');

        return view('admin.recruitments.jobApplications.assignTo')->with(['interview1'=>$interview1,
        'departments'=>$departments,'designations'=>$designations,'application'=>$application,'round'=>$round]); 
    }

    public function updateAssignTo(Request $request)
    {
        $assignTo = $request->assignTo;
        $roundAssign = $request->roundAssign;
        $jobId = $request->id;
        if($assignTo == 'MD' || $assignTo == 'COO' || $assignTo == 'CEO')
        {
            $assign = User::where('username', 'AWS '.$assignTo)->first();
        }
        else
        {
            $assign = User::where('empId', EmpDet::where('empCode', $assignTo)->value('id'))->first();
        }

        if($assign)
        {
            $application = Cif3Application::find($jobId);
            $application->assignTo = $assign->id;
            $application->round = $roundAssign;
            if($roundAssign == 1)
                $application->round1Status =  1;

            if($roundAssign == 2)
                $application->round2Status =  1;

            if($roundAssign == 3)
                $application->round3Status =  1;

            if($roundAssign == 4)
                $application->round4Status =  1;

            $application->round = $roundAssign;
            $application->updated_by = Auth::user()->username;

            if($application->save())
            {
                $interview = Interview::where('candidateId', $jobId)->where('round', $roundAssign)->where('active', 1)->first();
                if(!$interview)
                    $interview = new Interview;
                
                $interview->candidateId=$jobId;
                $interview->assignTo = $assign->id;
                $interview->round=$roundAssign;
                $interview->appStatus='Pending';
                $interview->updated_by = Auth::user()->username;
                if($interview->save())
                {
                    $assign->forInterviewer = 1;
                    $assign->save();
                    return redirect('/candidateApplication/list')->with("success","Application Assigned Successfully.");
                }
            }
        }
        else
        {
            return redirect()->back()->withInput()->with("error","Please select valid employee");
        }        
    }

    public function updateStatus(Request $request)
    {
        $jobId = $request->id;
        $roundAssign = $request->roundAssign;
        $interview = Interview::where('candidateId', $jobId)
        ->where('round', $roundAssign)
        ->where('active', 1)
        ->first();

        $application  = Cif3Application::where('id', $interview->candidateId)->first();

        if($interview)
        {
            if($roundAssign == 1 || $roundAssign == 3)
            {
                
                if($request->rating1 == 0 || $request->rating22 == 0 || $request->rating333 == 0 || $request->rating4444 == 0 || $request->rating55555 == 0 || $request->rating666666 == 0)
                    return redirect()->back()->withInput()->with("error","Please update Rating....");
            
                $interview->rating1 = $request->rating1;
                $interview->rating2 = $request->rating22;
                $interview->rating3 = $request->rating333;
                $interview->rating4 = $request->rating4444;
                $interview->rating5 = $request->rating55555;
                $interview->rating6 = $request->rating666666;
                $interview->expectedSalary = $request->expectedSalary;
                $interview->offeredSalary = $request->offeredSalary;
                $interview->postOffered = $request->postOffered;
                $interview->remarks = $request->remarks;
                $interview->appStatus = $request->appStatus;
  //            return $interview;
                $interview->save();
            }
    
            if($roundAssign == 2)
            {
                $interview->demoDate = $request->demoDate;
                $interview->branchId = $request->branchId;
                $interview->subject = $request->subject;
                $interview->standard = $request->standard;
                $interview->topic = $request->topic;
                $interview->nameOfObserver = $request->nameOfObserver;
                $interview->remarks = $request->remarks;
                $interview->recomandation = $request->recomandation;
                if(!empty($request->file('uploadFile')))
                {
                    $fileName = $request->mobileNo.'_Report_'.date('Ymdhi').'.'.$request->uploadFile->extension();  
                    $request->uploadFile->move(public_path('admin/candidatesDocuments/'), $fileName);
                    $interview->uploadFile = $fileName;
                }  
     
                $interview->videoLink = $request->videoLink;
                $interview->appStatus = $request->appStatus;
                $interview->save();
     
            }

            if($roundAssign == 4)
            {
                $interview->branchId = $request->branchId;
                $interview->postOffered = $request->postOffered;
                $interview->sectionSelectedFor = $request->sectionSelectedFor;
                $interview->subjectSelectedFor = $request->subjectSelectedFor;
                $interview->dateOfJoining = $request->dateOfJoining;
                $interview->reportingAuthId = $request->reportingAuthId;
                $interview->mentorBuddy = $request->mentorBuddy;
                $interview->timing = $request->timing;
                $interview->salary = $request->finalSalary;
                $interview->hikeComment = $request->hikeComment;
                $interview->remarks = $request->remarks;
                $interview->signs = $request->signs;
                $interview->username = $request->username;
                $interview->appStatus = $request->appStatus;
                $interview->save();
     
            }

            $application = Cif3Application::find($jobId);
            $application->appStatus = $request->appStatus;
            if($roundAssign == 1)
                $application->round1Status =  2;

            if($roundAssign == 2)
                $application->round2Status = 2;

            if($roundAssign == 3)
                $application->round3Status =  2;

            if($roundAssign == 4)
                $application->round4Status =  2;
            
            $application->updated_by = Auth::user()->name;
            $application->save();
            return redirect('/candidateApplication/list')->with("success","Application Update Successfully.");
        }
        else
        {
          return redirect()->back()->withInput()->with("error","Candidate not found...");
        }   
    }

    public function printCIF($id)
    {
        $application = Cif3Application::join('emp_jobs', 'cif3_applications.jobId', 'emp_jobs.id')
        ->select('emp_jobs.jobPosition','cif3_applications.*')
        ->where('cif3_applications.id', $id)
        ->first();

        $interview1 = Interview::where('candidateId', $id)
        ->where('round', 1)
        ->where('active', 1)
        ->first();

        $interview2 = Interview::where('candidateId', $id)
        ->where('round', 2)
        ->where('active', 1)
        ->first();
        
        $file = $application->firstName.'_'.$application->lastName.'.pdf';
        $pdf = PDF::loadView('admin.recruitments.jobApplications.applicationPdfView',compact('application','interview1','interview2'));
        return $pdf->stream($file);  
    }

    public function applicationPrint($id)
    {
        $application = Cif3Application::join('designations', 'cif3_applications.designationId', 'designations.id')
        ->join('departments', 'cif3_applications.departmentId', 'departments.id')
        ->select('departments.name as departmentName', 'designations.name as designationName', 'cif3_applications.*')
        ->where('cif3_applications.id', $id)
        ->first();

        $interview1 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 1)
        ->where('interviews.active', 1)
        ->first();

        if($interview1)
        {
            $interview1['postOffered']=Designation::where('id', $interview1->postOffered)->value('name');
        }

        $interview2 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 2)
        ->where('interviews.active', 1)
        ->first();
      
        if($interview2)
        {
            $interview2['postOffered']=Designation::where('id', $interview2->postOffered)->value('name');
        }

        $interview3 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 3)
        ->where('interviews.active', 1)
        ->first();

        if($interview3)
        {
            $interview3['postOffered']=Designation::where('id', $interview3->postOffered)->value('name');
        }

        $interview4 = Interview::join('users', 'interviews.assignTo', 'users.id')
        ->select('interviews.*','users.name')
        ->where('interviews.candidateId', $id)
        ->where('interviews.round', 4)
        ->where('interviews.active', 1)
        ->first();

        if($interview4)
        {
            $interview4['postOffered']=Designation::where('id', $interview4->postOffered)->value('name');
            $interview4['reportingAuthId']=Designation::where('id', $interview4->reportingAuthId)->value('name');
        }

        // list($width, $height) = getimagesize(public_path('/admin/images/recPhotos/'.$application->profilePhoto));
        // if ($width > $height || $width == $height) {
        //     return 'HORIZONTAL';
        // } elseif ($height > $width) {
        //     return 'VERTICAL';
        // }
        // return $this->ImageFlip(public_path('/admin/images/recPhotos/'.$application->profilePhoto));
       
        //return $this->ImageFlip();
        $file = $application->firstName.'_'.$application->lastName.'.pdf';
        $pdf = PDF::loadView('admin.recruitments.jobApplications.applicationPrint',compact('application','interview1','interview2','interview3','interview4'));
        return $pdf->stream($file);  
    }

    public function ImageFlip ( $imgsrc, $mode )
    {

        $width                        =    imagesx ( $imgsrc );
        $height                       =    imagesy ( $imgsrc );

        $src_x                        =    0;
        $src_y                        =    0;
        $src_width                    =    $width;
        $src_height                   =    $height;

        switch ( $mode )
        {

            case '1': //vertical
                $src_y                =    $height -1;
                $src_height           =    -$height;
            break;

            case '2': //horizontal
                $src_x                =    $width -1;
                $src_width            =    -$width;
            break;

            case '3': //both
                $src_x                =    $width -1;
                $src_y                =    $height -1;
                $src_width            =    -$width;
                $src_height           =    -$height;
            break;

            default:
                return $imgsrc;

        }

        $imgdest                    =    imagecreatetruecolor ( $width, $height );

        if ( imagecopyresampled ( $imgdest, $imgsrc, 0, 0, $src_x, $src_y , $width, $height, $src_width, $src_height ) )
        {
            return $imgdest;
        }

        return $imgsrc;

    }

}
