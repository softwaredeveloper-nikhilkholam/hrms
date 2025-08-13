@if(session('success'))
    <input type="hidden" value="{{session('success')}}" id="sucMsg">    
    <input type="hidden" value="1" id="sucMsgId">    
@endif

@if(session('warning'))
    <input type="hidden" value="{{session('warning')}}" id="sucMsg">    
    <input type="hidden" value="2" id="sucMsgId">    
@endif

@if(session('error'))
    <input type="hidden" value="{{session('error')}}" id="sucMsg">    
    <input type="hidden" value="3" id="sucMsgId">  
@endif
