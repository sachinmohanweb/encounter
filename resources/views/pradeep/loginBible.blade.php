@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection


@section('style')
@endsection

@section('content')


<div class="container-fluid p-0">
   <div class="row m-0">
      <div class="col-12 p-0">
         <div class="login-card login-page" style="background: url(assets/images/banner/login.jpg) !important; background-position:center !important; background-size: 100% !important;">
            <div>
               <div><a class="logo" href="{{ route('index') }}"><img class="img-fluid for-light" src="{{asset('assets/images/logo.png')}}" alt="looginpage" style="max-width:150px;"><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo.png')}}" alt="looginpage"></a></div>
               <div class="login-main">
                  <form class="theme-form">
                     <h4>Sign in to account</h4>
                     <p>Enter your email & password to login</p>
                     <div class="form-group">
                        <label class="col-form-label">Email Address</label>
                        <input class="form-control" type="email" required="" placeholder="Test@gmail.com">
                     </div>
                     <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input class="form-control" type="password" name="login[password]" required="" placeholder="*********">
                        <div class="show-hide"><span class="show">                         </span></div>
                     </div>
                     <div class="form-group mt-4 d-flex justify-content-center">
                        <button class="btn btn-primary btn-block" type="submit">Sign in</button>
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
@endsection