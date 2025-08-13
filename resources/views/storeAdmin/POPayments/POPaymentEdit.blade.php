@php
    $user = Auth::user();
     $editable = (($payment->status == 1 || $payment->status == 4) && $user->userType == '61');
    if ($payment->status == 1) {
        $statusText = 'Pending';
    } elseif ($payment->status == 2) {
        $statusText = 'Transferred';
    } elseif ($payment->status == 3) {
        $statusText = 'Rejected';
    }
    else
    {
            $statusText = 'Hold';
    }
@endphp

@extends('layouts.storeMaster')

@section('title', 'Inventory Management')
@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-0 font-weight-bold" style="color:red;">Update Payment</h5>
                <div>
                   <a href="/payments/POUnpaidPaymentList" class="btn mb-1 btn-primary">Pending List</a>
                    <a href="/payments/POHoldPaymentList" class="btn mb-1 btn-primary">Hold List</a>
                    <a href="/payments/POPaidPaymentList" class="btn mb-1 btn-primary">Paid List</a>
                    <a href="/payments/PORejectedPaymentList" class="btn mb-1 btn-primary">Rejected List</a>
                </div>
            </div>
            <div class="card-body">
                {!! Form::open(['action' => ['storeController\PaymentsController@POPaymentUpdate'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

                {{-- Vendor Info --}}
                <div class="form-row mb-3 text-center">
                    <div class="col-md-12">
                        <h5>{{ $payment->vendorName }}</h5>
                        <h5>{{ $payment->address }}</h5>
                        <h5>{{ $payment->contactPerson1 }} - {{ $payment->contactPerNo1 }}</h5>
                    </div>
                </div>
                <hr>

                {{-- PO Info --}}
                <div class="form-row mb-3 text-center">
                    <div class="col-md-2"><h6>PO Amount</h6><h5 style="color:green;">{{ number_format($quotation->poAmount, 2) }}</h5></div>
                    <div class="col-md-2"><h6>Paid Amount</h6><h5 style="color:green;">{{ number_format($quotation->paidAmount, 2) }}</h5></div>
                    <div class="col-md-2"><h6>Remaining</h6><h5 style="color:green;">{{ number_format($quotation->poAmount - $quotation->paidAmount, 2) }}</h5></div>
                    <div class="col-md-2"><h6>PO Number</h6><h5 style="color:green;">{{ $payment->poNumber }}</h5></div>
                    <div class="col-md-2"><h6>Payment Percent</h6><h5 style="color:green;">{{ number_format($payment->percent, 2) }}%</h5></div>
                    <div class="col-md-2"><h6>Payment Amount</h6><h5 style="color:green;">{{ number_format($payment->amount, 2) }}</h5></div>
                </div>

                <hr>

                {{-- Form Fields --}}
                <div class="form-row mb-3">
                    <div class="col-md-2">
                        <h6 class="text-center">Select Status<span style="color:red;">*</span></h6>
                        {{Form::select('paymentStatus', ['1'=>'Pending', '2'=>'Approved', '3'=>'Rejected', '4'=>'Hold'], old('paymentStatus', $payment->status), ['class'=>'form-control', 'required', 'id'=>'paymentStatus'])}}
                    </div>

                    <div class="col-md-2 statusView1">
                        <h6 class="text-center">Account Number <span style="color:red;">*</span></h6>
                        <input type="text" class="form-control" name="accountNo" value="{{ old('accountNo', $payment->accountNo) }}" {{ $editable ? 'required' : 'readonly class=form-control-plaintext text-dark' }}>
                    </div>

                    <div class="col-md-2 statusView2">
                        <h6 class="text-center">Bank Name <span style="color:red;">*</span></h6>
                        <input type="text" class="form-control text-uppercase" id="bankBranch" name="bankBranch" value="{{ old('bankBranch', $payment->bankBranch) }}" {{ $editable ? 'required' : 'readonly class=form-control-plaintext text-dark' }}>
                    </div>

                    <div class="col-md-2 statusView3">
                        <h6 class="text-center">IFSC Code <span style="color:red;">*</span></h6>
                        <input type="text" class="form-control text-uppercase" id="IFSCCode" name="IFSCCode" value="{{ old('IFSCCode', $payment->IFSCCode) }}" {{ $editable ? 'required' : 'readonly class=form-control-plaintext text-dark' }}>
                    </div>

                    <div class="col-md-2 statusView4">
                        <h6 class="text-center">Transaction Number <span style="color:red;">*</span></h6>
                        <input type="text" class="form-control" name="transactionNumber" value="{{ old('transactionNumber', $payment->transactionId) }}" {{ $editable ? 'required' : 'readonly class=form-control-plaintext text-dark' }}>
                    </div>

                    <div class="col-md-2 statusView5">
                        <h6 class="text-center">Transfer Date <span style="color:red;">*</span></h6>
                        <input type="date" class="form-control" name="transferDate" value="{{ old('transferDate', $payment->transferDate) }}" {{ $editable ? 'required' : 'readonly class=form-control-plaintext text-dark' }}>
                    </div>
                </div>

                {{-- Remark --}}
                <div class="form-row mb-3">
                    <div class="col-md-12">
                        <h6 class="text-center">Payment Remark</h6>
                        <textarea name="paymentRemark" class="form-control" rows="4">{{ old('paymentRemark', $payment->paymentRemark) }}</textarea>
                    </div>
                </div>

                {{-- Submit Button --}}
                @if($editable)
                <div id="submitBtnWrapper" class="form-row mb-3 justify-content-center">
                    <input type="hidden" name="vendorId" value="{{ $payment->vendorId }}">
                    <input type="hidden" name="paymentId" value="{{ $payment->id }}">
                    <button type="submit" class="btn btn-success">Save Payment</button>
                    &nbsp;&nbsp;<button type="reset" class="btn btn-primary">Reset</button>
                </div>
                @else
                <div class="form-row">
                    <div class="col-md-3 text-center"><h6>Status</h6><h5>{{ $statusText }}</h5></div>
                    <div class="col-md-3 text-center"><h6>Updated At</h6><h5>{{ $payment->updated_at->format('d-m-Y H:i') }}</h5></div>
                    <div class="col-md-3 text-center"><h6>Updated By</h6><h5>{{ $payment->updated_by }}</h5></div>
                </div>
                @endif

                <hr>

                {{-- Payment History --}}
                @if($paymentHistory->count())
                    <div class="table-responsive">
                        <h5 style="color:blue;">Payment History</h5>
                        <table class="table table-bordered table-striped mt-2">
                            <thead class="thead-light text-uppercase">
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Percent</th>
                                    <th>Amount</th>
                                    <th>Transfer Date</th>
                                    <th>Transaction ID</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentHistory as $i => $history)
                                    @if($history->amount != 0)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ date('d-m-Y', strtotime($history->forDate)) }}</td>
                                            <td>{{ $history->percent }}%</td>
                                            <td>{{ number_format($history->amount, 2) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($history->transferDate)) }}</td>
                                            <td>{{ $history->transactionId }}</td>
                                            <td>{{ $history->paymentRemark ?: 'NA' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <h5 class="text-danger">No Payment History found for this PO.</h5>
                @endif

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const statusSelect = document.getElementById('paymentStatus');

    const fieldMap = {
        accountNo: '.statusView1',
        bankBranch: '.statusView2',
        IFSCCode: '.statusView3',
        transactionNumber: '.statusView4',
        transferDate: '.statusView5'
    };

    const originalValues = {};
    for (const field in fieldMap) {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) originalValues[field] = input.value;
    }

    function toggleFields(status) {
        const isHold = status === '4';
        const isApproved = status === '2';
        const isPendingOrRejected = status === '1' || status === '3';

        for (const [field, selector] of Object.entries(fieldMap)) {
            const input = document.querySelector(`[name="${field}"]`);
            const wrapper = document.querySelector(selector);

            if (isHold) {
                input.removeAttribute('required');
                input.setAttribute('readonly', 'readonly');
                input.classList.add('form-control-plaintext', 'text-dark');
                input.classList.remove('form-control');
                input.value = '';
                wrapper.style.display = 'none';
            } else {
                input.removeAttribute('readonly');
                input.classList.remove('form-control-plaintext', 'text-dark');
                input.classList.add('form-control');
                input.value = originalValues[field] || '';
                wrapper.style.display = 'block';

                // Required logic
                if (['transactionNumber', 'transferDate'].includes(field)) {
                    if (isApproved) {
                        input.setAttribute('required', 'required');
                    } else {
                        input.removeAttribute('required'); // Pending & Rejected: NOT required
                    }
                } else {
                    input.setAttribute('required', 'required'); // Always required
                }
            }
        }

        const btnWrapper = document.getElementById('submitBtnWrapper');
        if (btnWrapper) {
            btnWrapper.style.display = isHold ? 'none' : 'flex';
        }
    }

    // Input capitalization
    document.getElementById('bankBranch').addEventListener('input', e => {
        e.target.value = e.target.value.toUpperCase();
    });
    document.getElementById('IFSCCode').addEventListener('input', e => {
        e.target.value = e.target.value.toUpperCase();
    });

    statusSelect.addEventListener('change', function () {
        toggleFields(this.value);
    });

    toggleFields(statusSelect.value);
});
</script>

@endsection
