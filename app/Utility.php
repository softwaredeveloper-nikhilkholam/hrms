<?php

namespace App\Helpers;

use Excel;
use Auth;
use Response;
use App\EmpApplication;
use App\AttendanceDetail;
use App\BiometricMachine;
use App\AttendanceLog;
use App\EmpDet;
use App\HolidayDept;
use App\HrPolicy;
use App\SalarySheet;
use App\AttendanceOldLog;
use App\EmployeeLetter;
use DateTime;
use DateInterval;
use DatePeriod;
class Utility
{ 
    public function getLastSalary()
    {
        $emps = SalarySheet::whereActive(1)->where('month',  date('Y-m', strtotime('-1 month')))->get();
        $days = date('t', strtotime('-1 month'));
        $totSalary=0;
        if(count($emps))
        {
            foreach($emps as $emp)
            {
                if($emp->grossSalary != 0)
                {
                    $perDay = $emp->grossSalary / $days;
                    $totSalary += $emp->totalDays*$perDay;
                }
            }
        }
                
        return $totSalary;
    }

    public function getExpectedSalary()
    {
        $attendances = AttendanceDetail::join('emp_dets', 'attendance_details.empId','emp_dets.id')
        ->select('attendance_details.dayStatus', 'emp_dets.salaryScale')
        ->where('attendance_details.active',1)
        ->where('attendance_details.forDate',  '>=', date('Y-m-01'))
        ->where('attendance_details.forDate',  '<=', date('Y-m-d'))
        ->whereIn('attendance_details.dayStatus', ['P','PH','WO','WOP','WOPH'])
        ->orderBy('attendance_details.empId')
        ->orderBy('attendance_details.forDate')
        ->get();

        // P 1
        // PH 0.5
        // WO 1
        // WOP 2
        // WOPH 1.5
        $totExp = 0;
        $days = date('t');
        foreach($attendances as $attend)
        {
            if($attend->salaryScale != 0)
            {
                $perDay = $attend->salaryScale / $days;
                if($attend->dayStatus == 'P')
                    $totExp += $perDay;
                elseif($attend->dayStatus == 'PH')
                    $totExp += $perDay/2;
                elseif($attend->dayStatus == 'WO')
                    $totExp += $perDay;
                elseif($attend->dayStatus == 'WOP')
                    $totExp += $perDay*2;
                elseif($attend->dayStatus == 'WOPH')
                    $totExp += $perDay + $perDay/2;
            }
        }

        return $totExp;
    }

    public function setOfficeTime($empId, $fromTime, $toTime)
    {
        AttendanceDetail::where('empId', $empId)
        ->where('forDate', '>=', $fromTime)
        ->where('forDate', '<=', $toTime)
        ->update(['dayStatus'=>'0', 'inTime'=>'0', 'outTime'=>'0', 'workingHr'=>'0','AGFStatus'=>'0']);

        $temps = AttendanceDetail::where('empId', $empId)
        ->where('month', 'Jan')
        ->where('dayStatus', '!=', 'WO')
        ->orderBy('day')
        ->get();
        foreach($temps as $tp)
        {
            $temp = AttendanceDetail::find($tp->id);
        
            $rule4 = HrPolicy::where('name', 'Rule 4')->where('active', 1)->first();
            if($rule4)
            {    
                $finalLateTime = date('H:i', strtotime('+'.$rule4->temp1.' min', strtotime($fromTime)));
                $firstHalf = date('H:i', strtotime('+'.$rule4->temp7.' hour', strtotime($fromTime)));
                $secondHalf = date('H:i', strtotime('-'.$rule4->temp7.' hour', strtotime($toTime)));
            }

            $temp->dayStatus=0;
    
            if($temp->dayStatus == "WO")
            {
                $temp->inTime = $log->logDateTime; 
                if(strtotime($log->logTime) > strtotime($finalLateTime))
                {  
                    if(strtotime($log->logTime) > strtotime($firstHalf))
                    { 
                        $temp->dayStatus = 'WOPH'; 
                        $temp->extraWorkingDay = 0.5;
                    }
                    else
                    {
                        $temp->dayStatus = 'WOPL'; 
                        $temp->extraWorkingDay = 1;
                        $temp->lateMarkDay = 1;
                    }
                }
                else
                {
                    $temp->dayStatus = 'WOP'; 
                    $temp->extraWorkingDay = 1;
                }
                
            }
            elseif($temp->dayStatus == "0")
            {
                if(strtotime($log->logTime) > strtotime($finalLateTime))
                {
                    if(strtotime($log->logTime) > strtotime($firstHalf))
                    { 
                        $temp->dayStatus = 'PH'; 
                    }
                    else
                    {
                        $temp->dayStatus = 'PL'; 
                        $temp->lateMarkDay = 1;
                    }
                }
                else
                    $temp->dayStatus = 'P'; 
    
                $temp->inTime = $log->logDateTime; 
            }
            elseif($temp->dayStatus == 'WOPH' || $temp->dayStatus == 'WOPLH' || $temp->dayStatus == 'PLH' || $temp->dayStatus == 'WOPH' || $temp->dayStatus == 'PH' || $temp->dayStatus == 'P' || $temp->dayStatus == 'WOP' || $temp->dayStatus == 'PL' || $temp->dayStatus == 'WOPL')
            {
                $util= new Utility;
                $temp->outTime = $log->logDateTime; 
                if($temp->outTime != "0" && $temp->inTime != "0")
                {
                    $temp->workingHr = $util->timeDiff($temp->outTime, $temp->inTime);
                    if(strtotime($log->logTime) < strtotime($secondHalf))
                    { 
                        if($temp->dayStatus == 'P')
                            $temp->dayStatus = 'PH'; 
                        elseif($temp->dayStatus == 'PL')
                        {
                            $temp->dayStatus = 'PLH'; 
                            $temp->lateMarkDay = 1;
                        }
                        elseif($temp->dayStatus == 'WOP')
                        {
                            $temp->dayStatus = 'WOPH'; 
                            $temp->extraWorkingDay = 0.5;
                        }
                        elseif($temp->dayStatus == 'WOPL')
                        {
                            $temp->dayStatus = 'WOPLH'; 
                            $temp->extraWorkingDay = 1;
                            $temp->lateMarkDay = 1;
                        }
                    }
                    else
                    {
                        if($temp->dayStatus == 'PH')
                            $temp->dayStatus = 'P'; 
                        elseif($temp->dayStatus == 'PLH')
                        {
                            $temp->dayStatus = 'PL'; 
                            $temp->lateMarkDay = 1;
                        }
                        elseif($temp->dayStatus == 'WOPH')
                        {
                            $temp->dayStatus = 'WOP'; 
                            $temp->extraWorkingDay = 1;
                        }
                        elseif($temp->dayStatus == 'WOPLH')
                        {
                            $temp->dayStatus = 'WOPL'; 
                            $temp->extraWorkingDay = 1;     
                            $temp->lateMarkDay = 1;                               
                        }
                    }
                }
            }
    
            $temp->save();
        }
    }

