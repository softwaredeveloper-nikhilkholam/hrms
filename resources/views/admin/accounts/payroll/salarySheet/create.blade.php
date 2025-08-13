<?php
    use App\Helpers\Utility;
    use App\Retention;
    $util = new Utility();
    
    $name = Session()->get('name');
    $user = Auth::user();
    $userType = $user->userType;
    $language = $user->language;
?>
@extends('layouts.master')
@section('title', 'Management')
@section('content') 
<div class="container">
	<div class="container">                        
		<!--Page header-->
        <div class="page-header d-xl-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Salary Sheet</h4>
                </div>
                <div class="page-rightheader ml-md-auto">
                    <div class="align-items-end flex-wrap my-auto right-content breadcrumb-right">
                        <div class="btn-list">
                        </div>
                    </div>
                </div>
            </div>
        <!--End Page header-->
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::open(['action' => 'admin\AccountsController@salarySheet', 'method' => 'GET', 'class' => 'form-horizontal']) !!}
                            <div class="row mt-5">
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Organisation :</label>
                                        {{Form::select('organisation', $organisations, (isset($organisation))?$organisation:null, ['placeholder'=>'Select Organisation','class'=>'form-control', 'id'=>'organisation'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Section:</label>
                                        {{Form::select('section', ['Teaching'=>'Teaching', 'Non Teaching'=>'Non Teaching'], ((isset($section))?$section:null), ['placeholder'=>'Select Section','class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Branches:</label>
                                        {{Form::select('branchId', $branches, ((isset($branchId))?$branchId:null), ['placeholder'=>'Select Branch','class'=>'form-control'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Month<b style="color:Red;">*</b>:</label>
                                        <input type="month" class="form-control" name="month" value="{{(isset($month))?$month:date('M-Y')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label">Salary Type:</label>
                                        {{Form::select('salaryType', ['0'=>'Regular', '1'=>'Arriers'], ((isset($salaryType))?$salaryType:null), ['placeholder'=>'Select Salary Type','class'=>'form-control', 'required'])}}
                                    </div>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <div class="form-group mt-5">
                                        <input type="hidden" value="2" name="flag">
                                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!} 
                    </div>
                    <div class="card-body">
                        @if(isset($salarySheet))
                            @if(count($salarySheet) > 0)
                                <style>
                                        .table-container {
                                            overflow-x: auto;
                                            width: 100%;
                                        }

                                        table {
                                            border-collapse: collapse;
                                            width: max-content;
                                            min-width: 100%;
                                        }

                                        th, td {
                                            white-space: nowrap;
                                            padding: 8px;
                                            border: 1px solid #ccc;
                                            background: #fff;
                                        }

                                        thead th {
                                            background-color:#2e8b57fc;
                                            color:white;
                                            position: sticky;
                                            top: 0;
                                            z-index: 3;
                                        }

                                        /* Sticky Columns */
                                        .sticky-col {
                                            position: sticky;
                                            background: #f1f1f1;
                                            z-index: 2;
                                        }

                                        .sticky-col-1 { left: 0; z-index: 4; }
                                        .sticky-col-2 { left: 75px; }
                                        .sticky-col-3 { left: 290px; }
                                        .sticky-col-4 { left: 400px; }
                                </style>
                               <div class="table-container">
                                <table class="table card-table table-vcenter text-nowrap table-primary mb-0" id="example">
                                        <thead >
                                            <tr style="background-color:#2e8b57fc !important;color:white !important;">
                                                <th style="background-color:#2e8b57fc !important;color:white !important;" class="border-bottom-0  text-center">Sr. No</th>
                                                <th style="background-color:#2e8b57fc !important;color:white !important;" class="border-bottom-0  text-center">Employee Name</th>
                                                <th style="background-color:#2e8b57fc !important;color:white !important;" class="border-bottom-0  text-center">Organisation</th>
                                                <th style="background-color:#2e8b57fc !important;color:white !important;" class="border-bottom-0  text-center">Code</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Designation</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Department</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Category</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Branch</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Joining Date</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Basic Salary (2024-2025)</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Basic Salary (2025-2026)</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Per Day</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Total Days in Month</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Present Days</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">WF</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Absent Days</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Extra Working</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Gross Payble Salary</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">WL Amount</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Advance Against Salary</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Other Deductions</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">TDS</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">MLWF</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">ESIC</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">PT</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">PF</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Retention</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Total Deduction</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Extra Work Salary</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Net Salary</th>                                                
                                                <th class="border-bottom-0  text-center" style="color:white;">A/C No</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">IFSC CODE</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Bank</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Branch</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Remark</th>
                                                <th class="border-bottom-0  text-center" style="color:white;">Status<?php $i=1; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            @foreach($salarySheet as $sheet)
                                                @if(date('Y-m') <= '2025-05')
                                                    <?php 
                                                        if($sheet->type == '1')
                                                        {
                                                            $sheet->totPresent = $sheet->totalDays; 
                                                            $holdStatus = $util->getSalaryHoldStatus($sheet->empId, $sheet->forDate);
                                                            //  $sheet->retention = Retention::where('empId', $sheet->empId)->where('month', $sheet->forDate)->value('retentionAmount');
                                                        }
                                                        else
                                                        {
                                                            $sheet->grossSalary = $sheet->tempSalary; 
                                                            $sheet->totPresent = $sheet->totalDays; 
                                                            $holdStatus = $util->getSalaryHoldStatus($sheet->empId, $sheet->forDate);
                                                            $sheet->retention = Retention::where('empId', $sheet->empId)->where('month', $sheet->forDate)->value('retentionAmount');
                                                        }

                                                    ?>
                                                    <tr>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="sticky-col sticky-col-1">{{$i++}}</th>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="sticky-col sticky-col-2">{{$sheet->name}} <b style="color:blue;">{{($holdStatus == '1')?'Hold':''}}</b></th>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="sticky-col sticky-col-3">{{($sheet->empOrganisation)}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="sticky-col sticky-col-4">{{$sheet->empCode}}</th>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$sheet->designationName}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$sheet->departmentName}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$sheet->section}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$sheet->shortName}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->jobJoingDate != '')?(date('d-m-Y', strtotime($sheet->jobJoingDate))):'NA'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->grossSalary != '0')?$util->numberFormat($sheet->grossSalary):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->grossSalary != '0')?$util->numberFormat($sheet->grossSalary/(date('t', strtotime($sheet->forDate)))):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{date('t', strtotime($sheet->forDate))}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{number_format(($sheet->totPresent - $sheet->extraWorking), 1)}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->totWLeave != '0')?$util->numberFormat($sheet->totWLeave):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{number_format($sheet->totAbsent, 1)}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{number_format($sheet->extraWorking, 1)}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{number_format($totRs=($sheet->totPresent-$sheet->extraWorking)*($sheet->grossSalary/(date('t', strtotime($sheet->forDate)))), 1)}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->totWLeave != '0')?$util->numberFormat($sheet->totWLeave*($sheet->grossSalary/(date('t', strtotime($sheet->forDate))))):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->advanceAgainstSalary != '0')?$util->numberFormat($sheet->advanceAgainstSalary):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->otherDeduction != 0)?$util->numberFormat($sheet->otherDeduction):0}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->TDS != '0')?$util->numberFormat($sheet->TDS):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->MLWF != '0')?$util->numberFormat($sheet->MLWF):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->ESIC != '0')?$util->numberFormat($sheet->ESIC):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->PT != '0')?$util->numberFormat($sheet->PT):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->PF != '0')?$util->numberFormat($sheet->PF):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->retention != '0')?$util->numberFormat($sheet->retention):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$util->numberFormat($totDedRs = $sheet->retention+$sheet->advanceAgainstSalary+$sheet->otherDeduction+$sheet->TDS+$sheet->MLWF+$sheet->ESIC+$sheet->PT+$sheet->PF)}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->extraWorking != '0')?$util->numberFormat($sheet->extraWorking*($sheet->grossSalary/(date('t', strtotime($sheet->forDate))))):'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$util->numberFormatRound(round((($totRs)+($sheet->extraWorking*($sheet->grossSalary/(date('t', strtotime($sheet->forDate)))))) - $totDedRs))}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->bankAccountNo != ' ')?$sheet->bankAccountNo:'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->bankIFSCCode != ' ')?$sheet->bankIFSCCode:'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->bankName != ' ')?$sheet->bankName:'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->bankBranch != ' ')?$sheet->bankBranch:'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->remark != ' ')?$sheet->remark:'-'}}</td>
                                                        <td  style="color:<?php echo ($holdStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($holdStatus == 0)?'Regular':'Hold'}}</td>
                                                    </tr>
                                                 @else
                                                    <tr>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="sticky-col sticky-col-1">{{$i++}}</th>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="sticky-col sticky-col-2">{{$sheet->name}} <b style="color:blue;">{{($sheet->salaryStatus == '1')?'Hold':''}}</b></th>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="sticky-col sticky-col-3">{{($sheet->empOrganisation)}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="sticky-col sticky-col-4">{{$sheet->empCode}}</th>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$sheet->designationName}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$sheet->departmentName}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$sheet->section}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$sheet->shortName}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->jobJoingDate != '')?(date('d-m-Y', strtotime($sheet->jobJoingDate))):'NA'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->prevGrossSalary != '0')?$util->numberFormat($sheet->prevGrossSalary):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->grossSalary != '0')?$util->numberFormat($sheet->grossSalary):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->grossSalary != '0')?$util->numberFormat($sheet->grossSalary/(date('t', strtotime($sheet->month)))):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{date('t', strtotime($sheet->month))}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{number_format($sheet->presentDays, 1)}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->totalDeductions != '0')?$util->numberFormat($sheet->totalDeductions):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{number_format($sheet->absentDays, 1)}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{number_format($sheet->extraWorkDays, 1)}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{number_format($totRs=($sheet->presentDays)*($sheet->grossSalary/(date('t', strtotime($sheet->month)))), 1)}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->totalDeductions != '0')?$util->numberFormat($sheet->totalDeductions*($sheet->grossSalary/(date('t', strtotime($sheet->month))))):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->advanceAgainstSalary != '0')?$util->numberFormat($sheet->advanceAgainstSalary):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->otherDeduction != 0)?$util->numberFormat($sheet->otherDeduction):0}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->TDS != '0')?$util->numberFormat($sheet->TDS):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->MLWL != '0')?$util->numberFormat($sheet->MLWL):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->ESIC != '0')?$util->numberFormat($sheet->ESIC):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->PT != '0')?$util->numberFormat($sheet->PT):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->PF != '0')?$util->numberFormat($sheet->PF):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->retention != '0')?$util->numberFormat($sheet->retention):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$util->numberFormat($totDedRs = $sheet->retention+$sheet->advanceAgainstSalary+$sheet->otherDeduction+$sheet->TDS+$sheet->MLWL+$sheet->ESIC+$sheet->PT+$sheet->PF)}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->extraWorkDays != '0')?$util->numberFormat($sheet->extraWorkDays*($sheet->grossSalary/(date('t', strtotime($sheet->month))))):'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{$util->numberFormatRound(round((($totRs)+($sheet->extraWorkDays*($sheet->grossSalary/(date('t', strtotime($sheet->month)))))) - $totDedRs))}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->bankAccountNo != ' ')?$sheet->bankAccountNo:'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->bankIFSCCode != ' ')?$sheet->bankIFSCCode:'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->bankName != ' ')?$sheet->bankName:'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->bankBranch != ' ')?$sheet->bankBranch:'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->remark != ' ')?$sheet->remark:'-'}}</td>
                                                        <td  style="color:<?php echo ($sheet->salaryStatus == '1')?'red !important':'black !important'; ?>;" class="text-center">{{($sheet->salaryStatus == 0)?'Regular':'Hold'}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row" style="margin-top:15px;">
                                    <div class='col-md-8'></div>
                                    <div class='col-md-4'>
                                        <a href="/accounts/exportSalarySheet/{{(isset($organisation))?$organisation:'0'}}/{{((isset($branchId))?$branchId:'0')}}/{{((isset($section))?$section:'0')}}/{{((isset($salaryType))?$salaryType:'0')}}/{{(isset($month))?$month:date('M-Y')}}" class="btn btn-danger"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Salary Sheet</a>&nbsp;
                                        <a href="/accounts/exportBankDetails/{{(isset($organisation))?$organisation:'0'}}/{{((isset($branchId))?$branchId:'0')}}/{{((isset($section))?$section:'0')}}/{{((isset($salaryType))?$salaryType:'0')}}/{{(isset($month))?$month:date('M-Y')}}" class="btn btn-primary"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Bank Detail</a>
                                    </div>
                                </div>
                            @else
                                <h4 style="color:red;">Not Found Records.</h4>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
<script>
new DataTable('#example', {
    fixedColumns: true,
    fixedHeader: true,
    paging: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: 300
});
</script>
