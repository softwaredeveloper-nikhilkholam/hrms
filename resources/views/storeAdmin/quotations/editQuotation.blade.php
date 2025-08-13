<?php
    $userType = Auth::user()->userType;
    use App\Helpers\Utility;
    $util=new Utility(); 
?>
@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-4"><b style="color:red;">Generate Quotation</b></div>
                        <div  class="col-lg-8 text-right">
                            @if(Auth::user()->userType == '701' || $userType == '801')
                                <a href="/quotation" class="btn mb-1 btn-success">Generate</a>
                            @endif
                            <a href="/quotation/quotationList" class="btn mb-1 btn-primary">Pending List</a>
                            <a href="/quotation/approvedQuotationList" class="btn mb-1 btn-primary">Approved List</a>
                            <a href="/quotation/rejectedQuotationList" class="btn mb-1 btn-primary">Rejected List</a>
                            <a href="/quotation/saveList" class="btn mb-1 btn-primary">Save List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">      
                    <div class="col-md-2">
                        <label style="font-size:12px !important;"><b>Select Category<span style="color:red;">*</span></b></label>
                        {{Form::select('categoryId', $categories, null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Category','class'=>'form-control', 'id'=>'categoryId', ''])}}
                    </div> 
                    <div class="col-md-2">
                        <div style="margin-bottom: 0rem;" class="form-group">
                            <label style="font-size:12px !important;"><b>Select Sub-Category<span style="color:red;">*</span></b></label>
                            {{Form::select('subCategoryId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Sub-Category','class'=>'form-control', 'id'=>'subCategoryId', ''])}}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="margin-bottom: 0rem;" class="form-group">
                            <label style="font-size:12px !important;">Select Product<span style="color:red;">*</span></b></label>
                            {{Form::select('productId', [], null, ["style"=>"font-size:13px !important; height: 35px !important;", 'placeholder'=>'Select Product','class'=>'form-control', 'id'=>'productId', ''])}}
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div style="margin-bottom: 0rem;margin-top: 30px;" class="form-group">
                            <label style="font-size:12px !important;"></label>
                            <button type="button" id="editQuotation" class="btn btn-danger" style="font-size:15px !important;">+&nbsp;&nbsp;Add</button>
                        </div>
                    </div>
                </div> 
             
                {!! Form::open(['action' => 'storeController\PurchaseTransactions@store', 'method' => 'POST', 'class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) !!}
                    <div class="row mt-2">  
                        <?php $ik=1; ?>
                        @if(count($quotations) >= 1)
                            @foreach($quotations as $quot)
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header" style="background-color:#0080007d;text-align:center;">
                                            <h4>Quotation {{ $quot->quotNo }}</h4>
                                            <input type="hidden" value="{{$quot->vendorId}}" name="vendorId[]">
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table">
                                                        <tr>
                                                            <td class="text-left" style="vertical-align: top;" width="40%">
                                                                Vendor Details : 
                                                                <h6>{{ $quot->vendorName }}</h6>
                                                                <h6>{{ $quot->address }}</h6>
                                                                <h6>({{ $quot->materialProvider }})</h6>
                                                            </td>
                                                            <td class="text-left" style="vertical-align: top;" width="20%">
                                                                Quotation Date : 
                                                                <h6>{{ date('d-m-Y', strtotime($quot->created_at)) }}</h6>
                                                            </td>
                                                            <td class="text-left" style="vertical-align: top;" width="40%">
                                                                Type of Company : 
                                                                <h6>{{ $quot->typeOfCompanyName }}</h6>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" style="vertical-align: top;" width="40%">
                                                                Bank Details : 
                                                                <h6>A/c No.: {{ $quot->accountNo }}</h6>
                                                                <h6>IFSC Code: {{ $quot->IFSCCode }}</h6>
                                                                <h6>Bank Branch: {{ $quot->bankBranch }}</h6>
                                                            </td>
                                                            <td class="text-left" style="vertical-align: top;" width="20%">
                                                                Terms of Payment: 
                                                                <h6>{{ $quot->termsOfPayment }}%</h6>
                                                            </td>
                                                            <td class="text-left" style="vertical-align: top;" width="40%">
                                                                Shipping Address: 
                                                                <h6>{{ $quot->shippingAddress }}</h6>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" style="vertical-align: top;" width="40%">
                                                                Quotation For : 
                                                                <input type="text" value="{{ $quot->quotationFor }}" name="quotationFor[]" class="form-control" required>
                                                            </td>
                                                            <td class="text-left" style="vertical-align: top;" width="20%">
                                                                Requisition No: 
                                                                <h6>{{ $quot->reqNo }}</h6>
                                                            </td>
                                                            <td class="text-left" style="vertical-align: top;" width="40%">
                                                                Office Address: 
                                                                <h6>Above hotel Shree Kateel, next to Amit Bloofield, near to Navle Bridge, Narhe, Pune, Maharashtra 411046</h6>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" style="vertical-align: top;">Tentative Delivery Date: 
                                                                <h6>{{ date('d-m-Y', strtotime($quot->tentativeDate)) }}</h6>
                                                            </td>
                                                            <td class="text-left" style="vertical-align: top;">Quotation Raised By:  
                                                                <h6>{{ $quot->userName }}</h6>
                                                            </td>
                                                            <td class="text-left" style="vertical-align: top;">Already Paid:  
                                                                <h6>{{ ($quot->alreadyPaid == 1)?'Yes':'No' }}</h6>
                                                            </td>                                                           
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left" style="vertical-align: top;">Upload Quotation:  
                                                            <input type="file" style="font-size:12px;line-height: 18px !important;" placeholder="Requisition No" name="quotationFile[]"  id="quotationFile" value="" class="form-control" required>
                                                            </td>
                                                            <td class="text-left" style="vertical-align: top;">Already Paid By(Employee Code):  
                                                                <h6>{{ $quot->alreadyPaidBy }}</h6>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="table-responsive mb-3">
                                                <table class="table table-bordered mb-0" id="editQuotationProductTable_{{$ik}}">
                                                    <thead class="bg-white text-uppercase">
                                                        <tr class="ligth ligth-data">
                                                            <th class="text-center" style="padding: 0px 4px !important;font-size:13px;" width="3%">No</th>
                                                            <th class="text-center" style="padding: 0px 4px !important;font-size:13px;">Product</th>
                                                            <th class="text-center" style="padding: 0px 4px !important;font-size:13px;">Qty</th>
                                                            <th class="text-center" style="padding: 0px 4px !important;font-size:13px;">Date</th>
                                                            <th class="text-center" style="padding: 0px 4px !important;font-size:13px;">Unit Price</th>
                                                            <th class="text-center" style="padding: 0px 4px !important;font-size:13px;">CGST %</th>
                                                            <th class="text-center" style="padding: 0px 4px !important;font-size:13px;">SGST %</th>
                                                            <th class="text-center" style="padding: 0px 4px !important;font-size:13px;">Discount</th>
                                                            <th class="text-center" style="padding: 0px 4px !important;font-size:13px;">Amount</th>
                                                            <th class="text-center" style="padding: 0px 4px !important;font-size:13px;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $productDetails = $util->getQuotationProductDetail($quot->id); @endphp
                                                        @foreach($productDetails as $index => $row)
                                                            <tr id="">
                                                                <td style="padding: 0px 4px !important;font-size:12px;">{{ $index + 1 }}</td>
                                                                <td class="text-left" style="padding: 0px 4px !important;font-size:12px;">
                                                                    <b>{{ $row->productName }}</b><br>
                                                                    {{ $row->categoryName ?? 'NA' }} | {{ $row->subCategoryName ?? 'NA' }} | 
                                                                    {{ $row->color ?? 'NA' }} | {{ $row->size ?? 'NA' }} | {{ $row->company ?? 'NA' }}
                                                                    <input class="form-control" type="text" name="remark1[]" value="{{ $row->remark }}" placeholder="If any Remark">
                                                                    <input type="hidden" name="productId{{$ik}}[]" value="{{ $row->productId }}">
                                                                    <input type="hidden" name="orderId{{$ik}}[]" value="{{ $row->id }}">
                                                                </td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;">
                                                                    <input class="form-control qty{{$ik}}" type="text" name="qty{{$ik}}[]" value="{{ $row->qty }}" placeholder="Product Qty">
                                                                </td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input class="form-control" type="date" name="forDate{{$ik}}[]" value="{{ date('Y-m-d', strtotime($row->forDate)) }}"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input class="form-control unitPrice{{$ik}}" type="text" name="unitPrice{{$ik}}[]" value="{{ $row->unitPrice }}" onkeypress="return isNumberKey(event)"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input class="form-control cgst{{$ik}}" type="text" name="cgst{{$ik}}[]" value="{{ $row->cgst }}" onkeypress="return isNumberKey(event)"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input class="form-control sgst{{$ik}}" type="text" name="sgst{{$ik}}[]" value="{{ $row->sgst }}" onkeypress="return isNumberKey(event)"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input class="form-control discount{{$ik}}" type="text" name="discount{{$ik}}[]" value="{{ $row->discount }}" onkeypress="return isNumberKey(event)"></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><input class="form-control amount{{$ik}}" type="text" name="amount{{$ik}}[]" value="{{ $row->amount }}" readonly></td>
                                                                <td style="padding: 0px 4px !important;font-size:12px;"><button type="button" class="btn btn-danger btnDelete">x</button></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="8" class="text-right"><b>Total Rs.</b></td>
                                                            <td width="15%"><input type="text" name="totalRs[]" class="form-control total" id="totalRs{{$ik}}" value="{{ $quot->totalRs }}" readonly></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8" class="text-right"><b>Transportation Rs.</b></td>
                                                            <td><input type="text" name="transportationRs[]" class="form-control" id="transportationRs{{$ik}}" value="{{ $quot->transportationRs }}" required></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8" class="text-right"><b>Loading Rs.</b></td>
                                                            <td><input type="text" name="loadingRs[]" class="form-control" id="loadingRs{{$ik}}" value="{{ $quot->loadingRs }}" required></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8" class="text-right"><b>Unloading Rs.</b></td>
                                                            <td><input type="text" name="unloadingRs[]" class="form-control" id="unloadingRs{{$ik}}" value="{{ $quot->unloadingRs }}" required></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8" class="text-right"><b>Final Rs.</b></td>
                                                            <td><input type="text" name="finalRs[]" class="form-control" id="finalRs{{$ik}}" value="{{ $quot->finalRs }}" readonly></td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>

                                            </div>      
                                        </div>
                                    </div>
                                </div>
                                <?php $ik++; ?>
                                <input type="hidden" value="{{$quot->id}}" name="quotationId[]">
                            @endforeach
                        @endif                          
                    </div>  
                    <div class="row">  
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <button type="submit" name="buttonStatus" value="save" class="btn btn-success mr-2">Save Quotation</button>
                            <button type="submit" name="buttonStatus" value="generate" class="btn btn-primary mr-2">Generate Quotation</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
