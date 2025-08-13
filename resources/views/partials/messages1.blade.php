@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fa fa-check"></i> {{session('success')}}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fa fa-warning"></i> {{session('warning')}}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <i class="icon fa fa-warning"></i> {{session('error')}}
    </div>
@endif


@if(session('test'))
    <div class="alert alert-danger alert-dismissible">
        <i class="icon fa fa-warning"></i> {{session('test')}}
    </div>
@endif
