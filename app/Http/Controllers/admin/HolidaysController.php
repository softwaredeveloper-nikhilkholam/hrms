<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\Utility;
use App\Holiday;
use App\HolidayDept;
use App\Department;
use App\Designation;
use App\AttendanceDetail;
use App\ContactusLandPage;
use App\EmpDet;
use App\User;
use App\EmpApplication;
use App\AttendanceLog;
use App\HolidayUploadList;
use App\HrPolicy;
use App\LogTimeOld;
use App\Appraisal;
use App\Retention;
use DB;
use Auth;


use App\CommonForm;
use DateTime;

class HolidaysController extends Controller
{
    public function index()
    {
        // $appraisals = Appraisal::where('month', '2025-04')->get();
        // foreach($appraisals as $app)
        // {
        //     $empDet = EmpDet::Where('id', $app->empId)->first();
        //     $section = Department::Where('id', $empDet->departmentId)->value('section');
        //     if($app->hikeRs != 0 && $section == 'Teaching')
        //     {
        //         $retention = new Retention;
        //         $retention->empId = $app->empId;
        //         $retention->retentionAmount =  $app->hikeRs / 2;
        //         $retention->month = '2025-06';
        //         $retention->remark = "Retention for the month of Jun 2025";
        //         $retention->updated_by = Auth::user()->username;
        //         $retention->save();
        //     }
        // }

        $holidays = Holiday::where('forDate', '>=',date('Y-m-01'))->where('forDate', '<=',date('Y-m-t'))->orderBy('forDate')->get();
        return view('admin.holidays.list')->with(['holidays'=>$holidays]);
    }

    public function dlist()
    {
        $holidays = Holiday::where('forDate', '<', date('Y-m-d'))->orderBy('forDate')->get();
        return view('admin.holidays.dlist')->with(['holidays'=>$holidays]);
    }

    public function create(Request $request)
    {
        $departments = Department::whereActive(1)->orderBy('name')->get(['name', 'id']);
        $branches = ContactusLandPage::whereActive(1)->orderBy('branchName')->get(['branchName', 'id']);
        $branchIds = $request->branchOption;
        $departmentIds = $request->departmentOption;
        if(!empty($branchIds) && !empty($departmentIds))
        {
            $designations = Designation::join('departments','designations.departmentId', 'departments.id')
            ->select('designations.name', 'designations.id','departments.name as departmentName')
            ->whereIn('designations.departmentId', $departmentIds)
            ->where('designations.active', 1)
            ->orderBy('departments.name')
            ->orderBy('designations.name')
            ->get();
            return view('admin.holidays.create')->with(['branchIds'=>$branchIds, 'departmentIds'=>$departmentIds,'branches'=>$branches, 'departments'=>$departments, 'designations'=>$designations]);
        }

        return view('admin.holidays.create')->with(['branches'=>$branches, 'departments'=>$departments]);
    }

    public function uploadList()
    {
        return view('admin.holidays.uploadList'); 
    }

    public function uploadHolidayList(Request $request)
    {
        if(!empty($request->file('fileName')))
        {
            $originalImage= $request->file('fileName');
            $fileName = date('dmhis').'.'.$originalImage->extension();  
            $originalImage->move(public_path('admin/holidayLists/'), $fileName);   

            HolidayUploadList::where('active', 1)->update(['active'=>'0']);

            $list = new HolidayUploadList;
            $list->fileName = $fileName;
            $list->year = $request->year;
            $list->updated_by = Auth::user()->username;
            $list->save();
        } 
        return redirect('/holidays')->with("success","Holiday List Uploaded Successfully..");
    }

    public function store(Request $request)
    {
        if (empty($request->branchIds) && empty($request->departmentIds) && empty($request->designationOption)) {
            return redirect()->back()->withInput()->with("error", "Please select at least one Branch, Department, and Designation.");
        }
    
        $forDate = $request->forDate;
    
        // Fetch employees based on active status and filters
        $employees = EmpDet::where('active', 1)
            ->when(!empty($request->branchIds), function ($query) use ($request) {
                return $query->whereIn('branchId', $request->branchIds);
            })
            ->when(!empty($request->designationOption), function ($query) use ($request) {
                return $query->whereIn('designationId', $request->designationOption);
            })
            ->get();
    
        // Store Holiday
        $holiday = Holiday::create([
            'branchIds'      => implode(",", $request->branchIds),
            'departmentIds'  => implode(",", $request->departmentIds),
            'designationIds' => implode(",", $request->designationOption),
            'branchCount'    => count($request->branchIds),
            'departmentCount'=> count($request->departmentIds),
            'designationCount'=> count($request->designationOption),
            'holidayType'    => $request->holidayType,
            'forDate'        => $forDate,
            'name'           => $request->name,
            'updated_by'     => Auth::user()->username,
        ]);
    
        // Process each employee
        foreach ($employees as $emp) {
            // Upsert HolidayDept
            HolidayDept::updateOrCreate(
                ['empId' => $emp->empId, 'forDate' => $forDate],
                [
                    'holidayId'    => $holiday->id,
                    'empCode'      => $emp->empCode,
                    'branchId'     => $emp->branchId,
                    'departmentId' => $emp->departmentId,
                    'designationId'=> $emp->designationId,
                    'paymentType'  => $request->holidayType,
                    'updated_by'   => Auth::user()->username,
                ]
            );
    
            // Update AttendanceDetail if exists
            AttendanceDetail::where('empCode', $emp->empCode)
                ->where('forDate', $forDate)
                ->update([
                    'dayStatus'   => 'WO',
                    'paymentType' => $request->holidayType,
                    'holiday'     => (date('D', strtotime($forDate)) == 'Sun') ? 1 : 2,
                ]);
        }
    
        return redirect('/holidays')->with("success", "Holiday stored successfully.");
    }
    
