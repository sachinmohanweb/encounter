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
                        <div class="col-lg-1 col-md-1 col-12">
                           <div class="form-group">
                              <label for="">Day No</label>
                              <br>
                              <button class="butn_disabled nav-link">{{$day}}</button>
                              <input type="hidden" class="form-control" name="day" value="{{$day}}" required>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Bible</label>
                              <button class="butn_disabled nav-link">{{$course->bible_name}}</button>

                           </div>
                        </div>
                        <div class="col-lg-8 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Text Description</label>
                              <textarea name="text_description" id="" rows="2" class="form-control" ></textarea>
                           </div>
                        </div>
                        <div class="col-lg-12 col-12">
                           <div class="form-group">
                              <label for=""> Video Links</label>
                              <div id="video-links-container">
                                 <div class="add-link video-link-box pb-1">
                                    <div class="row">
                                       <div class="col-lg-6 col-md-6 col-12 mb-2">
                                          <input type="text" class="form-control" name="video_title[]" placeholder="Title">
                                       </div>
                                       <div class="col-lg-6 col-md-6 col-12 mb-2">
                                          <input type="text" class="form-control" name="video_description[]" placeholder="Description">
                                       </div>
                                    </div>
                                    <div class="row align-items-center">
                                       <div class="col-lg-7 col-md-7 col-12 mb-2">
                                          <input type="text" class="form-control" name="video_link[]" placeholder="Link">
                                       </div>
                                       <div class="col-lg-3 col-md-3 col-12 mb-2">
                                          <input type="file" class="form-control" name="video_thumbnail[]" accept="image/*" placeholder="Thumbnail">
                                       </div>
                                       <div class="col-lg-2 col-md-2 col-12 mb-2 d-flex align-items-center">
                                          <ul class="action d-flex mb-0" style="list-style:none;padding-left:0;">
                                             <li class="add pe-2">
                                                <i class="fa fa-plus-square-o" onclick="addVideoLink(this)"></i>
                                             </li>
                                             <li class="delete">
                                                <i class="fa fa-trash" onclick="removeVideoLink(this)"></i>
                                             </li>
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-12 col-12">
                           <div class="form-group">
                              <label for=""> Spotify Link</label>
                              <div id="spotify-links-container">
                                 <div class="add-link d-flex align-items-center spotify-link pb-1">
                                    <input type="text" class="form-control" name="spotify_title[]" placeholder="Title">
                                    <input type="text" class="form-control" name="spotify_description[]" placeholder="Description">
                                    <input type="text"  class="form-control" name="spotify_link[]" placeholder="Link">
                                     <ul class="action">
                                       <li class="add pe-2"><i class="fa fa-plus-square-o" onclick="addSpotifyLink(this)"></i>
                                       </li>
                                       <li class="delete"><i class="fa fa-trash" onclick="removeSpotifyLink(this)"></i></li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Website Links</label>
                              <div class="add-link d-flex align-items-center">
                                 <input type="text"  class="form-control" name="website_link">
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for="">  Audio File</label>
                              <div class="add-link d-flex align-items-center">
                                 <input class="form-control" type="file" id="formFile" 
                                 name="audio_file">
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

<script type="text/javascript">
   function addVideoLink(element) {
      let videoLink = document.querySelector('.video-link-box').cloneNode(true);
      // Clear all input values including file inputs
      let inputs = videoLink.querySelectorAll('input');
      inputs.forEach(input => {
         if (input.type === 'file') {
            input.value = '';
         } else {
            input.value = '';
         }
      });
      
      // Remove any existing thumbnail preview images
      let previewImages = videoLink.querySelectorAll('.video-thumbnail-preview');
      previewImages.forEach(img => img.remove());
      
      // Remove any existing small text elements (current thumbnail info)
      let smallElements = videoLink.querySelectorAll('small');
      smallElements.forEach(small => small.remove());
      
      document.getElementById('video-links-container').appendChild(videoLink);
      
      // Add event listeners to new file inputs
      let newFileInputs = videoLink.querySelectorAll('input[type="file"]');
      newFileInputs.forEach(input => {
         input.addEventListener('change', handleThumbnailPreview);
      });
   }

   function removeVideoLink(element) {
      let videoLink = element.closest('.video-link-box');
      if (document.querySelectorAll('.video-link-box').length > 1) {
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
   
   function handleThumbnailPreview(event) {
      const file = event.target.files[0];
      // Scope to the correct video-link-box
      const videoBox = event.target.closest('.video-link-box');
      const container = event.target.closest('.col-lg-3');
      // Remove only the preview in this video-link-box
      if (container) {
         const existingPreview = container.querySelector('.video-thumbnail-preview');
         if (existingPreview) {
            existingPreview.remove();
         }
      }
      // Remove any <small> in this container (current thumb text)
      if (container) {
         const small = container.querySelector('small');
         if (small) small.remove();
      }
      if (file && file.type.startsWith('image/')) {
         const reader = new FileReader();
         reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'video-thumbnail-preview';
            img.alt = 'Thumbnail Preview';
            if (container) container.appendChild(img);
         };
         reader.readAsDataURL(file);
      }
   }
   
   // Add event listeners to existing file inputs
   document.addEventListener('DOMContentLoaded', function() {
      const fileInputs = document.querySelectorAll('input[type="file"]');
      fileInputs.forEach(input => {
         input.addEventListener('change', handleThumbnailPreview);
      });
   });
</script>
@endsection