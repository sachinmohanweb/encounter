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
                              <label for="">Bible<span style="color:red">*</span></label>

                              <select class="js-data-example-ajax form-select" id="bible" name="bible_id">
                                   @foreach($bibles as $bible)
                                     @if($course->bible_id == $bible->bible_id)
                                           <option value="{{ $bible->bible_id }}" selected>
                                             {{ $bible->bible_name }}</option>
                                     @else
                                           <option value="{{ $bible->bible_id }}">
                                              {{ $bible->bible_name }}</option>
                                     @endif
                                   @endforeach                                
                              </select>

                           </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for="">Course Name<span style="color:red">*</span></label>
                              <input type="text" placeholder="Course name"
                              name="course_name" class="form-control" required value="{{$course->course_name}}">
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">No of days<span style="color:red">*</span></label>
                              <input type="number" placeholder="No of days" name="no_of_days" class="form-control" required value="{{$course->no_of_days}}">
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for=""> Course Creator<span style="color:red">*</span></label>
                              <input type="text" placeholder="Course createor name" name="course_creator" class="form-control" required value="{{$course->course_creator}}">
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for=""> Creator Designation<span style="color:red">*</span></label>
                              <input type="text" placeholder="Course creator designation" name="creator_designation" class="form-control" required value="{{$course->creator_designation}}">
                           </div>
                        </div>
                        <div class="col-lg-4 col-12">
                           <div class="form-group ps-5">
                              <label for="">Current Visibility<span style="color:red">*</span></label>

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
                        <div class="col-lg-4 col-12">
                           <div class="form-group">
                              <label for=""> Creator Image <span style="color:red;font-size: x-small;">(200px X 200px )</span></label>
                             <input class="form-control" type="file"  name="creator_image">
                           </div>
                        </div>
                        @if($course->creator_image && file_exists(public_path($course->creator_image)))

                           <div class="col-lg-2 col-12" id="OldCreatorImage">
                              <div class="form-group" >
                                 <img src="{{asset($course->creator_image) }}" width="80px">
                              </div>
                           </div>
                        @endif
                        
                         <div class="col-lg-3 col-12">
                           <div class="form-group">
                              <label for=""> Thumbnail<span style="color:red;font-size: x-small;"> (512px X 512px )</span></label>
                             <input class="form-control" type="file" id="formFile"  name="thumbnail">
                           </div>
                        </div>
                        @if($course->thumbnail && file_exists(public_path($course->thumbnail)))

                           <div class="col-lg-2 col-12" id="OldImage">
                              <div class="form-group" >
                                 <img src="{{asset($course->thumbnail) }}" width="80px">
                              </div>
                           </div>
                        @endif

                        
                        <div class="col-lg-12 col-12">
                           <div class="form-group">
                              <label for=""> Description</label>
                              <textarea name="description" id="" rows="2" class="form-control">{{$course->description}}</textarea>
                           </div>
                        </div>
                         <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for=""> Introduction Video Link<span style="color:red">*</span></label>
                              <input type="text" placeholder="Introduction Video LInk" name="intro_video" class="form-control" required value="{{$course->intro_video}}">
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for=""> Introduction Audio Link<span style="color:red">*</span></label>
                              <input type="text" placeholder="Introduction Audio LInk" name="intro_audio" class="form-control" required value="{{$course->intro_audio}}">
                           </div>
                        </div>
                         <div class="col-lg-12 col-12">
                           <div class="form-group">
                              <label for=""> Introduction Commentary <span style="color:red">*</span></label>
                              <textarea name="intro_commentary" id="" rows="2" class="form-control">{{$course->intro_commentary}}</textarea>
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