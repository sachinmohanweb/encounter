<!DOCTYPE html>
<html lang="en">
<head>

     <title>Encounter Bible Study</title>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=Edge">
     <meta name="description" content="">
     <meta name="keywords" content="">
     <meta name="team" content="">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

     <link rel="stylesheet" href="{{asset('home_index/css/bootstrap.min.css')}}">
     <link rel="stylesheet" href="{{asset('home_index/css/vegas.min.css')}}">
     <link rel="stylesheet" href="{{asset('home_index/css/font-awesome.min.css')}}">
     <link rel="stylesheet" href="{{asset('home_index/css/templatemo-style.css')}}">

</head>
<body>

     <section class="preloader">
          <div class="spinner">
               <span class="spinner-rotate"></span>
          </div>
     </section>

     <!-- HOME -->
     <section id="home">
        <div class="overlay"></div>
          <div class="container">
               <div class="row">
                    <div class="col-md-12 col-sm-12">
                         <div class="home-info">
                              <h1>"Encounter Bible Study"</h1>
                              <h2>A New Way to Engage with God’s Word</h2>
                              <ul class="countdown">
                                  <h3>Your destination for deeper biblical insights, spiritual growth, and community.
                                  </h3>   
                              </ul>
                              <div class="subscribe-form">
                                <!-- <a href="{{url('admin')}}"> Sign In as Admin</a> -->
								<a class="btn btn-success" href="{{ url('admin') }}">Sign In as Admin</a>
                              </div>
                         </div>
                    </div>

               </div>
          </div>
     </section>

     <!-- SCRIPTS -->     
     <script src="{{asset('home_index/js/jquery.js')}}"></script>
     <script src="{{asset('home_index/js/bootstrap.min.js')}}"></script>
     <script src="{{asset('home_index/js/vegas.min.js')}}"></script>
     <script src="{{asset('home_index/js/countdown.js')}}"></script>
     <script src="{{asset('home_index/js/init.js')}}"></script>
     <script src="{{asset('home_index/js/custom.js')}}"></script>

</body>
</html>