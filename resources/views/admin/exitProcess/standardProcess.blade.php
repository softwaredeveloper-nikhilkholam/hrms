`<?php
    use App\Helpers\Utility;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $language = $user->language;
    $userType = $user->userType;
    $userId = $user->id;
    $empId = $user->empId;
    $uName = $user->name;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">Standard Process</h4>
            </div> 
            <div class="page-rightheader">
                <h4 class="page-title">Archive</h4>
            </div> 
        </div>
        <!--End Page header-->
        @if(isset($resins))
            @if($resins)
                {!! Form::open(['action' => 'admin\HrPoliciesController@storeExitProcess', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mt-5">
                                        <div class="col-md-6 col-lg-6"><h4 style="color:red;">EXIT PROCESS STATUS [<b style="color:blue;"> {{($resins->processType == 1)?'Standard Resignation':(($resins->processType == 2)?'Absconding':(($resins->processType == 3)?'Sabitical':'Termination'))}} </b>]</h4></div>
                                        <div class="col-md-2 col-lg-2"><button class="btn btn-primary"></button> : In Progress</div>
                                        <div class="col-md-2 col-lg-2"><button class="btn btn-success"></button> : Completed</div>
                                        <div class="col-md-2 col-lg-2"><button class="btn btn-default"></button> : Pending</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    @if($resins->status == 0)
                                        <hr style=" border: 5px solid gray;border-radius: 5px;">
                                    @elseif($resins->status == 1)
                                        <hr style=" border: 5px solid #3567fe;border-radius: 5px;">
                                    @else
                                        <hr style=" border: 5px solid #0fce95;border-radius: 5px;">
                                    @endif
                                    <div class="row mt-5">
                                        @if($resins->section != 'Teaching')
                                            <div class="col-md-8 col-lg-8"><h4 style="color:red;">{{$noticeP}} Months Notice Period for Non Teaching</h4></div>
                                            <?php $months="+".$noticeP." Months"; ?>
                                        @else
                                            <div class="col-md-8 col-lg-8"><h4 style="color:red;">{{$noticeP}} Months Notice Period for Teaching</h4></div>
                                            <?php $months="+".$noticeP."  Months"; ?>
                                        @endif
                                        <div class="col-md-2 col-lg-2"><b style="color:green;">Applied Date: {{date('d-m-Y', strtotime($resins->applyDate))}}</b></div>
                                        <div class="col-md-2 col-lg-2"><a href="/employees/{{$resins->empId}}" class="btn btn-success" target="_blank">More Details</a></div>
                                    </div>
                                    <hr>
                                    <div class="row mt-5">
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label font-weight-bold">Employee Code: <h5 style="color:red;">{{ $resins->empCode }}</h5></label>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label font-weight-bold">Employee Name: <h5 style="color:red;">{{ $resins->name }}</h5></label>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label font-weight-bold">Branch: <h5 style="color:red;">{{ $resins->branchName }}</h5></label>
                                        </div>
                                         <div class="col-md-2 mb-3">
                                            <label class="form-label font-weight-bold">Department: <h5 style="color:red;">{{ $resins->departmentName }}</h5></label>
                                        </div>
                                         <div class="col-md-2 mb-3">
                                            <label class="form-label font-weight-bold">Designation: <h5 style="color:red;">{{ $resins->designationName }}</h5></label>
                                        </div>
                                         <div class="col-md-2 mb-3">
                                            <label class="form-label font-weight-bold">Joining Date: <h5 style="color:red;">{{  date('d-m-Y', strtotime($resins->jobJoingDate)) }}</h5></label>
                                        </div>
                                    </div>
                                    <div class="row">
                                       <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description <span class="text-red">*</span>:</label>
                                                <div class="form-control mb-4" style="white-space: pre-wrap; height: auto; min-height: 200px; overflow-y: auto;">
                                                    @php
                                                        $filePath = public_path("admin/resignationLetters/" . $resins->fileName);
                                                        echo file_exists($filePath) ? e(file_get_contents($filePath)) : 'File not found.';
                                                    @endphp
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label">Last Working Date<span class="text-red">*</span></label>
                                                @if($userType == "51")
                                                    <input type="date" class="form-control" placeholder="Employee Code" value="{{date('Y-m-d', strtotime($resins->expectedLastDate))}}" name="expectedLastDate" required>
                                                @else
                                                   <h5 style="color:red;">{{date('d-m-Y', strtotime($resins->expectedLastDate))}}</h5>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2">
                                            <div class="form-group">
                                                <label class="form-label">Requested Last Date<span class="text-red">*</span></label>
                                                @if($userType == "51")
                                                    <input type="date" class="form-control" placeholder="Employee Code" value="{{date('Y-m-d', strtotime($resins->reqExitDate))}}" name="reqExitDate" required>
                                                @else
                                                   <h5 style="color:red;">{{date('d-m-Y', strtotime($resins->reqExitDate))}}</h5>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($empId == $resins->empId)
                                        <div class="table-responsive">
                                            <table class="table table-vcenter text-nowrap table-bordered border-top border-bottom" id="">
                                                <thead>
                                                    <tr>
                                                        <th class="border-bottom-0"  width="5%">Reporting Authority</th>
                                                        <th class="border-bottom-0"  width="5%">Store Dept</th>
                                                        <th class="border-bottom-0"  width="5%">IT Dept</th>
                                                        <th class="border-bottom-0"  width="5%">ERP Dept</th>
                                                        <th class="border-bottom-0"  width="5%">HR Dept</th>
                                                        <th class="border-bottom-0"  width="5%">MD/CEO/COO</th>
                                                        <th class="border-bottom-0"  width="5%">Accounts Dept</th>
                                                        <th class="border-bottom-0" width="5%">Status<?php $i=1; ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $temp = $resins;?>
                                                    <tr>
                                                        <td><b style="color:{{($temp->reportingAuth == 0)?'red':(($temp->reportingAuth == 1)?'purple':'green')}};">{{($temp->reportingAuth == 0)?'Pending':(($temp->reportingAuth == 1)?'In Progress':'Completed')}}<br>{{($temp->reportingAuthDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->storeDept == 0)?'red':(($temp->storeDept == 1)?'purple':'green')}};">{{($temp->storeDept == 0)?'Pending':(($temp->storeDept == 1)?'In Progress':'Completed')}}<br>{{($temp->storeDeptDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->itDept == 0)?'red':(($temp->itDept == 1)?'purple':'green')}};">{{($temp->itDept == 0)?'Pending':(($temp->itDept == 1)?'In Progress':'Completed')}}<br>{{($temp->itDeptDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->erpDept == 0)?'red':(($temp->erpDept == 1)?'purple':'green')}};">{{($temp->erpDept == 0)?'Pending':(($temp->erpDept == 1)?'In Progress':'Completed')}}<br>{{($temp->erpDeptDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->hrDept == 0)?'red':(($temp->hrDept == 1)?'purple':'green')}};">{{($temp->hrDept == 0)?'Pending':(($temp->hrDept == 1)?'In Progress':'Completed')}}<br>{{($temp->hrDeptDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->finalPermission == 0)?'red':(($temp->finalPermission == 1)?'purple':'green')}};">{{($temp->finalPermission == 0)?'Pending':(($temp->finalPermission == 1)?'In Progress':'Completed')}}<br>{{($temp->finalPermissionDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->accountDept == 0)?'red':(($temp->accountDept == 1)?'purple':'green')}};">{{($temp->accountDept == 0)?'Pending':(($temp->accountDept == 1)?'In Progress':'Completed')}}<br>{{($temp->accountDeptDate != null)?date('d-m-Y H:i'):'-'}}</td>
                                                        <td><b style="color:{{($temp->status == 0)?'red':(($temp->status == 1)?'purple':'green')}};">{{($temp->status == 0)?'Pending':(($temp->status == 1)?'In Progress':'Completed')}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>   
                    @if($empId != $resins->empId)    
                        <div class="row">
                            <div class="col-md-12">             
                                <div class="card">
                                    <div class="card-body">
                                        @if($resins->reportingAuth == 0)
                                            <hr style=" border: 5px solid gray;border-radius: 5px;">
                                        @elseif($resins->reportingAuth == 1)
                                            <hr style=" border: 5px solid #3567fe;border-radius: 5px;">
                                        @else
                                            <hr style=" border: 5px solid #0fce95;border-radius: 5px;">
                                        @endif
                                        <h4 style="color:purple;"><center><b>Reporting Authority Of {{$resins->reportingAuthorityName}}</b></center></h4>
                                        @if($resins->section == 'Teaching')
                                            <div class="row mt-5">
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Details<span class="text-red">*</span></label>
                                                        {{Form::select('details', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->details:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Book Set<span class="text-red">*</span></label>
                                                        {{Form::select('bookSet', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->bookSet:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Keys<span class="text-red">*</span></label>
                                                        {{Form::select('officeKeys', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->officeKeys:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Muster<span class="text-red">*</span></label>
                                                        {{Form::select('muster', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->muster:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Report Card<span class="text-red">*</span></label>
                                                        {{Form::select('reportCard', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->reportCard:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Planner<span class="text-red">*</span></label>
                                                        {{Form::select('planner', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->planner:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Library Books<span class="text-red">*</span></label>
                                                        {{Form::select('libraryBooks', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->libraryBooks:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Teacher's Kit<span class="text-red">*</span></label>
                                                        {{Form::select('teachersKit', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->teachersKit:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">log book<span class="text-red">*</span></label>
                                                        {{Form::select('logBook', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->logBook:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="row mt-5">
                                            <div class="col-md-2 col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Original Documents<span class="text-red">*</span></label>
                                                    {{Form::select('originalDocs', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->originalDocs:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                    <input type="hidden" name="processStep" value="1">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Experience Certificate<span class="text-red">*</span></label>
                                                    {{Form::select('experienceCertificate', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->experienceCertificate:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Retention Amount<span class="text-red">*</span></label>
                                                    {{Form::select('retentionAmt', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->retentionAmt:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2">
                                                <div class="form-group">
                                                    <label class="form-label">Salary<span class="text-red">*</span></label>
                                                    {{Form::select('salary', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->salary:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'])}}
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label">Reporting Authority Comments<span class="text-red">*</span></label>
                                                    <textarea name="comment1" class="form-control" maxlength="1000" {{($userId == $resins->reportingAuthId || $userType == '51')?'required':'disabled'}}>{{(isset($exitProcess1)?$exitProcess1->comment:null)}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   

                            @if($resins->storeDept == 1 || $resins->storeDept == 2 || $userType == '51')
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">   
                                            <h4 style="color:purple;"><center><b>For Store Department</b></center></h4>
                                            @if($resins->storeDept == 0)
                                                <hr style=" border: 5px solid gray;border-radius: 5px;">
                                            @elseif($resins->storeDept == 1)
                                                <hr style=" border: 5px solid #3567fe;border-radius: 5px;">
                                            @else
                                                <hr style=" border: 5px solid #0fce95;border-radius: 5px;">
                                            @endif
                                            <div class="row mt-5">
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Apron (If no charge applicable)<span class="text-red">*</span></label>
                                                        {{Form::select('apron', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess2)?$exitProcess2->apron:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '91' && $resins->storeDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">I-CARD( If no charge applicable )<span class="text-red">*</span></label>
                                                        {{Form::select('iCard', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess2)?$exitProcess2->iCard:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '91' && $resins->storeDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                        <input type="hidden" name="processStep" value="2">
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Comments for Store Department<span class="text-red">*</span></label>
                                                        <textarea name="comment2" class="form-control" maxlength="1000" {{(($userType == '91' && $resins->storeDept == 1)  || $userType == '51')?'required':'disabled'}}>{{(isset($exitProcess2)?$exitProcess2->comment:null)}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
                            @endif

                            @if($resins->itDept == 1 || $resins->itDept == 2 || $userType == '51')
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">    
                                            <h4 style="color:purple;"><center><b>For IT Department</b></center></h4>
                                            @if($resins->itDept == 0)
                                                <hr style=" border: 5px solid gray;border-radius: 5px;">
                                            @elseif($resins->itDept == 1)
                                                <hr style=" border: 5px solid #3567fe;border-radius: 5px;">
                                            @else
                                                <hr style=" border: 5px solid #0fce95;border-radius: 5px;">
                                            @endif
                                            <div class="row mt-5">
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Mail Id Deleted<span class="text-red">*</span></label>
                                                        {{Form::select('mailId', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess3)?$exitProcess3->mailId:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                        <input type="hidden" name="processStep" value="3">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Remove from Whatsup Group<span class="text-red">*</span></label>
                                                        {{Form::select('removeWhatsapp', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess3)?$exitProcess3->removeWhatsapp:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Asset<span class="text-red">*</span></label>
                                                        {{Form::select('asset', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess3)?$exitProcess3->asset:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Laptop<span class="text-red">*</span></label>
                                                        {{Form::select('laptop', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess3)?$exitProcess3->laptop:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Mouse<span class="text-red">*</span></label>
                                                        {{Form::select('mouse', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess3)?$exitProcess3->mouse:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Bag<span class="text-red">*</span></label>
                                                        {{Form::select('bag', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess3)?$exitProcess3->bag:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Mobile<span class="text-red">*</span></label>
                                                        {{Form::select('mobile', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess3)?$exitProcess3->mobile:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Sim<span class="text-red">*</span></label>
                                                        {{Form::select('sim', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess3)?$exitProcess3->sim:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Debit Charge<span class="text-red">*</span></label>
                                                        {{Form::select('debitCharge', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess3)?$exitProcess3->debitCharge:null), ['placeholder'=>'Select Submition Status','class'=>'form-control debitCharge', (($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-lg-4 debitRs">
                                                    <div class="form-group">
                                                        <label class="form-label">Amount<span class="text-red">*</span></label>
                                                        <input type="text" value="{{(isset($exitProcess3)?$exitProcess3->debitAmount:0)}}" name="debitAmount" onkeypress="return jobIsNumberKey(event)" class="form-control" {{(($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'}}>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Comments for IT Department<span class="text-red">*</span></label>
                                                        <textarea name="comment3" class="form-control" maxlength="1000" {{(($userType == '71' && $resins->itDept == 1)  || $userType == '51')?'required':'disabled'}}>{{(isset($exitProcess3)?$exitProcess3->comment:null)}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($resins->erpDept == 1 || $resins->erpDept == 2 || $userType == '51')
                                <div class="col-md-12"> 
                                    <div class="card">
                                        <div class="card-body">    
                                            <h4 style="color:purple;"><center><b>For ERP Department</b></center></h4>
                                            @if($resins->erpDept == 0)
                                                <hr style=" border: 5px solid gray;border-radius: 5px;">
                                            @elseif($resins->erpDept == 1)
                                                <hr style=" border: 5px solid #3567fe;border-radius: 5px;">
                                            @else
                                                <hr style=" border: 5px solid #0fce95;border-radius: 5px;">
                                            @endif
                                            <div class="row mt-5">
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Remove from Concession List<span class="text-red">*</span></label>
                                                        <input type="hidden" name="processStep" value="4">
                                                        {{Form::select('concession', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess4)?$exitProcess4->concession:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '81' && $resins->erpDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Remove from Biometric<span class="text-red">*</span></label>
                                                        {{Form::select('biometric', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess4)?$exitProcess4->biometric:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '81' && $resins->erpDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Remove from ERP<span class="text-red">*</span></label>
                                                        {{Form::select('erp', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess4)?$exitProcess4->erp:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', (($userType == '81' && $resins->erpDept == 1)  || $userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Uploading an Attachment<span class="text-red">*</span></label>
                                                        @if(isset($exitProcess4))
                                                            <a href="/admin/exitProcessDocs/ERPDocs/{{$exitProcess4->uploadERPAttachment}}"><b style="color:red;">Click Here</b></a> 
                                                        @else
                                                            <input type="file" value="" name="uploadERPAttachment" onkeypress="return jobIsNumberKey(event)" class="form-control" {{(($userType == '81' && $resins->erpDept == 1)  || $userType == '51')?'required':'disabled'}}>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Comments For ERP<span class="text-red">*</span></label>
                                                        <textarea name="comment4" class="form-control" maxlength="1000" {{(($userType == '81' && $resins->erpDept == 1)  || $userType == '51')?'required':'disabled'}}>{{(isset($exitProcess4)?$exitProcess4->comment:null)}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($resins->hrDept == 1 || $resins->hrDept == 2 || $userType == '51')
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body"> 
                                            <h4 style="color:purple;"><center><b>For HR Department</b></center></h4>
                                            @if($resins->hrDept == 0)
                                                <hr style=" border: 5px solid gray;border-radius: 5px;">
                                            @elseif($resins->hrDept == 1)
                                                <hr style=" border: 5px solid #3567fe;border-radius: 5px;">
                                            @else
                                                <hr style=" border: 5px solid #0fce95;border-radius: 5px;">
                                            @endif
                                            <div class="row mt-5">
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">Salary Calculation<span class="text-red">*</span></label>
                                                        {{Form::select('salCalculation', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess5)?$exitProcess5->salCalculation:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userType == '51')?'required':'disabled'])}}
                                                        <input type="hidden" name="processStep" value="5">
                                                    
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">FUL & FINAL SETTELMENT<span class="text-red">*</span></label>
                                                        {{Form::select('fullAndfinal', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess5)?$exitProcess5->fullAndfinal:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-lg-2">
                                                    <div class="form-group">
                                                        <label class="form-label">ORIGINAL DOCUMENTS<span class="text-red">*</span></label>
                                                        {{Form::select('originalDoc', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess5)?$exitProcess5->originalDoc:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userType == '51')?'required':'disabled'])}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Comments<span class="text-red">*</span></label>
                                                        <textarea name="comment5" class="form-control" maxlength="1000" {{($userType == '51')?'required':'disabled'}}>{{(isset($exitProcess5)?$exitProcess5->comment:null)}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($resins->finalPermission == 1 || $resins->finalPermission == 2 || ($userType == '51' && $resins->hrDept == 2))
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">  
                                            @if($resins->section == 'Non Teaching')       
                                                <h4 style="color:purple;"><center><b>For COO</b></center></h4>
                                                @if($resins->finalPermission == 0)
                                                    <hr style=" border: 5px solid gray;border-radius: 5px;">
                                                @elseif($resins->finalPermission == 1)
                                                    <hr style=" border: 5px solid #3567fe;border-radius: 5px;">
                                                @else
                                                    <hr style=" border: 5px solid #0fce95;border-radius: 5px;">
                                                @endif
                                                <div class="row mt-5">
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">ORIGINAL DOCUMENTS<span class="text-red">*</span></label>
                                                            {{Form::select('originalDoc', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess6)?$exitProcess6->originalDoc:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userType == '501' || $userType == '51')?'required':'disabled'])}}
                                                            <input type="hidden" name="processStep" value="6">
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Experience Certificate<span class="text-red">*</span></label>
                                                            {{Form::select('experienceCertificate', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess6)?$exitProcess6->experienceCertificate:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userType == '501' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Retention Amount<span class="text-red">*</span></label>
                                                            {{Form::select('retentionAmt', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess6)?$exitProcess6->retentionAmt:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userType == '501' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Salary<span class="text-red">*</span></label>
                                                            {{Form::select('salary', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess6)?$exitProcess6->salary:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userType == '501' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Remove from Concession List<span class="text-red">*</span></label>
                                                            {{Form::select('concession', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess6)?$exitProcess6->concession:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userType == '501' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-5">
                                                    <div class="col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Comments<span class="text-red">*</span></label>
                                                            <textarea name="comment6" class="form-control" maxlength="1000" {{($userType == '501' || $userType == '51')?'required':'disabled'}}>{{(isset($exitProcess6)?$exitProcess6->comment:null)}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <h4 style="color:purple;"><center><b>For MD / CEO</b></center></h4>
                                                <hr>
                                                <div class="row mt-5">
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">ORIGINAL DOCUMENTS<span class="text-red">*</span></label>
                                                            {{Form::select('originalDoc', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess5)?$exitProcess5->originalDoc:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userType == '51' || $userType == '401' || $userType == '201')?'required':'disabled'])}}
                                                            <input type="hidden" name="processStep" value="6">
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Experience Certificate<span class="text-red">*</span></label>
                                                            {{Form::select('experienceCertificate', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->experienceCertificate:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '401' || $userType == '201' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Retention Amount<span class="text-red">*</span></label>
                                                            {{Form::select('retentionAmt', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->retentionAmt:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '401' || $userType == '201' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Salary<span class="text-red">*</span></label>
                                                            {{Form::select('salary', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess1)?$exitProcess1->salary:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userId == $resins->reportingAuthId || $userType == '401' || $userType == '201' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Remove from Concession List<span class="text-red">*</span></label>
                                                            {{Form::select('concession', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess4)?$exitProcess4->concession:null), ['placeholder'=>'Select Submition Status','class'=>'form-control', ($userType == '81' || $userType == '401' || $userType == '201' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-5">
                                                    <div class="col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Comments<span class="text-red">*</span></label>
                                                            <textarea name="comment6" class="form-control" maxlength="1000" {{($userType == '51' || $userType == '401' || $userType == '201' || $userType == '51')?'required':'disabled'}}>{{(isset($exitProcess6)?$exitProcess6->comment:null)}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($resins->accountDept == 1 || $resins->accountDept == 2 ||  ($userType == '51' && $resins->hrDept == 2))
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">  
                                            <h4 style="color:purple;"><center><b>For Accounts Department</b></center></h4>
                                            @if($resins->accountDept == 0)
                                                <hr style=" border: 5px solid gray;border-radius: 5px;">
                                            @elseif($resins->accountDept == 1)
                                                <hr style=" border: 5px solid #3567fe;border-radius: 5px;">
                                            @else
                                                <hr style=" border: 5px solid #0fce95;border-radius: 5px;">
                                            @endif
                                            <div class="row mt-5">
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Retention<span class="text-red">*</span></label>
                                                            {{Form::select('retention', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess7)?$exitProcess7->retention:null), ['placeholder'=>'Select Submition Status', 'id'=>'retention','class'=>'form-control', ($userType == '61' || $userType == '51')?'required':'disabled'])}}
                                                            <input type="hidden" name="processStep" value="8">
                                                        
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Retention Amount<span class="text-red">*</span></label>
                                                            {{Form::text('retentionAmt', (isset($exitProcess7)?$exitProcess7->retentionAmt:0), ['placeholder'=>'Retention Amount','class'=>'form-control', 'id'=>'retentionAmt', 'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Salary<span class="text-red">*</span></label>
                                                            {{Form::select('salary', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess7)?$exitProcess7->salary:null), ['placeholder'=>'Select Submition Status','id'=>'salary', 'class'=>'form-control', ($userType == '61' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Salary Amount<span class="text-red">*</span></label>
                                                            {{Form::text('salaryAmt', (isset($exitProcess7)?$exitProcess7->salaryAmt:0), ['placeholder'=>'Salary Amount','class'=>'form-control', 'id'=>'salaryAmt', 'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Salary Advance<span class="text-red">*</span></label>
                                                            {{Form::select('salaryAdvance', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess7)?$exitProcess7->salaryAdvance:null), ['placeholder'=>'Select Submition Status','id'=>'salaryAdvance','class'=>'form-control', ($userType == '61' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Salary Advance Amount<span class="text-red">*</span></label>
                                                            {{Form::text('salaryAdvanceAmt', (isset($exitProcess7)?$exitProcess7->salaryAdvanceAmt:0), ['placeholder'=>'Salary Advance Amount','class'=>'form-control','id'=>'salaryAdvanceAmt' ])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Any Debit<span class="text-red">*</span></label>
                                                            @if($exitProcess7)
                                                                {{Form::select('anyDebit', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], $exitProcess7->anyDebit, ['placeholder'=>'Select Submition Status','id'=>'anyDebit','class'=>'form-control', ($userType == '61' || $userType == '51')?'required':'disabled'])}}
                                                            @else
                                                                {{Form::select('anyDebit', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess3))?$exitProcess3->debitCharge:null, ['placeholder'=>'Select Submition Status','id'=>'anyDebit','class'=>'form-control', ($userType == '61' || $userType == '51')?'required':'disabled'])}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Debit Amount<span class="text-red">*</span></label>
                                                            @if(!empty($exitProcess7))
                                                                {{Form::text('anyDebitAmt', $exitProcess7->salaryAdvanceAmt, ['placeholder'=>'Debit Amount','class'=>'form-control', 'id'=>'anyDebitAmt', 'readonly'])}}
                                                            @else
                                                                {{Form::text('anyDebitAmt', (isset($exitProcess3))?$exitProcess3->debitAmount:null, ['placeholder'=>'Debit Amount','class'=>'form-control', 'id'=>'anyDebitAmt', ($userType == '61' || $userType == '51')?'required':'disabled'])}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Full & Final Settelment<span class="text-red">*</span></label>
                                                            {{Form::select('fullAndSet', ['Yes'=>'Yes', 'Not Applicable'=>'Not Applicable', 'No'=>'No'], (isset($exitProcess7)?$exitProcess7->fullAndSet:null), ['placeholder'=>'Select Submition Status','id'=>'fullAndSet','class'=>'form-control', ($userType == '61' || $userType == '51')?'required':'disabled'])}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 col-lg-2">
                                                        <div class="form-group">
                                                            <label class="form-label">Full & Final Settelment Amount<span class="text-red">*</span></label>
                                                            {{Form::text('fullAndSetAmt', (isset($exitProcess7)?$exitProcess7->fullAndSetAmt:null), ['placeholder'=>'Full & Final Settelment Amount','class'=>'form-control', 'id'=>'fullAndSetAmt','disabled'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <div class="row mt-5">
                                                    <div class="col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            <label class="form-label">Comments<span class="text-red">*</span></label>
                                                            <textarea name="comment7" class="form-control" maxlength="1000" {{($userType == '61' || $userType == '51')?'required':'disabled'}}>{{(isset($exitProcess7)?$exitProcess7->comment:null)}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-5">
                                                    <div class="col-md-6 col-lg-6 d-flex justify-content-between align-items-center">
                                                        <h4 style="color:red;">Details of Cheque Issue&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-success btn-sm" id="addChequeEntry">+ Add</button></h4>
                                                    </div>
                                                </div>

                                                <div id="chequeContainer">
                                                    <div class="row cheque-entry">
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                <label class="form-label">S.No</label>
                                                                <input type="text" class="form-control sno" value="1" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="form-label">Payee Name</label>
                                                                <input type="text" name="cheques[0][payeeName]" value="{{$resins->name}}" class="form-control" placeholder="Payee Name" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="form-label">Date</label>
                                                                <input type="date" name="cheques[0][payeeDate]" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="form-label">Amount</label>
                                                                <input type="text" name="cheques[0][payeeAmount]" class="form-control" placeholder="Amount" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="form-label">Bank Details</label>
                                                                <input type="text" name="cheques[0][payeeBank]" class="form-control" placeholder="Bank Details" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="form-label">Cheque No</label>
                                                                <input type="text" name="cheques[0][payeeChequeNo]" class="form-control" placeholder="Cheque No" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1 d-flex align-items-end">
                                                            <button type="button" class="btn btn-danger btn-sm remove-entry">Remove</button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        @if($empId == $resins->empId && $resins->accountDept == '2' && ($userType == '51' && $resins->hrDept == '2'))
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body">  
                                            <h4 style="color:purple;"><center><b>For Acknowledge & Acceptance By {{$uName}}</b></center></h4>
                                            <hr>
                                        
                                            <div class="row mt-5">
                                                <div class="col-md-12 col-lg-12">
                                                    <h5>I share cordial relationship with the organization.</h5>
                                                    <h5>I resign from the job Willingly.</h5><br><br>

                                                    <h5>With this I, {{$uName}}	confirm that nothing is pending towards me and this is my full & final settlement. 
                                                    Henceforth I shall not make any financial & Legal claim to the organization. 
                                                    I will not cause the organization any discrepancy or any other consequences in the future. 
                                                    I have received all documents and dues.
                                                    Thanks for your cooperation.</h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-12 col-md-12 col-lg-12">
                                                    <div class="card">
                                                        <div class="card-body">         
                                                            <div class="row" style="margin-top:10px;">
                                                                <div class="col-md-4"></div>
                                                                <div class="col-md-4">
                                                                    <input type="hidden" name="empId" value="{{$resins->empId}}">
                                                                    <input type="hidden" name="empCode" value="{{$resins->empCode}}">
                                                                    <input type="hidden" name="parentId" value="{{$resins->id}}">
                                                                    <input type="hidden" name="processType" value="1">
                                                                    <input type="hidden" name="processStep" value="9">
                                                                    <button type="Submit" class="empAdd btn btn-success btn-lg">Accept & Save</button>
                                                                    <a href="{{ url()->previous() }}" class="btn btn-danger btn-lg">Cancel</a>
                                                                </div>
                                                                <div class="col-md-4"></div>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($empId != $resins->empId)
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-lg-12">
                                    <div class="card">
                                        <div class="card-body"> 
                                            @if($userType == '51')
                                                <div class="row" style="margin-top:10px;">
                                                    <div class="col-md-3"></div>
                                                    <div class="col-md-2">Forcefully Closed Remark:</div>        
                                                    <div class="col-md-6"><input type="text" value="" class="form-control" placeholder="Forcefully Closed Remark" name="forcefullyRemark"></div>        
                                                </div>
                                            @endif
                                            <div class="row" style="margin-top:10px;">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <input type="hidden" name="empId" value="{{$resins->empId}}">
                                                    <input type="hidden" name="empCode" value="{{$resins->empCode}}">
                                                    <input type="hidden" name="parentId" value="{{$resins->id}}">
                                                    <input type="hidden" name="processType" value="1">
                                                    <button type="Submit" class="empAdd btn btn-success btn-lg" name="action" value="1">{{(isset($exitProcess1)?"Update":"Save")}}</button>
                                                    @if($userType == '51')
                                                        <button type="submit" id="submitBtn" class="empAdd btn btn-danger btn-lg" name="action" value="0">
                                                            <span class="spinner-border spinner-border-sm d-none" id="spinner" role="status" aria-hidden="true"></span>
                                                            <span id="btnText">Forcefully Closed</span>
                                                        </button>
                                                    @endif
                                                   
                                                    <a href="{{ route('exit.printNDC', $resins->id) }}" class="btn btn-primary btn-lg" target="_blank">
                                                        <i class="fa fa-print mr-2"></i> Print NDC
                                                    </a>
                                                    <a href="{{ url()->previous() }}" class="btn btn-danger btn-lg">Cancel</a>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                {!! Form::close() !!} 
            @endif
        @endif
	</div>
</div>
<script>
    let chequeIndex = 1;
    function confirmSubmit(event) {
        const confirmed = confirm("Are you sure you want to forcefully close this?");
        if (!confirmed) {
            return false; // cancel submission
        }

        // Optional: show spinner and disable button
        let btn = document.getElementById("submitBtn");
        btn.disabled = true;
        document.getElementById("spinner").classList.remove("d-none");
        document.getElementById("btnText").textContent = "Processing...";
        
        return true; // allow submission
    }

    function updateSerialNumbers() {
        const entries = document.querySelectorAll('#chequeContainer .cheque-entry');
        entries.forEach((entry, idx) => {
            // Update serial number
            entry.querySelector('.sno').value = idx + 1;

            // Update name attributes for form fields
            entry.querySelectorAll('input').forEach(input => {
                let name = input.getAttribute('name');
                if (name && name.includes('cheques')) {
                    const key = name.split('[')[2]; // e.g. name]
                    input.setAttribute('name', `cheques[${idx}][${key}`);
                }
            });
        });
    }

    document.getElementById('addChequeEntry').addEventListener('click', () => {
        const container = document.getElementById('chequeContainer');
        const newEntry = container.querySelector('.cheque-entry').cloneNode(true);

        // Clear values
        newEntry.querySelectorAll('input').forEach(input => {
            if (!input.classList.contains('sno')) {
                input.value = '';
            }
        });

        container.appendChild(newEntry);
        updateSerialNumbers();
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-entry')) {
            const allEntries = document.querySelectorAll('.cheque-entry');
            if (allEntries.length > 1) {
                e.target.closest('.cheque-entry').remove();
                updateSerialNumbers();
            }
        }
    });

    // Initial serial number
    updateSerialNumbers();
</script>
@endsection
