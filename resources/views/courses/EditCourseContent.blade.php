@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Edit Course Content</h3>
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
                  <form class="theme-form" action="{{route('admin.update.course.content')}}" method="Post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="id" value="{{$content->id}}">
                     <input type="hidden" name="course_id" value="{{$content->course_id}}">
                     <input type="hidden" name="day" value="{{$content->day}}">

                     <div class="verse d-flex flex-wrap">
                        <div class="col-lg-1 col-md-1 col-12">
                           <div class="form-group">
                              <label for="">Day No</label>
                              <br>
                              <button class="butn_disabled nav-link">{{$content->day}}</button>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Bible</label>
                              <button class="butn_disabled nav-link">{{$content->bible_name}}</button>

                           </div>
                        </div>
                        <div class="col-lg-8 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Text Description*</label>
                              <textarea name="text_description" id="" rows="2" class="form-control">{{$content->text_description}}</textarea>
                           </div>
                        </div>
                        <div class="col-lg-12 col-12">
                           <div class="form-group">
                              <label for=""> Video Links</label>
                              <div id="video-links-container">
                                 @if(count($content->CourseContentVideoLink) === 0)
                                    <div class="add-link d-flex align-items-center video-link pb-1">
                                        <input type="text" class="form-control" name="video_title[]" placeholder="Title">
                                        <input type="text" class="form-control" name="video_description[]" placeholder="Description">
                                       <input type="text" class="form-control" name="video_link[]" placeholder="Enter video link">
                                       
                                       <ul class="action">
                                          <li class="add pe-2">
                                             <i class="fa fa-plus-square-o" onclick="addVideoLink(this)"></i>
                                          </li>
                                          <li class="delete">
                                             <i class="fa fa-trash" onclick="removeVideoLink(this)"></i>
                                          </li>
                                       </ul>
                                    </div>
                                 @else
                                    @foreach($content->CourseContentVideoLink as $key => $link)
                                       <div class="add-link d-flex align-items-center video-link pb-1">
                                          <input type="text" class="form-control" name="video_title[]" 
                                          value="{{ $link->title }}"placeholder="Title">
                                          <input type="text" class="form-control" name="video_description[]" 
                                          value="{{ $link->description }}" placeholder="Description">
                                          <input type="text" class="form-control" name="video_link[]" 
                                          value="{{ $link->video_spotify_link }}" placeholder="Enter video link">
                                          <ul class="action">
                                             <li class="add pe-2">
                                                <i class="fa fa-plus-square-o" onclick="addVideoLink(this)"></i>
                                             </li>
                                             <li class="delete">
                                                <i class="fa fa-trash" onclick="removeVideoLink(this)"></i>
                                             </li>
                                          </ul>
                                       </div>
                                    @endforeach
                                 @endif
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-12 col-12">
                           <div class="form-group">
                              <label for=""> Spotify Link</label>
                              <div id="spotify-links-container">
                                 @if(count($content->CourseContentSpotifyLink) === 0)
                                    <div class="add-link d-flex align-items-center spotify-link pb-1">
                                       <input type="text" class="form-control" name="spotify_title[]" placeholder="Title">
                                       <input type="text" class="form-control" name="spotify_description[]" placeholder="Description">
                                       <input type="text"  class="form-control" name="spotify_link[]" placeholder="Link">
                                       <ul class="action">
                                          <li class="add pe-2">
                                             <i class="fa fa-plus-square-o" onclick="addSpotifyLink(this)"></i>
                                          </li>
                                          <li class="delete">
                                             <i class="fa fa-trash" onclick="removeSpotifyLink(this)"></i>
                                          </li>
                                       </ul>
                                    </div>
                                 @else
                                    @foreach($content->CourseContentSpotifyLink as $key => $link)
                                       <div class="add-link d-flex align-items-center spotify-link pb-1">
                                          <input type="text" class="form-control" name="spotify_title[]" 
                                          value="{{ $link->title }}" placeholder="Title">
                                          <input type="text" class="form-control" name="spotify_description[]" 
                                          value="{{ $link->description }}" placeholder="Description">
                                          <input type="text" class="form-control" name="spotify_link[]" 
                                          value="{{ $link->video_spotify_link }}" placeholder="Enter spotify link">
                                          <ul class="action">
                                             <li class="add pe-2">
                                                <i class="fa fa-plus-square-o" onclick="addSpotifyLink(this)"></i>
                                             </li>
                                             <li class="delete">
                                                <i class="fa fa-trash" onclick="removeSpotifyLink(this)"></i>
                                             </li>
                                          </ul>
                                       </div>
                                    @endforeach
                                 @endif
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Website Links</label>
                              <div class="add-link d-flex align-items-center">
                                 <input type="text"  class="form-control" name="website_link"
                                 value="{{$content->website_link}}">
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for="">  Audio File</label>
                              <div class="add-link d-flex align-items-center">
                                 <input class="form-control" type="file" id="formFile" name="audio_file" value="{{$content->audio_file}}">
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4 col-9">
                           <div class="form-group">
                              <label for=""> Image</label>
                              <input class="form-control" type="file" id="formFile" name="image">
                           </div>
                        </div>
                        <div class="col-lg-2 col-3" style="padding-top: 25px;">
                           <div class="form-group">
                              @if($content->image)
                              <img class="img-fluid for-light" alt="image" src="{{ asset($content->image) }}"  style="width:150px;">
                              @endif
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Documents</label>
                              <input class="form-control" type="file" id="formFile" name="documents"
                              value="{{$content->documents}}">
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              @if($content->documents)
                                 <a href="{{asset($content->documents)}}"
                                 target="_blank">view documents</a>
                              @endif
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="encounter-btn d-flex justify-content-center mt-4">
                              <button class="btn btn-pill btn-danger btn-lg" type="button" data-bs-original-title="" title="" onclick="window.location='{{ route('admin.course.details',[$content->course_id]) }}'">Cancel</button>
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

<script type="text/javascript">
   function addVideoLink(element) {
      let videoLink = document.querySelector('.video-link').cloneNode(true);
      //videoLink.querySelector('input').value = '';
      let inputs = videoLink.querySelectorAll('input');
      inputs.forEach(input => input.value = '');
      document.getElementById('video-links-container').appendChild(videoLink);
   }

   function removeVideoLink(element) {
      let videoLink = element.closest('.video-link');
      if (document.querySelectorAll('.video-link').length > 1) {
         videoLink.remove();
      } else {
         alert("At least one video link is required.");
      }
   }
   function addSpotifyLink(element) {
      let SpotifyLink = document.querySelector('.spotify-link').cloneNode(true);
      //SpotifyLink.querySelector('input').value = '';
      let inputs = SpotifyLink.querySelectorAll('input');
      inputs.forEach(input => input.value = '');
      document.getElementById('spotify-links-container').appendChild(SpotifyLink);
   }

   function removeSpotifyLink(element) {
      let SpotifyLink = element.closest('.spotify-link');
      if (document.querySelectorAll('.spotify-link').length > 1) {
         SpotifyLink.remove();
      } else {
         alert("At least one Spotify link is required.");
      }
   }
</script>
@endsection