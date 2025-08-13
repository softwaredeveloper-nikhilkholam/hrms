<?php

namespace App\Exports;

use App\AttendanceDetail;
use App\Designation;
use Auth;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class AttendanceReportExport  implements FromCollection, WithHeadings, WithColumnFormatting, WithCalculatedFormulas, WithEvents
{
    private $empCode;
    private $organisation;
    private $section;
    private $branchId;
    private $month;
    
    private $days;

    public function __construct($empCode, $organisation, $section, $branchId, $month)
    {
        $this->empCode = $empCode;
        $this->organisation = $organisation;
        $this->section = $section;
        $this->branchId = $branchId;
        $this->month = $month;
        $this->rowCount = 0;
    }

    public function columnFormats(): array
    {

        return [
            'G' => NumberFormat::FORMAT_NUMBER, // Salary formatted as currency
            'K' => NumberFormat::FORMAT_NUMBER, // Salary formatted as currency
            'Q' => NumberFormat::FORMAT_NUMBER, // Bonus formatted as currency
            'R' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
            'R' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
            'AA' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
            'AB' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
            'AC' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
            'AD' => NumberFormat::FORMAT_NUMBER, // Total salary formatted as currency
        ];
    }
    
    public function collection()
    {
        $empCode=$this->empCode;
        $organisation=$this->organisation;
        $section=$this->section;
        $branchId=$this->branchId;
        $month=$this->month;

        if(date('Y-m') == $month)
            $days = date('d');
        else
            $days = date('t', strtotime($month));

        // Determine the start date, month, year, and number of days in the month
        $month = date('M', strtotime($month));
        $year = date('Y', strtotime($month));
        
        // Start building the query
        $attendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId', '=', 'emp_dets.id')
        ->join('contactus_land_pages', 'emp_dets.branchId', '=', 'contactus_land_pages.id')
        ->join('departments', 'emp_dets.departmentId', '=', 'departments.id')
        ->join('designations', 'emp_dets.designationId', '=', 'designations.id')
        ->select('attendance_details.*', 'emp_dets.name', 'emp_dets.startTime', 'emp_dets.endTime', 'emp_dets.DOB', 
                'emp_dets.firmType', 'emp_dets.jobJoingDate', 'designations.name as designationName', 'contactus_land_pages.branchName')
        ->where('attendance_details.month', $month)
        ->where('attendance_details.year', $year)
        ->where('emp_dets.jobJoingDate', '<=', date('Y-m-d'))
        ->where('emp_dets.active', 1)
        ->where('attendance_details.day', '<=', $days);
        if($empCode != '')
            $attendances = $attendances->where('emp_dets.empCode', $empCode);

        if($organisation!= '')
            $attendances = $attendances->where('emp_dets.organisation', $organisation);

        if($section!= '')
            $attendances = $attendances->where('departments.section', $section);

        if($branchId!= '')
            $attendances = $attendances->where('contactus_land_pages.id', $branchId);

        $attendances = $attendances->orderBy('attendance_details.empCode')
        ->orderBy('attendance_details.forDate')
        ->get();
        $this->rowCount = count($attendances);
        $k=0; 
        $tempA=[];
        $tempB=[];
        $tempC=[];
        $tempD=[];
        $attendanceData=[];
        $no=1;

        if(count($attendances))
        {
            foreach($attendances as $key => $attend)
            {
                if($k==0)
                {
                    $deduction=0;$wLeave=0;$sandwitchFlag=0;$tempDayStatus=0;$totDays=$lateMark=$extraW=0;
                    $tempA['no'] = $no++;
                    $tempB['no'] = '';
                    $tempC['no'] = '';
                    $tempD['no'] = '';

                    $tempA['name']="Name: ".$attend->name;
                    $tempB['name']="Emp Code: ".$attend->empCode;
                    $tempC['name']="Designation: ".$attend->designationName;
                    $tempD['name']="Office Time: ".($attend->startTime != '')?(date('H:i', strtotime($attend->startTime))." To ".date('H:i', strtotime($attend->endTime))):"NA";
                }
                $holidayFlag=$jobJoining=0;
                if($attend->jobJoingDate <= $attend->forDate)
                    $jobJoining = 0;
                else
                    $jobJoining = 1;

                if($attend->lastDate != null && $attend->lastDate < $attend->forDate)
                    $lastDay = 1;
                else
                    $lastDay = 0;

                if($attend->inTime)
                    $attend->inTime = date('H:i', strtotime($attend->inTime));
                else
                    $attend->inTime = "";

                if($attend->outTime)
                    $attend->outTime = date('H:i', strtotime($attend->outTime));
                else
                    $attend->outTime = "";

                if($attend->workingHr)
                    $attend->workingHr = round($attend->workingHr, 2);
                else
                    $attend->workingHr = "";

                if($attend->holiday != 0)
                {
                    $prev = $attendances[$key-1];
                    if(($k+1) < $days)
                        $next = $attendances[$key+1];

                    if(isset($next) && $prev)
                    {
                        $i=0;
                        while(isset($next->dayStatus) == 0 && isset($next->holiday) == 0)
                        {
                            $next = $attendances[$key+$i];
                            $i++;
                        }
                    
                        if(isset($next) && isset($prev))
                        {
                        
                            if(($prev->dayStatus == '0' || $prev->dayStatus == 'A') && ($next->dayStatus == '0' || $next->dayStatus == 'A'))
                            {    
                                if($prev->AGFStatus == 0 && $next->AGFStatus == 0 && $deduction >= 4)
                                    $holidayFlag=1;
                                else
                                    $holidayFlag=0;
                                
                            }
                        }
                    }
                }

                if($jobJoining == 0)
                {
                    if($attend->forDate == $attend->jobJoingDate)
                    {
                        $tempA['day'.$k] = "";
                        $tempB['day'.$k] = "New Joinnee";
                        $tempC['day'.$k] = "";
                        $tempD['day'.$k] = "";
                    }

                    if($lastDay == 1)
                    {
                        $tempA['day'.$k] = "";
                        $tempB['day'.$k] = "✗";
                        $tempC['day'.$k] = "";
                        $tempD['day'.$k] = "";
                    }
                    elseif($attend->empCode == '4001' || $attend->empCode == '4002' || $attend->empCode == '4003' || $attend->empCode == '4004' || $attend->empCode == '4005' || $attend->empCode == '4006')
                    {
                        $tempA['day'.$k] = ($attend->dayStatus == 'WO')?'WO':'P';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        $totDays=$totDays+1;
                    }
                    elseif($attend->dayStatus == 'WO' && (isset($attendances[$key-1]) && $attendances[$key-1]->dayStatus == 'A') && (isset($attendances[$key+3]) && $attendances[$key+3]->dayStatus == 'A'))
                    {
                        if($attendances[$key-1]->AGFStatus == 0 || $attendances[$key+3]->AGFStatus == 0)
                        {
                            $tempA['day'.$k] = 'A';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $deduction=$deduction+1;
                            $wLeave=$wLeave+1;    
                        }
                        else
                        {
                            $tempA['day'.$k] = 'WO';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $totDays=$totDays+1;
                        }
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $extraW=$extraW+1;
                                }
                                else
                                {
                                    $extraW=$extraW+0.5;
                                }
                            }
                        }                                                              
                    }
                    elseif($attend->dayStatus == 'WO' && (isset($attendances[$key-2]) && $attendances[$key-2]->dayStatus == 'A') && (isset($attendances[$key+2]) && $attendances[$key+2]->dayStatus == 'A'))
                    {
                        if($attendances[$key-2]->AGFStatus == 0 || $attendances[$key+2]->AGFStatus == 0)
                        {
                            $tempA['day'.$k] = 'A';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $deduction=$deduction+1;
                            $wLeave=$wLeave+1;  
                        }
                        else
                        {
                            $tempA['day'.$k] = 'WO';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $totDays=$totDays+1;
                        }
                        if($attend->repAuthStatus != 0)
                        {
                            $tempA['day'.$k] = $tempA['day'.$k] . '[ '.($attend->repAuthStatus != 0)?'✓':'✗';
                            $tempA['day'.$k] = $tempA['day'.$k] . ' '.($attend->HRStatus != 0)?'✓':'✗';
                            $tempA['day'.$k] = $tempA['day'.$k] . ' '.($attend->AGFStatus != 0)?'✓':'✗'. ' ]';
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $extraW=$extraW+1;
                                }
                                else
                                {
                                    $extraW=$extraW+0.5;
                                }
                            }
                        }  
                    }
                    elseif($attend->dayStatus == 'WO' && (isset($attendances[$key-3]) && $attendances[$key-3]->dayStatus == 'A') && (isset($attendances[$key+1]) && $attendances[$key+1]->dayStatus == 'A'))
                    {
                        if($attendances[$key-3]->AGFStatus == 0 || $attendances[$key+1]->AGFStatus == 0)
                        {
                            $tempA['day'.$k] = 'A';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $deduction=$deduction+1;
                            $wLeave=$wLeave+1;  
                        }
                        else
                        {
                            $tempA['day'.$k] = 'WO';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $totDays=$totDays+1;
                        }
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $extraW=$extraW+1;
                                }
                                else
                                {
                                    $extraW=$extraW+0.5;
                                }
                            }
                        }     
                    }
                    elseif($attend->dayStatus == 'WO' && isset($attendances[$key+1]) && isset($attendances[$key+2]) && isset($attendances[$key+2]))
                    {
                        if((($attendances[$key+1]->dayStatus == 'A' || $attendances[$key+1]->dayStatus == '0') && $attendances[$key+1]->AGFStatus == 0) && (($attendances[$key-1]->dayStatus == 'A' || $attendances[$key-1]->dayStatus == '0')  && $attendances[$key-1]->AGFStatus == 0))
                        {   
                            $tempA['day'.$k] = 'A';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $deduction=$deduction+1;
                            $wLeave=$wLeave+1;
                        }
                        else
                        {
                            $tempA['day'.$k] = 'WO';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $totDays=$totDays+1;
                            if($attend->repAuthStatus != 0)
                            {
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                if($attend->AGFStatus != 0)
                                {
                                    if($attend->AGFDayStatus == 'Full Day')
                                    {
                                        $extraW=$extraW+1;
                                    }
                                    else
                                    {
                                        $extraW=$extraW+0.5;
                                    }
                                }
                            }     
                        }
                    }
                    elseif($attend->paymentType == 3 && $attend->dayStatus == 'WO')
                    {                                                      
                        if($attend->halfDayTime <= $attend->workingHr)
                        {
                            $tempA['day'.$k] = 'WO';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            $totDays=$totDays+1;
                            if($attend->repAuthStatus != 0)
                            {
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                if($attend->AGFStatus != 0)
                                {
                                    if($attend->AGFDayStatus == 'Full Day')
                                    {
                                        $extraW=$extraW+1;
                                    }
                                    else
                                    {
                                        $extraW=$extraW+0.5;
                                    }
                                }
                            }     
                        }
                        else
                        {
                            $tempA['day'.$k] = 'A';
                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                            if($attend->repAuthStatus != 0)
                            {
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                if($attend->AGFStatus != 0)
                                {
                                    if($attend->AGFDayStatus == 'Full Day')
                                    {
                                        $extraW=$extraW+1;
                                    }
                                    else
                                    {
                                        $extraW=$extraW+0.5;
                                    }
                                }
                            }     
                        }
                    }
                    elseif($attend->dayStatus == 'WO' && isset($attendances[$key+1]) && isset($attendances[$key-1]) && $attendances[$key+1]->dayStatus == 'A' && ($attendances[$key-1]->dayStatus == 'A' && $attendances[$key-1]->AGFStatus == 0))
                    {
                        $tempA['day'.$k] = 'A';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        $deduction=$deduction+1;
                        $wLeave=$wLeave+1;
                    }
                    elseif(($attend->dayStatus != 'WO') && ($attend->outTime == NULL || $attend->inTime == $attend->outTime))
                    {
                        $tempA['day'.$k] = 'A';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    $totDays=$totDays+0.5;
                                }
                            }
                            else
                            {
                                $deduction=$deduction+1;
                            }
                        }
                        else
                        {
                            $deduction=$deduction+1;
                        }
                    }
                    elseif($attend->dayStatus == 'WO' && $attend->dayName == 'Sun' && $holidayFlag == 1)
                    {
                        $tempA['day'.$k] = 'A';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        $deduction=0;
                        $wLeave++;
                    }
                    elseif($attend->dayStatus == 'A')
                    {
                        $tempA['day'.$k] = 'A';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    $totDays=$totDays+0.5;
                                }
                            }
                            else
                            {
                                $deduction=$deduction+1;
                            }
                        
                        }
                        else
                        {
                            $deduction=$deduction+1;
                        }
                    }
                    elseif($attend->dayStatus == 'WO' && $attend->dayName == 'Sun') 
                    {
                        if(isset($attendances[$key-1]) && isset($attendances[$key+1]))
                        {
                            if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                            {
                                if($deduction == 3 || $deduction == 3.5)
                                {
                                    $tempA['day'.$k] = 'P/2';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                    $wLeave=$wLeave+0.5;
                                    $totDays=$totDays+0.5;
                                }
                                elseif($deduction >= 4)
                                {
                                    $tempA['day'.$k] = 'A';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                    $wLeave++; 
                                    $tempDayStatus='A';
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                                    {
                                        if($attendances[$key+1]->AGFStatus != 0 || $attendances[$key-1]->AGFStatus != 0)
                                        {
                                            $tempA['day'.$k] = 'WO';
                                            $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                            $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                            $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                            if($attend->paymentType == 3)
                                            {
                                                $totDays=$totDays+0.5;   
                                            }
                                            elseif($attend->paymentType == 2)
                                            {
                                                $totDays=$totDays+0;  
                                            }
                                            else
                                            { 
                                                $totDays=$totDays+1; 
                                            }
                                        }
                                        else
                                        {
                                            if($attend->dayStatus == 'WO')
                                            {
                                                $tempA['day'.$k] = 'WO';
                                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                                if($attend->paymentType == 3)
                                                {
                                                    $totDays=$totDays+0.5;
                                                }   
                                                elseif($attend->paymentType == 2)
                                                {
                                                    $totDays=$totDays+0; 
                                                }
                                                else
                                                { 
                                                    $totDays=$totDays+1; 
                                                }
                                            }
                                            else
                                            {
                                                $tempA['day'.$k] = 'A';
                                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                                $wLeave++;
                                                $sandwitchFlag++;
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $tempA['day'.$k] = 'WO';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        if($attend->paymentType == 3)
                                        {
                                            $totDays=$totDays+0.5; 
                                        }
                                        elseif($attend->paymentType == 2)
                                        {
                                            $totDays=$totDays+0;  
                                        }
                                        else
                                        {
                                            $totDays=$totDays+1;
                                        }
                                    }
                                }
                                $deduction=0;
                                if($attend->repAuthStatus != 0)
                                {
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                    if($attend->AGFStatus != 0)
                                    {
                                        if($attend->AGFDayStatus == 'Full Day')
                                        {
                                            $extraW=$extraW+1;
                                        }
                                        else
                                        {
                                            $extraW=$extraW+0.5;
                                        }
                                    }
                                }     
                            }
                            else
                            { 
                                if($deduction == 3 || $deduction == 3.5)
                                {
                                    $tempA['day'.$k] = 'P/2';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                    $wLeave=$wLeave+0.5;
                                    $totDays=$totDays+0.5;
                                }
                                elseif($deduction >= 4)
                                {
                                    $tempA['day'.$k] = 'A';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                    $wLeave++; 
                                    $tempDayStatus='A';
                                }
                                else
                                { 
                                    if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                                    {    
                                        $tempA['day'.$k] = 'A';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        $wLeave++;
                                        $sandwitchFlag++;
                                    }
                                    else
                                    { 
                                        $tempA['day'.$k] = 'WO';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        if($attend->paymentType == 3)
                                        {
                                            $totDays=$totDays+0.5;   
                                        }    
                                        elseif($attend->paymentType == 2)
                                        {
                                            $totDays=$totDays+0;
                                        }
                                        else
                                        { 
                                            $totDays=$totDays+1;
                                        }
                                    }
                                }
                                $deduction=0;
                                if($attend->repAuthStatus != 0)
                                {
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                    if($attend->AGFStatus != 0)
                                    {
                                        if($attend->AGFDayStatus == 'Full Day')
                                        {
                                            $extraW=$extraW+1;
                                        }
                                        else
                                        {
                                            $extraW=$extraW+0.5;
                                        }
                                    }
                                }     
                            }    
                        }    
                        else
                        {
                            if($deduction == 3 || $deduction == 3.5)
                            {
                                $tempA['day'.$k] = 'P/2';
                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                $wLeave=$wLeave+0.5;
                                $totDays=$totDays+0.5;
                            }
                            elseif($deduction >= 4)
                            {
                                $tempA['day'.$k] = 'A';
                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                $wLeave++; 
                                $tempDayStatus='A';
                            }
                            else
                            {
                                if(isset($attendances[$key-1]) && isset($attendances[$key+1]))
                                {
                                    if(($attendances[$key-1]->dayStatus == '0' && $attendances[$key+1]->dayStatus == '0') || ($attendances[$key-1]->outTime == NULL && ($attendances[$key+1]->dayStatus == '0' || $attendances[$key+1]->dayStatus == 'A')))
                                    {    
                                        $tempA['day'.$k] = 'WO';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        $wLeave++;
                                        $sandwitchFlag++;
                                    }
                                    else
                                    {
                                        $tempA['day'.$k] = 'WO';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        if($attend->paymentType == 3)
                                        {
                                            $totDays=$totDays+0.5;  
                                        } 
                                        elseif($attend->paymentType == 2)
                                        {
                                            $totDays=$totDays+0;  
                                        }
                                        else
                                        { 
                                            $totDays=$totDays+1;
                                        }
                                    }
                                }
                                else
                                {
                                    $tempA['day'.$k] = 'WO';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;

                                    if($attend->paymentType == 3)
                                    {
                                        $totDays=$totDays+0.5;   
                                    }
                                    elseif($attend->paymentType == 2)
                                    {
                                        $totDays=$totDays+0;
                                    }
                                    else
                                    {
                                        $totDays=$totDays+1;
                                    }
                                }
                            }
                            $deduction=0;
                            if($attend->repAuthStatus != 0)
                            {
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                if($attend->AGFStatus != 0)
                                {
                                    if($attend->AGFDayStatus == 'Full Day')
                                    {
                                        $extraW=$extraW+1;
                                    }
                                    else
                                    {
                                        $extraW=$extraW+0.5;
                                    }
                                }
                            }     
                        }  
                    }
                    elseif($attend->dayStatus == 'WO')
                    {
                        if($deduction == 5)
                        {
                            if($attend->repAuthStatus != 0)
                            {
                                if($attend->AGFStatus != 0)
                                {
                                    $tempA['day'.$k] = 'WO';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                }
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                if($attend->AGFStatus != 0)
                                {
                                    if($attend->AGFDayStatus == 'Full Day')
                                    {
                                        $extraW=$extraW+1;
                                    }
                                    else
                                    {
                                        $extraW=$extraW+0.5;
                                    }
                                }
                            }
                            else
                            {
                                $tempA['day'.$k] = 'A';
                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                $wLeave++;
                                $sandwitchFlag++;
                            }
                        }
                        else
                        {
                            if(isset($attendances[$key+1]) && isset($attendances[$key-1]))
                            {
                                if((($attendances[$key+1]->dayStatus == 'A' || $attendances[$key+1]->dayStatus == '0') && $attendances[$key+1]->AGFStatus == 0) && (($attendances[$key-1]->dayStatus == 'A' || $attendances[$key-1]->dayStatus == '0') && $attendances[$key-1]->AGFStatus == 0)){
                                    $tempA['day'.$k] = 'A';
                                    $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                    $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                    $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                    $wLeave++;
                                
                                    if($tempDayStatus == 'A' && $attendances[$key+1]->dayStatus == '0'  && $attend->dayStatus != 'WO'){
                                        $tempA['day'.$k] = 'A';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        $wLeave++;
                                        $sandwitchFlag++; $tempDayStatus = 0;
                                    }else{
                                        $tempA['day'.$k] = 'WO';
                                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                        if($attend->repAuthStatus != 0)
                                        {
                                            $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                            $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                            $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                            if($attend->AGFStatus != 0)
                                            {
                                                if($attend->AGFDayStatus == 'Full Day'){
                                                    $extraW=$extraW+1;
                                                }
                                                else{
                                                    $extraW=$extraW+0.5;
                                                }
                                            }
                                        }     
                                        if($attend->paymentType == 3){
                                            $totDays=$totDays+0.5;   
                                        }elseif($attend->paymentType == 2){
                                            $totDays=$totDays+0; 
                                        }else{ 
                                            $totDays=$totDays+1; 
                                        }
                                    }
                                }                                                              
                            }
                            else
                            {
                                $tempA['day'.$k] = 'WO';
                                $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                                $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                                $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                                if($attend->repAuthStatus != 0)
                                {
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->repAuthStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->HRStatus != 0)?'✓':'✗';
                                    $tempA['day'.$k] = $tempA['day'.$k] . '['.($attend->AGFStatus != 0)?'✓':'✗'. ']';
                                    if($attend->AGFStatus != 0)
                                    {
                                        if($attend->AGFDayStatus == 'Full Day')
                                        {
                                            $extraW=$extraW+1;
                                        }
                                        else
                                        {
                                            $extraW=$extraW+0.5;
                                        }
                                    }
                                }
                                if($attend->paymentType == 3)
                                {
                                    $totDays=$totDays+0.5;   
                                }
                                elseif($attend->paymentType == 2)
                                {
                                    $totDays=$totDays+0;  
                                }
                                else
                                {
                                    $totDays=$totDays+1;
                                }
                            } 
                        }
                    }
                    elseif($attend->dayStatus == 'P')
                    {
                        $tempA['day'.$k] = 'P';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        $totDays=$totDays+1;
                    }
                    elseif($attend->dayStatus == 'PL')
                    {
                        $tempA['day'.$k] = 'PBL';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus == 0)
                            {
                                ++$lateMark;
                            }
                        }
                        else
                        {
                            ++$lateMark;
                        }
                        $totDays=$totDays+1;
                    }
                    elseif($attend->dayStatus == 'PLH')
                    {
                        $tempA['day'.$k] = 'P/2';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        $lateMark++;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    $totDays=$totDays+0.5;
                                }
                            }
                        }
                        else
                        {
                            $totDays=$totDays+0.5;                                                            
                        }
                    }
                    elseif($attend->dayStatus == 'PH')   
                    { 
                        $tempA['day'.$k] = 'P/2';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    $totDays=$totDays+0.5;
                                }
                            }
                            else
                            {
                                $deduction=$deduction+0.5;
                                $totDays=$totDays+0.5;
                            }
                        }
                        else
                        {
                            $totDays=$totDays+0.5;
                            $deduction=$deduction+0.5;
                        }
                    }
                    else
                    {
                        $tempA['day'.$k] = 'A';
                        $tempB['day'.$k] = ($attend->inTime == '')?'-':$attend->inTime;
                        $tempC['day'.$k] = ($attend->outTime == '')?'-':$attend->outTime;
                        $tempD['day'.$k] = ($attend->workingHr == '')?'0.0':$attend->workingHr;
                        if($attend->repAuthStatus != 0)
                        {
                            if($attend->AGFStatus != 0)
                            {
                                if($attend->AGFDayStatus == 'Full Day')
                                {
                                    $totDays=$totDays+1;
                                }
                                else
                                {
                                    $totDays=$totDays+0.5;
                                }
                            }
                            else
                            {
                                $deduction=$deduction+1;
                            }
                        }
                        else
                        {
                            $deduction=$deduction+1;
                        }
                    }
                    
                    if($attend->dayName == 'Sun')
                    {
                        $deduction = 0;
                    }                                                             
                }
                else
                {
                    $tempA['day'.$k] = 'NA';
                    $tempB['day'.$k] = '';
                    $tempC['day'.$k] = '';
                    $tempD['day'.$k] = '';
                }
                
                $k++;
                if($k == $days)
                {
                    $lateMark=((int)($lateMark/3)); 
                    $tempA['totPresent'] = $totDays-$lateMark;
                    $tempB['totPresent'] = '';
                    $tempC['totPresent'] = '';
                    $tempD['totPresent'] = '';

                    $tempA['totAbsent'] = $days-$totDays-$wLeave;
                    $tempB['totAbsent'] = '';
                    $tempC['totAbsent'] = '';
                    $tempD['totAbsent'] = '';

                    $tempA['totWLeave'] = $wLeave+$lateMark;
                    $tempB['totWLeave'] = '';
                    $tempC['totWLeave'] = '';
                    $tempD['totWLeave'] = '';

                    $tempA['extraWork'] = $extraW;
                    $tempB['extraWork'] = '';
                    $tempC['extraWork'] = '';
                    $tempD['extraWork'] = '';

                    $tempA['total'] = ($totDays-$lateMark)+($extraW);
                    $tempB['total'] = '';
                    $tempC['total'] = '';
                    $tempD['total'] = '';

                    $tempA['status'] = ($attend->salaryHoldRelease == 1)?'Hold':'Release';
                    $tempB['status'] = '';
                    $tempC['status'] = '';
                    $tempD['status'] = '';

                    $k=0;
                    array_push($attendanceData, $tempA);
                    array_push($attendanceData, $tempB);
                    array_push($attendanceData, $tempC);
                    array_push($attendanceData, $tempD);
                }
            }

        }
        $rowCount = count($attendanceData)*4;
        
        return $attendances = collect($attendanceData);

    }

   
    public function headings(): array
    {
        $month=$this->month;

        if($month == date('Y-m'))
            $days = date('d');
        else
            $days = date('t', strtotime($month));

        $tpArray1=$tpArray2=$tpArray3=[];

        $tpArray1 = [];
        array_push($tpArray1, "sr. No.");
        array_push($tpArray1, "Employee");
        for($k=1; $k<=$days; $k++)
        {
            array_push($tpArray1, $k);
        }
      
        array_push($tpArray1, "Present");
        array_push($tpArray1, "Absent");
        array_push($tpArray1, "WL");
        array_push($tpArray1, "Extra");
        array_push($tpArray1, "Total");
        array_push($tpArray1, "Status");
       
        return $tpArray1;
    }

    public function styles(Worksheet $sheet)
    {
      // Column B (2nd column) - Left Align
        $sheet->getStyle("B2:B{$rowCount}")
        ->getAlignment()
        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        // All other columns - Center Align
        $columns = range('A', 'AM');
        foreach ($columns as $col) {
        if ($col !== 'B') {
            $sheet->getStyle("{$col}2:{$col}{$rowCount}")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()
                ->getStyle('A1:AM1')                                
                ->getFont()
                ->setSize(12)
                ->setBold(true)
                ->getColor()
                ->setARGB('DD4B39');


                $cells = 'A1:AD'.($this->rowCount+2);
                $event->sheet->getStyle($cells)->getAlignment()->setHorizontal('left');

                $event->sheet->getDelegate()->freezePane('A2');
                $event->sheet->getDelegate()->freezePane('B1');
                $event->sheet->getDelegate()->freezePane('C1');

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                $event->sheet->getStyle('A1:AM1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
                
                $cells = 'A1:AM'.($this->rowCount+2);
                $event->sheet->getStyle($cells)->applyFromArray($styleArray);

                $cells1 = 'B1:I'.($this->rowCount+2);
                $cells2 = 'J1:AC'.($this->rowCount+2);
                $event->sheet->getStyle($cells1)->getAlignment()->setHorizontal('left');
                $event->sheet->getStyle($cells2)->getAlignment()->setHorizontal('center');
               

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('G')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('H')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('I')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('J')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('K')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('L')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('M')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('N')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('O')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('P')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Q')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('R')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('S')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('T')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('U')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('V')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('W')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('X')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Y')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('Z')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AA')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AB')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AC')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AD')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AE')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AF')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AG')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AH')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AI')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AJ')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AK')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AL')->getFont()->setName('Times New Roman');
                $event->sheet->getDelegate()->getStyle('AM')->getFont()->setName('Times New Roman');

                $event->sheet->getDelegate()->getStyle('A')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('B')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('C')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('D')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('E')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('F')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('G')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('H')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('I')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('J')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('K')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('L')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('M')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('N')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('O')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('P')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('Q')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('R')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('S')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('T')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('U')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('V')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('W')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('X')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('Y')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('Z')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AA')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AB')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AC')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AD')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AE')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AF')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AG')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AH')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AI')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AJ')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AK')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AL')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('AM')->getFont()->setSize(14);

                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getColumnDimension('J')->setAutoSize(true);
                $event->sheet->getColumnDimension('K')->setAutoSize(true);
                $event->sheet->getColumnDimension('L')->setAutoSize(true);
                $event->sheet->getColumnDimension('M')->setAutoSize(true);
                $event->sheet->getColumnDimension('N')->setAutoSize(true);
                $event->sheet->getColumnDimension('O')->setAutoSize(true);
                $event->sheet->getColumnDimension('P')->setAutoSize(true);
                $event->sheet->getColumnDimension('Q')->setAutoSize(true);
                $event->sheet->getColumnDimension('R')->setAutoSize(true);
                $event->sheet->getColumnDimension('S')->setAutoSize(true);
                $event->sheet->getColumnDimension('T')->setAutoSize(true);
                $event->sheet->getColumnDimension('U')->setAutoSize(true);
                $event->sheet->getColumnDimension('V')->setAutoSize(true);
                $event->sheet->getColumnDimension('W')->setAutoSize(true);
                $event->sheet->getColumnDimension('X')->setAutoSize(true);
                $event->sheet->getColumnDimension('Y')->setAutoSize(true);
                $event->sheet->getColumnDimension('Z')->setAutoSize(true);
                $event->sheet->getColumnDimension('AA')->setAutoSize(true);
                $event->sheet->getColumnDimension('AB')->setAutoSize(true);
                $event->sheet->getColumnDimension('AC')->setAutoSize(true);
                $event->sheet->getColumnDimension('AD')->setAutoSize(true);
                $event->sheet->getColumnDimension('AE')->setAutoSize(true);
                $event->sheet->getColumnDimension('AF')->setAutoSize(true);
                $event->sheet->getColumnDimension('AG')->setAutoSize(true);
                $event->sheet->getColumnDimension('AH')->setAutoSize(true);
                $event->sheet->getColumnDimension('AI')->setAutoSize(true);
                $event->sheet->getColumnDimension('AJ')->setAutoSize(true);
                $event->sheet->getColumnDimension('AK')->setAutoSize(true);
                $event->sheet->getColumnDimension('AL')->setAutoSize(true);
                $event->sheet->getColumnDimension('AM')->setAutoSize(true);
            },
        ];
    }
}