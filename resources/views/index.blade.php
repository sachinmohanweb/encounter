@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection


@section('style')
<style type="text/css">
      .show-hide span {
         color: #813b0b;
      }
      .color_change{
        background-color: #813b0b;
        color: #fff;
      }
    </style>
@endsection

@section('content')


<div class="container-fluid p-0">
   <div class="row m-0">
      <div class="col-12 p-0">
         <div class="login-card login-page" style="background: url(assets/images/banner/login.jpg) !important; background-position:center !important; background-size: 100% !important;">
            <div>
               <div><a class="logo" href="{{ route('index') }}">
                  <img class="img-fluid for-light" src="{{asset('assets/images/logo.png')}}" alt="looginpage">
                  <img class="img-fluid for-dark" src="{{asset('assets/images/logo1.png')}}" alt="looginpage" 
                  style="max-width:150px;">
               </a></div>
               <div class="login-main">
                  <form class="theme-form" action="{{ route('admin.login') }}" method="Post">
                     @csrf
                     <h4 class="text-center">Welcome Admin</h4>
                     <p class="text-center">Please enter your login details</p>
                     @if (Session::has('success'))
                        <div class="alert alert-success">
                           <ul>
                              <li>{!! \Session::get('success') !!}</li>
                           </ul>
                        </div>
                     @endif

                     @if(session('error'))
                         <div class="alert alert-danger">
                             {{ session('error') }}
                         </div>
                     @endif

                     @if($errors->any())
                        <h6 style="color:red">{{$errors->first()}}</h6>
                     @endif
                     <div class="form-group">
                        <label class="col-form-label">Email Address</label>
                        <input class="form-control" type="email" required="" placeholder="Test@gmail.com" 
                        name="email">
                     </div>
                     <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input class="form-control" type="password" required="" placeholder="*********" 
                        name="password" id="password">
                        <div class="show-hide">
                           <span class="show color_change1" id="ShowPassword"></span></div>
                     </div>
                     <div class="form-group mt-4 d-flex justify-content-center">
                        <button class="btn btn-block color_change" type="submit" >Sign in</button>
                     </div>
                    
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')
<script>
   $(document).ready(function(){
      $("#ShowPassword").click(function(){
          var passwordField = $("#password");
          var fieldType = passwordField.attr('type');
          if (fieldType === 'password') {
              passwordField.attr('type', 'text');
          } else {
              passwordField.attr('type', 'password');
          }
      });
  });
</script>
@endsection