    public function edit($id)
    {
        $holiday = Holiday::find($id);
        $departments = Department::whereActive(1)->orderBy('name')->get(['name', 'id']);
        foreach($departments as $dept)
        {
            $checkHol = HolidayDept::where('departmentId', $dept->id)
            ->where('holidayId', $id)
            ->count();
            if($checkHol)
                $dept['status'] = 1;
            else
                $dept['status'] = 0;

        }

        return view('admin.holidays.edit')->with(['departments'=>$departments,'holiday'=>$holiday]);
    }

    public function show($id)
    {
        $holiday = Holiday::find($id);
        $designationIds = array_unique(explode(',', $holiday->designationIds));
        $branchIds = explode(',', $holiday->branchIds);
        $designations = Designation::join('departments', 'designations.departmentId', 'departments.id')
        ->whereIn('designations.id', $designationIds)
        ->where('designations.active', 1)
        ->distinct('designations.name')
        ->orderBy('departments.name')
        ->orderBy('designations.name')
        ->get(['departments.name as departmentName','designations.name', 'designations.id']);

        $branches = ContactusLandPage::whereIn('id', $branchIds)->whereActive(1)->orderBy('branchName')->get(['branchName', 'id']);
        return view('admin.holidays.show')->with(['holiday'=>$holiday,'branches'=>$branches,'designations'=>$designations]);
    }

