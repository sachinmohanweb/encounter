@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Add New Batch</h3>
@endsection
@section('content')
<div class="container-fluid">
   <div class="card">
      <div class="card-body">
         @if (Session::has('success'))
           <div class="alert alert-success">
              <ul>
                 <li>{!! Session::get('success') !!}</li>
              </ul>
           </div>
         @endif
         @if (Session::has('error'))
           <div class="alert alert-danger">
              <ul>
                 <li>{!! Session::get('error') !!}</li>
              </ul>
           </div>
         @endif
         @if($errors->any())
            <h6 style="color:red;padding: 20px 0px 0px 30px;">{{$errors->first()}}</h6>
         @endif
         <div class="row">
            <div class="col-md-12">
               <div class="date-picker">
                  <form class="theme-form" action="{{route('admin.save.batch')}}" method="Post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="course_id" value="{{$course_id}}">
                     <div class="verse d-flex align-items-center flex-wrap">
                        <div class="col-md-6 col-12">
                           <div class="form-group">
                              <label for=""> Batch Name*</label>
                              <input type="text" placeholder="Batch Name" class="form-control"
                              name="batch_name" required>
                           </div>
                        </div>
                        <div class="col-md-6 col-12">
                           <div class="form-group">
                              <label for=""> Start Date*</label>
                              <div class="input-group">
                                 <!-- datepicker-here -->
                              <input class="form-control digits" type="date" data-language="en" name="start_date" required>
                           </div>
                           </div>
                        </div>
                        <div class="col-md-5 col-12">
                           <div class="form-group">
                              <label for=""> End Date*</label>
                              <div class="input-group">
                              <input class="form-control digits" type="date" data-language="en" name="end_date" required>
                           </div>
                           </div>
                        </div>
                        <div class="col-md-5 col-12">
                           <div class="form-group">
                              <label for=""> Last Date for Enrollment*</label>
                              <div class="input-group">
                              <input class="form-control digits" type="date" data-language="en" name="last_date" required>
                           </div>
                           </div>
                        </div>
                        <div class="col-lg-2 col-12">
                           <div class="form-group ps-5">
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="status" 
                                 id="flexRadioDefault1" value="1">
                                 <label class="form-check-label" for="flexRadioDefault1">
                                 Enable
                                 </label>
                              </div>
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="status" id="flexRadioDefault2" value="2">
                                 <label class="form-check-label" for="flexRadioDefault2">
                                 disable
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="encounter-btn d-flex justify-content-center mt-4">
                              <button class="btn btn-pill btn-danger btn-lg" type="button" data-bs-original-title="" title="" onclick="window.location='{{ route('admin.course.details',[$course_id]) }}'">Cancel</button>
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