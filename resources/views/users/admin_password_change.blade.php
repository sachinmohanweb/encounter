@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Change Admin Password</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="card">
      
      <div class="card-body">
         <div class="row">
	         <!-- Display Validation Errors -->
			@if ($errors->any())
			    <div class="alert alert-danger">
			        <ul>
			            @foreach ($errors->all() as $error)
			                <li>{{ $error }}</li>
			            @endforeach
			        </ul>
			    </div>
			@endif

			<!-- Display Status Message -->
			@if (session('status'))
			    <div class="alert alert-success">
			        {{ session('status') }}
			    </div>
			@endif
            <div class="col-md-12">
               <form action="{{route('password.update')}}" method="Post" 
                  enctype="multipart/form-data">
                  @csrf
                  <div class="notification-sec d-flex flex-wrap">
                     <div class="col-lg-2"></div>
                     <div class="col-lg-8">
                         <div class="form-group">
                            <label for="">Old Password</label>
                            <input type="text" class="form-control" name="old_password" required>
                         </div>
                     </div>
                     <div class="col-lg-2"></div>
                     
                     <div class="col-lg-2"></div>
                     <div class="col-lg-8">
                         <div class="form-group">
                            <label for="">New Password</label>
                            <input type="text" class="form-control" name="new_password" required>
                         </div>
                     </div>
                     <div class="col-lg-2"></div>

                     <div class="col-lg-2"></div>
                     <div class="col-lg-8">
                         <div class="form-group">
                            <label for="">Confirm New Password</label>
                            <input type="text" class="form-control" name="new_password_confirmation" required>
                         </div>
                     </div>
                     <div class="col-lg-2"></div>
                    
                     <div class="col-12">
                            <div class="encounter-btn d-flex justify-content-center mt-4">
                                 <a class="btn btn-pill btn-danger btn-lg" onclick="window.location='{{ route('admin.dashboard') }}'">Cancel</a>
                                <button class="btn btn-pill btn-success btn-lg ms-3" type="submit" data-bs-original-title="" title="">Submit</button>
                            </div>
                        </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
@endsection