<?php

namespace App\Http\Controllers\admin\recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmpJob;
use App\ContactusLandPage;
use App\Department;
use App\Designation;
use App\EmpJobDescription;
use App\JobApplication;
use Auth;

class EmpJobsController extends Controller
{
    public function index()
    {
        $jobs = EmpJob::where('lastDateToApply', '>=', date('Y-m-d'))
        ->orderBy('id', 'desc')
        ->get();
        return view('admin.recruitments.empjobs.list')->with(['jobs'=>$jobs]);
    }

    public function dlist()
    {
        $jobs = EmpJob::where('lastDateToApply', '<', date('Y-m-d'))
        ->orderBy('id', 'desc')
        ->get();
        return view('admin.recruitments.empjobs.dlist')->with(['jobs'=>$jobs]);
    }

    public function create()
    {
        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $departments = Department::whereActive(1)->pluck('name', 'id');
        return view('admin.recruitments.empjobs.create')->with(['branches'=>$branches, 'departments'=>$departments]);
    }

    public function store(Request $request)
    {
        $empJob = new EmpJob();
        $empJob->jobPosition=$request->jobPosition;
        $empJob->departmentId=$request->departmentId;
        $empJob->designationId=$request->designationId;
        $empJob->jobType=$request->jobType;
        $empJob->noOfVacancy=$request->noOfVacancy;
        $empJob->experience=$request->experience;
        $empJob->postedDate=$request->postedDate;
        $empJob->lastDateToApply=$request->lastDateToApply;
        $empJob->skill=$request->skill;
        $empJob->language=$request->language;
        $empJob->gender=$request->gender;
        $empJob->salaryFrom=$request->salaryFrom;
        $empJob->salaryTo=$request->salaryTo;
        $empJob->branchId=$request->branchId;
        $empJob->startTime1=$request->startTime1;
        $empJob->endTime1=$request->endTime1;
        $empJob->slots=$request->slots;
        $empJob->startTime2=$request->startTime2;
        $empJob->endTime2=$request->endTime2;
        $empJob->updated_by=Auth::user()->username;
        if($empJob->save())
        {
            if(isset($request->jobDescription))
            {
                $description=count($request->jobDescription);
                if($description >= 1)
                {
                    for($i=0; $i<$description; $i++)
                    {
                        $empJobDesc = new EmpJobDescription;
                        $empJobDesc->empJobId=$empJob->id;
                        $empJobDesc->description=$request->jobDescription[$i];
                        $empJobDesc->updated_by=Auth::user()->username;
                        $empJobDesc->save();
                    }
                }
            }
            return redirect('/empJobs')->with("success","New Job Vacancy Added Successfully..");
        }
    }

   
    public function show($id)
    {
        $empJob = EmpJob::join('contactus_land_pages', 'emp_jobs.branchId', 'contactus_land_pages.id')
        ->join('designations', 'emp_jobs.designationId', 'designations.id')
        ->select('designations.name as designationName', 'contactus_land_pages.branchName', 'contactus_land_pages.address',
        'emp_jobs.*')
        ->where('emp_jobs.id', $id)
        ->first();
        $jobDesc = EmpJobDescription::where('empJobId', $empJob->id)->whereActive(1)->get();
        if(!$jobDesc)
            $jobDesc=[];

        return view('admin.recruitments.empjobs.show')->with(['empJob'=>$empJob,'jobDesc'=>$jobDesc]);
    }

    public function edit($id)
    {
        $empJob = EmpJob::find($id);
        $jobDescs = EmpJobDescription::where('empJobId', $empJob->id)->get();
        $branches = ContactusLandPage::whereActive(1)->pluck('branchName', 'id');
        $departments = Department::whereActive(1)->pluck('name', 'id');
        $designations = Designation::where('departmentId', $empJob->departmentId)->whereActive(1)->pluck('name', 'id');
       
        return view('admin.recruitments.empjobs.edit')->with(['empJob'=>$empJob,'jobDescs'=>$jobDescs,
        'branches'=>$branches,'departments'=>$departments,'designations'=>$designations]);
    }
    
