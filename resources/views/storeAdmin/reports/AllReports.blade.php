@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <div  class="col-lg-12"><b style="color:red;">Store Reports</b></div>
                    <div  class="col-lg-5"></div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row"> 
                <div class="col-md-6">                      
                    <ul id="iq-sidebar-toggle" class="iq-menu">
                        <li class=""><a href="/reports/vendorReport"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>&nbsp;&nbsp;Vendors Reports</span></a></li>    
                        <li class=""><a href="#"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>&nbsp;&nbsp;Quotation Reports</span></a></li>    
                        <li class=""><a href="#"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>&nbsp;&nbsp;WO Reports</span></a></li>    
                        <li class=""><a href="#"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>&nbsp;&nbsp;PO Reports</span></a></li>    
                        <li class=""><a href="#"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>&nbsp;&nbsp;Requisition Reports</span></a></li>    
                        <li class=""><a href="#"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>&nbsp;&nbsp;Purchase Requisition Reports</span></a></li>    
                        <li class=""><a href="#"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>&nbsp;&nbsp;Event Requisition Reports</span></a></li>    
                        <li class=""><a href="#"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>&nbsp;&nbsp;Purchase Product Reports</span></a></li>    
                        <li class=""><a href="#"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>&nbsp;&nbsp;PO Payment Reports</span></a></li>    
                        <li class=""><a href="#"><i class="fa fa-file-archive-o"  style="color:red;"></i><span>&nbsp;&nbsp;WO Payment Reports</span></a></li>    
                    </ul>   
                </div> 
                <div class="col-md-6">                      
                    <div class="form-group">
                        <label>Q</label>
                    </div>
                </div>      
            </div>
        </div>
    </div>
@endsection
