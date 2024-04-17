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
                  <form class="theme-form" action="{{route('admin.save.course')}}" method="Post" enctype="multipart/form-data">
                     @csrf
                     <div class="verse d-flex align-items-center flex-wrap">
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for="">Course Name*</label>
                              <input type="text" placeholder="Course Name" name="course_name" class="form-control" required>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for=""> Course Creator*</label>
                              <input type="text" placeholder="Course Name" name="course_creator" class="form-control" required>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">No of days*</label>
                               <input type="number" placeholder="Course Name" name="no_of_days" class="form-control" required>
                           </div>
                        </div>
                        <div class="col-lg-4 col-12">
                           <div class="form-group">
                              <label for=""> Description</label>
                              <textarea name="description" id="" rows="2" class="form-control"></textarea>
                           </div>
                        </div>
                        <div class="col-lg-3 col-12">
                           <div class="form-group ps-5">
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="status" 
                                 id="flexRadioDefault1" value="1" checked>
                                 <label class="form-check-label" for="flexRadioDefault1">
                                 Enable
                                 </label>
                              </div>
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="status" 
                                 id="flexRadioDefault2" value="2">
                                 <label class="form-check-label" for="flexRadioDefault2">
                                 disable
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-1 col-12">
                           <div class="form-group">
                              <img src="" id="ImagePreview" width="80px">
                           </div>
                        </div>
                         <div class="col-lg-4 col-12">
                           <div class="form-group">
                              <label for=""> Thumbnail</label>
                             <input class="form-control" type="file" id="ImageFile" name="thumbnail">
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="encounter-btn d-flex justify-content-center mt-4">
                              <a class="btn btn-pill btn-danger btn-lg" onclick="window.location='{{ route('admin.course.list') }}'">Cancel</a>
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