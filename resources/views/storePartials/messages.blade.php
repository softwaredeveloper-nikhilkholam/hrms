@if(session('success'))
    <div class="alert text-white bg-success" role="alert" id="messageAlert">
        <div class="iq-alert-icon">
           <i class="ri-alert-line"></i>
        </div>
        <div class="iq-alert-text">{{session('success')}}</div>
     </div>
@endif

@if(session('warning'))
    <div class="alert text-white bg-warning" role="alert" id="messageAlert">
        <div class="iq-alert-icon">
        <i class="ri-alert-line"></i>
        </div>
        <div class="iq-alert-text">{{session('warning')}}</div>
    </div>
@endif

@if(session('error'))
    <div class="alert text-white bg-danger" role="alert" id="messageAlert">
        <div class="iq-alert-icon">
        <i class="ri-information-line"></i>
        </div>
        <div class="iq-alert-text">{{session('error')}}</div>
    </div>
@endif
