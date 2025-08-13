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
              <a href="/" ><button type="button" class="btn btn-link"><i class="fa fa-home"></i> Home</button></a>
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
            @if(isset($jobs))    
              @if(count($jobs) > 0) 
                @foreach($jobs as $temp)
                  <div class="col-md-4">
                    <div class="price-box">
                      @if($temp->lastDateToApply == date('Y-m-d'))
                        <div class="ribbon "><span style="background-color:yellow;color:red;">Last Day</span></div>
                      @else
                        <div class="ribbon blue"><span>Latest</span></div>
                      @endif
                      <ul class="pricing-list">
                        <li class="price-title">{{$temp->jobPosition}}</li>
                        <li class="price-subtitle"></li>
                        <li class="price-text strong"><i class="bi bi-check-circle-fill blue"></i><strong>Posted Date : {{date('d M Y', strtotime($temp->postedDate))}}</strong></li>
                        <li class="price-text"><i class="bi bi-check-circle-fill blue"></i>Job Type: {{$temp->jobType}}</li>
                        <li class="price-text"><i class="bi bi-check-circle-fill blue"></i>Eligibility: {{$temp->education}}</li>
                        <li class="price-text"><i class="bi bi-check-circle-fill blue"></i>Job Experience: {{$temp->experience}}</li>
                        <li class="price-text"><i class="bi bi-check-circle-fill blue"></i>Vacancy: {{$temp->noOfVacancy}}</li>
                        <li class="price-tag"><a href="/jobs/showJobDet/{{$temp->id}}" >View & Apply</a></li>
                      </ul>
                    </div>
                  </div>
                @endforeach
              @endif
            @endif
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