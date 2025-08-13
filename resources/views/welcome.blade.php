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
      <nav class="navbar navbar-expand-lg navbar-fixed-top" style="margin-top:-100px;">
        <div class="container">
          <a class="navbar-brand" href="#"><img src="{{asset('landingpage/images/logo.png')}}" style="width: 180px;"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"><i class="bi bi-list"></i></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll justify-content-center">
              <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="#aboutus">About us</a></li>
              <li class="nav-item"><a class="nav-link" href="#team">Our team</a></li>
              <li class="nav-item"><a class="nav-link" href="#contact">Contact Us</a></li>
              <li class="nav-item"><a class="nav-link" href="#careers">Careers</a></li>
            </ul>
            <!-- <div class="col-md-3 justify-content-left">
              <a href="/jobs"><button type="button" class="btn btn-link"><i class="bi bi-job"></i> Careers</button></a>
            </div> -->
            <div class="col-md-2 text-end">
              <a href="/login" target="_blank"><button type="button" class="btn btn-link"><i class="bi bi-person"></i> Login</button></a>
            </div>
          </div>
        </div>
      </nav>
    </header>
    <main  style="margin-top:100px;">
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
        @if(isset($slider))
            <section class="home-section" id="home" style="background-image: url('landingpage/sliders/<?php echo $slider->image; ?>'); background-repeat: no-repeat;background-repeat:no-repeat;background-size:contain;background-position:center;">
              <div class="home-section-overlay"></div>
              <div class="container home-wrapper">
                <div class="row">
                  @if($slider->titleDescAlign == 1)
                    <div class="col-md-12 text-left" style="margin-top: -68px;">
                  @elseif($slider->titleDescAlign == 2)
                    <div class="col-md-12 text-center" style="margin-top: -68px;">
                  @else
                    <div class="col-md-12" style="margin-top: -68px;text-align: end;">
                  @endif
                    <b style="font-size:80px;color:white;">{{$slider->title}}</b><br>
                    <b class="hero-text" style="font-size:25px;color:white;">{{$slider->description}}</b>
                    <ul id="countdown">
                      <li>
                          <span class=""></span>
                          <p class=""></p>
                      </li>
                      <li>
                          <span class=""></span>
                          <p class=""></p>
                      </li>
                      <li>
                          <span class=""></span>
                          <p class=""></p>
                      </li>
                      <li>
                          <span class=""></span>
                          <p class=""></p>
                      </li>
                  </ul>
                  <a href="#about" class="arrow-down scrool"><i class="bi bi-chevron-double-down"></i></a>
                </div>
                </div>
              </div>
            </section>
        @endif

        @if(isset($about))
            <section class="section-grey" id="aboutus">
              <div class="container">
                <div class="row align-items-center">
                  <div class="col-md-5 col-sm-12">
                    <h2>About Us</h2>
                    <p>{{$about->description}}</p>
                    <div class="row">
                      <div class="col-md-4 col-sm-6">
                        <div class="testim-platform">
                          <p></p>
                          
                        </div>
                      </div>
                      <div class="col-md-4 col-sm-6">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-6">
                    <div class="accordion accordion-flush" id="accordionOne">
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="bi bi-pencil-fill"></i> {{$about->subTitle1}}
                          </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionOne">
                          <div class="accordion-body">
                            {{$about->description1}}
                          </div>
                        </div>
                      </div>                  
                      @if($about->subTitle2 != null)
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              <i class="bi bi-bar-chart-line-fill"></i> {{$about->subTitle2}}
                            </button>
                          </h2>
                          <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionOne">
                            <div class="accordion-body">
                              {{$about->description2}}
                            </div>
                          </div>
                        </div>
                      @endif

                      @if($about->subTitle3 != null)
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                              <i class="bi bi-hand-thumbs-up-fill"></i> {{$about->subTitle3}}
                            </button>
                          </h2>
                          <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionOne">
                            <div class="accordion-body">
                              {{$about->description3}}
                            </div>
                          </div>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </section>
        @endif

        @if(isset($teams))
          @if(count($teams) >= 1)
            <section class="section-white medium-padding-bottom" id="team">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-center">
                      <h2>Meet Our Team</h2>
                  </div>
                    @foreach($teams as $team)
                      <div class="col-sm-12 col-md-3 margin-top-30">
                        <img src="/landingpage/teams/{{$team->photo}}" class="team-img width-100" alt="pic">
                        <div class="team-item">
                          <h4>{{$team->name}}</h4>
                          <div class="team-info"><p>{{$team->designation}}</p></div>
                          <p>{{$team->description}}</p>
                        </div>
                      </div>
                    @endforeach
                </div>
              </div>
            </section>
          @endif
        @endif

        @if(isset($vediosCt))
          @if($vediosCt >= 1)
            <section class="section-grey small-padding-bottom">
              <div class="container" style="overflow: hidden">
                <div class="row">
                  <div class="col-md-12 mx-auto padding-top-10 padding-bottom-30">
                    <div id="carouselTestimonials" class="carousel slide" data-bs-ride="carousel">            
                      <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselTestimonials" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        @for($i=0; $i<$vediosCt; $i++)
                          <button type="button" data-bs-target="#carouselTestimonials" data-bs-slide-to="{{$i}}" aria-label="Slide {{$i}}"></button>
                        @endfor
                      </div>
                      <div class="carousel-inner">
                        <?php $j=0; ?>
                        @foreach($vedios as $vedio)
                          @if($j==0)
                            <div class="carousel-item active">   
                            <?php $j=2; ?>
                          @else             
                            <div class="carousel-item">  
                          @endif              
                            <div class="row align-items-center testim-inner">
                            <div class="col-md-6">
                                <div class="video-popup-wrapper margin-right-25">
                                  <div class="popup-gallery">                        
                                    <img src="{{asset('landingpage/images/youtube-logo.jpg')}}" alt="testimonials" class="width-100 image-shadow video-popup-image responsive-bottom-margins">
                                    <a class="video-play-icon" href="{{$vedio->link}}" target="_blank">
                                      <i class="bi bi-caret-right-fill"></i>
                                    </a>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6 testim-info" style="position: relative;width: 800px;height: 300px;overflow: auto;">
                                <i class="bi bi-chat-left-quote green"></i>
                                <p>{{$vedio->description}}</p>
                                <h6><span class="red">@ {{$vedio->copyWriter}}</span></h6>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                </div>
                </div>
              </div>
            </section>
          @endif
        @endif

        @if(isset($busLogo))
          @if(count($busLogo) >= 1)
            <section class="section-white small-padding-top">
              <div class="container">
                <div class="row">
                  <div class="col-md-12 text-center padding-bottom-20">
                    <h3>Our Business Venture</h3>
                  </div>
                </div>
                <div class="row justify-content-center">
                  <div class="col-md-1"></div>
                    @foreach($busLogo as $logo)
                      <div class="col-md-2 col-xs-6">
                        <div class="our-partners">
                          <img src="/landingpage/businesslogo/{{$logo->logoImage}}" class="width-100" alt="pic">
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
            </section>
          @endif
        @endif

        @if(isset($funCounts))
            <section class="section-grey medium-padding-bottom">
              <div class="container">
                <div class="row">
                  <div class="col-md-7 mx-auto margin-bottom-10 text-center">
                    <h3>Our Achievements</h3>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3 fun-facts-box blue text-center">
                    <i class="bi bi-gift-fill"></i>
                    <p class="fun-facts-title"><span class="facts-numbers">{{$funCounts->projects}}</span><br>Projects</p>
                  </div>
                  <div class="col-md-3 fun-facts-box red text-center">
                    <i class="bi bi-heart-fill"></i>
                    <p class="fun-facts-title"><span class="facts-numbers">{{$funCounts->happyParents/1000}}k</span><br>Happy Parents</p>
                  </div>
                  <div class="col-md-3 fun-facts-box green text-center">
                    <i class="bi bi-award-fill"></i>
                    <p class="fun-facts-title"><span class="facts-numbers">{{$funCounts->awards}}</span><br>Awards</p>
                  </div>
                  <div class="col-md-3 fun-facts-box red text-center">
                    <i class="bi bi-basket3-fill"></i>
                    <p class="fun-facts-title"><span class="facts-numbers">{{$funCounts->happyEmployees}}+</span><br>Happy Employees</p>
                  </div>
                </div>
              </div>
            </section>
        @endif

        @if(isset($contacts))
          @if(count($contacts) >= 1)
            <section class="section-white" id="contact">
              <div class="container">
                <div class="row">
                  <div class="col-md-6">
                    <h3>AWS Branches</h3>
                    <ul class="benefits">
                        @foreach($contacts as $contact)
                          <li style="cursor:pointer;"><i class="bi bi-check blue"></i>{{$contact->branchName}}</li>
                        @endforeach
                    </ul>
                  </div>
                  <div class="col-md-6 responsive-top-margins">
                    <h3>Find us<i class="bi bi-geo-alt-fill"></i></h3>
                    <input type="hidden" value="{{$contacts[0]->link}}" id="firstLink">
                    <p id="link"></p>
                    <h5 id="subTitle">{{$contacts[0]->subTitle}}</h5>
                    <p class="contact-info" id="address"><i class="bi bi-geo-alt-fill"></i> {{$contacts[0]->address}}</p>
                    <p class="contact-info" id="email"><i class="bi bi-envelope-open-fill"></i> <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{$contacts[0]->email}}">{{$contacts[0]->email}}</a></p>
                    <p class="contact-info" id="phoneNo"><i class="bi bi-telephone-fill"></i>{{$contacts[0]->phoneNo}}</p>
                  </div>
                </div>
              </div>
            </section>
          @endif
        @endif

        @if(isset($jobs))    
            <section class="section-white" id="careers">
              <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center padding-top-10" >
                      <h2>Careers</h2>
                      @if(count($jobs) > 0) 
                        <h5><a href="/jobs">View More...</a></h5>
                      @else
                        <h5>No More Opening...</h5>
                      @endif
                    </div>
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
                </div>
              </div>
            </section>
        @endif

        @if(isset($media))
            <div class="footer">
              <div class="container">
                <div class="row">
                  <div class="col-md-7">
                    <p>© 2021. Designed By Aaryans Group & Developed By Nikhil Kholam</p>
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