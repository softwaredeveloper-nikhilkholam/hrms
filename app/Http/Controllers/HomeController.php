<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactusLandPage;
use App\Helpers\Utility;
use App\SliderLandPage;
use App\BusinessLogoLandPage;
use App\FunFactsLandPage;
use App\VedioLandPage;
use App\CommonForm;
use App\BdayWish;
use App\HolidayUploadList;
use App\StorePurchaseRequisition;
use App\UserRole;
use App\TeamLandPage;
use App\BioMetricStatus;
use App\SocialMediaLandPage;
use App\AboutsLandPage;
use App\HolidayDept;
use App\Department;
use App\EventRequisition;
use App\Designation;
use App\AttendanceDetail;
use App\StoreRepaire;
use App\EmpDet;
use App\Ticket;
use App\FormAndCircular;
use App\EmpRating;
use App\EmpApplication;
use App\AttendanceConfirm;
use App\OtherAsset;
use App\MobileAsset;
use App\SystemAsset;
use App\City;
use App\User;
use App\BiometricMachine;
use App\ExitProcessStatus;
use App\StoreQuotationPayment;
use App\StoreWorkOrderPayment;
use App\StoreQuotation;
use App\StoreWorkOrder;
use App\Retention;
use App\Notice;
use App\NoticeDetail;
use App\Inward;
use App\StoreOutward;
use App\Notification;
use Auth;
use DateTime;
use Hash;
use DB;
use Carbon\Carbon;

use App\StoreVendor;
use App\StoreCategory;
use App\StoreSubCategory;
use App\StoreHall;
use App\StoreRack;
use App\StoreShel;
use App\StoreUnit;
use App\StoreProduct;
use App\StoreScrap;
use App\BranchStock;
use App\StoreRequisition;
use DateInterval;
use DatePeriod;


