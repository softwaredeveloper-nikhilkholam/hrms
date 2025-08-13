<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\ContactusLandPage;
use App\SliderLandPage;
use App\BusinessLogoLandPage;
use App\FunFactsLandPage;
use App\VedioLandPage;
use App\TeamLandPage;
use App\SocialMediaLandPage;
use App\JobApplication;
use App\AboutsLandPage;
use App\EmpJob;
use App\EmpJobDescription;
use App\Designation;
use App\Cif2Application;
use App\Cif3Application;
use App\CifApplication;
use Image;
use File;
use DB;

class LandingPagesController extends Controller
{
    public function index()
    {
        $contacts = ContactusLandPage::whereActive(1)->get();
        $vedios = VedioLandPage::whereActive(1)->get();
        $teams = TeamLandPage::whereActive(1)->get();
        $busLogo = BusinessLogoLandPage::whereActive(1)->get();
        $jobs = EmpJob::whereActive(1)->where('postedDate', '<=', date('Y-m-d'))
        ->where('postedDate', '<=', date('Y-m-d'))
        ->where('lastDateToApply', '>=', date('Y-m-d'))
        ->where('active', 1)
        ->take(3)
        ->get();

        $slider = SliderLandPage::whereActive(1)->first();
        $funCounts = FunFactsLandPage::whereActive(1)->first();       
        $media = SocialMediaLandPage::whereActive(1)->first();
        $about = AboutsLandPage::whereActive(1)->first();
        $vediosCt= count($vedios);
        return view('welcome')->with(['jobs'=>$jobs,'about'=>$about,'media'=>$media,'teams'=>$teams,'vediosCt'=>$vediosCt,'vedios'=>$vedios, 'contacts'=>$contacts, 'slider'=>$slider, 'busLogo'=>$busLogo, 'funCounts'=>$funCounts]);
    }

    public function jobs()
    {
        $jobs = EmpJob::whereActive(1)->where('postedDate', '<=', date('Y-m-d'))
        ->where('postedDate', '<=', date('Y-m-d'))
        ->where('lastDateToApply', '>=', date('Y-m-d'))
        ->where('active', 1)
        ->get();
        $media = SocialMediaLandPage::whereActive(1)->first();
        return view('newJobs.jobs')->with(['media'=>$media, 'jobs'=>$jobs]);
    }

    public function showJobDet($id)
    {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $empTep = EmpJob::find($id);
        if($empTep->branchId != '')
        {
            $empJob = EmpJob::join('contactus_land_pages', 'emp_jobs.branchId', 'contactus_land_pages.id')
            ->join('designations', 'emp_jobs.designationId', 'designations.id')
            ->select('designations.name as designationName', 'contactus_land_pages.branchName', 'contactus_land_pages.address',
            'emp_jobs.*')
            ->where('emp_jobs.id', $id)
            ->first();
        }
        else
        {
            $empJob = EmpJob::join('designations', 'emp_jobs.designationId', 'designations.id')
            ->select('designations.name as designationName','emp_jobs.*')
            ->where('emp_jobs.id', $id)
            ->first();
        }
        $jobDesc = EmpJobDescription::where('empJobId', $empJob->id)->whereActive(1)->get();
        return view('newJobs.showJob')->with(['empJob'=>$empJob,'actual_link'=>$actual_link,'jobDesc'=>$jobDesc]);
    }

    public function applyForm($id)
    {
        $empJob = EmpJob::join('designations', 'emp_jobs.designationId', 'designations.id')
        ->join('departments', 'emp_jobs.departmentId', 'departments.id')
        ->select('designations.name as designationName', 'departments.section','departments.name as departmentName','emp_jobs.*')
        ->where('emp_jobs.id', $id)
        ->first();
        return view('newJobs.applyForm')->with(['empJob'=>$empJob]);
    }


    public function getDesignations($section)
    {
        return Designation::join('departments', 'designations.departmentId', 'departments.id')
        ->select('designations.id','designations.name')
        ->where('designations.active', 1)
        ->where('departments.active', 1)
        ->where('departments.section', $section)
        ->orderBy('designations.name')
        ->where('designations.interviewStatus', 1)
        ->get();
    }

    public function newJob($type)
    {
        $designations=[];
        return view('newJobs.applyJob')->with(['type'=>$type, 'designations'=>$designations]);

        if($type == 'Non Teaching')
        {
            $designations = Designation::join('departments', 'designations.departmentId', 'departments.id')
            ->where('designations.active', 1)
            ->where('departments.active', 1)
            ->where('designations.interviewStatus', 1)
            ->where('departments.section', 'Non Teaching')
            ->pluck('designations.name','designations.id');
            return view('newJobs.newJob')->with(['type'=>$type, 'designations'=>$designations]);

        }

        if($type == 'Teaching')
        {
            $designations = Designation::join('departments', 'designations.departmentId', 'departments.id')
            ->where('designations.active', 1)
            ->where('departments.active', 1)
            ->where('designations.interviewStatus', 1)
            ->where('departments.section', 'Teaching')
            ->pluck('designations.name','designations.id');
            return view('newJobs.newJob')->with(['type'=>$type, 'designations'=>$designations]);

        }

        if($type == 'ADF')
        {
            $designations = Designation::join('departments', 'designations.departmentId', 'departments.id')
            ->where('designations.active', 1)
            ->where('departments.active', 1)
            ->where('designations.interviewStatus', 1)
            ->where('departments.name', 'ADF')
            ->pluck('designations.name','designations.id');
            return view('newJobs.newJob')->with(['type'=>$type, 'designations'=>$designations]);

        }

        if($type == 'AFF')
        {
            $designations = Designation::join('departments', 'designations.departmentId', 'departments.id')
            ->where('departments.active', 1)
            ->where('departments.active', 1)
            ->where('designations.interviewStatus', 1)
            ->where('departments.name', 'AFF')
            ->pluck('designations.name','designations.id');
            return view('newJobs.newJob')->with(['type'=>$type, 'designations'=>$designations]);

        }

    } 

    public function applyJobApplication(Request $request)
    {
        $jobEntry = Cif3Application::where('mobileNo', $request->mobileNo)
        ->where('designationId', $request->designationId)
        ->where('currentStatus', 0)
        ->first();

        if($jobEntry)
        {
            return redirect()->back()->withInput()->with("error","Duplicate Entry.......");
        } 

       
        $job = new Cif3Application;
        $job->section=$request->section;
        $job->forDate=date('Y-m-d');
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
        $job->updated_by=$request->firstName.' '.$request->lastName; 


        // try
        // {
        //     DB::beginTransaction();
            if($job->save())
            {
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

                

                $job->save();
//                DB::commit();
            }

        // }
        // catch(\Exception $e)
        // {
        //     DB::rollBack();
        //     return redirect()->back()->withInput()->with("error","there is Some issue.....");
        // }
        
        return redirect()->back()->with("success","Application Form Save Successfully....");

        return redirect('/')->with(["success"=>"success"]);
    }
}