    //call every 5 mins
    public function getTodayBiometricData()
    {
        $machines = BiometricMachine::whereActive(1)->get();

        if(count($machines))
        {
            foreach($machines as $machine)
            {
                // $yesterday = date('Y-m-d', strtotime('-4 days', strtotime(date('Y-m-d'))));
                $yesterday = date('2022-07-01');
                // $today = $yesterday = date('Y-m-d');
                $today = date('Y-m-d');
            
                $soapUrl = "http://45.250.225.45:888/iclock/webapiservice.asmx"; 
                $soapUser = "HRMS";  //  username
                $soapPassword = "hrms"; // password

                $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                    <soap12:Body>
                                        <GetTransactionsLog xmlns="http://tempuri.org/">';
                $xml_post_string .= '<FromDate>'.$yesterday.'</FromDate>';
                $xml_post_string .= '<ToDate>'.$today.'</ToDate>';
                $xml_post_string .= '<SerialNumber>'.$machine->serialNo.'</SerialNumber>';
                $xml_post_string .= '<UserName>HRMS</UserName>
                                    <UserPassword>hrms</UserPassword>
                                    <strDataList></strDataList>
                                    </GetTransactionsLog>
                                    </soap12:Body>
                                    </soap12:Envelope>';   

                $headers = array(
                            "Content-type: text/xml;charset=\"utf-8\"",
                            "Accept: text/xml",
                            "Cache-Control: no-cache",
                            "Pragma: no-cache",
                            "Content-length: ".strlen($xml_post_string),
                        ); //SOAPAction: your op URL

                // PHP cURL  for https connection with auth
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_URL, $soapUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($ch); 
                curl_close($ch);

                $Json = json_encode($response);
                if($Json != 'false')
                {
                    $d= strstr($Json, '<\/GetTransactionsLogResult>');
                    $d1= strstr($d, '>');
            
                    $response1 = str_replace('\t'," ",$d1);
                    $response2 = str_replace('\r\n'," ",$response1);
                    $response3 = str_replace('<\/strDataList><\/GetTransactionsLogResponse><\/soap:Body><\/soap:Envelope>"',"",$response2);
                    $finalResp = explode(" ",$response3);
                    $ct = count($finalResp);
                        
                    $m=0;
                    $firstId = 0;
                    for($k=0; $k<$ct; $k++)
                    {
                        $m=$k; 
                        if(isset($finalResp[$m]) && isset($finalResp[$m+1]) && isset($finalResp[$m+2]))
                        {
                            $attLog = AttendanceLog::where('employeeCode', $finalResp[$m])
                            ->where('logDate', $finalResp[$m+1])
                            ->where('logTime', $finalResp[$m+2])
                            ->first();
                            if(!$attLog)
                                $attLog = new AttendanceLog;

                            $attLog->employeeCode=$finalResp[$m];
                            $attLog->logDateTime=$finalResp[$m+1].' '.$finalResp[$m+2];
                            $attLog->logDate=$finalResp[$m+1];
                            $attLog->logTime=$finalResp[$m+2];
                            $attLog->deviceShortName=$machine->deviceShortName;
                            $attLog->serialNo=$machine->serialNo;
                            $attLog->save();
                            if($firstId == 0)
                                $firstId=$attLog->id;
            
                            $k=$m+2;
                            $m=0;
                        }
                    }
            
                    $firstD = AttendanceLog::find($firstId);
                    if($firstD)
                    {
                        $firstD->employeeCode = str_replace('><strDataList>',"",$firstD->employeeCode);
                        $firstD->save();                        
                    }                          
                }
            }
        } 
    }
    //call every 4 hrs.
    public function getLast6DayBiometricData()
    {
        $machines = BiometricMachine::whereActive(1)
        ->where('status', 0)
        ->take(5)
        ->get();

        if(count($machines))
        {
            foreach($machines as $machine)
            {
                $yesterday = date('Y-m-d', strtotime('-7 days', strtotime(date('Y-m-d'))));
                $today = $yesterday = date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))));
            
                $soapUrl = "http://45.250.225.45:888/iclock/webapiservice.asmx"; 
                $soapUser = "HRMS";  //  username
                $soapPassword = "hrms"; // password

                $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                    <soap12:Body>
                                        <GetTransactionsLog xmlns="http://tempuri.org/">';
                $xml_post_string .= '<FromDate>'.$yesterday.'</FromDate>';
                $xml_post_string .= '<ToDate>'.$today.'</ToDate>';
                $xml_post_string .= '<SerialNumber>'.$machine->serialNo.'</SerialNumber>';
                $xml_post_string .= '<UserName>HRMS</UserName>
                                    <UserPassword>hrms</UserPassword>
                                    <strDataList></strDataList>
                                    </GetTransactionsLog>
                                    </soap12:Body>
                                    </soap12:Envelope>';   

                $headers = array(
                            "Content-type: text/xml;charset=\"utf-8\"",
                            "Accept: text/xml",
                            "Cache-Control: no-cache",
                            "Pragma: no-cache",
                            "Content-length: ".strlen($xml_post_string),
                        ); //SOAPAction: your op URL

                // PHP cURL  for https connection with auth
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_URL, $soapUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($ch); 
                curl_close($ch);

                $Json = json_encode($response);
                if($Json != 'false')
                {
                    $d= strstr($Json, '<\/GetTransactionsLogResult>');
                    $d1= strstr($d, '>');
            
                    $response1 = str_replace('\t'," ",$d1);
                    $response2 = str_replace('\r\n'," ",$response1);
                    $response3 = str_replace('<\/strDataList><\/GetTransactionsLogResponse><\/soap:Body><\/soap:Envelope>"',"",$response2);
                    $finalResp = explode(" ",$response3);
                    $ct = count($finalResp);
                        
                    $m=0;
                    $firstId = 0;
                    for($k=0; $k<$ct; $k++)
                    {
                        $m=$k; 
                        if(isset($finalResp[$m]) && isset($finalResp[$m+1]) && isset($finalResp[$m+2]))
                        {
                            $attLog = AttendanceLog::where('employeeCode', $finalResp[$m])
                            ->where('logDate', $finalResp[$m+1])
                            ->where('logTime', $finalResp[$m+2])
                            ->first();
                            if(!$attLog)
                                $attLog = new AttendanceLog;

                            $attLog->employeeCode=$finalResp[$m];
                            $attLog->logDateTime=$finalResp[$m+1].' '.$finalResp[$m+2];
                            $attLog->logDate=$finalResp[$m+1];
                            $attLog->logTime=$finalResp[$m+2];
                            $attLog->deviceShortName=$machine->deviceShortName;
                            $attLog->serialNo=$machine->serialNo;
                            $attLog->save();
                            if($firstId == 0)
                                $firstId=$attLog->id;
            
                            $k=$m+2;
                            $m=0;
                        }
                    }
            
                    $firstD = AttendanceLog::find($firstId);
                    if($firstD)
                    {
                        $firstD->employeeCode = str_replace('><strDataList>',"",$firstD->employeeCode);
                        $firstD->save();                        
                    }                          
                }

                BiometricMachine::where('id', $machine->id)
                ->where('status', 0)
                ->update(['status'=>'1']);

            }
        } 
        else
        {
            BiometricMachine::where('active', 1)->update(['status'=>'0']);
        }
    }

    //call daily at 01.02, 01.08, 01.13
    public function getLast10DayBiometricData()
    {
        $machines = BiometricMachine::whereActive(1)
        ->where('status1', 0)
        ->take(5)
        ->get();

        if(count($machines))
        {
            foreach($machines as $machine)
            {
                $yesterday = date('Y-m-d', strtotime('-17 days', strtotime(date('Y-m-d'))));
                $today = date('Y-m-d', strtotime('-7 days', strtotime(date('Y-m-d'))));
            
                $soapUrl = "http://45.250.225.45:888/iclock/webapiservice.asmx"; 
                $soapUser = "HRMS";  //  username
                $soapPassword = "hrms"; // password

                $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                    <soap12:Body>
                                        <GetTransactionsLog xmlns="http://tempuri.org/">';
                $xml_post_string .= '<FromDate>'.$yesterday.'</FromDate>';
                $xml_post_string .= '<ToDate>'.$today.'</ToDate>';
                $xml_post_string .= '<SerialNumber>'.$machine->serialNo.'</SerialNumber>';
                $xml_post_string .= '<UserName>HRMS</UserName>
                                    <UserPassword>hrms</UserPassword>
                                    <strDataList></strDataList>
                                    </GetTransactionsLog>
                                    </soap12:Body>
                                    </soap12:Envelope>';   

                $headers = array(
                            "Content-type: text/xml;charset=\"utf-8\"",
                            "Accept: text/xml",
                            "Cache-Control: no-cache",
                            "Pragma: no-cache",
                            "Content-length: ".strlen($xml_post_string),
                        ); //SOAPAction: your op URL

                // PHP cURL  for https connection with auth
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_URL, $soapUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($ch); 
                curl_close($ch);

                $Json = json_encode($response);
                if($Json != 'false')
                {
                    $d= strstr($Json, '<\/GetTransactionsLogResult>');
                    $d1= strstr($d, '>');
            
                    $response1 = str_replace('\t'," ",$d1);
                    $response2 = str_replace('\r\n'," ",$response1);
                    $response3 = str_replace('<\/strDataList><\/GetTransactionsLogResponse><\/soap:Body><\/soap:Envelope>"',"",$response2);
                    $finalResp = explode(" ",$response3);
                    $ct = count($finalResp);
                        
                    $m=0;
                    $firstId = 0;
                    for($k=0; $k<$ct; $k++)
                    {
                        $m=$k; 
                        if(isset($finalResp[$m]) && isset($finalResp[$m+1]) && isset($finalResp[$m+2]))
                        {
                            $attLog = AttendanceLog::where('employeeCode', $finalResp[$m])
                            ->where('logDate', $finalResp[$m+1])
                            ->where('logTime', $finalResp[$m+2])
                            ->first();
                            if(!$attLog)
                                $attLog = new AttendanceLog;

                            $attLog->employeeCode=$finalResp[$m];
                            $attLog->logDateTime=$finalResp[$m+1].' '.$finalResp[$m+2];
                            $attLog->logDate=$finalResp[$m+1];
                            $attLog->logTime=$finalResp[$m+2];
                            $attLog->deviceShortName=$machine->deviceShortName;
                            $attLog->serialNo=$machine->serialNo;
                            $attLog->save();
                            if($firstId == 0)
                                $firstId=$attLog->id;
            
                            $k=$m+2;
                            $m=0;
                        }
                    }
            
                    $firstD = AttendanceLog::find($firstId);
                    if($firstD)
                    {
                        $firstD->employeeCode = str_replace('><strDataList>',"",$firstD->employeeCode);
                        $firstD->save();                        
                    }                          
                }

                BiometricMachine::where('id', $machine->id)->update(['status1'=>'1']);
            }
        } 
        else
        {
            BiometricMachine::where('active', 1)->update(['status1'=>'0']);
        }
    }
  
    //call daily at 02.03, 02.12 
    public function get20DayBiometricData()
    {
        $machines = BiometricMachine::whereActive(1)
        ->where('status2', 0)
        ->take(1)
        ->get();

        if(count($machines))
        {
            foreach($machines as $machine)
            {
                $yesterday = date('Y-m-d', strtotime('-32 days', strtotime(date('Y-m-d'))));
                $today = date('Y-m-d', strtotime('-17 days', strtotime(date('Y-m-d'))));
            
                $soapUrl = "http://45.250.225.45:888/iclock/webapiservice.asmx"; 
                $soapUser = "HRMS";  //  username
                $soapPassword = "hrms"; // password

                $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                    <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                                    <soap12:Body>
                                        <GetTransactionsLog xmlns="http://tempuri.org/">';
                $xml_post_string .= '<FromDate>'.$yesterday.'</FromDate>';
                $xml_post_string .= '<ToDate>'.$today.'</ToDate>';
                $xml_post_string .= '<SerialNumber>'.$machine->serialNo.'</SerialNumber>';
                $xml_post_string .= '<UserName>HRMS</UserName>
                                    <UserPassword>hrms</UserPassword>
                                    <strDataList></strDataList>
                                    </GetTransactionsLog>
                                    </soap12:Body>
                                    </soap12:Envelope>';   

                $headers = array(
                            "Content-type: text/xml;charset=\"utf-8\"",
                            "Accept: text/xml",
                            "Cache-Control: no-cache",
                            "Pragma: no-cache",
                            "Content-length: ".strlen($xml_post_string),
                        ); //SOAPAction: your op URL

                // PHP cURL  for https connection with auth
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_URL, $soapUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $response = curl_exec($ch); 
                curl_close($ch);

                $Json = json_encode($response);
                if($Json != 'false')
                {
                    $d= strstr($Json, '<\/GetTransactionsLogResult>');
                    $d1= strstr($d, '>');
            
                    $response1 = str_replace('\t'," ",$d1);
                    $response2 = str_replace('\r\n'," ",$response1);
                    $response3 = str_replace('<\/strDataList><\/GetTransactionsLogResponse><\/soap:Body><\/soap:Envelope>"',"",$response2);
                    $finalResp = explode(" ",$response3);
                    $ct = count($finalResp);
                        
                    $m=0;
                    $firstId = 0;
                    for($k=0; $k<$ct; $k++)
                    {
                        $m=$k; 
                        if(isset($finalResp[$m]) && isset($finalResp[$m+1]) && isset($finalResp[$m+2]))
                        {
                            $attLog = AttendanceLog::where('employeeCode', $finalResp[$m])
                            ->where('logDate', $finalResp[$m+1])
                            ->where('logTime', $finalResp[$m+2])
                            ->first();
                            if(!$attLog)
                                $attLog = new AttendanceLog;

                            $attLog->employeeCode=$finalResp[$m];
                            $attLog->logDateTime=$finalResp[$m+1].' '.$finalResp[$m+2];
                            $attLog->logDate=$finalResp[$m+1];
                            $attLog->logTime=$finalResp[$m+2];
                            $attLog->deviceShortName=$machine->deviceShortName;
                            $attLog->serialNo=$machine->serialNo;
                            $attLog->save();
                            if($firstId == 0)
                                $firstId=$attLog->id;
            
                            $k=$m+2;
                            $m=0;
                        }
                    }
            
                    $firstD = AttendanceLog::find($firstId);
                    if($firstD)
                    {
                        $firstD->employeeCode = str_replace('><strDataList>',"",$firstD->employeeCode);
                        $firstD->save();                        
                    }                          
                }
                BiometricMachine::where('id', $machine->id)->update(['status2'=>'1']);
            }
        } 
        else
        {
            BiometricMachine::where('active', 1)->update(['status2'=>'0']);
        }
    }
  
    //call every 2min 
    public function updateAttendanceLogs()
    {
        $str = HrPolicy::select('temp2')->where('name', 'Rule 3')->first();
        $arr = explode(",", $str->temp2);

        // $employees = EmpDet::select('empCode', 'id', 'jobJoingDate', 'departmentId', 'branchId', 'startTime','endTime')
        // ->where('attendanceStatus', 0)
        // ->where('active', 1)
        // ->take(200)
        // ->get();

        // if($employees)
        // {
        //     // $forDate = date('2022-07-01');
        //     foreach($employees as $emp)
        //     {
        //         $days= date('t', strtotime($forDate));
        //         $tempDay=0;
        //         for($i=1; $i<=$days; $i++)
        //         {
        //             if($i >= 1 && $i <= 9)
        //                 $tempDay = '0'.$i;
        //             else
        //                 $tempDay = $i;

        //             $tempDate = date('Y-m', strtotime($forDate)).'-'.$tempDay;
        //             $temp = AttendanceDetail::where('empCode', $emp->empCode)
        //             ->where('forDate', $tempDate)
        //             ->first();
        //             if(!$temp)
        //                 $temp = new AttendanceDetail;

        //             $temp->empId = $emp->id;
        //             $temp->empCode = $emp->empCode;
        //             $temp->day = $tempDay;
        //             $temp->month = date('M', strtotime($forDate));
        //             $temp->year = date('Y', strtotime($forDate));
        //             $temp->forDate = $tempDate;
        //             $temp->dayName = date('D', strtotime($temp->forDate));
        //             if($temp->dayName == 'Sun')
        //             {
        //                 $temp->dayStatus = 'WO';
        //                 $temp->holiday=1;
        //             }
        
        //             $thirdSat = date('Y-m-d', strtotime('third saturday of '.date('M', strtotime($forDate)).' '.date('Y',strtotime($forDate))));

        //             if($emp->jobJoingDate <= $temp->forDate)
        //             {
        //                 if($emp->departmentId != "" || $emp->departmentId != NULL)
        //                 {
        //                     if($thirdSat == $temp->forDate)
        //                     {
        //                         if(in_array($emp->departmentId, $arr))
        //                         {
        //                             $temp->dayStatus='WO';
        //                             $temp->holiday=1;
        //                         }
        //                     }
        //                 }
        //             }
        //             else
        //             {
        //                 $temp->dayStatus='A';
        //             }

        //             $appAGF = EmpApplication::select('id')
        //             ->where('empId', $emp->id)
        //             ->where('startDate', $forDate)
        //             ->where('type', 1)
        //             ->where('status', 1)
        //             ->first();
        //             if($appAGF)
        //             {
        //                 $temp->AGFStatus = $appAGF->id;
        //             }
        //             $temp->save();
        //         }

        //         EmpDet::where('id', $emp->id)->update(['attendanceStatus'=>1]);
        //     }
        // }

        // return 'asdf';

        $empCodes = EmpDet::where('active', 1)
        ->where('attendanceStatus', 0)
        ->take(40)
        ->orderBy('empCode')
        ->pluck('empCode');

        $util= new Utility;

        if(count($empCodes) != 0)
        {
            EmpDet::where('active', 1)->update(['attendanceStatus'=>0]);
        }
        else
        {
            $attLogs = AttendanceLog::whereIn('employeeCode', $empCodes)
            ->where('status', 0)
            ->orderBy('employeeCode')
            ->orderBy('logDateTime')
            ->get();

            if(count($attLogs))
            {
                foreach($attLogs as $log)
                {
                    $forDate=$log->logDate;
                    $logDateTime = $log->logDateTime;
                    $temp = AttendanceDetail::where('empCode', $log->employeeCode)
                    ->where('forDate', $forDate)
                    ->first();
                    if($temp)
                    {
                        $days= date('t', strtotime($forDate));
                        $thirdSat = date('Y-m-d', strtotime('third saturday of '.date('M', strtotime($forDate)).' '.date('Y',strtotime($forDate))));
            
                        $emp = EmpDet::select('empCode', 'id', 'jobJoingDate', 'departmentId', 'branchId', 'startTime','endTime')
                        ->where('empCode', $log->employeeCode)
                        ->first();
                        if($emp)
                        {
                            $appAGF = EmpApplication::select('id')
                            ->where('empId', $emp->id)
                            ->where('startDate', $forDate)
                            ->where('type', 1)
                            ->where('status', 1)
                            ->first();
                            if($appAGF)
                                $temp->AGFStatus = $appAGF->id;
                            else
                                $temp->AGFStatus = 0;

                            $temp->officeInTime = $emp->startTime;
                            $temp->officeOutTime = $emp->endTime;
            
                            $rule4 = HrPolicy::where('name', 'Rule 4')->where('active', 1)->first();
                            if($rule4)
                            {    
                                $finalLateTime = date('H:i', strtotime('+'.$rule4->temp1.' min', strtotime($emp->startTime)));
                                $firstHalf = date('H:i', strtotime('+'.$rule4->temp7.' hour', strtotime($emp->startTime)));
                                $secondHalf = date('H:i', strtotime('-'.$rule4->temp7.' hour', strtotime($emp->endTime)));
                            }
                            
                            $workingHr=0;
                            if($temp->inTime == 0)
                            {
                                $logIn = $log->logDateTime;
                                $temp->inTime = $logIn; 
                                $logTime = date('H:i', strtotime($logIn)); 
                                if($temp->dayStatus == "WO" && $temp->holiday == 1)
                                {
                                    $temp->dayStatus = 'WOP'; 
                                    $temp->extraWorkingDay = 1;
                                }
                                else
                                {
                                    if(strtotime($logTime) > strtotime($finalLateTime))
                                    {
                                        if(strtotime($logTime) > strtotime($firstHalf))
                                            $temp->dayStatus = 'PH'; 
                                        else
                                        {
                                            $temp->dayStatus = 'PL'; 
                                            $temp->lateMarkDay = 1;
                                        }
                                    }
                                    else
                                        $temp->dayStatus = 'P'; 
                                }


                                $temp->inTime = $logIn; 
                            }
                            else
                            {
                                if($temp->inTime != $log->logDateTime)
                                {
                                    $temp->outTime = $log->logDateTime;
                                    $workingHr = $util->getDiff($temp->outTime, $temp->inTime);
                                
                                    $temp->workingHr = $util->timeDiff($temp->outTime, $temp->inTime);

                                    if($workingHr != 0)
                                    {
                                        if($temp->holiday == "1")
                                        {
                                            if($workingHr < 4)
                                            {
                                                $temp->dayStatus = "WO";
                                                $temp->extraWorkingDay = 0;
                                            }
                                            elseif($workingHr >= 4 && $workingHr <= 6.00)
                                            {
                                                $temp->dayStatus = "WOPH";
                                                $temp->extraWorkingDay = 0.5;
                                            }
                                            elseif($workingHr >= 6)
                                            {
                                                $temp->dayStatus = "WOP";
                                                $temp->extraWorkingDay = 1;
                                            }
                                            else
                                            {
                                                $temp->dayStatus = "WO";
                                                $temp->extraWorkingDay = 0;
                                            }
                                        }
                                        else
                                        {
                                            if(strtotime(date('H:i', strtotime($temp->outTime))) < strtotime($secondHalf))
                                            { 
                                                if($temp->dayStatus == 'P')
                                                    $temp->dayStatus = 'PH'; 
                                                elseif($temp->dayStatus == 'PL')
                                                {
                                                    $temp->dayStatus = 'PLH'; 
                                                    $temp->lateMarkDay = 1;
                                                }
                                                else
                                                {
                                                    $temp->dayStatus = 'PH'; 
                                                    $temp->lateMarkDay = 0;
                                                }

                                                // $temp->dayStatus = 'A'; 
                                                $temp->extraWorkingDay = 100;
                                            }
                                            else
                                            {
                                                if($workingHr < 4)
                                                {
                                                    $temp->dayStatus = 'A'; 
                                                }
                                                elseif($workingHr >= 4 && $workingHr <= 6)
                                                {
                                                    if($temp->dayStatus == 'P')
                                                        $temp->dayStatus = 'PH'; 
                                                    elseif($temp->dayStatus == 'PL')
                                                    {
                                                        $temp->dayStatus = 'PLH'; 
                                                        $temp->lateMarkDay = 1;
                                                    }
                                                    else
                                                    {
                                                        $temp->dayStatus = 'PH'; 
                                                        $temp->lateMarkDay = 0;
                                                    }
                                                }
                                                elseif($workingHr > 6)
                                                {
                                                    if($temp->dayStatus == 'PH')
                                                        $temp->dayStatus = 'P'; 
                                                    elseif($temp->dayStatus == 'PLH')
                                                    {
                                                        $temp->dayStatus = 'PL'; 
                                                        $temp->lateMarkDay = 1;
                                                    }
                                                    elseif($temp->dayStatus == 'A')
                                                    {
                                                        $inTime = date('H:i', strtotime($temp->inTime));
                                                        if(strtotime($inTime) > strtotime($finalLateTime))
                                                        {
                                                            if(strtotime($inTime) > strtotime($firstHalf))
                                                                $temp->dayStatus = 'PH'; 
                                                            else
                                                            {
                                                                $temp->dayStatus = 'PL'; 
                                                                $temp->lateMarkDay = 1;
                                                            }
                                                        }
                                                        else
                                                            $temp->dayStatus = 'P';
                                                    }
                                                }
                                            }
                                        }

                                    }
                                    else
                                    {
                                        if($temp->dayStatus == 'WOP')
                                            $temp->dayStatus = 'WO';
                                        else
                                            $temp->dayStatus = 'A';

                                    }
                                }

                            }
                            $temp->save();

                            $prevLog = AttendanceDetail::where('empCode', $temp->empCode)
                            ->where('forDate', date('Y-m-d', strtotime('-1 day', strtotime($temp->forDate))))
                            ->first();
                            if($prevLog)
                            {
                                if($prevLog->outTime == 0)
                                {
                                    if($prevLog->dayStatus == 'P' || $prevLog->dayStatus == 'PL' || $prevLog->dayStatus == 'PH' || $prevLog->dayStatus == 'PLH')
                                        $prevLog->dayStatus = "A"; 
                                    
                                    if($prevLog->dayStatus == 'WOP' || $prevLog->dayStatus == 'WOPH')
                                        $prevLog->dayStatus = "WO"; 
                                    
                                    $prevLog->save();
                                }
                            }
                            // return 'asdf';
                            // $deleteLog = AttendanceLog::find($log->id);
                            // if($deleteLog)
                            //     $deleteLog->delete();
                        }

                        AttendanceLog::where('id', $log->id)->update(['status'=>'1']);
                    }
                }

                EmpDet::whereIn('empCode', $empCodes)->update(['attendanceStatus'=>1]);
            }
        }
    }

    public function getMonthlyEmpAttendance($empCode, $month)
    {
        if($month == date('M-Y'))
        {
            $totD = date('d');
            $firstDate = date('Y-m-01');
            $lastDate = date('Y-m-d');
        }
        else
        {
            $totD = date('t', strtotime($month));
            $firstDate = date('Y-m-01', strtotime($month));
            $lastDate = date('Y-m-t', strtotime($month));
        }

        $attendances = AttendanceDetail::where('forDate', '>=', $firstDate)
        ->where('forDate', '<=', $lastDate)
        ->where('empCode', $empCode)
        ->get();

        $totDays=$lateMark=$extraW=0;
        foreach($attendances as $key => $attend)
        {
            $lateMark=$lateMark+$attend->lateMarkDay; 
            if($lateMark == 3)
            {
                $totDays=$totDays-1;
                $lateMark=0;
            }

            if($attend->dayStatus == 'WO')
            {
                $sandwichPol=1;
                if(isset($attendances[$key-1]->forDate))
                {
                    if($attendances[$key-1]->dayStatus == 'A' || $attendances[$key-1]->dayStatus == '0')
                        $sandwichPol++;
                }
                if(isset($attendances[$key-2]->forDate))
                {
                    if($attendances[$key-2]->dayStatus == 'A' || $attendances[$key-2]->dayStatus == '0')
                        $sandwichPol++;
                }
                if(isset($attendances[$key-3]->forDate))
                {
                    if($attendances[$key-3]->dayStatus == 'A' || $attendances[$key-3]->dayStatus == '0')
                        $sandwichPol++;
                }
                if(isset($attendances[$key-4]->forDate))
                {
                    if($attendances[$key-4]->dayStatus == 'A' || $attendances[$key-4]->dayStatus == '0')
                        $sandwichPol++;
                }
                if(isset($attendances[$key-5]->forDate))
                {
                    if($attendances[$key-5]->dayStatus == 'A' || $attendances[$key-5]->dayStatus == '0')
                        $sandwichPol++;
                }
                if(isset($attendances[$key-6]->forDate))
                {
                    if($attendances[$key-6]->dayStatus == 'A' || $attendances[$key-6]->dayStatus == '0')
                        $sandwichPol++;
                }

                if($sandwichPol == 3)
                    $totDays=$totDays+0.5;
                    
                if($sandwichPol <= 2)
                    $totDays=$totDays+1;

                $extraW=$extraW+$attend->extraWorkingDay; 
            }
            elseif($attend->dayStatus == 'WOP')
            {
                $totDays=$totDays+1;
                $extraW=$extraW+$attend->extraWorkingDay; 
            }
            elseif($attend->dayStatus == 'WOPL')
            {
                $extraW=$extraW+$attend->extraWorkingDay; 
                $totDays=$totDays+1;
            }
            elseif($attend->dayStatus == 'WOPLH')
            {
                $extraW=$extraW+$attend->extraWorkingDay; 
                $totDays=$totDays+1;
            }
            elseif($attend->dayStatus == 'WOPH')
            {
                $extraW=$extraW+$attend->extraWorkingDay; 
                $totDays=$totDays+1;
            }
            elseif($attend->dayStatus == 'P')
            {
                $totDays=$totDays+1; 
            }
            elseif($attend->dayStatus == 'PL')
            {
                $extraW=$extraW+$attend->extraWorkingDay; 
                $totDays=$totDays+1;
            }
            elseif($attend->dayStatus == 'PLH')
            {
                $extraW=$extraW+$attend->extraWorkingDay; 
                $totDays=$totDays+1;
            }
            elseif($attend->dayStatus == 'PH')
            {
                $extraW=$extraW+$attend->extraWorkingDay; 
                $totDays=$totDays+1;
            }
            else
            {
                if($attend->forDate == "2021-01-26" || $attend->forDate == "2021-08-15")
                    $totDays=$totDays-3;
            }
        }

        $presentDays = $totDays;
        $absentDays = $totD - $totDays;
        $extraWo = $extraW;
        return [$totD, $presentDays, $absentDays, $extraWo];
    }

    public function calculateExperience($startDate)
    {
        $datetime1=new DateTime($startDate);
        $datetime2=new DateTime(date('Y-m-d'));
        $interval=$datetime1->diff($datetime2);
        return $interval->format('%y.%m Years');
    }

    public function getDiff($t1, $t2)
    {
        $date1 = strtotime($t1); 
        $date2 = strtotime($t2); 

        $diff = abs($date2 - $date1);
        $years = floor($diff / (365*60*60*24)); 
        $months = floor(($diff - $years * 365*60*60*24)
                                    / (30*60*60*24)); 
        $days = floor(($diff - $years * 365*60*60*24 - 
                    $months*30*60*60*24)/ (60*60*24));
        $hours = floor(($diff - $years * 365*60*60*24 
            - $months*30*60*60*24 - $days*60*60*24)
                                        / (60*60)); 
        $minutes = floor(($diff - $years * 365*60*60*24 
                - $months*30*60*60*24 - $days*60*60*24 
                                - $hours*60*60)/ 60); 
       
        return $hours.'.'.$minutes;
    }

    public function getLastDate($empCode, $forDate, $days)
    {
        $forDate = date('Y-m-d', strtotime('-'.$days.' day', strtotime($forDate)));
        return AttendanceDetail::where('empCode', $empCode)->where('forDate', $forDate)
        ->where(function($query)
        {
            $query->Where('dayStatus', '0')
            ->orWhere('dayStatus', 'A');

        })->value('dayStatus');
    }

    public function updateAGF()
    {
        $application = EmpApplication::where('type', 1)
        ->where('status', 1)
        ->where('startDate', '>=', date('Y-m-01'))
        ->where('startDate', '<=', date('Y-m-t'))
        ->get();
        foreach($application as $app)
        {
            $temp = AttendanceDetail::where('empId', $app->empId)
            ->where('forDate', $app->startDate)
            ->first();
            if($temp)
            {
                $temp->AGFStatus=$app->id;
                $temp->save();
            }
        }
    }

    public function getVerion()
    {
        return '1.0';
    }

    public function numberFormatRound($num)
    {
        $explrestunits = "" ;
        $num=preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $dec="00";
        if(count($words)<=2){
            $num=$words[0];
            if(count($words)>=2){$dec=$words[1];}
            if(strlen($dec)<2){$dec="$dec"."0";}else{$dec=substr($dec, 0, 2);}
        }
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){
                // creates each of the 2's group and adds a comma to the end
                if($i==0)
                {
                    if($expunit[$i]=='0-'){
                        $explrestunits .= '-';
                    }else{
                        $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                    }
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return "$thecash"; 
    }

    public function numberFormatDec($num) 
    {
        $explrestunits = "" ;
        $num=preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $dec="000";
        if(count($words)<=3){
            $num=$words[0];
            if(count($words)>=2){$dec=$words[1];}
            if(strlen($dec)<3){$dec="$dec"."0";}else{$dec=substr($dec, 0, 3);}
        }
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){
                // creates each of the 2's group and adds a comma to the end
                if($i==0) 
                {
                    if($expunit[$i]=='0-'){
                        $explrestunits .= '-';
                    }else{
                        $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                    }
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return "$thecash.$dec"; 
    }

    public function numberFormat($num) 
    {
        $explrestunits = "" ;
        $num=preg_replace('/,+/', '', $num);
        $words = explode(".", $num);
        $dec="00";
        if(count($words)<=2){
            $num=$words[0];
            if(count($words)>=2){$dec=$words[1];}
            if(strlen($dec)<2){$dec="$dec"."0";}else{$dec=substr($dec, 0, 2);}
        }
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){
                // creates each of the 2's group and adds a comma to the end
                if($i==0) 
                {
                    if($expunit[$i]=='0-'){
                        $explrestunits .= '-';
                    }else{
                        $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                    }
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        return "$thecash.$dec"; 
    }

    public function numberToWord($num)
    {
        $number = $num;
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " . 
                $words[$point = $point % 10] : '';
         return $result;
    }

    public function dateFormat($date,$time)
    {
        switch($time){
            case '1':
                return date('d M Y',strtotime($date));
            break;
            case '2':
                return date('d M Y, h:i A',strtotime($date));
            break;
        } 
    } 

    public function getLastAppointmentLetter($empId)
    {
        return EmployeeLetter::where('letterType', 2)
        ->where('empId', $empId)
        ->where('active', 1)
        ->orderBy('id', 'desc')
        ->value('id');
    }

    public function getNotifications()
    {
        $userType = Auth::user()->userType;
        $empId = Auth::user()->empId;
        if($empId != 0)
        {
            if($userType == '11')
            {
                $repIds1 = EmpDet::where('reportingId', $empId)->where('active', 1)->pluck('id');
                $repIds2 = EmpDet::whereIn('reportingId', $repIds1)->where('active', 1)->pluck('id');

                $collection = collect($repIds1);
                $merged = $collection->merge($repIds2);
                $reportyId = $merged->all();

                $applications1 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_applications.active', 1)
                ->where('emp_dets.active', 1)
                ->whereIn('emp_dets.reportingId', $reportyId)
                ->whereIn('emp_applications.type', [1, 4])
                ->where('emp_applications.status1', 0)
                ->where('emp_applications.created_at', '>=', date('2022-05-01 00:00:00'))
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();

                $applications2 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_dets.active', 1)
                ->where('emp_applications.status', 0)
                ->whereIn('emp_applications.type', [2, 3])
                ->whereIn('emp_dets.reportingId', $reportyId)
                ->where('emp_applications.created_at', '>=', date('2022-05-01 00:00:00'))
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();

                $temp1 = collect($applications1);
                $temp2 = collect($applications2);
                return $applications =$temp1->merge($temp2);
   
            }

            if($userType == '21')
            {
                // return EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                // ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                // 'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                // ->where('emp_applications.status1', 0)
                // ->where('emp_applications.active', 1)
                // ->where('emp_dets.active', 1)
                // ->where('emp_applications.type', '!=',4)
                // ->where('emp_dets.reportingId', $empId)
                // ->orderBy('emp_applications.created_at', 'desc')
                // ->get();

                $applications1 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_applications.active', 1)
                ->where('emp_dets.active', 1)
                ->where('emp_dets.reportingId', $empId)
                ->whereIn('emp_applications.type', [1, 4])
                ->where('emp_applications.status1', 0)
                ->where('emp_applications.created_at', '>=', date('2022-05-01 00:00:00'))
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();

                $applications2 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_dets.active', 1)
                ->where('emp_applications.status', 0)
                ->whereIn('emp_applications.type', [2, 3])
                ->where('emp_dets.reportingId', $empId)
                ->where('emp_applications.created_at', '>=', date('2022-05-01 00:00:00'))
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();

                $temp1 = collect($applications1);
                $temp2 = collect($applications2);
                return $applications =$temp1->merge($temp2);
            }

            if($userType == '31')
            {
                // return EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                // ->select('emp_applications.id','emp_dets.name', 'emp_dets.id as empId','emp_dets.empCode',
                // 'emp_applications.type','emp_applications.startDate', 'emp_applications.created_at', 'emp_dets.firmType')
                // ->where('emp_applications.status1', 0)
                // ->where('emp_applications.active', 1)
                // ->where('emp_dets.active', 1)
                // ->where('emp_applications.type', '!=',4)
                // ->where('emp_dets.id', $empId)
                // // ->where('emp_applications.updated_at', '<=', date('Y-m-d H:i:s', strtotime('-1 day')))
                // ->orderBy('emp_applications.created_at', 'desc')
                // ->get();

                // $applications1 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                // ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                // 'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                // ->where('emp_applications.active', 1)
                // ->where('emp_dets.active', 1)
                // ->where('emp_dets.id', $empId)
                // ->whereIn('emp_applications.type', [1, 4])
                // ->where('emp_applications.status', 0)
                // ->where('emp_applications.created_at', '>=', date('2022-05-01 00:00:00'))
                // ->orderBy('emp_applications.created_at', 'desc')
                // ->get();

                // $applications2 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                // ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
                // 'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
                // ->where('emp_dets.active', 1)
                // ->where('emp_applications.status', 0)
                // ->whereIn('emp_applications.type', [2, 3])
                // ->whereIn('emp_dets.reportingId', $empId)
                // ->where('emp_applications.created_at', '>=', date('2022-05-01 00:00:00'))
                // ->orderBy('emp_applications.created_at', 'desc')
                // ->get();

                // $temp1 = collect($applications1);
                // $temp2 = collect($applications2);
                // return $applications =$temp1->merge($temp2);
                return [];
            }
        }
        elseif($userType == '51')
        {
            $applications1 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
            'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
            ->where('emp_applications.active', 1)
            ->where('emp_dets.active', 1)
            ->whereIn('emp_applications.type', [1, 4])
            ->where('emp_applications.status1', 0)
            ->where('emp_applications.created_at', '>=', date('2022-05-01 00:00:00'))
            ->orderBy('emp_applications.created_at', 'desc')
            ->get();

            $applications2 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
            'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
            ->where('emp_applications.active', 1)
            ->where('emp_dets.active', 1)
            ->where('emp_applications.status', 0)
            ->whereIn('emp_applications.type', [2, 3])
            ->where('emp_applications.created_at', '>=', date('2022-05-01 00:00:00'))
            ->orderBy('emp_applications.created_at', 'desc')
            ->get();

            $temp1 = collect($applications1);
            $temp2 = collect($applications2);
            return $applications =$temp1->merge($temp2);

        }
        elseif($userType == '61')
        {
            return $applications1 = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.id','emp_dets.name','emp_dets.id as empId', 'emp_dets.empCode',
            'emp_applications.type', 'emp_applications.startDate','emp_applications.created_at', 'emp_dets.firmType')
            ->where('emp_applications.active', 1)
            ->where('emp_dets.active', 1)
            ->whereIn('emp_applications.type', [1, 4])
            ->where('emp_applications.status1', 1)
            ->where('emp_applications.created_at', '>=', date('2022-05-01 00:00:00'))
            ->orderBy('emp_applications.created_at', 'desc')
            ->get();
        }
        else
        {
            return [];
        }
    }
    
    public function getNotificationsMinAge()
    {
        $user = Auth::user();
        $userType = $user->userType;
        $empId = $user->empId;
        
        if($empId != 0)
        {
            if($userType == '11')
            {
                $repIds1 = EmpDet::where('reportingId', $empId)->pluck('id');
                $repIds2 = EmpDet::whereIn('reportingId', $repIds1)->pluck('id');

                $collection = collect($repIds1);
                $merged = $collection->merge($repIds2);
                $reportyId = $merged->all();

                $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_applications.status', 0)
                ->where('emp_applications.active', 1)
                ->where('emp_applications.created_at', '>=', date('Y-m-d H:i:s', strtotime('-2 min')))
                ->whereIn('emp_dets.reportingId', $reportyId)
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();
            }

            if($userType == '21')
            {
                $applications =  EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_applications.status', 0)
                ->where('emp_applications.active', 1)
                ->where('emp_dets.reportingId', $empId)
                ->where('emp_applications.created_at', '>=', date('Y-m-d H:i:s', strtotime('-2 min')))
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();
            }

            if($userType == '31')
            {
                $applications =  EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
                ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode',
                'emp_applications.type', 'emp_applications.created_at', 'emp_dets.firmType')
                ->where('emp_applications.status', 0)
                ->where('emp_applications.active', 1)
                ->where('emp_applications.created_at', '>=', date('Y-m-d H:i:s', strtotime('-2 min')))
                ->where('emp_dets.id', $empId)
                ->orderBy('emp_applications.created_at', 'desc')
                ->get();
            }
        }
        elseif($userType == '51'  || $userType == '401' || $userType == '201' || $userType == '501')
        {
            $applications =  $applications = EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode',
            'emp_applications.type', 'emp_applications.created_at', 'emp_dets.firmType')
            ->where('emp_applications.status', 0)
            ->where('emp_applications.active', 1)
            ->where('emp_applications.created_at', '>=', date('Y-m-d H:i:s', strtotime('-2 min')))
            ->orderBy('emp_applications.created_at', 'desc')
            ->get();

            // $exitProcess = ExitProcessStatus::
            // ->where('accountDept', 1)
            // ->where('hrDept', 0)
            // ->get();

            // return [$applications, $exitProcess];
        }
        elseif($userType == '91'  || $userType == '401' || $userType == '201' || $userType == '501')
        {

        }

        foreach($applications as $app)
        {
            if($app->type == 1)
                $app['type'] = "AGF";
            elseif($app->type == 2)
                $app['type'] = "Exit Pass";
            elseif($app->type == 3)
                $app['type'] = "Leave";
            else
                $app['type'] = "Travelling Allowance";

        }

        return $applications;
    }

    public function getPersonalNotifications()
    {
        $empId = Auth::user()->empId;
        
        return EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode',
        'emp_applications.type', 'emp_applications.created_at', 'emp_dets.firmType')
        ->where('emp_applications.status', 0)
        ->where('emp_applications.type', '!=', 4)
        ->where('emp_applications.active', 1)
        ->where('emp_dets.id', $empId)
        ->whereDate('emp_dets.updated_at', '<=', date('Y-m-d', strtotime('-1 day')))
        ->orderBy('emp_applications.created_at', 'desc')
        ->get();
        
    }

    public function getEmployeeNotifications()
    {
        $empId = Auth::user()->empId;
        return EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode', 'emp_applications.type', 'emp_dets.profilePhoto', 'emp_dets.gender')
        ->where('emp_applications.active', 1)
        ->where('emp_applications.status', '!=', 0)
        ->where('emp_dets.id', $empId)
        ->where('emp_applications.updated_at', '<=', date('Y-m-d h:i:s', strtotime('-3')))
        ->get();
    }

    public function getTimeDiff($forDate)
    {
        // Formulate the Difference between two dates
        $date1 = strtotime($forDate);
        $date2 = strtotime(date('Y-m-d H:i:s'));
        $diff = abs($date2 - $date1);
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24)
                                    / (30*60*60*24));

        $days = floor(($diff - $years * 365*60*60*24 -
                    $months*30*60*60*24)/ (60*60*24));
   
        $hours = floor(($diff - $years * 365*60*60*24
            - $months*30*60*60*24 - $days*60*60*24)
                                        / (60*60));
     
        $minutes = floor(($diff - $years * 365*60*60*24
                - $months*30*60*60*24 - $days*60*60*24
                                - $hours*60*60)/ 60);

        $temp = '';
        if($days != 0)
        {
            $temp = $days.' day ';
        }

        if($hours != 0)
        {
            $temp = $temp.$hours.' hour ';
        }

        if($minutes != 0)
        {
            $temp = $temp.$minutes.' Min. ';
        }
        
        if($minutes == 0 && $hours == 0 && $days == 0)
        {
            $temp= 'just ago';
        }
        return $temp;
    }

    public function getDateDiff($forDate)
    {
        // Formulate the Difference between two dates
        $date1 = strtotime($forDate);
        $date2 = strtotime(date('Y-m-d'));
      
        $diff = abs($date2 - $date1);

        $years = floor($diff / (365*60*60*24));

        $months = floor(($diff - $years * 365*60*60*24)
                                    / (30*60*60*24));

        $days = floor(($diff - $years * 365*60*60*24 -
                    $months*30*60*60*24)/ (60*60*24));
   

        if($days != 0)
        { 
            return $months.' Months & '.$days.' Days left';
        }
        else
        {
            return 'Today';
        }
    }

    public function get_next_birthday($birthday) {
        $date = new DateTime($birthday);
        $date->modify(date('Y') - $date->format('Y') . ' years');
        if($date < new DateTime()) {
            $date->modify('+1 year');
        }
    
        return $date->format('d M Y');
    }

    public function getWorkingTime($time1, $time2)
    {
        $d1 = new DateTime($time1);
        $d2 = new DateTime($time2);
        $interval = $d1->diff($d2);
        // $diffInSeconds = $interval->s; //45
        $diffInMinutes = $interval->i; //23
        $diffInHours   = $interval->h; //8

        return $diffInHours.':'.$diffInMinutes;
    }

    public function timeDiff($t1, $t2)
    {
        $date1 = strtotime($t1); 
        $date2 = strtotime($t2); 

        $diff = abs($date2 - $date1);
        $years = floor($diff / (365*60*60*24)); 
        $months = floor(($diff - $years * 365*60*60*24)
                                    / (30*60*60*24)); 
        $days = floor(($diff - $years * 365*60*60*24 - 
                    $months*30*60*60*24)/ (60*60*24));
        $hours = floor(($diff - $years * 365*60*60*24 
            - $months*30*60*60*24 - $days*60*60*24)
                                        / (60*60)); 
        $minutes = floor(($diff - $years * 365*60*60*24 
                - $months*30*60*60*24 - $days*60*60*24 
                                - $hours*60*60)/ 60); 
       
        return $hours.':'.$minutes;
    }

    public function getNotification($empId)
    {
        $empId = Auth::user()->empId;
        $userType = Auth::user()->userType;

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

        if($userType != '31')
        {
            $reportee =  EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
            ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode', 'emp_applications.type', 'emp_dets.firmType')
            ->where('emp_applications.active', 1)
            ->where('emp_applications.status', 0);
            if($empId != '')
                $reportee =$reportee->whereIn('emp_dets.id', $users);

            $reportee =$reportee->where('emp_applications.updated_at', '<=', date('Y-m-d h:i:s', strtotime('-3')))
            ->get();
        }
        else
        {
            $reportee=[];
        }

        $personal =  EmpApplication::join('emp_dets', 'emp_applications.empId', 'emp_dets.id')
        ->select('emp_applications.id','emp_dets.name', 'emp_dets.empCode', 'emp_applications.type', 'emp_dets.firmType')
        ->where('emp_applications.active', 1)
        ->where('emp_applications.status', [0,1])
        ->where('emp_dets.id', $empId)
        ->where('emp_applications.updated_at', '<=', date('Y-m-d h:i:s', strtotime('-3')))
        ->get();

        return [$personal, $reportee];

    }
}