    public function update(Request $request, $id)
    {
        $empJob = EmpJob::find($id);
        $empJob->jobPosition=$request->jobPosition;
        $empJob->departmentId=$request->departmentId;
        $empJob->designationId=$request->designationId;
        $empJob->jobType=$request->jobType;
        $empJob->noOfVacancy=$request->noOfVacancy;
        $empJob->experience=$request->experience;
        $empJob->postedDate=$request->postedDate;
        $empJob->lastDateToApply=$request->lastDateToApply;
        $empJob->skill=$request->skill;
        $empJob->language=$request->language;
        $empJob->gender=$request->gender;
        $empJob->salaryFrom=$request->salaryFrom;
        $empJob->salaryTo=$request->salaryTo;
        $empJob->branchId=$request->branchId;
        $empJob->education=$request->education;
        $empJob->updated_by=Auth::user()->username;
        if($empJob->save())
        {
            if(isset($request->jobDescription))
            {
                $description=count($request->jobDescription);
                if($description >= 1)
                {
                    EmpJobDescription::where('empJobId', $id)->delete();
                    for($i=0; $i<$description; $i++)
                    {
                        $empJobDesc = new EmpJobDescription;
                        $empJobDesc->empJobId=$empJob->id;
                        $empJobDesc->description=$request->jobDescription[$i];
                        $empJobDesc->updated_by=Auth::user()->username;
                        $empJobDesc->save();
                    }
                }
            }
            return redirect('/empJobs')->with("success","Job Vacancy updated Successfully..");
        }
    }

