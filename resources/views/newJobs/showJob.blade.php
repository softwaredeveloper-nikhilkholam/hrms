<!doctype html>
<html lang="en">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="author" content="Epic-Themes">
      <meta name="keywords" content="landing page, click-through, lead gen, marketing campaign, mobile app launch, one page template, product launch, products, responsive, saas, startup, html template, html5, css3, bootstrap, creative, designer, freelancer">
      <meta name="description" content="Landing Page Template for Creative Agencies, Apps, Portfolio Websites and Small Businesses">
      <title>AWS</title>
      <link href="{{asset('landingpage/css/bootstrap.min.css')}}" rel="stylesheet">
      <link href="{{asset('landingpage/css/style.css')}}" rel="stylesheet">
      <link href="{{asset('landingpage/css/bootstrap-icons.css')}}" rel="stylesheet">
      <link href="{{asset('landingpage/css/animate.css')}}" rel="stylesheet">
      <link href="{{asset('landingpage/css/style-magnific-popup.css')}}" rel="stylesheet">
      <link rel="preconnect" href="https://fonts.gstatic.com/">
      <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&amp;family=Open+Sans:ital@0;1&amp;display=swap" rel="stylesheet">
      <link rel="shortcut icon" href="images/favicon.ico">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <header>
      <nav class="navbar navbar-expand-lg navbar-fixed-top">
        <div class="container">
          <a class="navbar-brand" href="#"><img src="{{asset('landingpage/images/logo.png')}}" style="width: 180px;"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="bi bi-list"></i></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll justify-content-center">
            </ul>
            <div class="col-md-4 text-end">
              <a href="/" target="_blank"><button type="button" class="btn btn-link"><i class="fa fa-home"></i> Home</button></a>
              <a href="/login" target="_blank"><button type="button" class="btn btn-link"><i class="bi bi-person"></i> Login</button></a>
            </div>
          </div>
        </div>
      </nav>
    </header>
    <main>
      <section class="section-white" id="pricing">
        <div class="container">
          <div class="row">
            <div class="col-md-12 text-center padding-top-40">
              <h2>Job Vacancy</h2>
            </div>
            <div class="col-md-3">
                <div class="price-box">
                    @if($empJob->lastDateToApply == date('Y-m-d'))
                      <div class="ribbon "><span style="background-color:yellow;color:red;">Last Day</span></div>
                    @else
                      <div class="ribbon blue"><span>Latest</span></div>
                    @endif
                    <div class="mt-3">
                        <label class="form-label mb-0">Experiences</label>
                        <p class="text-muted">{{$empJob->experience}}</p>
                    </div>
                    <div class="mt-3">
                        <label class="form-label mb-0">vacancy</label>
                        <p class="text-muted">{{$empJob->noOfVacancy}}</p>
                    </div>
                    <div class="mt-3">
                        <label class="form-label mb-0">Job Type</label>
                        <p class="text-muted">{{$empJob->jobType}}</p>
                    </div>
                    <div class="mt-3">
                        <label class="form-label mb-0">Posted Date</label>
                        <p class="text-muted">{{date('d M Y', strtotime($empJob->postedDate))}}</p>
                    </div>
                    <div class="mt-3">
                        <label class="form-label mb-0">Last Date To Apply</label>
                        <p class="text-muted">{{date('d M Y', strtotime($empJob->lastDateToApply))}}</p>
                    </div>
                    <a class="mb-0">
                        <div class="icons">
                            <a class="btn btn-primary icons" data-target="#apply" data-toggle="modal" href="{{route('applyJob',['id' => $empJob->id])}}"><i class="si si-check mr-1"></i>Apply Now</a>
                        </div>
                    </a><br>
                    <a class="mb-0">
                        <div class="icons">
                          <a class="btn btn-success icons" href="https://api.whatsapp.com/send?phone=whatsappphonenumber&text=<?php echo $actual_link; ?>" data-target="#apply" data-toggle="modal" target="_blank">Share via Whatsapp</a>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="price-box">
                    <ul class="pricing-list">
                        <li class="price-title">{{$empJob->jobPosition}}</li>
                        <li class="price-subtitle"></li>
                        <li class="price-text strong"><strong>Posted Date : {{date('d M Y', strtotime($empJob->postedDate))}}</strong></li>
                        <li class="price-text"><i class="fa fa-briefcase"></i>{{$empJob->branchName}}</li>
                        <li class="price-text"><i class="fa fa-map-marker"></i>{{$empJob->address}}</li>
                    </ul>
                    <ul class="pricing-list">
                        <li class="price-text strong"><strong>Description</strong></li>
                        <?php $i=1; ?>
                        @if(isset($jobDesc))
                            <table>
                            @foreach($jobDesc as $desc)
                                <tr>
                                    <td style="text-align: left;vertical-align: top;">{{$desc->description}}</td>
                                </tr>
                            @endforeach
                            </table>
                        @endif
                    </ul>
                    <table width="100%">
                        <tr>
                            <td>
                                <ul class="pricing-list">
                                    <li class="price-text strong"><strong>Job Details</strong></li>
                                </ul>
                            </td>
                            <td>
                                <ul class="pricing-list">
                                    <li class="price-text strong"><strong></strong></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ul class="pricing-list">
                                    <li class="price-text strong"><strong>Job Role : {{$empJob->designationName}}</strong></li>
                                </ul>
                            </td>
                            <td>
                                <ul class="pricing-list">
                                    <li class="price-text strong"><strong>Job Experience : {{$empJob->experience}}</strong></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ul class="pricing-list">
                                    <li class="price-text strong"><strong>Min Salary : Rs. {{$empJob->salaryFrom}}</strong></li>
                                </ul>
                            </td>
                            <td>
                                <ul class="pricing-list">
                                    <li class="price-text strong"><strong>Languages : {{$empJob->language}}</strong></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ul class="pricing-list">
                                    <li class="price-text strong"><strong>Max Salary : Rs. {{$empJob->salaryTo}}</strong></li>
                                </ul>
                            </td>
                            <td>
                                <ul class="pricing-list">
                                    <li class="price-text strong"><strong>Eligibility : {{$empJob->education}}</strong></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <ul class="pricing-list">
                                    <li class="price-text strong"><strong>Job skill : {{$empJob->skill}}</strong></li>
                                </ul>
                            </td>
                            <td>
                                <ul class="pricing-list">
                                    <li class="price-text strong"><strong></strong></li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </section>
      @if(isset($media))
          <div class="footer">
            <div class="container">
              <div class="row">
                <div class="col-md-7">
                  <p>© 2021. Designed by <a href="https://mail.google.com/mail/?view=cm&fs=1&to=nikhilkholam8668@gmail.com" target="_blank">Nikhil Kholam</a></p>
                </div>
                  <div class="col-md-5">
                    <ul class="footer_social">
                      <li>Follw us:</li>
                      @if($media->twitter != null)
                        <li><a href="https://www.twitter.com/{{$media->twitter}}" class="twitter" target="_blank"><i class="bi bi-twitter"></i></a></li>
                      @endif
                      @if($media->instagram != null)
                        <li><a href="https://www.instagram.com/{{$media->instagram}}" class="instagram" target="_blank"><i class="bi bi-instagram"></i></a></li>
                      @endif
                      @if($media->whatsapp != null)
                        <li><a href="https://api.whatsapp.com/send?phone=91{{$media->whatsapp}}&text=Hello" class="whatsapp" target="_blank"><i class="bi bi-whatsapp"></i></a></li>
                      @endif
                      @if($media->gmail != null)
                        <li><a href="https://mail.google.com/mail/?view=cm&fs=1&to={{$media->gmail}}" class="google" target="_blank"><i class="bi bi-google"></i></a></li>
                      @endif
                      @if($media->facebook != null)
                        <li><a href="https://www.facebook.com/{{$media->facebook}}" class="facebook" target="_blank"><i class="bi bi-facebook"></i></a></li>
                      @endif
                    </ul>
                  </div>
              </div>
            </div>
          </div>
      @endif
    </main>
    <script src="{{asset('landingpage/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('landingpage/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('landingpage/js/jquery.scrollTo-min.js')}}"></script>
    <script src="{{asset('landingpage/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('landingpage/js/jquery.nav.js')}}"></script>
    <script src="{{asset('landingpage/js/wow.js')}}"></script>
    <script src="{{asset('landingpage/js/countdown.js')}}"></script>
    <script src="{{asset('landingpage/js/plugins.js')}}"></script>
    <script src="{{asset('landingpage/js/custom.js')}}"></script>
    <script src="{{asset('landingpage/js/demo.js')}}"></script>
  </body>
</html>