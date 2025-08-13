@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div  class="col-lg-9"><b style="color:red;">Scrap List</b></div>
                        <div  class="col-lg-3">
                            <a href="/scraps/create" class="btn mb-1 btn-primary">Add Scrap</a>
                            <a href="/scraps" class="btn mb-1 btn-primary">
                                Scrap List <span class="badge badge-danger ml-2">{{$countScraps}}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                <div class="card-body">
                    <div class="row">     
                        <div class="col-md-3"> 
                            <b>Scrap Category</b>:&nbsp;&nbsp;&nbsp;<h6>{{$scrap->scrapCategoryName}}</h6>
                        </div>
                        <div class="col-md-3"> 
                            <b>Product Category</b>:&nbsp;&nbsp;&nbsp;<h6>{{$scrap->productCategoryName}}</h6>
                        </div>
                        <div class="col-md-3"> 
                            <b>Product Sub Category</b>:&nbsp;&nbsp;&nbsp;<h6>{{$scrap->productSubCategoryName}}</h6>
                        </div>
                        <div class="col-md-3"> 
                            <b>Product Name</b>:&nbsp;&nbsp;&nbsp;<h6>{{$scrap->productName}}</h6>
                        </div>
                    </div> 
                    <div class="row" style="margin-top:10px;">  
                        <div class="col-md-3"> 
                            <b>Unit</b>:&nbsp;&nbsp;&nbsp;<h6>{{$scrap->unitName}}</h6>
                        </div>   
                        <div class="col-md-3"> 
                            <b>Qty</b>:&nbsp;&nbsp;&nbsp;<h6>{{$scrap->qty}}</h6>
                        </div>
                        <div class="col-md-3"> 
                            <b>Estimated Price</b>:&nbsp;&nbsp;&nbsp;<h6>{{$scrap->estimatedPrice}}</h6>
                        </div>
                        <div class="col-md-3"> 
                            <b>Total Amount</b>:&nbsp;&nbsp;&nbsp;<h6>{{$scrap->qty*$scrap->estimatedPrice}}</h6>
                        </div>
                        
                    </div> 
                    <div class="row" style="margin-top:10px;">     
                        <div class="col-md-3"> 
                            <b>Date of Scrap</b>:&nbsp;&nbsp;&nbsp;<h6>{{date('d-m-Y', strtotime($scrap->dateOfScrap))}}</h6>
                        </div>
                        <div class="col-md-3"> 
                            <b>Image</b>:&nbsp;&nbsp;&nbsp;<h6><a href="/storeAdmin/scrapImages/{{$scrap->image}}" target="_blank" >{{$scrap->image}}</a></h6>
                        </div>
                    </div> 
                    <div class="row" style="margin-top:10px;">     
                        <div class="col-md-6"> 
                            <b>Description</b>:&nbsp;&nbsp;&nbsp;<h6>{{$scrap->description}}</h6>
                        </div>
                        <div class="col-md-3"> 
                            <b>Added At</b>:&nbsp;&nbsp;&nbsp;<h6>{{date('d-m-Y H:i', strtotime($scrap->created_at))}}</h6>
                        </div>
                        <div class="col-md-3"> 
                            <b>Updated By</b>:&nbsp;&nbsp;&nbsp;<h6>{{$scrap->updated_by}}</h6>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
