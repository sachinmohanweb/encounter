@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Add Notifications</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="card">
      
      <div class="card-body">
         <div class="row">
            <div class="col-md-12">
               <form action="">
                  <div class="notification-sec d-flex flex-wrap">
                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Title</label>
                            <input type="text" class="form-control">
                         </div>
                     </div>
                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Content</label>
                           <textarea name="" id="" class="form-control" rows="2"></textarea>
                         </div>
                     </div>
                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Redirection</label>
                            <input type="text" class="form-control">
                         </div>
                     </div>
                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Thumbnail/image</label>
                            <label for="formFile" class="form-label">Default file input example</label>
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