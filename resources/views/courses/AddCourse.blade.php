@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Add Course</h3>
@endsection
@section('content')
<div class="container-fluid">
   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-md-12">
               <div class="date-picker">
                  <form class="theme-form">
                     <div class="verse d-flex align-items-center flex-wrap">
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for="">Course Name*</label>
                              <input type="text" placeholder="Course Name" class="form-control">
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for=""> Course Creator*</label>
                              <input type="text" placeholder="Course Name" class="form-control">
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">No of days*</label>
                               <input type="number" placeholder="Course Name" class="form-control">
                           </div>
                        </div>
                        <div class="col-lg-4 col-12">
                           <div class="form-group">
                              <label for=""> Description</label>
                              <textarea name="" id="" rows="2" class="form-control"></textarea>
                           </div>
                        </div>
                        <div class="col-lg-3 col-12">
                           <div class="form-group ps-5">
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                 <label class="form-check-label" for="flexRadioDefault1">
                                 Enable
                                 </label>
                              </div>
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                 <label class="form-check-label" for="flexRadioDefault2">
                                 disable
                                 </label>
                              </div>
                           </div>
                        </div>
                         <div class="col-lg-5 col-12">
                           <div class="form-group">
                              <label for=""> Thumbnail</label>
                             <input class="form-control" type="file" id="formFile">
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="encounter-btn d-flex justify-content-center mt-4">
                              <button class="btn btn-pill btn-danger btn-lg" type="button" data-bs-original-title="" title="">Cancel</button>
                              <button class="btn btn-pill btn-success btn-lg ms-3" type="button" data-bs-original-title="" title="">Submit</button>
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