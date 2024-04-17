@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Edit Course</h3>
@endsection
@section('content')
<div class="container-fluid">
   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-md-12">
               <div class="date-picker">
                  <form class="theme-form"action="{{route('admin.update.course')}}" method="Post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="id" value="{{$course->id}}">
                     <div class="verse d-flex align-items-center flex-wrap">
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for="">Course Name*</label>
                              <input type="text" placeholder="Course Name"
                              name="course_name" class="form-control" required value="{{$course->course_name}}">
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for=""> Course Creator*</label>
                              <input type="text" placeholder="Course Name" name="course_creator" class="form-control" required value="{{$course->course_creator}}">
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">No of days*</label>
                              <input type="number" placeholder="Course Name" name="no_of_days" class="form-control" required value="{{$course->no_of_days}}">
                           </div>
                        </div>
                        <div class="col-lg-4 col-12">
                           <div class="form-group">
                              <label for=""> Description</label>
                              <textarea name="description" id="" rows="2" class="form-control">{{$course->description}}</textarea>
                           </div>
                        </div>
                        <div class="col-lg-3 col-12">
                           <div class="form-group ps-5">
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="status" 
                                 id="flexRadioDefault1" value="1" {{ $course->status == 'Active' ? 'checked' : '' }}>
                                 <label class="form-check-label" for="flexRadioDefault1">
                                 Enable
                                 </label>
                              </div>
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="status" 
                                 id="flexRadioDefault2" value="2" {{ $course->status == 'Suspended' ? 'checked' : '' }}>
                                 <label class="form-check-label" for="flexRadioDefault2">
                                 disable
                                 </label>
                              </div>
                           </div>
                        </div>
                        @if($course->thumbnail && file_exists(public_path($course->thumbnail)))

                           <div class="col-lg-2 col-12" id="OldImage">
                              <div class="form-group" >
                                 <img src="{{asset($course->thumbnail) }}" width="80px">
                              </div>
                           </div>
                        @endif
                         <div class="col-lg-3 col-12">
                           <div class="form-group">
                              <label for=""> Thumbnail</label>
                             <input class="form-control" type="file" id="formFile"  name="thumbnail">
                           </div>
                        </div>
                       <div class="col-12">
                           <div class="encounter-btn d-flex justify-content-center mt-4">
                              <a class="btn btn-pill btn-danger btn-lg" onclick="window.location='{{ route('admin.course.details',[$course->id]) }}'">Cancel</a>
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