    public function update(Request $request, $id)
    {
        $holiday = Holiday::find($id);
        $oldDate = $holiday->forDate;
        $holiday->forDate=$request->forDate;
        $holiday->name=$request->name;
        $holiday->updated_by=Auth::user()->username;
        if($holiday->save())
        {
            $deptsCt = count($request->departmentId);
            $depts = $request->departmentId;
            $holDep = HolidayDept::where('holidayId', $holiday->id)->update(['active'=>0]);
            for($i=0; $i<$deptsCt; $i++)
            {
                $holDep = HolidayDept::where('holidayId', $holiday->id)
                ->where('departmentId', $depts[$i])
                ->first();

                if(!$holDep)
                    $holDep = new HolidayDept;

                $holDep->holidayId=$holiday->id;
                $holDep->departmentId=$depts[$i];
                $holDep->updated_by=Auth::user()->username;
                $holDep->active=1;
                if($holDep->save())
                {
                    $empIds = EmpDet::where('departmentId', $depts[$i])->pluck('id');

                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $request->forDate)
                    ->where('dayStatus', '0')
                    ->update(['dayStatus'=>'WO', 'paymentType'=>1, 'holiday'=>1]);   
                    
                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $oldDate)
                    ->where('dayStatus', 'WO')
                    ->update(['dayStatus'=>'0', 'paymentType'=>1, 'holiday'=>0]);

                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $request->forDate)
                    ->where('dayStatus', 'WO')
                    ->update(['dayStatus'=>'WO', 'paymentType'=>1, 'holiday'=>1]);

                   
                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $request->forDate)
                    ->where('dayStatus', 'P')
                    ->update(['dayStatus'=>'WOP', 'paymentType'=>1, 'holiday'=>1]); 
                    
                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $oldDate)
                    ->where('dayStatus', 'WOP')
                    ->update(['dayStatus'=>'P', 'paymentType'=>1, 'holiday'=>0]); 

                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $request->forDate)
                    ->where('dayStatus', 'PL')
                    ->update(['dayStatus'=>'WOPL', 'paymentType'=>1, 'holiday'=>1]);                    
                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $oldDate)
                    ->where('dayStatus', 'WOPL')
                    ->update(['dayStatus'=>'PL', 'paymentType'=>1, 'holiday'=>0]); 
                    
                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $request->forDate)
                    ->where('dayStatus', 'PLH')
                    ->update(['dayStatus'=>'WOPH', 'paymentType'=>1, 'holiday'=>1]);  
                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $oldDate)
                    ->where('dayStatus', 'WOPL')
                    ->update(['dayStatus'=>'PL', 'paymentType'=>1, 'holiday'=>0]);                    

                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $request->forDate)
                    ->where('dayStatus', 'PH')
                    ->update(['dayStatus'=>'WOPH', 'paymentType'=>1, 'holiday'=>1]);
                    AttendanceDetail::whereIn('empId', $empIds)
                    ->where('forDate', $oldDate)
                    ->where('dayStatus', 'WOPH')
                    ->update(['dayStatus'=>'PH', 'paymentType'=>1, 'holiday'=>0]); 

                   
      
                }
            }
        }
        return redirect('/holidays')->with("success","Holiday updated successfully..");
    }

    public function activate($id)
    {
        $id=$request->id;
        $holiday = Holiday::find($id);
        HolidayDept::where('holidayId', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        Holiday::where('id', $id)->update(['active'=>1, 'updated_by'=>Auth::user()->username]);
        
        $empCodes = HolidayDept::where('holidayId', $id)->where('active', 1)->pluck('empCode');
        AttendanceDetail::whereIn('empCode', $empCodes)
        ->where('forDate', $holiday->forDate)
        ->where('dayStatus', 'A')
        ->update(['dayStatus'=>'WO', 'paymentType'=>1, 'holiday'=>1]);  

        AttendanceDetail::whereIn('empCode', $empCodes)
        ->where('forDate', $holiday->forDate)
        ->where('dayStatus', '0')
        ->update(['dayStatus'=>'WO', 'paymentType'=>1, 'holiday'=>1]);  

        AttendanceDetail::whereIn('empCode', $empCodes)
        ->where('forDate', $holiday->forDate)
        ->where('dayStatus', 'P')
        ->update(['dayStatus'=>'WOP', 'paymentType'=>1, 'holiday'=>1]);  

        AttendanceDetail::whereIn('empCode', $empCodes)
        ->where('forDate', $holiday->forDate)
        ->where('dayStatus', 'PL')
        ->update(['dayStatus'=>'WOPL', 'paymentType'=>1, 'holiday'=>1]);  

        AttendanceDetail::whereIn('empCode', $empCodes)
        ->where('forDate', $holiday->forDate)
        ->where('dayStatus', 'PH')
        ->update(['dayStatus'=>'WOPH', 'paymentType'=>1, 'holiday'=>1]);  

        AttendanceDetail::whereIn('empCode', $empCodes)
        ->where('forDate', $holiday->forDate)
        ->where('dayStatus', 'PLH')
        ->update(['dayStatus'=>'WOPLH', 'paymentType'=>1, 'holiday'=>1]);  
        
        return redirect('/holidays')->with("success","Holiday Activated successfully..");
    }

    public function deactivate($id)
    {
        // $id=$request->id;
        $holiday = Holiday::find($id);
        HolidayDept::where('holidayId', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        Holiday::where('id', $id)->update(['active'=>0, 'updated_by'=>Auth::user()->username]);
        
        // $empCodes = HolidayDept::where('holidayId', $id)->where('active', 1)->pluck('empCode');
        AttendanceDetail::where('forDate', $holiday->forDate)
        ->where('dayStatus', 'WO')
        ->update(['dayStatus'=>'A', 'paymentType'=>1, 'holiday'=>0]);  

        AttendanceDetail::where('forDate', $holiday->forDate)
        ->where('forDate', $holiday->forDate)
        ->where('dayStatus', 'WOP')
        ->update(['dayStatus'=>'WOP', 'paymentType'=>1, 'holiday'=>0]);  

        AttendanceDetail::where('forDate', $holiday->forDate)
        ->where('forDate', $holiday->forDate)
        ->where('dayStatus', 'WOPL')
        ->update(['dayStatus'=>'PL', 'paymentType'=>1, 'holiday'=>0]);  

        AttendanceDetail::where('forDate', $holiday->forDate)
        ->where('forDate', $holiday->forDate)
        ->where('dayStatus', 'WOPH')
        ->update(['dayStatus'=>'PH', 'paymentType'=>1, 'holiday'=>0]);  

        AttendanceDetail::where('forDate', $holiday->forDate)
        ->where('forDate', $holiday->forDate)
        ->where('dayStatus', 'WOPLH')
        ->update(['dayStatus'=>'PLH', 'paymentType'=>1, 'holiday'=>0]);  

        return redirect('/holidays')->with("success","Holiday Deactivated successfully..");        
    }

    public function holidayList()
    {
        $empId= Auth::user()->empId;
        $empCode= EmpDet::where('id', $empId)->value('empCode');
        $holidays = HolidayDept::join('holidays', 'holiday_depts.departmentId', 'holidays.id')
        ->select('holidays.name', 'holidays.forDate')
        ->where('holiday_depts.active', 1)
        ->where('holidays.active', 1)
        ->where('holiday_depts.empCode', $empCode)
        ->where('holidays.forDate', '>=', date('Y-m-d'))
        ->orderBy('holidays.forDate')
        ->paginate(10);
        return view('admin.holidays.holidayList')->with(['holidays'=>$holidays]);
    }
}