class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $userType = $user->userType;

        if($userType == '701' || $userType == '801'  || $userType == '1002')
        {
            if($userType == '1002')
            {
                $productIds = BranchStock::where('branchId', $user->reqBranchId)
                ->where('status', 1)
                ->distinct('productId')
                ->pluck('productId');

                $categoryCount = count(StoreProduct::whereIn('id', $productIds)->where('active', 1)->distinct('categoryId')->pluck('categoryId'));
                $subCategoryCount = count(StoreProduct::whereIn('id', $productIds)->where('active', 1)->distinct('subCategoryId')->pluck('subCategoryId'));
                $productCount = count($productIds);
                return view('dashboards.branchAdminDashboard')->with(['categoryCount'=>$categoryCount,'subCategoryCount'=>$subCategoryCount,'productCount'=>$productCount]);
            }
            else
                return $this->purchaseHome();
        }

        if($userType == '61' && $user->HRMSAccess == 0)
        {
            return $this->purchaseHome();
        }

        if($userType == '51') // New 26-11-2023
        {
            $notices = Notice::where('toDate', '>=' ,date('Y-m-d'))->get();
            $employees = EmpDet::where('lastDate', NULL)->where('active', 1)->count();
            $branches = ContactusLandPage::where('active', 1)->count();
            $departments = Department::where('active', 1)->count();
            $male = EmpDet::where('active', 1)->where('gender', 'Male')->count();
            $female = EmpDet::where('active', 1)->where('gender', 'Female')->count();

            $departmentWiseEmployees = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->select('departments.name as departmentName', DB::raw('count(emp_dets.departmentId) as departmentCount'))
            ->where('emp_dets.active', 1)
            ->groupBy('departments.name')
            ->get();
            
            $tickets = Ticket::where('created_at', '>=',date('Y-m-01'))
            ->where('created_at', '<=', date('Y-m-t'))
            ->count();

            $ticketMonthWise = Ticket::select(DB::raw('count(id) as totalTicket'), 'ticketMonth')
            ->where('created_at', '>=',date('Y-m-01', strtotime('-12 months')))
            ->where('created_at', '<=', date('Y-m-t'))
            ->groupBy('ticketMonth')
            ->get();
            
            $newJoinee = EmpDet::where('jobJoingDate', '>=',date('Y-m-01'))
            ->where('jobJoingDate', '<=', date('Y-m-t'))
            ->where('departmentId', '!=', NULL)
            ->where('active', 1)
            ->count();

            
            $start    = new DateTime(date('Y-m-d', strtotime('-12 month')));
            $start->modify('first day of this month');
            $end      = new DateTime(date('Y-m-d'));
            $end->modify('first day of next month');
            $interval = DateInterval::createFromDateString('1 month');
            $period   = new DatePeriod($start, $interval, $end);

            $ndcNewJoineeList=[];
            foreach ($period as $dt) 
            {
                $temp=[];
                $forDate = $dt->format("Y-m");
                $temp['forDate'] = $forDate;

                $temp['newJoinneeCount'] = EmpDet::where('jobJoingDate', '>=',date('Y-m-01', strtotime($forDate)))
                ->where('jobJoingDate', '<=', date('Y-m-t', strtotime($forDate)))
                ->count();

                $temp['ndcCount'] = EmpDet::where('lastDate', '>=',date('Y-m-01', strtotime($forDate)))
                ->where('lastDate', '<=', date('Y-m-t', strtotime($forDate)))
                ->count();

                $temp['newTeachingJoinneeCount'] = EmpDet::join('departments', 'emp_dets.departmentId','departments.id')
                ->where('emp_dets.jobJoingDate', '>=',date('Y-m-01', strtotime($forDate)))
                ->where('emp_dets.jobJoingDate', '<=', date('Y-m-t', strtotime($forDate)))
                ->where('departments.section', 'Teaching')
                ->count();

                $temp['newNonTeachingJoinneeCount'] = EmpDet::join('departments', 'emp_dets.departmentId','departments.id')
                ->where('emp_dets.jobJoingDate', '>=',date('Y-m-01', strtotime($forDate)))
                ->where('emp_dets.jobJoingDate', '<=', date('Y-m-t', strtotime($forDate)))
                ->where('departments.section', 'Non Teaching')
                ->count();

                $temp['ndcTeachingCount'] = EmpDet::join('departments', 'emp_dets.departmentId','departments.id')
                ->where('emp_dets.lastDate', '>=',date('Y-m-01', strtotime($forDate)))
                ->where('emp_dets.lastDate', '<=', date('Y-m-t', strtotime($forDate)))
                ->where('departments.section', 'Teaching')
                ->count();

                $temp['ndcNonTeachingCount'] = EmpDet::join('departments', 'emp_dets.departmentId','departments.id')
                ->where('emp_dets.lastDate', '>=',date('Y-m-01', strtotime($forDate)))
                ->where('emp_dets.lastDate', '<=', date('Y-m-t', strtotime($forDate)))
                ->where('departments.section', 'Non Teaching')
                ->count();

                array_push($ndcNewJoineeList, $temp);        
            }

            $leftEmployees = ExitProcessStatus::where('created_at', '>=',date('Y-m-01'))
            ->where('created_at', '<=', date('Y-m-t'))
            ->where('active', 1)
            ->count();

            $newTeachingJoinee = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('departments.section', 'Teaching')
            ->where('emp_dets.jobJoingDate', '>=',date('Y-m-01'))
            ->where('emp_dets.jobJoingDate', '<=', date('Y-m-t'))
            ->where('emp_dets.active', 1)
            ->count();

            $leftTeachingEmployees = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('departments.section', 'Teaching')
            ->where('exit_process_statuses.created_at', '>=',date('Y-m-01'))
            ->where('exit_process_statuses.created_at', '<=', date('Y-m-t'))
            ->where('exit_process_statuses.active', 1)
            ->count();

            $newNonTeachingJoinee = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('departments.section', 'Non Teaching')
            ->where('emp_dets.jobJoingDate', '>=',date('Y-m-01'))
            ->where('emp_dets.jobJoingDate', '<=', date('Y-m-t'))
            ->where('emp_dets.active', 1)
            ->count();

            $leftNonTeachingEmployees = ExitProcessStatus::join('emp_dets', 'exit_process_statuses.empId', 'emp_dets.id')
            ->join('departments', 'emp_dets.departmentId', 'departments.id')
            ->where('departments.section', 'Non Teaching')
            ->where('exit_process_statuses.created_at', '>=',date('Y-m-01'))
            ->where('exit_process_statuses.created_at', '<=', date('Y-m-t'))
            ->where('exit_process_statuses.active', 1)
            ->count();

            $branchWiseEmployees = EmpDet::join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('contactus_land_pages.branchName', 'emp_dets.branchId',DB::raw('count(emp_dets.branchId) as branchCount'))
            ->where('emp_dets.active', 1)
            ->groupBy('contactus_land_pages.branchName', 'emp_dets.branchId')
            ->orderBy('contactus_land_pages.branchName')
            ->get();
            $totalEmployees = EmpDet::where('active', 1)->count();
            foreach($branchWiseEmployees as $temp)
            {
                $temp['teachingCount'] = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
                ->where('departments.section', 'Teaching')
                ->where('emp_dets.branchId', $temp->branchId)
                ->where('emp_dets.active', 1)
                ->count();

                $temp['nonTeachingCount'] = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')
                ->where('departments.section', 'Non Teaching')
                ->where('emp_dets.branchId', $temp->branchId)
                ->where('emp_dets.active', 1)
                ->count();

                $totalPer = $temp['teachingCount'] + $temp['nonTeachingCount'];
                
                $temp['teachingPercentage'] = ($temp['teachingCount']/$totalPer)*100;
                $temp['nonTeachingPercentage'] = ($temp['nonTeachingCount']/$totalPer)*100;
            }

            Notification::create([
                'message' => '🎉 John Doe got birthday wishes!',
            ]);

            $birthdays = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select(
                'designations.name as designationName',
                'contactus_land_pages.branchName',
                'emp_dets.name',
                'emp_dets.gender',
                'emp_dets.profilePhoto',
                'emp_dets.DOB',
                'emp_dets.id'
            )
            ->where('emp_dets.active', 1)
            ->whereRaw('MONTH(emp_dets.DOB) = ? AND DAY(emp_dets.DOB) = ?', [
                Carbon::now()->month,
                Carbon::now()->day
            ])
            ->orderBy('emp_dets.name') // optional sorting
            ->get();

            $empLate=AttendanceDetail::where('forDate', date('Y-m-d'))
            ->whereIn('dayStatus', ['PL', 'PLH', 'WOPL', 'WOPH'])
            ->where('active', 1)
            ->count();
    
            $empOnTime=AttendanceDetail::where('forDate', date('Y-m-d'))
            ->whereIn('dayStatus', ['P', 'WOP'])
            ->where('active', 1)
            ->count();
    
            $tempEmpTimes= "[['Task', 'Hours per Day'],['On Time',".$empOnTime."],['Late Mark',".$empLate."]]";

            $activeTeachingEmp = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')->where('departments.section', 'Teaching')->where('emp_dets.active', 1)->count();
            $deactiveTeachingEmp = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')->where('departments.section', 'Teaching')->where('emp_dets.active', 0)->count();
            
            $activeNonTeachingEmp = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')->where('departments.section', 'Non Teaching')->where('emp_dets.active', 1)->count();
            $deactiveNonTeachingEmp = EmpDet::join('departments', 'emp_dets.departmentId', 'departments.id')->where('departments.section', 'Non Teaching')->where('emp_dets.active', 0)->count();
           
            $tempTeachingActiveDeactive= "[['Task', 'Hours per Day'],['Active',".$activeTeachingEmp."],['Deactive',".$deactiveTeachingEmp."]]";
            $tempNonTeachingActiveDeactive= "[['Task', 'Hours per Day'],['Active',".$activeNonTeachingEmp."],['Deactive',".$deactiveNonTeachingEmp."]]";

            $activeEmps = EmpDet::whereActive(1)->count();

            // last 7 days
            $attendanceGraph = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->select(DB::raw('count(attendance_details.id) as totEmps'),'attendance_details.forDate')
            ->where('attendance_details.forDate', '>=', date('Y-m-d', strtotime('-6 days')))
            ->where('attendance_details.forDate', '<=', date('Y-m-d'))
            ->whereNotIn('attendance_details.dayStatus', ['0', 'A'])
            ->where('attendance_details.active', 1)
            ->groupBy('attendance_details.forDate')
            ->take(7)
            ->get();
            $tempGraph = "[ ['Type', 'Total Employees', 'Present', 'Absent'],";
            foreach($attendanceGraph as $graph)
            {
                $tempGraph.="['".date('d-M', strtotime($graph->forDate))."', ".$activeEmps.", ".$graph->totEmps.", ".($activeEmps-$graph->totEmps)."],";
            }

            $newarraynama3 = rtrim($tempGraph, ", ");
            $tempGraph=$newarraynama3."]";


            $agfCt = EmpApplication::where('type', 1)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $exitPassCt = EmpApplication::where('type', 2)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $leaveCt = EmpApplication::where('type', 3)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $travelCt = EmpApplication::where('type', 4)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $totApplications = $agfCt+$exitPassCt+$leaveCt+$travelCt;
    
            if($totApplications == 0)
            {
                $msg="{
                        title: 'Not Found Current Month Pending Applications',
                        is3D: true,
                        };";
                $notFound=100;
            }
            else
            {
                $msg="{
                    title: 'Current Month Pending Applications',
                    is3D: true,
                    };";
                $notFound=0;
            }
    
            $totApplications = $agfCt+$exitPassCt+$leaveCt+$travelCt;

            $attendanceDet = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select(DB::raw('count(attendance_details.id) as totEmps'), 'emp_dets.branchId','contactus_land_pages.shortName')
            ->where('attendance_details.forDate', date('Y-m-d'))
            ->whereNotIn('attendance_details.dayStatus', ['0', 'A'])
            ->where('attendance_details.active', 1)
            ->orderBy('contactus_land_pages.shortName')
            ->groupBy('emp_dets.branchId','contactus_land_pages.shortName')
            ->get();
            $temp= "[['Task', 'Hours per Day'],";
            foreach($attendanceDet as $attend)
            {
                $attend->shortName=$attend->shortName." (".$attend->totEmps.")";
                $temp.="['".$attend->shortName."',".$attend->totEmps."],";
            }

            $newarraynama = rtrim($temp, ", ");
            $temps=$newarraynama."]";            
          
            return view('newDashboards.hrDashboard')->with(['notices'=>$notices,'birthdays'=>$birthdays,'employees'=>$employees,'branches'=>$branches,
            'departments'=>$departments,'tickets'=>$tickets,'newJoinee'=>$newJoinee,'leftEmployees'=>$leftEmployees,'ndcNewJoineeList'=>$ndcNewJoineeList,
            'newTeachingJoinee'=>$newTeachingJoinee,'leftTeachingEmployees'=>$leftTeachingEmployees,'ticketMonthWise'=>$ticketMonthWise,
            'branchWiseEmployees'=>$branchWiseEmployees,'newNonTeachingJoinee'=>$newNonTeachingJoinee,'male'=>$male, 'female'=>$female,'totalEmployees'=>$totalEmployees,
            'leftNonTeachingEmployees'=>$leftNonTeachingEmployees,'departmentWiseEmployees'=>$departmentWiseEmployees,'tempEmpTimes'=>$tempEmpTimes,
            'tempTeachingActiveDeactive'=>$tempTeachingActiveDeactive,'tempNonTeachingActiveDeactive'=>$tempNonTeachingActiveDeactive,'tempGraph'=>$tempGraph,'notFound'=>$notFound,'msg'=>$msg,
            'agfCt'=>$agfCt,'exitPassCt'=>$exitPassCt,'leaveCt'=>$leaveCt,'travelCt'=>$travelCt,'temps'=>$temps]);
        }

        if($userType == '11' || $userType == '21' || $userType == '31') // 26-11-2023
        {         
            $util = new Utility();
            $user = Auth::user();
            $empId = $user->empId;
            $username = $user->username;
            $userType = $user->userType;
            $employee = EmpDet::select('id','branchId','DOB','startTime','reportingId','reportingType','empCode', 'firmType', 'departmentId','name', 'idCardStatus')
            ->where('id', $empId)->first();

            if($employee->idCardStatus == 'Pending')
                return view('admin.employees.profileUpdate')->with(['employee'=>$employee]);

            $retention = Retention::where('empId', $empId)->where('remark', '!=','Old Retention')->get();
            $oldRetention = Retention::where('empId', $empId)->where('remark', 'Old Retention')->first();
           
            if($employee)
                session()->put('section', Department::where('id', $employee->departmentId)->value('section')); 
                
            $notices = NoticeDetail::join('notices', 'notice_details.noticeId', 'notices.id')
            ->where('notice_details.empId', $empId)
            ->where('notices.status', 1)
            ->where('notices.toDate', '>=' ,date('Y-m-d'))
            ->get();
          
            $holidayList = HolidayUploadList::where('active', 1)->first();
            $repoName = User::where('empId', $employee->reportingId)->value('name');
            if($repoName == '')
                $repoName = User::where('id', $employee->reportingId)->value('name');

            $birthdays = EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('designations.name as designationName','contactus_land_pages.branchName',
            'emp_dets.name','emp_dets.gender','emp_dets.profilePhoto','emp_dets.DOB','emp_dets.id')
            ->where('emp_dets.active', 1)
            ->whereRaw('DAYOFYEAR(curdate()) <= DAYOFYEAR(emp_dets.DOB) AND DAYOFYEAR(curdate()) + 7 >=  dayofyear(emp_dets.DOB)')
            ->orderByRaw('DAYOFYEAR(emp_dets.DOB)')
            ->take(5)
            ->get();
            
            $wishes=[];
            if(date('d-m') == date('d-m', strtotime($employee->DOB)))
            {
                $wishes = BdayWish::join('emp_dets', 'bday_wishes.empId', 'emp_dets.id')
                ->join('departments', 'emp_dets.departmentId', 'departments.id')
                ->select('emp_dets.name', 'departments.name as departmentName')
                ->where('toEmpId', $empId)
                ->get();
            }
        
            $flag=0;
            $myBirthday= EmpDet::where('id',$empId)->value('DOB');
            if(date('m-Y') == date('m-Y', strtotime($myBirthday)))
                $flag=1;

            // 1] Late Mark
            $todayLateMark = AttendanceDetail::where('forDate', date('Y-m-d'))
            ->where('empId', $empId)
            ->whereIn('dayStatus', ['PL', 'WOPL', 'WOPLH'])
            ->first();

            $punchTime = AttendanceDetail::where('forDate', date('Y-m-d'))
            ->where('empId', $empId)
            ->value('inTime');
            // 2] forms & circular
            $forms = FormAndCircular::orderBy('updated_at', 'desc')
            ->whereDate('created_at', '>=', date('Y-m-d', strtotime('-2 Days')))
            ->first();
            
            $attendanceNoti = AttendanceConfirm::where('branchId', $employee->branchId)
            ->first();

            // 3] Application Update
            $empId = Auth::user()->empId;
            $application = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.type', 'emp_applications.startDate','emp_applications.status', 
            'emp_applications.updated_by', 'emp_applications.updated_at')
            ->where('emp_applications.active', 1)
            ->where('emp_applications.status', '!=', 0)
            ->where('emp_dets.id', $empId)
            ->where('emp_applications.updated_at', date('Y-m-d H:i:s'))
            ->orderBy('emp_applications.updated_at', 'desc')
            ->first();

            $holidays = HolidayDept::join('holidays', 'holiday_depts.holidayId', 'holidays.id')
            ->select('holidays.name', 'holidays.forDate')
            ->where('holiday_depts.empCode', $employee->empCode)
            ->where('holiday_depts.active', 1)
            ->where('holidays.active', 1)
            ->where('holidays.forDate', '>=', date('Y-m-d'))
            ->orderBy('holidays.forDate')
            ->take(9)
            ->get();

            // Attendance Data
            $attend = $util->getMonthlyEmpAttendance(session()->get('empCode'), date('M-Y'));
            $totDays = $attend[0];
            $presentDays = $attend[1];
            $absentDays = $attend[2];
            $extraDays = $attend[3];
            $weekoff=$attend[4];


            return view('newDashboards.employeeDashboard')->with(['notices'=>$notices,'employee'=>$employee, 'holidays'=>$holidays, 
            'birthdays'=>$birthdays,'forms'=>$forms, 'application'=>$application,'todayLateMark'=>$todayLateMark,
            'wishes'=>$wishes,'flag'=>$flag,'attendanceNoti'=>$attendanceNoti,'holidayList'=>$holidayList,
            'totDays'=>$totDays,'absentDays'=>$absentDays,'presentDays'=>$presentDays,'extraDays'=>$extraDays, 'weekoff'=>$weekoff, 
            'repoName'=>$repoName, 'punchTime'=>$punchTime, 'wishes'=>$wishes, 'retention'=>$retention, 'oldRetention'=>$oldRetention]);
        }

        if($user->wishView == 0)
        {
            $newUser=User::find($user->id);
            $newUser->wishView =1;
            $newUser->save();
        }
        
        $employee = EmpDet::select('reportingId', 'reportingType', 'empCode', 'firmType', 'departmentId')
        ->where('id', $user->empId)
        ->first();
           
        if($employee)
        {
            if($employee->reportingType == 1)
                $repoName = EmpDet::where('id', $employee->reportingId)->value('name');
            else
                $repoName = User::where('id', $employee->reportingId)->value('name');
        }
        else
        {
            $repoName = '';
        }
        
        $activeEmps = EmpDet::whereActive(1)->count();
        $branches = ContactusLandPage::whereActive(1)->get();
        $activeBranches = count($branches);
        $activeDepartments = Department::whereActive(1)->count();
        $activeDesignations = Designation::whereActive(1)->count();
        $activeAssets1 = SystemAsset::whereActive(1)->count();
        $activeAssets2 = MobileAsset::whereActive(1)->count();
        $activeAssets3 = OtherAsset::whereActive(1)->count();
        $ratingCts = EmpRating::count();
        $ratings = EmpRating::select(DB::raw('COUNT(id) as totFeed'),DB::raw('SUM(star1) as star1'),DB::raw('SUM(star2) as star2'),DB::raw('SUM(star3) as star3'),DB::raw('SUM(star4) as star4'),DB::raw('SUM(star5) as star5'))
        ->first();
        $activeAssets = $activeAssets1+$activeAssets2+$activeAssets3;

        $attendanceDet = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        ->select(DB::raw('count(attendance_details.id) as totEmps'), 'emp_dets.branchId','contactus_land_pages.shortName')
        ->where('attendance_details.forDate', date('Y-m-d'))
        ->whereNotIn('attendance_details.dayStatus', ['0', 'A'])
        ->where('attendance_details.active', 1)
        ->orderBy('contactus_land_pages.shortName')
        ->groupBy('emp_dets.branchId','contactus_land_pages.shortName')
        ->get();
        $temp= "[['Task', 'Hours per Day'],";
        foreach($attendanceDet as $attend)
        {
            $attend->shortName=$attend->shortName." (".$attend->totEmps.")";
            $temp.="['".$attend->shortName."',".$attend->totEmps."],";
        }

        $newarraynama = rtrim($temp, ", ");
        $temps=$newarraynama."]";
        $male = EmpDet::where('active', 1)->where('gender', 'Male')->count();
        $female = EmpDet::where('active', 1)->where('gender', 'Female')->count();

        $newJoined = EmpDet::whereMonth('jobJoingDate', date('m', strtotime('-1 month')))->whereYear('jobJoingDate', date('Y', strtotime('-1 month')))->where('active', 1)->count();
        $tickets = Ticket::where('status', 1)->count();

        $agfCt = EmpApplication::where('type', 1)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
        $exitPassCt = EmpApplication::where('type', 2)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
        $leaveCt = EmpApplication::where('type', 3)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
        $travelCt = EmpApplication::where('type', 4)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
        $totApplications = $agfCt+$exitPassCt+$leaveCt+$travelCt;

        if($totApplications == 0)
        {
            $msg="{
                    title: 'Not Found Current Month Pending Applications',
                    is3D: true,
                    };";
            $notFound=100;
        }
        else
        {
            $msg="{
				title: 'Current Month Pending Applications',
				is3D: true,
				};";
            $notFound=0;
        }

        $totApplications = $agfCt+$exitPassCt+$leaveCt+$travelCt;

        // Accounts 
        $attendanceDetAcct = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
        ->select(DB::raw('count(attendance_details.id) as totEmps'), 'emp_dets.organisation')
        ->where('attendance_details.forDate', date('Y-m-d'))
        ->whereNotIn('attendance_details.dayStatus', ['0', 'A'])
        ->whereIn('emp_dets.organisation', [1,2,3])
        ->where('attendance_details.active', 1)
        ->groupBy('emp_dets.organisation')
        ->get();
        
        $temp1= "[['Task', 'Hours per Day'],";
        foreach($attendanceDetAcct as $attend)
        {
            if($attend->organisation == 1)
                $temp1.="['Ellora',".$attend->totEmps."],";
            elseif($attend->organisation == 2)
                $temp1.="['Snayraa',".$attend->totEmps."],";
            else
                $temp1.="['Tejasha',".$attend->totEmps."],";
        }

        $newarraynama1 = rtrim($temp1, ", ");
        $temps1=$newarraynama1."]";
        $util = new Utility();

        if($userType != '51')
        {
            $lastSalary = $util->getLastSalary();
            $lastSalary = ($lastSalary != 0)?$util->numberFormatRound($lastSalary):0;

            $expectedSalary = $util->getExpectedSalary();
            $expectedSalary = ($expectedSalary != 0)?$util->numberFormatRound($expectedSalary):0;

        }
        else
        {
            $lastSalary=0;
            $expectedSalary=0;
        }

        $empLate=AttendanceDetail::where('forDate', date('Y-m-d'))
        ->whereIn('dayStatus', ['PL', 'PLH', 'WOPL', 'WOPH'])
        ->where('active', 1)
        ->count();

        $empOnTime=AttendanceDetail::where('forDate', date('Y-m-d'))
        ->whereIn('dayStatus', ['P', 'WOP'])
        ->where('active', 1)
        ->count();

        $tempEmpTimes= "[['Task', 'Hours per Day'],['On Time',".$empOnTime."],['Late Mark',".$empLate."]]";

        // last 7 days
        $attendanceGraph = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
        ->select(DB::raw('count(attendance_details.id) as totEmps'),'attendance_details.forDate')
        ->where('attendance_details.forDate', '>=', date('Y-m-d', strtotime('-6 days')))
        ->where('attendance_details.forDate', '<=', date('Y-m-d'))
        ->whereNotIn('attendance_details.dayStatus', ['0', 'A'])
        ->where('attendance_details.active', 1)
        ->groupBy('attendance_details.forDate')
        ->take(7)
        ->get();
        $tempGraph = "[ ['Type', 'Total Employees', 'Present', 'Absent'],";
        foreach($attendanceGraph as $graph)
        {
            $tempGraph.="['".date('d-M', strtotime($graph->forDate))."', ".$activeEmps.", ".$graph->totEmps.", ".($activeEmps-$graph->totEmps)."],";
        }

        $newarraynama3 = rtrim($tempGraph, ", ");
        $tempGraph=$newarraynama3."]";

       
        if($userType == '00' || $userType == '007' || $userType == '501' || $userType == '401' || $userType == '301' || $userType == '201')
        {
            $pendingNdc = ExitProcessStatus::where('finalPermission', 1)->count();
            $ndc = ExitProcessStatus::count();
            
            $temp = ['pendingNdc'=>$pendingNdc,'ndc'=>$ndc, 'activeEmps'=>$activeEmps,'activeBranches'=>$activeBranches,
            'activeDepartments'=>$activeDepartments,'activeDesignations'=>$activeDesignations,
            'activeAssets'=>$activeAssets,'repoName'=>$repoName,'ratings'=>$ratings,'ratingCts'=>$ratingCts,
            'agfCt'=>$agfCt,'exitPassCt'=>$exitPassCt,'leaveCt'=>$leaveCt,'travelCt'=>$travelCt,'tempEmpTimes'=>$tempEmpTimes,
            'tickets'=>$tickets,'newJoined'=>$newJoined,'temps1'=>$temps1,'lastSalary'=>$lastSalary,'expectedSalary'=>$expectedSalary,
            'temps'=>$temps, 'male'=>$male, 'msg'=>$msg, 'female'=>$female, 'msg'=>$msg, 'notFound'=>$notFound, 'tempGraph'=>$tempGraph];
    
            return view('applications')->with($temp);
        }
         
        if($user->newUser == 0) 
        {
            return view('auth.passwords.reset');
        }

        if($userType == '31' || $userType == '21')
        {
            return redirect('/dashboard');
        }
        
        if($userType == '51')
        {
            $pendingNdc = ExitProcessStatus::where('hrDept', 1)->count();
            $ndc = ExitProcessStatus::count();
            $temp = ['activeEmps'=>$activeEmps,'activeBranches'=>$activeBranches,'pendingNdc'=>$pendingNdc,'ndc'=>$ndc
            ,'activeDepartments'=>$activeDepartments,'activeDesignations'=>$activeDesignations,
            'activeAssets'=>$activeAssets,'repoName'=>$repoName,'ratings'=>$ratings,'ratingCts'=>$ratingCts,
            'agfCt'=>$agfCt,'exitPassCt'=>$exitPassCt,'leaveCt'=>$leaveCt,'travelCt'=>$travelCt,'tempEmpTimes'=>$tempEmpTimes,
            'tickets'=>$tickets,'newJoined'=>$newJoined,'temps1'=>$temps1,'lastSalary'=>$lastSalary,'expectedSalary'=>$expectedSalary,
            'temps'=>$temps, 'male'=>$male, 'msg'=>$msg, 'female'=>$female, 'msg'=>$msg, 'notFound'=>$notFound, 'tempGraph'=>$tempGraph];
    
            return view('dashboards.hrDepDashboard')->with($temp);
        }

        if($userType == '61')
        {
            $temp = ['activeEmps'=>$activeEmps,'activeBranches'=>$activeBranches,
            'activeDepartments'=>$activeDepartments,'activeDesignations'=>$activeDesignations,
            'activeAssets'=>$activeAssets,'repoName'=>$repoName,'ratings'=>$ratings,'ratingCts'=>$ratingCts,
            'agfCt'=>$agfCt,'exitPassCt'=>$exitPassCt,'leaveCt'=>$leaveCt,'travelCt'=>$travelCt,'tempEmpTimes'=>$tempEmpTimes,
            'tickets'=>$tickets,'newJoined'=>$newJoined,'temps1'=>$temps1,'lastSalary'=>$lastSalary,'expectedSalary'=>$expectedSalary,
            'temps'=>$temps, 'male'=>$male, 'msg'=>$msg, 'female'=>$female, 'msg'=>$msg, 'notFound'=>$notFound, 'tempGraph'=>$tempGraph];
    
            return view('dashboards.accountDepDashboard')->with($temp);
        }

        if($userType == '71')
        {
            $pendingNdc = ExitProcessStatus::where('itDept', 1)->count();
            $ndc = ExitProcessStatus::count();
            $temp = ['activeEmps'=>$activeEmps,'activeBranches'=>$activeBranches,
            'pendingNdc'=>$pendingNdc,'ndc'=>$ndc];
    
            return view('dashboards.itDepDashboard')->with($temp);
        }

        if($userType == '81')
        {
            $pendingNdc = ExitProcessStatus::where('erpDept', 1)->count();
            $ndc = ExitProcessStatus::count();
            $temp = ['activeEmps'=>$activeEmps,'activeBranches'=>$activeBranches,
            'pendingNdc'=>$pendingNdc,'ndc'=>$ndc];
    
            return view('dashboards.erpDepDashboard')->with($temp);
        }

        if($userType == '91')
        {
            $pendingNdc = ExitProcessStatus::where('storeDept', 1)->count();
            $ndc = ExitProcessStatus::count();
            $temp = ['activeEmps'=>$activeEmps,'activeBranches'=>$activeBranches,
            'pendingNdc'=>$pendingNdc,'ndc'=>$ndc];

            return view('dashboards.storeDepDashboard')->with($temp);
        }

        if($userType == '101')
        {
            $pendingNdc = ExitProcessStatus::where('storeDept', 1)->count();
            $ndc = ExitProcessStatus::count();
            $temp = ['activeEmps'=>$activeEmps,'activeBranches'=>$activeBranches,
            'pendingNdc'=>$pendingNdc,'ndc'=>$ndc];

            return view('dashboards.securityDepDashboard')->with($temp);
        }
        
        if($userType == '11'  || $userType == '601')
        {
            $temp = ['activeEmps'=>$activeEmps,'activeBranches'=>$activeBranches,
            'activeDepartments'=>$activeDepartments,'activeDesignations'=>$activeDesignations,
            'activeAssets'=>$activeAssets,'repoName'=>$repoName,'ratings'=>$ratings,'ratingCts'=>$ratingCts,
            'agfCt'=>$agfCt,'exitPassCt'=>$exitPassCt,'leaveCt'=>$leaveCt,'travelCt'=>$travelCt,'tempEmpTimes'=>$tempEmpTimes,
            'tickets'=>$tickets,'newJoined'=>$newJoined,'temps1'=>$temps1,'lastSalary'=>$lastSalary,'expectedSalary'=>$expectedSalary,
            'temps'=>$temps, 'male'=>$male, 'msg'=>$msg, 'female'=>$female, 'msg'=>$msg, 'notFound'=>$notFound, 'tempGraph'=>$tempGraph];
    
            return view('dashboards.generalMgrDashboard')->with($temp);
        }
    }

    public function landingPage()
    {
        $sliderCt = SliderLandPage::whereActive(1)->count();
        $aboutCt = AboutsLandPage::whereActive(1)->count();
        $teamsCt = TeamLandPage::whereActive(1)->count();
        $mediaCt = SocialMediaLandPage::whereActive(1)->count();
        $contactsCt = ContactusLandPage::whereActive(1)->count();
        $funCountsCt = FunFactsLandPage::whereActive(1)->count();  
        $vediosCt = VedioLandPage::whereActive(1)->count();
        $busLogoCt = BusinessLogoLandPage::whereActive(1)->count();

        return view('dashboards.landingPageDashboard')->with(['contactsCt'=>$contactsCt,'vediosCt'=>$vediosCt,
        'teamsCt'=>$teamsCt,'busLogoCt'=>$busLogoCt, 'sliderCt'=>$sliderCt,'funCountsCt'=>$funCountsCt,'mediaCt'=>$mediaCt,'aboutCt'=>$aboutCt]);
    }

    public function selectApplication($type)
    {
        if($type==1)
        {
            $sliderCt = SliderLandPage::whereActive(1)->count();
            $aboutCt = AboutsLandPage::whereActive(1)->count();
            $teamsCt = TeamLandPage::whereActive(1)->count();
            $mediaCt = SocialMediaLandPage::whereActive(1)->count();
            $contactsCt = ContactusLandPage::whereActive(1)->count();
            $funCountsCt = FunFactsLandPage::whereActive(1)->count();  
            $vediosCt = VedioLandPage::whereActive(1)->count();
            $busLogoCt = BusinessLogoLandPage::whereActive(1)->count();

            return view('dashboards.landingPageDashboard')->with(['contactsCt'=>$contactsCt,'vediosCt'=>$vediosCt,
            'teamsCt'=>$teamsCt,'busLogoCt'=>$busLogoCt, 'sliderCt'=>$sliderCt,'funCountsCt'=>$funCountsCt,'mediaCt'=>$mediaCt,'aboutCt'=>$aboutCt]);
        }

        if($type==2)
        {
            $activeEmps = EmpDet::whereActive(1)->count();
            $branches = ContactusLandPage::whereActive(1)->get();
            $activeBranches = count($branches);
            $activeDepartments = Department::whereActive(1)->count();
            $activeDesignations = Designation::whereActive(1)->count();
            $activeAssets1 = SystemAsset::whereActive(1)->count();
            $activeAssets2 = MobileAsset::whereActive(1)->count();
            $activeAssets3 = OtherAsset::whereActive(1)->count();
            $ratingCts = EmpRating::count();
            $ratings = EmpRating::select(DB::raw('COUNT(id) as totFeed'),DB::raw('SUM(star1) as star1'),DB::raw('SUM(star2) as star2'),DB::raw('SUM(star3) as star3'),DB::raw('SUM(star4) as star4'),DB::raw('SUM(star5) as star5'))
            ->first();
            $activeAssets = $activeAssets1+$activeAssets2+$activeAssets3;

            $attendanceDet = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select(DB::raw('count(attendance_details.id) as totEmps'), 'emp_dets.branchId','contactus_land_pages.shortName')
            ->where('attendance_details.forDate', date('Y-m-d'))
            ->whereNotIn('attendance_details.dayStatus', ['0', 'A'])
            ->where('attendance_details.active', 1)
            ->orderBy('contactus_land_pages.shortName')
            ->groupBy('emp_dets.branchId','contactus_land_pages.shortName')
            ->get();
            $temp= "[['Task', 'Hours per Day'],";
            foreach($attendanceDet as $attend)
            {
                $attend->shortName=$attend->shortName." (".$attend->totEmps.")";
                $temp.="['".$attend->shortName."',".$attend->totEmps."],";
            }

            $newarraynama = rtrim($temp, ", ");
            $temps=$newarraynama."]";
            $male = EmpDet::where('active', 1)->where('gender', 'Male')->count();
            $female = EmpDet::where('active', 1)->where('gender', 'Female')->count();

            $newJoined = EmpDet::whereMonth('jobJoingDate', date('m', strtotime('-1 month')))->whereYear('jobJoingDate', date('Y', strtotime('-1 month')))->where('active', 1)->count();
            $tickets = Ticket::where('status', 1)->count();

            $agfCt = EmpApplication::where('type', 1)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $exitPassCt = EmpApplication::where('type', 2)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $leaveCt = EmpApplication::where('type', 3)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $travelCt = EmpApplication::where('type', 4)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $totApplications = $agfCt+$exitPassCt+$leaveCt+$travelCt;

            if($totApplications == 0)
            {
                $msg="{
                        title: 'Not Found Current Month Pending Applications',
                        is3D: true,
                        };";
                $notFound=100;
            }
            else
            {
                $msg="{
                    title: 'Current Month Pending Applications',
                    is3D: true,
                    };";
                $notFound=0;
            }

            $totApplications = $agfCt+$exitPassCt+$leaveCt+$travelCt;
            
            $pendingNdc = ExitProcessStatus::where('hrDept', 1)->count();
            $ndc = ExitProcessStatus::count();
            $repoName='';
                    $empLate=AttendanceDetail::where('forDate', date('Y-m-d'))
            ->whereIn('dayStatus', ['PL', 'PLH', 'WOPL', 'WOPH'])
            ->where('active', 1)
            ->count();

            $empOnTime=AttendanceDetail::where('forDate', date('Y-m-d'))
            ->whereIn('dayStatus', ['P', 'WOP'])
            ->where('active', 1)
            ->count();

            $tempEmpTimes= "[['Task', 'Hours per Day'],['On Time',".$empOnTime."],['Late Mark',".$empLate."]]";

            // last 7 days
            $attendanceGraph = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->select(DB::raw('count(attendance_details.id) as totEmps'),'attendance_details.forDate')
            ->where('attendance_details.forDate', '>=', date('Y-m-d', strtotime('-6 days')))
            ->where('attendance_details.forDate', '<=', date('Y-m-d'))
            ->whereNotIn('attendance_details.dayStatus', ['0', 'A'])
            ->where('attendance_details.active', 1)
            ->groupBy('attendance_details.forDate')
            ->take(7)
            ->get();
            $tempGraph = "[ ['Type', 'Total Employees', 'Present', 'Absent'],";
            foreach($attendanceGraph as $graph)
            {
                $tempGraph.="['".date('d-M', strtotime($graph->forDate))."', ".$activeEmps.", ".$graph->totEmps.", ".($activeEmps-$graph->totEmps)."],";
            }

            $newarraynama3 = rtrim($tempGraph, ", ");
            $tempGraph=$newarraynama3."]";

            $newarraynama = rtrim($temp, ", ");
            $temps=$newarraynama."]";
            $male = EmpDet::where('active', 1)->where('gender', 'Male')->count();
            $female = EmpDet::where('active', 1)->where('gender', 'Female')->count();

            $newJoined = EmpDet::whereMonth('jobJoingDate', date('m', strtotime('-1 month')))->whereYear('jobJoingDate', date('Y', strtotime('-1 month')))->where('active', 1)->count();
            $tickets = Ticket::where('status', 1)->count();

            $agfCt = EmpApplication::where('type', 1)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $exitPassCt = EmpApplication::where('type', 2)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $leaveCt = EmpApplication::where('type', 3)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $travelCt = EmpApplication::where('type', 4)->whereMonth('startDate', date('m'))->whereYear('startDate', date('Y'))->where('status', 0)->count();
            $totApplications = $agfCt+$exitPassCt+$leaveCt+$travelCt;

            if($totApplications == 0)
            {
                $msg="{
                        title: 'Not Found Current Month Pending Applications',
                        is3D: true,
                        };";
                $notFound=100;
            }
            else
            {
                $msg="{
                    title: 'Current Month Pending Applications',
                    is3D: true,
                    };";
                $notFound=0;
            }

            $totApplications = $agfCt+$exitPassCt+$leaveCt+$travelCt;

            // Accounts 
            $attendanceDetAcct = AttendanceDetail::join('emp_dets', 'attendance_details.empId', 'emp_dets.id')
            ->select(DB::raw('count(attendance_details.id) as totEmps'), 'emp_dets.organisation')
            ->where('attendance_details.forDate', date('Y-m-d'))
            ->whereNotIn('attendance_details.dayStatus', ['0', 'A'])
            ->whereIn('emp_dets.organisation', [1,2,3])
            ->where('attendance_details.active', 1)
            ->groupBy('emp_dets.organisation')
            ->get();
            
            $temp1= "[['Task', 'Hours per Day'],";
            foreach($attendanceDetAcct as $attend)
            {
                if($attend->organisation == 1)
                    $temp1.="['Ellora',".$attend->totEmps."],";
                elseif($attend->organisation == 2)
                    $temp1.="['Snayraa',".$attend->totEmps."],";
                else
                    $temp1.="['Tejasha',".$attend->totEmps."],";
            }

            $newarraynama1 = rtrim($temp1, ", ");
            $temps1=$newarraynama1."]";


            $temp = ['activeEmps'=>$activeEmps,'activeBranches'=>$activeBranches,'pendingNdc'=>$pendingNdc,'ndc'=>$ndc
            ,'activeDepartments'=>$activeDepartments,'activeDesignations'=>$activeDesignations,
            'activeAssets'=>$activeAssets,'repoName'=>$repoName,'ratings'=>$ratings,'ratingCts'=>$ratingCts,
            'agfCt'=>$agfCt,'exitPassCt'=>$exitPassCt,'leaveCt'=>$leaveCt,'travelCt'=>$travelCt,'tempEmpTimes'=>$tempEmpTimes,
            'tickets'=>$tickets,'newJoined'=>$newJoined,'temps1'=>$temps1,
            'temps'=>$temps, 'male'=>$male, 'msg'=>$msg, 'female'=>$female, 'msg'=>$msg, 'notFound'=>$notFound, 'tempGraph'=>$tempGraph];
    
            return view('dashboards.hrDepDashboard')->with($temp);
        }

        if($type==3)
        {
           
            $user = Auth::user();
            $userType = $user->userType;
            if($userType == '801')
            {
                $prodCount = BranchStock::where('userId', $user->id)->count();
                $reqCount = StoreRequisition::where('userId', $user->id)->count();
                $prodReturnCount = BranchStock::where('userId', $user->id)->where('stock', '!=',0)->where('returnToStore', 'Yes')->count();
                return view('storeHome')->with(['prodCount'=>$prodCount,'reqCount'=>$reqCount,'prodReturnCount'=>$prodReturnCount]);
            }

            // Store department Counts
            $storeData = $this->getStoreDepartmentCounts();

            // Purchase Department Counts
            $purchaseData = $this->getPurchaseDepartmentCounts();            
            
            // Accounts department Counts
            // Requisitions Department Counts
            // Branch Admin counts

            // $category = StoreCategory::whereActive('1')->count();
            // $subCategory = StoreSubCategory::whereActive('1')->count();
            // $products = StoreProduct::whereActive('1')->count();
            // $halls = StoreHall::whereActive('1')->count();
            // $racks = StoreRack::whereActive('1')->count();
            // $shelfs = StoreShel::whereActive('1')->count();
            // $units = StoreUnit::whereActive('1')->count();
            // $scrapAmount = StoreScrap::whereStatus('1')->sum('amount');
            
            // $halls = StoreHall::whereActive('1')->count();

           

            return view('storeHome', compact('storeData','purchaseData'));
            // ->with(['WOreminders'=>$WOreminders,'POreminders'=>$POreminders,'outstandingPORs'=>$outstandingPORs,'outstandingWORs'=>$outstandingWORs,'outstandingRs'=>$outstandingRs,
            // 'paidPORs'=>$paidPORs,'paidWORs'=>$paidWORs,'paidWORs'=>$paidWORs,'pendingQuotationCount'=>$pendingQuotationCount,'pendingWorkOrderCount'=>$pendingWorkOrderCount
            // ,'vendorCount'=>$vendorCount,'scrapAmount'=>$scrapAmount,'category'=>$category, 'subCategory'=>$subCategory,'products'=>$products,'halls'=>$halls,'racks'=>$racks, 
            // 'shelfs'=>$shelfs,'units'=>$units]);

        }

        if($type==4)
        {
            return view('CRM.superAdminDashboard');
        }

        return redirect()->back()->withInput()->with("error","Invalid value selected.....");
    }

    public function getStoreDepartmentCounts()
    {
        $reqCounts = StoreRequisition::where('reqType', 1)
        ->selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');

        //Purchase Requisitions =  0 - Pending, 1 - In-Progress, 2 - Completed, 3 - Rejected, 4 - Hold, 5-Approved
        $purReqCounts = StorePurchaseRequisition::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Event Requisitions = 0 - Pending, 1 - Completed, 2 - Rejected, 3 - Hold, 4-Cancel, 5-InProgress
        $eventReqCounts = EventRequisition::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        //	0-pending, 1- closed, 2-inprogress, 3-cancel/rejected
        $repaireCount = StoreRepaire::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
    
        
       return $data = [
            'category' => StoreCategory::whereActive(1)->count(),
            'subCategory' => StoreSubCategory::whereActive(1)->count(),
            'products' => StoreProduct::whereActive(1)->count(),
            'halls' => StoreHall::whereActive(1)->count(),
            'racks' => StoreRack::whereActive(1)->count(),
            'shelfs' => StoreShel::whereActive(1)->count(),
            'units' => StoreUnit::whereActive(1)->count(),
            'scrapAmount' => StoreScrap::whereStatus(1)->sum('amount'),
            'scrapCount' => StoreScrap::whereStatus(1)->count(),
            'repairePendingCount' => $repaireCount[0] ?? 0,
            'repaireCompletedCount' => $repaireCount[1] ?? 0,
            'reqPendingCount' => $reqCounts[0] ?? 0,
            'reqCompletedCount' => $reqCounts[1] ?? 0,
            'reqRejectedCount' => $reqCounts[2] ?? 0,
            'eventReqPendingCount' => $eventReqCounts[0] ?? 0,
            'eventReqCompletedCount' => $eventReqCounts[1] ?? 0,
            'eventReqRejectedCount' => $eventReqCounts[2] ?? 0,
            'purReqPendingCount' => $purReqCounts[0] ?? 0,
            'purReqCompletedCount' => $purReqCounts[2] ?? 0,
            'purReqRejectedCount' => $purReqCounts[3] ?? 0,
            'purReqApprovedCount' => $purReqCounts[5] ?? 0,
            'users' => User::whereActive(1)->count(),
            'inwards' => Inward::whereActive(1)->count(),
            'outwards' => StoreOutward::whereActive(1)->count(),
            'returnProducts'=> DB::table('store_requisition_products')
            ->where('returnStatus', 1)
            ->selectRaw('SUM(receivedQty - returnQty) AS total')
            ->value('total'), 
            'returnReparieProducts'=> StoreRepaire::where('status', 0)
            ->selectRaw('SUM(count - returnCount) AS total')
            ->value('total')           
        ];
    }

    public function getPurchaseDepartmentCounts()
    {
        $user = Auth::user();
        $userId = $user->id;
        $userType = $user->userType;
        $today = Carbon::today(); // Store today's date once for reuse

        // Define status constants for clarity
        $STATUS_PENDING = 1;
        $STATUS_PAID = 2;

        // Check if user is of type 701 or 801
        $isLimitedUser = in_array($userType, ['701', '801']);

        // Base Query Modifications Based on User Type
        $quotationQuery = StoreQuotationPayment::query();
        $workOrderQuery = StoreWorkOrderPayment::query();
        $quotationMainQuery = StoreQuotation::query();
        $workOrderMainQuery = StoreWorkOrder::query();

        if ($isLimitedUser) {
            // Apply user-specific filtering
            $quotationQuery->join('store_quotations', 'store_quotation_payments.quotationId', '=', 'store_quotations.id')
                ->where('store_quotations.raisedBy', $userId);

            $workOrderQuery->join('store_work_orders', 'store_work_order_payments.orderId', '=', 'store_work_orders.id')
                ->where('store_work_orders.raisedBy', $userId);

            $quotationMainQuery->where('raisedBy', $userId);
            $workOrderMainQuery->where('raisedBy', $userId);
        }

        // Outstanding Payments
        $outstandingPORs = (clone $quotationQuery)->where('store_quotation_payments.status', $STATUS_PENDING)->sum('store_quotation_payments.amount');
        $outstandingWORs = (clone $workOrderQuery)->where('store_work_order_payments.status', $STATUS_PENDING)->sum('store_work_order_payments.amount');
        $outstandingRs = $outstandingPORs + $outstandingWORs;

        // Paid Payments
        $paidPORs = (clone $quotationQuery)->where('store_quotation_payments.status', $STATUS_PAID)->sum('store_quotation_payments.amount');
        $paidWORs = (clone $workOrderQuery)->where('store_work_order_payments.status', $STATUS_PAID)->sum('store_work_order_payments.amount');

        // Pending Orders Count
        $pendingQuotationCount = (clone $quotationMainQuery)->where('quotStatus', 'Pending')->count();
        $pendingWorkOrderCount = (clone $workOrderMainQuery)->where('WOStatus', 'Pending')->count();

        // Vendor Count (same for both user types)
        $vendorCount = StoreVendor::where('active', 1)->count();

        // Purchase Order Reminders
        $POreminders = (clone $quotationQuery)
            ->join('store_vendors', 'store_quotation_payments.vendorId', '=', 'store_vendors.id')
            ->where('store_quotation_payments.status', $STATUS_PENDING)
            ->whereDate('store_quotation_payments.forDate', '>=', $today)
            ->get(['store_vendors.name', 'store_quotation_payments.poNumber', 'store_quotation_payments.forDate', 'store_quotation_payments.amount']);

        // Work Order Reminders
        $WOreminders = (clone $workOrderQuery)
            ->join('store_vendors', 'store_work_order_payments.vendorId', '=', 'store_vendors.id')
            ->where('store_work_order_payments.status', $STATUS_PENDING)
            ->whereDate('store_work_order_payments.forDate', '>=', $today)
            ->get(['store_vendors.name', 'store_work_order_payments.poNumber', 'store_work_order_payments.forDate', 'store_work_order_payments.amount']);
       
        return [
            'outstandingPORs'=>$outstandingPORs,
            'outstandingWORs'=>$outstandingWORs,
            'outstandingRs'=>$outstandingRs,
            'paidPORs'=>$paidPORs,
            'paidWORs'=>$paidWORs,
            'pendingQuotationCount'=>$pendingQuotationCount,
            'pendingWorkOrderCount'=>$pendingWorkOrderCount,
            'vendorCount'=>$vendorCount,
            'POreminders'=>$POreminders,
            'WOreminders'=>$WOreminders,
        ];
    }

    public function updatePass(Request $request)
    {
        if($request->newPassword1 != '' && $request->newPassword2 != '')
        {
            if($request->newPassword1 == $request->newPassword2)
            {
                $user = Auth::user();
                $changePass = User::where('id', $user->id)->first();
                $changePass->password = Hash::make($request->newPassword1);
                $changePass->newUser = 1;
                $changePass->save();

                // $this->guard()->logout(); 
                $request->session()->flush();
                $request->session()->regenerate();     
            
                return redirect('/login')->with('success', 'Password Changed Successfully.');
            }
            else
            {
                return redirect()->back()->withInput()->with("error","Please enter same password in confirmation also");;
            }

        }
        else
        {
            return redirect()->back()->withInput()->with("error","Please Fill all required(*) fields...");;
        }
    }

    public function changeLanguage($type)
    {
        $user = Auth::user();
        $userId = $user->id;
        $username = $user->username;
        User::where('id', $userId)->update(['language'=>$type, 'updated_by'=>$username]);   
        return redirect('/dashboard')->with('success', 'Language Changed successfully');
    }

    public function dashboard()
    {
        $util = new Utility();
        $user = Auth::user();
        $empId = $user->empId;
        $empCode = $user->empCode;
        $userType = $user->userType;

        if($user->wishView == 0)
        {
            $newUser=User::find($user->id);
            $newUser->wishView =1;
            $newUser->save();
        }

        if($userType == '11' || $userType == '21' || $userType == '31') // 26-11-2023
        {
            $util = new Utility();
            $user = Auth::user();
            $empId = $user->empId;
            $empCode = $user->empCode;
            $userType = $user->userType;
            
            $date = now();
            $employee = EmpDet::select('branchId','DOB','startTime','reportingId','reportingType','empCode', 'firmType', 'departmentId')->where('id', $empId)->first();
           
            if($employee)
                session()->put('section', Department::where('id', $employee->departmentId)->value('section')); 
                
            $holidayList = HolidayUploadList::where('active', 1)->first();
            $repoName = User::where('empId', $employee->reportingId)->value('name');
            if($repoName == '')
                $repoName = User::where('id', $employee->reportingId)->value('name');

            $birthdays= EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
            ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
            ->select('designations.name as designationName','contactus_land_pages.branchName',
            'emp_dets.name','emp_dets.gender','emp_dets.profilePhoto','emp_dets.DOB','emp_dets.id')
            ->whereMonth('emp_dets.DOB', '>=', $date->month)
            ->whereDay('emp_dets.DOB', '>=', $date->day)
            ->where('emp_dets.active', 1)
            ->orWhere(function ($query) use ($date) {
                $query->whereMonth('emp_dets.DOB', '>=', $date->month)
                    ->whereDay('emp_dets.DOB', '>=', $date->day)
                    ->where('emp_dets.active',  1);
            })->orderBy(DB::raw("DATE_FORMAT(emp_dets.DOB,'%m-%d')"), 'asc')
            ->take(5)
            ->get();
            
            $wishes=[];
            if(date('d-m') == date('d-m', strtotime($employee->DOB)))
            {
                $wishes = BdayWish::join('emp_dets', 'bday_wishes.empId', 'emp_dets.id')
                ->join('departments', 'emp_dets.departmentId', 'departments.id')
                ->select('emp_dets.name', 'departments.name as departmentName')
                ->where('toEmpId', $empId)
                ->get();
            }
        
            $flag=0;
            $myBirthday= EmpDet::where('id',$empId)->value('DOB');
            if(date('m-Y') == date('m-Y', strtotime($myBirthday)))
                $flag=1;

            // 1] Late Mark
            $todayLateMark = AttendanceDetail::where('forDate', date('Y-m-d'))
            ->where('empId', $empId)
            ->whereIn('dayStatus', ['PL', 'WOPL', 'WOPLH'])
            ->first();

            $punchTime = AttendanceDetail::where('forDate', date('Y-m-d'))
            ->where('empId', $empId)
            ->value('inTime');
            // 2] forms & circular
            $forms = FormAndCircular::orderBy('updated_at', 'desc')
            ->whereDate('created_at', '>=', date('Y-m-d', strtotime('-2 Days')))
            ->first();
            
            $attendanceNoti = AttendanceConfirm::where('branchId', $employee->branchId)
            ->first();

            // 3] Application Update
            $empId = Auth::user()->empId;
            $application = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.type', 'emp_applications.startDate','emp_applications.status', 
            'emp_applications.updated_by', 'emp_applications.updated_at')
            ->where('emp_applications.active', 1)
            ->where('emp_applications.status', '!=', 0)
            ->where('emp_dets.id', $empId)
            ->where('emp_applications.updated_at', date('Y-m-d H:i:s'))
            ->orderBy('emp_applications.updated_at', 'desc')
            ->first();

            $holidays = HolidayDept::join('holidays', 'holiday_depts.holidayId', 'holidays.id')
            ->join('departments', 'holiday_depts.departmentId', 'departments.id')
            ->select('holidays.name', 'holidays.forDate')
            ->where('holiday_depts.active', 1)
            ->where('holidays.active', 1)
            ->where('holiday_depts.departmentId', $employee->departmentId)
            ->where('holidays.forDate', '>=', date('Y-m-d'))
            ->orderBy('holidays.forDate')
            ->take(9)
            ->get();

            // Attendance Data
            $attend = $util->getMonthlyEmpAttendance(session()->get('empCode'), date('M-Y'));
            $totDays = $attend[0];
            $presentDays = $attend[1];
            $absentDays = $attend[2];
            $extraDays = $attend[3];
            $weekoff=$attend[4];


            return view('newDashboards.employeeDashboard')->with(['employee'=>$employee, 'holidays'=>$holidays, 
            'birthdays'=>$birthdays,'forms'=>$forms, 'application'=>$application,'todayLateMark'=>$todayLateMark,
            'wishes'=>$wishes,'flag'=>$flag,'attendanceNoti'=>$attendanceNoti,'holidayList'=>$holidayList,
            'totDays'=>$totDays,'absentDays'=>$absentDays,'presentDays'=>$presentDays,'extraDays'=>$extraDays, 'weekoff'=>$weekoff, 
            'repoName'=>$repoName, 'punchTime'=>$punchTime, 'wishes'=>$wishes]);
        }

        // if($userType == '31' || $userType == '21' || $userType == '11')
        // {
        //     $date = now();
        //     $employee = EmpDet::select('branchId','DOB','startTime','reportingId','reportingType','empCode', 'firmType', 'departmentId')->where('id', $empId)->first();
           
        //     if($employee)
        //         session()->put('section', Department::where('id', $employee->departmentId)->value('section')); 
                
        //     $holidayList = HolidayUploadList::where('active', 1)->first();
        //     $repoName = User::where('empId', $employee->reportingId)->value('name');
        //     if($repoName == '')
        //         $repoName = User::where('id', $employee->reportingId)->value('name');

        //     $birthdays= EmpDet::join('designations', 'emp_dets.designationId', 'designations.id')
        //     ->join('contactus_land_pages', 'emp_dets.branchId', 'contactus_land_pages.id')
        //     ->select('designations.name as designationName','contactus_land_pages.branchName',
        //     'emp_dets.name','emp_dets.gender','emp_dets.profilePhoto','emp_dets.DOB','emp_dets.id')
        //     ->whereMonth('emp_dets.DOB', '>=', $date->month)
        //     ->whereDay('emp_dets.DOB', '>=', $date->day)
        //     ->where('emp_dets.active', 1)
        //     ->orWhere(function ($query) use ($date) {
        //         $query->whereMonth('emp_dets.DOB', '>=', $date->month)
        //             ->whereDay('emp_dets.DOB', '>=', $date->day)
        //             ->where('emp_dets.active',  1);
        //     })->orderBy(DB::raw("DATE_FORMAT(emp_dets.DOB,'%m-%d')"), 'asc')
        //     ->take(5)
        //     ->get();
            
        //     $wishes=[];
        //     if(date('d-m') == date('d-m', strtotime($employee->DOB)))
        //     {
        //         $wishes = BdayWish::join('emp_dets', 'bday_wishes.empId', 'emp_dets.id')
        //         ->join('departments', 'emp_dets.departmentId', 'departments.id')
        //         ->select('emp_dets.name', 'departments.name as departmentName')
        //         ->where('toEmpId', $empId)
        //         ->get();
        //     }
        
        //     $flag=0;
        //     $myBirthday= EmpDet::where('id',$empId)->value('DOB');
        //     if(date('m-Y') == date('m-Y', strtotime($myBirthday)))
        //         $flag=1;

        //     // 1] Late Mark
        //     $todayLateMark = AttendanceDetail::where('forDate', date('Y-m-d'))
        //     ->where('empId', $empId)
        //     ->whereIn('dayStatus', ['PL', 'WOPL', 'WOPLH'])
        //     ->first();

        //     $punchTime = AttendanceDetail::where('forDate', date('Y-m-d'))
        //     ->where('empId', $empId)
        //     ->value('inTime');
        //     // 2] forms & circular
        //     $forms = FormAndCircular::orderBy('updated_at', 'desc')
        //     ->whereDate('created_at', '>=', date('Y-m-d', strtotime('-2 Days')))
        //     ->first();
            
        //     $attendanceNoti = AttendanceConfirm::where('branchId', $employee->branchId)
        //     ->first();

        //     // 3] Application Update
        //     $empId = Auth::user()->empId;
        //     $application = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        //     ->select('emp_applications.type', 'emp_applications.startDate','emp_applications.status', 
        //     'emp_applications.updated_by', 'emp_applications.updated_at')
        //     ->where('emp_applications.active', 1)
        //     ->where('emp_applications.status', '!=', 0)
        //     ->where('emp_dets.id', $empId)
        //     ->where('emp_applications.updated_at', date('Y-m-d H:i:s'))
        //     ->orderBy('emp_applications.updated_at', 'desc')
        //     ->first();

        //     $holidays = HolidayDept::join('holidays', 'holiday_depts.holidayId', 'holidays.id')
        //     ->join('departments', 'holiday_depts.departmentId', 'departments.id')
        //     ->select('holidays.name', 'holidays.forDate')
        //     ->where('holiday_depts.active', 1)
        //     ->where('holidays.active', 1)
        //     ->where('holiday_depts.departmentId', $employee->departmentId)
        //     ->where('holidays.forDate', '>=', date('Y-m-d'))
        //     ->orderBy('holidays.forDate')
        //     ->take(9)
        //     ->get();

        //     // Attendance Data
        //     $attend = $util->getMonthlyEmpAttendance(session()->get('empCode'), date('M-Y'));
        //     $totDays = $attend[0];
        //     $presentDays = $attend[1];
        //     $absentDays = $attend[2];
        //     $extraDays = $attend[3];

        //     return view('dashboards.employeeHRMSDashboard')->with(['employee'=>$employee, 'holidays'=>$holidays, 
        //     'birthdays'=>$birthdays,'forms'=>$forms, 'application'=>$application,'todayLateMark'=>$todayLateMark,
        //     'wishes'=>$wishes,'flag'=>$flag,'attendanceNoti'=>$attendanceNoti,'holidayList'=>$holidayList,
        //     'totDays'=>$totDays,'absentDays'=>$absentDays,'presentDays'=>$presentDays,'extraDays'=>$extraDays, 
        //     'repoName'=>$repoName, 'punchTime'=>$punchTime, 'wishes'=>$wishes]);
        // }

        if($userType == '51')
        {
            return 'HR Department';
        }

        if($userType == '61')
        {
            return 'Account Department';
        }

        if($userType == '71')
        {
            return 'IT Department';
        }

        if($userType == '81')
        {
            return 'ERP Department';
        }

        if($userType == '91')
        {
            return 'Store Department';
        }
    }

    public function ratingUs(Request $request)
    {
        if($request->feedback == '' || $request->ratingVal == '')
            return redirect()->back()->withInput()->with("error","Please give Star Rating and Feedback also....");

        $rating = new EmpRating;
        $rating->empId = session()->get('empCode');
        $rating->remark = $request->feedback;
        $rating->updated_by = Auth::user()->username;
        if($request->ratingVal == 1)
            $rating->star1=1;
        elseif($request->ratingVal == 2)
            $rating->star2=1;
        elseif($request->ratingVal == 3)
            $rating->star3=1;
        elseif($request->ratingVal == 4)
            $rating->star4=1;
        else
            $rating->star5=1;

        $rating->save();

        return redirect('/dashboard')->with("success","Rating Updated Successfully..");
    }

    public function getBioMetricStatus(Request $request)
    {
        $machineId = $request->machineId;
        $forDate = $request->forDate;
        if($forDate == '')
            $forDate = date('Y-m-d');

        $machines = BiometricMachine::where('active', 1)->pluck('deviceShortName', 'id');
        
        $bioMetricStatus = BioMetricStatus::join('biometric_machines', 'bio_metric_statuses.machineId','biometric_machines.id')
        ->Select('biometric_machines.deviceShortName as machineName', 'biometric_machines.serialNo', 'bio_metric_statuses.*')
        ->where('bio_metric_statuses.active', 1)
        ->whereDate('bio_metric_statuses.created_at', $forDate);

        if($machineId != '')
            $bioMetricStatus = $bioMetricStatus->where('bio_metric_statuses.machineId', $machineId);

        $bioMetricStatus = $bioMetricStatus->get();

        return view('admin.users.bioMetricStatus')->with(['machines'=>$machines,'forDate'=>$forDate,'machineId'=>$machineId,'bioMetricStatus'=>$bioMetricStatus]);
    }

    public function getBranchDetails($name)
    {
        return ContactusLandPage::where('branchName', $name)->first();
    }

    public function getCities($regionId)
    {
        return City::where('regionId', $regionId)->get();
    }

    public function attendance()
    {
        return view('admin.attendance.list');
    }

    public function payslip()
    {
        return view('admin.payslips.list');
    } 

    public function leave()
    {
        return view('admin.leaves.list');
    }

    public function empChangePassword()
    {
        return view('auth.passwords.reset');
    }

    public function commonChanges(Request $request)
    {
        $common = CommonForm::where('active', 1)->first();
        return view('admin.commonForm')->with(['common'=>$common]);
    }

    public function updateCommonChanges(Request $request)
    {
        $common = CommonForm::where('active', 1)->update(['active'=>'0']);
        
        $common = new CommonForm;
        $common->AGFLastDate = $request->AGFLastDate;
        $common->AGFAuthrityLastDate = $request->AGFAuthrityLastDate;
        $common->AGFFromDate = $request->AGFFromDate;
        $common->AGFToDate = $request->AGFToDate;
        $common->teachingNoticePer = $request->teachingNoticePer;
        $common->nonTeachingNoticePer = $request->nonTeachingNoticePer;
        $common->ccEmailId1 = $request->ccEmailId1;
        $common->ccEmailId2 = $request->ccEmailId2;
        $common->ccEmailId3 = $request->ccEmailId3;
        $common->updated_by = Auth::user()->username;
        $common->save();
        return redirect('/home')->with("success","Information Updated successfully..");
    }
    
    public function getEmpTodayLateMark($empId)
    {
        return $tend = AttendanceDetail::where('forDate', date('Y-m-d'))
        ->where('empId', $empId)
        ->whereIn('dayStatus', ['PL', 'WOPL', 'WOPLH'])
        ->first();
    }

    public function notificationList()
    {
        $user = Auth::user();
        $empId = $user->empId;
        $userType = $user->userType;
        $util = new Utility();
   
        if($userType == '61' || $userType == '51' || $userType == '21' || $userType == '11')
        {
            $personalApplications = $util->getPersonalNotifications();
            $applications = $util->getNotifications();
            return view('partials.notificationList')->with(['personalApplications'=>$personalApplications,'applications'=>$applications]);
        }
        else
        {
            $personalApplications = $util->getPersonalNotifications();
            return view('partials.notificationList')->with(['personalApplications'=>$personalApplications]);
        }
    }

    public function getNotificationCount()
    {
        $user = Auth::user();
        $empId = $user->empId;
        $userType = $user->userType;
        
        $util = new Utility();
        $appCount=0; 
        if($empId != 0)
            $appCount = count($util->getNotifications()) +  count($util->getPersonalNotifications());
        else
        {
            if($userType == '51')
                $appCount = count($util->getNotifications());
            else
                $appCount=0;
        }

        return $appCount;
    }

    public function getNotificationMinAgo()
    {
        // $appCount = count($util->getNotifications()) +  count($util->getPersonalNotifications());
        $user = Auth::user();
        $empId = $user->empId;
        $userType = $user->userType;
        
        $util = new Utility();
        $appCount=0; 
        if($empId != 0)
            $appCount = $util->getNotifications();
        else
        {
            if($userType == '51')
                $appCount = count($util->getNotifications());
            else
                $appCount=0;
        }

        return $util->getNotificationsMinAge();
    }

    public function multiLogin()
    {
        return view('admin.multiLogin');
    }

    public function setMultiLogin(Request $request)
    {
        $crUser = Auth::user();
        $CrUserType = $crUser->userType;

        $user = User::select('id','username','userType', 'active')
        ->where('username', $request->username)
        ->where('userType', '!=', '00')
        ->first();
        if(!$user)
            return redirect()->back()->with("error","Invalid username");

        if(Auth::loginUsingId($user->id)) 
        {   
            $username = $user->username;
            $user = Auth::user();
            $username = $user->username;
            $userType = $user->userType;
            $userRole = UserRole::where('id', $user->userRoleId)->value('name');
            $request->session()->put('username', $username);               
            $request->session()->put('userType', $userType);               
            $request->session()->put('userRole', $userRole);
            if($userType == '31' || $userType == '21' || $userType == '11')
            {
                $empDet=EmpDet::where('id', $user->empId)->first();
                $request->session()->put('departmentId', $empDet->departmentId);               
                $request->session()->put('designationId', $empDet->designationId);       
                $request->session()->put('authorityName', $empDet->name);       
                $request->session()->put('profilePhoto', $empDet->profilePhoto);       
                $request->session()->put('salary', $empDet->salaryScale);       
                $request->session()->put('empCode', $empDet->empCode);       
            }   
 
            if($user->newUser == 0)
            {
                $request->session()->put('passFlag', '0');
                return redirect('/change-password');                   
            }   
            else 
            {     
                $request->session()->put('passFlag', '1');   
                $request->Session()->put('userRole', $userRole); 
                $request->Session()->put('name', $user->name); 
                if($userType == '11' || $userType == '21' || $userType == '31')
                    return redirect('/home');
                else
                    return redirect('/home');
            }
        }
        else 
        {
            return redirect()->back()->with("error","Invalid username or password!!!");
        }
    }

    public function storeHome()
    {
        $user = Auth::user();
        $userType = $user->userType;
        // if($userType == '801')
        // {
        //     $prodCount = BranchStock::where('userId', $user->id)->count();
        //     $reqCount = StoreRequisition::where('userId', $user->id)->count();
        //     $prodReturnCount = BranchStock::where('userId', $user->id)->where('stock', '!=',0)->where('returnToStore', 'Yes')->count();
        //     return view('storeHome')->with(['prodCount'=>$prodCount,'reqCount'=>$reqCount,'prodReturnCount'=>$prodReturnCount]);
        // }

        $storeData = $this->getStoreDepartmentCounts();


        if($userType == '501' || $userType == '401' || $userType == '201')
        {
            $purchaseData = $this->getPurchaseDepartmentCounts();            
            return view('storeHome', compact('storeData','purchaseData'));
            return view('storeHome')->with(['WOreminders'=>$WOreminders,'POreminders'=>$POreminders,'outstandingPORs'=>$outstandingPORs,'outstandingWORs'=>$outstandingWORs,'outstandingRs'=>$outstandingRs,
            'paidPORs'=>$paidPORs,'paidWORs'=>$paidWORs,'paidWORs'=>$paidWORs,'pendingQuotationCount'=>$pendingQuotationCount,'pendingWorkOrderCount'=>$pendingWorkOrderCount
            ,'vendorCount'=>$vendorCount,'scrapAmount'=>$scrapAmount,'category'=>$category, 'subCategory'=>$subCategory,'products'=>$products,'halls'=>$halls,'racks'=>$racks, 
            'shelfs'=>$shelfs,'units'=>$units]); 
        }

        return view('storeHome', compact('storeData'));
    }

    public function purchaseHome()
    {     
        $purchaseData = $this->getPurchaseDepartmentCounts();
        return view('purchaseHome', compact('purchaseData'));
    }

    public function commonPage()
    {
        return view('commonPage');
    }

    public function test()
    {
        return view('index-2');
    }

}