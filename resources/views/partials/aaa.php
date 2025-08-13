 $k=0;$no=1; 
if(count($attendances)){
    foreach($attendances as $key => $attend)
    {
        if($k==0)
        {
            $sandwichPol=$totDays=$lateMark=$extraW=$sandTp=$wfh=0; 
            $temp['empCode'] = $attend->empCode;
            $temp['name'] = $attend->name;
            $temp['departmentName'] = $attend->departmentName;
            $temp['designationName'] = $attend->designationName;
            $temp['joiningDate'] = ($attend->jobJoingDate == '' || $attend->jobJoingDate == NULL)? 'NA':date('d-m-Y', strtotime($attend->jobJoingDate));          
        }

        $holidayFlag=$jobJoining=0;
        if(($attend->dayStatus == 'PL' || $attend->dayStatus == 'PLH') && $attend->lateMarkDay == 0)
        {
            if($attend->AGFStatus == 0)
            {
                $lateMark=$lateMark+1; 
                if($lateMark == 3)
                {
                    $totDays=$totDays-1;
                    $lateMark=0;    
                }
            }
        }

        if($attend->jobJoingDate <= $attend->forDate)
        {
            if($attend->dayName == 'Mon' || $attend->dayName == 'Tue' || $attend->dayName == 'Wed' || $attend->dayName == 'Thu'  || $attend->dayName == 'Fri'  || $attend->dayName == 'Sat')
            {
                if($attend->AGFStatus == 0 && ($attend->dayStatus == '0' || $attend->dayStatus == 'A'))
                {
                    $sandwichPol++;
                }
            }
            $jobJoining = 0;
        }
        else
        {
            $jobJoining = 1;
        }
            
            if($jobJoining == 0)
            {
                if($attend->dayStatus == 'A')
                {
                    if($attend->holiday == 1 && $attend->AGFStatus != 0)
                    {
                        if($attend->paymentType == 1)
                        {
                            $temp['dayStatus'] = 'WOP';
                            $totDays=$totDays+1;
                            if($attend->extraWorkAllowed == 0)
                                $extraW=$extraW+1; 
                            
                        }
                        elseif($attend->paymentType == 2)
                        {
                            $temp['dayStatus'] = 'WO';
                            $totDays=$totDays+1;
                        }
                        else
                        {
                            $temp['dayStatus'] = 'WO';
                            $totDays=$totDays+1; 
                        }
                    }
                    else
                    {
                        if($attend->outTime == '0')
                        {
                            $temp['dayStatus'] = 'A';
                            if($attend->AGFStatus != '0' || $attend->AGFStatus != 0)
                            {
                                $temp['dayStatus'] = 'A (AGF)';
                                $totDays=$totDays+1;
                            }
                        }
                        else
                        {
                            if($attend->AGFStatus != '0')
                            {
                                $temp['dayStatus'] = 'A (AGF)';
                                $totDays=$totDays+1; 
                            }
                            else
                            {
                                $temp['dayStatus'] = 'A';  
                                $sandTp=1; 
                                if(($attend->month == 'Aug' && $attend->month == '15') && $attend->indDayStatus == 0)
                                    $totDays=$totDays-3;

                                if(($attend->month == 'Jan' && $attend->month == '26') && $attend->repubDayStatus == 0)
                                    $totDays=$totDays-3;
                                
                            }
                        }
                    }
                }
                elseif($attend->dayStatus == 'WO')
                {
                    $temp['dayStatus'] = 'WO';  
                    if($attend->AGFStatus != 0)
                    {
                        $temp['dayStatus'] = 'WO (AGF)';  
                        if($attend->paymentType == 1)
                        {
                            $totDays=$totDays+1;
                            if($attend->extraWorkAllowed == 0)
                                $extraW=$extraW+1; 
                            
                        }
                        elseif($attend->paymentType == 2)
                        {
                            $totDays=$totDays+1;
                        }
                        else
                        {
                            $totDays=$totDays+1; 
                        }
                    }
                    else
                    {
                        $prev = $attendances[$key-1];
                        $next = $attendances[$key+1];
                        
                        if($next && $prev)
                        {
                            $i=0;
                            while($next->holiday)
                            {
                                $next = $attendances[$key+$i];
                                $i++;
                            }
                                if(($prev->dayStatus == '0' || $prev->dayStatus == 'A') && ($next->dayStatus == '0' || $next->dayStatus == 'A'))
                                {    
                                    if($prev->AGFStatus == 0 && $next->AGFStatus == 0)
                                        $holidayFlag=1;
                                    else
                                        $holidayFlag=0;

                                }
                        }
                        
                            if($attend->dayName == 'Sun')
                            {
                                if($sandTp >= 1)
                                {
                                    $sandTp++;
                                }
                            
                                if($sandwichPol == 3)
                                {
                                    if($holidayFlag == 1)
                                    {

                                    }
                                    else
                                    {
                                        $totDays=$totDays+0.5;
                                        $temp['dayStatus'] = 'WOPH';  
                                    }
                                }
                                elseif($sandwichPol <= 2)
                                {
                                    if($attend->paymentType == 1)
                                    {
                                        if($holidayFlag == 1)
                                        {
                                            $totDays=$totDays+0; 
                                        }
                                        else
                                        {
                                            $totDays=$totDays+1; 
                                            $temp['dayStatus'] = 'WOP';  
                                        }
                                    }
                                    elseif($attend->paymentType == 2)
                                    {
                                        $totDays=$totDays+0; 
                                    }
                                    else
                                    {
                                        $totDays=$totDays+0.5; 
                                        $temp['dayStatus'] = 'WOPH';  
                                    }
                                }
                                else
                                {
                                    if($holidayFlag == 1)
                                    {
                                        $totDays=$totDays+0; 
                                    }
                                    else
                                    {
                                        $totDays=$totDays+1; 
                                        $temp['dayStatus'] = 'WOP';  
                                    }
                                }
                            }
                            else
                            { 
                                if($attend->paymentType == 1){
                                    if($holidayFlag == 1)
                                    {
                                        $temp['dayStatus'] = 'A';  
                                    }
                                    else
                                    {
                                         $totDays=$totDays+1;
                                         $temp['dayStatus'] = 'WO';  
                                        if($attend->AGFStatus != '0')
                                        {
                                            $temp['dayStatus'] = 'WOP';  
                                        }
                                    }
                                }
                                elseif($attend->paymentType == 2)
                                {
                                    $temp['dayStatus'] = 'A';  
                                }else{
                                     $totDays=$totDays+0.5; 
                                     $temp['dayStatus'] = 'PH';  
                                }
                          }
                        
                    }
                }elseif($attend->dayStatus == 'WOP'){

                    <div class="hr-listd">
                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                        if($attend->paymentType == 1){
                            <span class="badge badge-success">  
                            <span class="fa fa-star" data-toggle="tooltip" data-placement="top" title="Sunday/Holiday with Extra Work"></span></span>
                             
                                $totDays=$totDays+1;
                                if($attend->extraWorkAllowed == 0)
                                    $extraW=$extraW+1; 
                            
                        }elseif($attend->paymentType == 2){
                            <span class="badge badge-danger">  
                            <span class="fa fa-star" data-toggle="tooltip" data-placement="top" title="Sunday/Holiday with Extra Work"></span></span>
                             
                                $totDays=$totDays+1;
                            
                        }else{
                             $totDays=$totDays+1; 
                            <span class="badge badge-secondary">  
                            <span class="fa fa-star" style="color:white;" data-toggle="tooltip" data-placement="top" title="Holiday 50% Paid"></span></span>
                        }
                        
                    </div>
                }elseif($attend->dayStatus == 'WOPH'){
                    <div class="hr-listd">
                        <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                        if($attend->paymentType == 1){
                            <span class="badge badge-success">  
                            <span class="fa fa-star" data-toggle="tooltip" data-placement="top" title="Sunday/Holiday with Extra Work"></span>
                            if($attend->extraWorkAllowed == 0){
                                if($attend->AGFStatus != '0' || $attend->AGFStatus != 0){
                                    <br>AGF
                                     $extraW=$extraW+1; 
                                }else{
                                     $extraW=$extraW+0.5; 
                                }
                            }
                             $totDays=$totDays+1; 
                        }elseif($attend->paymentType == 2){
                            <span class="badge badge-danger">  
                            <span class="fa fa-star" data-toggle="tooltip" data-placement="top" title="Sunday/Holiday with Extra Work"></span>
                            if($attend->extraWorkAllowed == 0){
                                if($attend->AGFStatus != '0' || $attend->AGFStatus != 0){
                                    <br>AGF
                                     $extraW=$extraW+1; 
                                }else{
                                     $extraW=$extraW+0.5; 
                                }
                            }
                        }else{
                            <span class="badge badge-secondary">  
                            <span class="fa fa-star" style="color:white;" data-toggle="tooltip" data-placement="top" title="Holiday 50% Paid"></span>
                            if($attend->AGFStatus != '0' || $attend->AGFStatus != 0){
                                <br>AGF
                                 $totDays=$totDays+1;  
                            }else{
                                 $totDays=$totDays+0.5; 
                            }
                        }
                        </span>
                    </div>
                
                }elseif($attend->dayStatus == 'P'){
                    if($attend->outTime == 0 && $attend->AGFStatus == '0'){
                        <span class="badge badge-danger">  
                            <span class="feather feather-x-circle"  data-toggle="tooltip" data-placement="top" title="Absent"></span>
                            if($attend->AGFStatus != '0'){
                                <br>AGF
                                 $totDays=$totDays+1; 
                            }else{
                                 $sandTp=1; 
                            }
                        </span>
                    }else{
                        <div class="hr-listd">
                            <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                            if(date('m-d', strtotime($attend->forDate)) == '01-26' || date('m-d', strtotime($attend->forDate)) == '08-15'){
                                <img src="/admin/images/flag.png" height="30px" width="160px">
                            }else{
                                <span class="badge badge-success"><span class="feather feather-check-circle"></span>
                            }
                            if($attend->AGFStatus != '0' || $attend->AGFStatus != 0){
                                <br>AGF
                            }
                            if(date('m-d', strtotime($attend->forDate)) != '01-26' || date('m-d', strtotime($attend->forDate)) != '08-15'){
                                </span>
                            }
                        </div>
                         $totDays=$totDays+1; 
                    }

                }elseif($attend->dayStatus == 'PL'){
                    if($attend->outTime == 0 && $attend->AGFStatus == '0'){
                        <span class="badge badge-danger">  
                            <span class="feather feather-x-circle"  data-toggle="tooltip" data-placement="top" title="Absent"></span>
                            if($attend->AGFStatus != '0'){
                                <br>AGF
                                 $totDays=$totDays+1; 
                            }else{
                                 $sandTp=1; 
                            }
                        </span>
                    }else{
                        <div class="hr-listd">
                            <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                            <span class="badge badge-danger">
                            <span class="feather feather-check-circle"></span>
                             
                                $totDays=$totDays+1;
                            
                            if($attend->AGFStatus != '0' || $attend->AGFStatus != 0){
                                <br>AGF
                            }
                            </span>
                        </div>
                    }
                }elseif($attend->dayStatus == 'PLH'){
                    if($attend->lateMarkDay == 0){
                        if($attend->outTime == 0 && $attend->AGFStatus == '0'){
                            <span class="badge badge-danger">  
                                <span class="feather feather-x-circle"  data-toggle="tooltip" data-placement="top" title="Absent"></span>
                                if($attend->AGFStatus != '0'){
                                    <br>AGF
                                     $totDays=$totDays+1; 
                                }else{
                                     $sandTp=1; 
                                }
                            </span>
                        }else{
                            <div class="hr-listd">
                                <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                                <span class="badge badge-orange">  
                                <span class="fa fa-adjust"><br>PLH</span>
                            
                                if($attend->AGFStatus != '0' || $attend->AGFStatus != 0){
                                    <br>AGF
                                     $totDays=$totDays+1; 
                                }else{
                                     $totDays=$totDays+0.5; 
                                }
                                </span>
                            </div>
                        }
                    }else{
                        <div class="hr-listd">
                            <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                            <span class="badge badge-orange">  
                            <span class="fa fa-adjust"></span>
                            if($attend->AGFStatus != '0' || $attend->AGFStatus != 0){
                                <br>AGF
                                 $totDays=$totDays+1; 
                            }else{
                                 $totDays=$totDays+0.5; 
                                $sandwichPol=$sandwichPol+0.5; 
                            }
                            </span>
                        </div>
                    }
                }elseif($attend->dayStatus == 'PH'){
                    if($attend->outTime == 0 && $attend->AGFStatus == '0'){
                        <span class="badge badge-danger">  
                            <span class="feather feather-x-circle"  data-toggle="tooltip" data-placement="top" title="Absent"></span>
                            if($attend->AGFStatus != '0'){
                                <br>AGF
                                 $totDays=$totDays+1; 
                            }else{
                                 $sandTp=1;
                                $sandwichPol++; 
                            }
                        </span>
                    }else{
                        <div class="hr-listd">
                            <a href="#" data-toggle="modal" data-target="#presentmodal{{$attend->id}}" class="hr-listmodal"></a>
                            <span class="badge badge-orange">  
                            <span class="fa fa-adjust"></span>
                        
                            if($attend->AGFStatus != '0' || $attend->AGFStatus != 0){
                                <br>AGF
                                 $totDays=$totDays+1; 
                            }else{
                                 $totDays=$totDays+0.5; 
                                $sandwichPol=$sandwichPol+0.5; 
                            }
                            </span>
                        </div>
                    }
                }else{
                    <span class="badge badge-danger">  
                        <span class="feather feather-x-circle"  data-toggle="tooltip" data-placement="top" title="Absent"></span>
                        if($attend->AGFStatus != '0'){
                            <br>AGF
                             $totDays=$totDays+1; 
                        }else{
                             $sandTp=1; 
                        }
                    </span>
                }
                
                    if($attend->dayName == 'Sun')
                    {
                        $sandwichPol=0;
                    }
                
                <div class="modal fade" id="presentmodal{{$attend->id}}">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Date: {{date('d-M-Y', strtotime($attend->forDate))}}</h5>
                                <a href="#" class="btn btn-outline-primary" data-dismiss="modal">close</a>
                            </div>
                            <div class="modal-body">
                                <center><b style="color:purple;font-size:20px;">{{$attend->name}}</b><center>
                                <center><b style="color:green;font-size:14px;">Today's Office Time : {{$attend->officeInTime}} To {{$attend->officeOutTime}}</b><center>
                                
                                <div class="row mb-5 mt-4">
                                    <div class="col-md-4">
                                        <div class="pt-5 text-center">
                                            <h6 class="mb-1 fs-16 font-weight-semibold">    
                                                <b>{{($attend->inTime == "0")?'-':date('H:i', strtotime($attend->inTime))}}</b></b>
                                            </h6>
                                            <small class="text-muted fs-14">{{($attend->deviceInTime != '0')?("Logged in at ".$attend->deviceInTime):'Log In'}}</small>
                                        </div>
                                    </div>
                                    if($attend->dayStatus == "WOP" || $attend->dayStatus == "P" || $attend->dayStatus == "P"){
                                        <div class="col-md-4">
                                            <div class="chart-circle chart-circle-md" data-value="100" data-thickness="6" data-color="#0dcd94">
                                                <div class="chart-circle-value"><b>{{$attend->workingHr}} hrs</b></div>
                                            </div>
                                        </div>
                                    }else{
                                        <div class="col-md-4">
                                            if($attend->dayStatus == "WOPL" || $attend->dayStatus == "PL"){
                                                <div class="chart-circle chart-circle-md" data-value=".90" data-thickness="6" data-color="red">
                                            }else{
                                                <div class="chart-circle chart-circle-md" data-value=".50" data-thickness="6" data-color="red">
                                            }
                                                <div class="chart-circle-value"><b>{{$attend->workingHr}} hrs</b></div>
                                            </div>
                                        </div>
                                    }
                                    <div class="col-md-4">
                                        <div class="pt-5 text-center">
                                            <h6 class="mb-1 fs-16 font-weight-semibold"> 
                                                <b>
                                                    {{($attend->outTime == "0")?'-':date('H:i', strtotime($attend->outTime))}}
                                                </b>
                                            </h6>
                                            <small class="text-muted fs-14">{{($attend->deviceOutTime != '0')?("Logged Out at ".$attend->deviceOutTime):'Log Out'}}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-5 mt-2">
                                    <div class="col-md-12">
                                        <div class="pt-5 text-center">
                                        <center><b style="color:Red;">Till Date Calculations</b></center>
                                            <table class="table">
                                                
                                                    <th>Total Days</th>
                                                    <th>Total Extra Work</th>
                                                    <th>Total Late Mark</th>
                                                    <th>Day Status</th>
                                                </tr>
                                                
                                                    <td>{{$totDays}}</td>
                                                    <td>{{$extraW}}</td>
                                                    <td>{{$lateMark}}</td>
                                                    <td>{{$attend->dayStatus}}</td>
                                                </tr>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                if($attend->AGFStatus != '0' || $attend->AGFStatus != 0){
                                    <div class="row mb-5 mt-2">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="pt-5 text-center">
                                                <center><b style="color:green;font-size:20px;">AGF Approved</b><center>
                                                <center><a href="/empApplications/changeStatus/{{$attend->AGFStatus}}/1" target="_blank"><b style="color:red;font-size:15px;">click here for more Details</b></a><center>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                }
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>
            }else{
                    <b>NA</b>
            }
        </td>
         $k++; 
        if($k==$days){
                <td>{{$totDays}}<input type="hidden" name="totPresent[]" value="{{$totDays}}"></td>
                <td>{{$days-$totDays}}<input type="hidden" name="totAbsent[]" value="{{$days-$totDays}}"></td>
                <td>{{$extraW}}<input type="hidden" name="extraWork[]" value="{{$extraW}}"></td>
                <td>{{$totDays+$extraW}}<input type="hidden" name="total[]" value="{{$totDays+$extraW}}"></td>
            </tr>
             $k=0; 
        }
    }
}