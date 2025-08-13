@extends('layouts.master2')
@section('title', 'Management')
@section('content') 
<div class="container">
    <div class="row">
        <br>
    </div>
    <div class="row">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xl-1 col-lg-6 col-md-12"></div>    
                <div class="col-xl-3 col-lg-6 col-md-12">
                    <a href="/sliderLandPage">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Landing Page Slider</span>
                                            <h3 class="mb-0 mt-1 mb-2">{{$sliderCt}}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 bg-success my-auto  float-right"> <i class="feather feather-sliders"></i> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12">
                    <a href="/aboutusLandPage">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">About Us</span>
                                            <h3 class="mb-0 mt-1 mb-2">{{$aboutCt}}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 bg-primary my-auto  float-right"> <i class="fa fa-microphone" aria-hidden="true"></i> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12">
                    <a href="/ourTeamLandPage">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Our Team</span>
                                            <h3 class="mb-0 mt-1 mb-2">{{$teamsCt}}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 my-auto  float-right" style="background:green;"> <i class="feather feather-users"></i> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-1 col-lg-6 col-md-12"></div>   
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xl-1 col-lg-6 col-md-12"></div>    
                <div class="col-xl-3 col-lg-6 col-md-12">
                    <a href="/socialMediaLandPage">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Social Media Link</span>
                                            <h3 class="mb-0 mt-1 mb-2">{{$mediaCt}}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 bg-danger my-auto  float-right"><i class="fa fa-newspaper-o" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12">
                    <a href="/contactusLandPage">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Contact Us</span>
                                            <h3 class="mb-0 mt-1 mb-2">{{$contactsCt}}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 bg-warning my-auto  float-right"> <i class="feather feather-phone"></i> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12">
                    <a href="/funFactsCtLandPage">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Fun Facts Count</span>
                                            <h3 class="mb-0 mt-1 mb-2">{{$funCountsCt}}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 bg-info my-auto  float-right"><i class="fa fa-calculator" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12"></div>   
            </div>
        </div>
    </div>   
    <div class="row">
        <div class="col-xl-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xl-1 col-lg-6 col-md-12"></div>    
                <div class="col-xl-3 col-lg-6 col-md-12">
                    <a href="/vedioLandPage">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Videos</span>
                                            <h3 class="mb-0 mt-1 mb-2">{{$vediosCt}}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 bg-secondary my-auto  float-right"> <i class="fa fa-file-video-o" aria-hidden="true"></i> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12">
                    <a href="/businesslogoLandPage">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="mt-0 text-left"> <span class="fs-14 font-weight-semibold">Business Logo's</span>
                                            <h3 class="mb-0 mt-1 mb-2">{{$busLogoCt}}</h3>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="icon1 my-auto  float-right" style="background:#97270F;"> <i class="feather feather-link"></i> </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12">
                    
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12"></div>   
            </div>
        </div>
    </div>   
</div>
@endsection
