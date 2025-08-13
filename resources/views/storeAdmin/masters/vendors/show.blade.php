@extends('layouts.storeMaster')
@section('title', 'Inventory Management')
@section('content') 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div  class="col-lg-5"><b style="color:red;">Show Vendor Details</b></div>
                            <div  class="col-lg-7 text-right">
                                <a href="/vendor/create" class="btn mb-1 btn-primary btn-lg">Add</a>
                                <a href="/vendor/dlist" class="btn mb-1 btn-primary btn-lg">
                                    Deactive List <span class="badge badge-danger ml-2">{{$dVendors}}</span>
                                </a>
                                <a href="/vendor" class="btn mb-1 btn-primary btn-lg">
                                    Active List <span class="badge badge-danger ml-2">{{$vendors}}</span>
                                </a>
                                <a href="/vendor/{{$vendor->id}}/edit" class="btn mb-1 btn-success btn-lg">
                                    Edit Vendor
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-9">
                        <div><strong>Vendor Name:</strong> {{ $vendor->name }}</div>
                        <div><strong>Address:</strong> {{ $vendor->address }}</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <a href="/storeAdmin/images/vendors/{{$vendor->image}}" target="_blank">
                            <img src="/storeAdmin/images/vendors/{{$vendor->image}}" class="img-fluid rounded-circle" style="height: 200px; object-fit: cover;">
                        </a>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-3"><strong>Category:</strong> {{ $vendor->category }}</div>
                    <div class="col-md-3"><strong>Landline No:</strong> {{ $vendor->landlineNo }}</div>
                    <div class="col-md-3"><strong>WhatsApp No:</strong> {{ $vendor->whatsappNo }}</div>
                    <div class="col-md-3"><strong>PAN No:</strong> {{ $vendor->PANNO }}</div>

                    <div class="col-md-3"><strong>GST No:</strong> {{ $vendor->GSTNo }}</div>
                    <div class="col-md-3"><strong>Contact Person 1:</strong> {{ $vendor->contactPerson1 }}</div>
                    <div class="col-md-3"><strong>Contact No 1:</strong> {{ $vendor->contactPerNo1 }}</div>
                    <div class="col-md-3"><strong>Contact Person 2:</strong> {{ $vendor->contactPerson2 }}</div>
                    <div class="col-md-3"><strong>Contact No 2:</strong> {{ $vendor->contactPerNo2 }}</div>

                    <div class="col-md-3"><strong>Email:</strong> {{ $vendor->emailId }}</div>
                    <div class="col-md-3"><strong>Account No:</strong> {{ $vendor->accountNo }}</div>
                    <div class="col-md-3"><strong>IFSC Code:</strong> {{ $vendor->IFSCCode }}</div>
                    <div class="col-md-3"><strong>Bank Branch:</strong> {{ $vendor->bankBranch }}</div>

                    <div class="col-md-3"><strong>Outstanding Rs:</strong> {{ $vendor->outstandingRs }}</div>
                    <div class="col-md-3"><strong>Rating:</strong> {{ $vendor->rating }}/5</div>
                    <div class="col-md-3"><strong>Added By:</strong> {{ $vendor->addedBy }}</div>
                </div>

                <div class="mt-4">
                    <strong>Materials Provided:</strong>
                    <div class="border p-2 rounded bg-light" style="white-space: pre-wrap;color:red !important;">
                        {{ $vendor->materialProvider }}
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