    public function activate($id)
    {
        EmpJob::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        EmpJobDescription::where('empJob', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        return redirect('/empJobs')->with('success', 'Job Vacancy Activated Successfully!!!');
    }

    public function deactivate($id)
    {
        EmpJob::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        EmpJobDescription::where('empJob', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        return redirect('/empJobs/dlist')->with('success', 'Job Vacancy Deactivated Successfully!!!');
    }

    public function showJobDet($id)
    {
        $empJob = EmpJob::join('contactus_land_pages', 'emp_jobs.branchId', 'contactus_land_pages.id')
        ->join('designations', 'emp_jobs.designationId', 'designations.id')
        ->select('designations.name as designationName', 'contactus_land_pages.branchName', 'contactus_land_pages.address',
        'emp_jobs.*')
        ->where('emp_jobs.id', $id)
        ->first();
        $jobDesc = EmpJobDescription::where('empJobId', $empJob->id)->whereActive(1)->get();
        return view('newJobs.showJob')->with(['empJob'=>$empJob,'jobDesc'=>$jobDesc]);
    }

    public function vacancy()
    {
        $jobs = EmpJob::whereActive(1)->where('postedDate', '<=', date('Y-m-d'))
        ->where('postedDate', '<=', date('Y-m-d'))
        ->where('lastDateToApply', '>=', date('Y-m-d'))
        ->where('active', 1)
        ->get();

        return view('newJobs.vacancy')->with(['jobs'=>$jobs]);
    }

    public function jobApplicationList(Request $request)
    {
        $walkIns = JobApplication::join('departments', 'job_applications.departmentId', 'departments.id')
        ->join('designations', 'job_applications.designationId', 'designations.id')
        ->select('job_applications.*', 'departments.name as departmentName', 'designations.name as designationName')
        ->where('job_applications.appType', 1)
        ->where('job_applications.status', 1)
        ->paginate(10);

        $interDrives = JobApplication::where('appType', 2)->where('status', 1)->paginate(10);
        $recruitements = JobApplication::where('appType', 3)->where('status', 1)->paginate(10);
        return view('admin.recruitments.jobApplications.list')->with(['walkIns'=>$walkIns,'interDrives'=>$interDrives,'recruitements'=>$recruitements]); 
    }

    public function walkinJobApplication()
    {
        return view('admin.recruitments.jobApplications.walkinJobApplication'); 
    }

    public function interviewDriveJobApplication()
    {
        return view('admin.recruitments.jobApplications.interviewDriveJobApplication'); 
    }

    public function recruitementJobApplication()
    {
        return view('admin.recruitments.jobApplications.recruitementJobApplication'); 
    }

    public function storeJobApplication(Request $request)
    {
        $job = JobApplication::where('mobileNo', $request->mobileNo)->first();
        if(!$job)
            $job = new JobApplication;

        $job->section=$request->sectionId;
        $job->departmentId=$request->empDepartmentId;
        $job->designationId=$request->empDesignationId;
        $job->forDate=date('Y-m-d', strtotime($request->forDate));
        $job->firstName=$request->firstName;
        $job->middleName=$request->middleName;
        $job->lastName=$request->lastName;
        $job->mobileNo=$request->mobileNo;
        $job->motherName=$request->motherName;
        $job->fatherName=$request->fatherName;
        $job->maritalStatus=$request->maritalStatus;
        $job->language=$request->language;
        $job->board10Th=$request->board10Th;
        $job->yearPass10Th=$request->yearPass10Th;
        $job->percent10Th=$request->percent10Th;
        $job->board12Th=$request->board12Th;
        $job->yearPass12Th=$request->yearPass12Th;
        $job->percent12Th=$request->percent12Th;
        $job->boardGrad=$request->boardGrad;
        $job->yearPassGrad=$request->yearPassGrad;
        $job->percentGrad=$request->percentGrad;
        $job->boardPostG=$request->boardPostG;
        $job->yearPassPostG=$request->yearPassPostG;
        $job->percentPostG=$request->percentPostG;
        $job->totalWorkExp=$request->totalWorkExp;
        $job->organisation1=$request->organisation1;
        $job->exp1=$request->exp1;
        $job->respon1=$request->respon1;
        $job->reasonLeav1=$request->reasonLeav1;
        $job->organisation2=$request->organisation2;
        $job->exp2=$request->exp2;
        $job->respon2=$request->respon2;
        $job->reasonLeav2=$request->reasonLeav2;
        $job->organisation3=$request->organisation3;
        $job->exp3=$request->exp3;
        $job->respon3=$request->respon3;
        $job->reasonLeav3=$request->reasonLeav3;
        $job->yourStrenghths=$request->yourStrenghths;
        $job->hobbies=$request->hobbies;
        $job->appStatus=$request->appStatus;
        $job->appType=1;
        if($job->save())
        {
            $job = JobApplication::find($job->id);
            $job->refRecr = 'CA'.date('dmy',strtotime($request->forDate)).$job->id;
            $job->save();
        }

        return redirect('/empCif/jobApplicationList')->with("success",$job->refRecr." Application Submited Successfully.");
    }

    public function editJobApplication($id)
    {
        $application = JobApplication::find($id);
        $departments=Department::where('section', $application->section)->whereActive(1)->pluck('name', 'id');
        $designations=Designation::where('departmentId', $application->departmentId)->whereActive(1)->pluck('name', 'id');
        return view('admin.recruitments.jobApplications.editJobApplication')->with(['application'=>$application, 'departments'=>$departments, 'designations'=>$designations]); 
    } 

    public function updateJobApplication(Request $request, $id)
    {
        $job = JobApplication::find($id);
        $job->section=$request->sectionId;
        $job->departmentId=$request->empDepartmentId;
        $job->designationId=$request->empDesignationId;
        $job->forDate=date('Y-m-d', strtotime($request->forDate));
        $job->firstName=$request->firstName;
        $job->middleName=$request->middleName;
        $job->lastName=$request->lastName;
        $job->mobileNo=$request->mobileNo;
        $job->motherName=$request->motherName;
        $job->fatherName=$request->fatherName;
        $job->maritalStatus=$request->maritalStatus;
        $job->language=$request->language;
        $job->board10Th=$request->board10Th;
        $job->yearPass10Th=$request->yearPass10Th;
        $job->percent10Th=$request->percent10Th;
        $job->board12Th=$request->board12Th;
        $job->yearPass12Th=$request->yearPass12Th;
        $job->percent12Th=$request->percent12Th;
        $job->boardGrad=$request->boardGrad;
        $job->yearPassGrad=$request->yearPassGrad;
        $job->percentGrad=$request->percentGrad;
        $job->boardPostG=$request->boardPostG;
        $job->yearPassPostG=$request->yearPassPostG;
        $job->percentPostG=$request->percentPostG;
        $job->totalWorkExp=$request->totalWorkExp;
        $job->organisation1=$request->organisation1;
        $job->exp1=$request->exp1;
        $job->respon1=$request->respon1;
        $job->reasonLeav1=$request->reasonLeav1;
        $job->organisation2=$request->organisation2;
        $job->exp2=$request->exp2;
        $job->respon2=$request->respon2;
        $job->reasonLeav2=$request->reasonLeav2;
        $job->organisation3=$request->organisation3;
        $job->exp3=$request->exp3;
        $job->respon3=$request->respon3;
        $job->reasonLeav3=$request->reasonLeav3;
        $job->yourStrenghths=$request->yourStrenghths;
        $job->hobbies=$request->hobbies;
        $job->appStatus=$request->appStatus;
        $job->appType=$request->appType;
        if($job->save())
        {
            $job = JobApplication::find($job->id);
            $job->refRecr = 'CA'.date('dmy',strtotime($request->forDate)).$job->id;
            $job->save();
        }

        return redirect('/empCif/jobApplicationList')->with("success","Application save Successfully.");
    }

    public function showJobApplication($id)
    {
        return view('admin.recruitments.jobApplications.show');   
    }
}
