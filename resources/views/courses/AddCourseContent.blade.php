@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Add Course Content</h3>
@endsection
@section('breadcrumb-items')
<li class="breadcrumb-item">Daily Bible Verse</li>
<li class="breadcrumb-item active">Add Daily Bible Verse</li>
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
                  <form class="theme-form" action="{{route('admin.save.course.content')}}" method="Post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="course_id" value="{{$course_id}}">
                     <div class="verse d-flex flex-wrap">
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for="">Day No*</label>
                              <select class="form-select" aria-label="Default select example" 
                              name="day" required>
                                 <option value="{{$day}}" selected>Day {{$day}}</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for="">Book*</label>
                              <select class="form-select" aria-label="Default select example" 
                              name="book"required>
                                 <option value="">Select Book</option>
                                 <option value="1">Exodus</option>
                                 <option value="2">Psalms</option>
                                 <option value="3">Proverbs</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Chapter*</label>
                              <select class="form-select" aria-label="Default select example"
                              name="chapter" required>
                                 <option value=""> Select Chapter</option>
                                 <option value="1">1</option>
                                 <option value="2">2</option>
                                 <option value="3">3</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Verses From*</label>
                              <select class="form-select" aria-label="Default select example"
                              name="verse_from" required>
                                 <option value=""> Select Starting Verse</option>
                                 <option value="1">2</option>
                                 <option value="2">3</option>
                                 <option value="3">4</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Verses To*</label>
                              <select class="form-select" aria-label="Default select example"
                              name="verse_to" required>
                                 <option value="">Select Ending Verse</option>
                                 <option value="1">1</option>
                                 <option value="2">2</option>
                                 <option value="3">3</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-6 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Text Description*</label>
                              <textarea name="text_description" id="" rows="2" class="form-control"></textarea>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Video Links</label>
                              <div class="add-link d-flex align-items-center">
                                 <input type="text"  class="form-control" name="video_link">
                                 <!-- <ul class="action">
                                    <li class="add"><i class="fa fa-plus-square-o"></i>
                                    </li>
                                    <li class="delete"><i class="fa fa-trash"></i></li>
                                 </ul> -->
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for="">  Audio File</label>
                              <div class="add-link d-flex align-items-center">
                                 <input class="form-control" type="file" id="formFile" 
                                 name="audio_file">
                                <!--  <ul class="action">
                                    <li class="add"><i class="fa fa-plus-square-o"></i>
                                    </li>
                                    <li class="delete"><i class="fa fa-trash"></i></li>
                                 </ul> -->
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Spotify Link</label>
                              <div class="add-link d-flex align-items-center">
                                 <input type="text"  class="form-control" name="spotify_link">
                                 <!-- <ul class="action">
                                    <li class="add"><i class="fa fa-plus-square-o"></i>
                                    </li>
                                    <li class="delete"><i class="fa fa-trash"></i></li>
                                 </ul> -->
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Website Links</label>
                              <div class="add-link d-flex align-items-center">
                                 <input type="text"  class="form-control" name="website_link">
                                 <!-- <ul class="action">
                                    <li class="add"><i class="fa fa-plus-square-o"></i>
                                    </li>
                                    <li class="delete"><i class="fa fa-trash"></i></li>
                                 </ul> -->
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Image</label>
                              <input class="form-control" type="file" id="formFile" name="image">
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Documents</label>
                              <input class="form-control" type="file" id="formFile" name="documents">
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="encounter-btn d-flex justify-content-center mt-4">
                              <button class="btn btn-pill btn-danger btn-lg" type="button" data-bs-original-title="" title="" 
                              onclick="window.location='{{ route('admin.course.details',[$course_id]) }}'">
                              Cancel</button>
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