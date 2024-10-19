@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
<style type="text/css">
   .image_td {
    width: 120px;
    height: 120px;
    text-align: center;
    vertical-align: middle;
    padding: 0;
}

.image_td img {
    max-width: 100%;
    max-height: 100%;
    display: block;
    margin: auto;
}
@media (max-width: 770px) {
    .course-content .nav {
      height: 300px;
      margin-bottom: 30px;
    }
}
</style>
@endsection
@section('breadcrumb-title')
<h3>Course Content</h3>
@endsection
@section('content')

@php 
   $activeTab = request()->query('active_tab', 1); 
@endphp

<div class="container-fluid">
   <div class="card course-content">
      <div class="d-flex align-items-start flex-column flex-md-row">
         <div class="nav d-block nav-pills me-3 mb-md-0" id="v-pills-tab" role="tablist" aria-orientation="vertical">

            @for($i=1;$i<$course->no_of_days+1;$i++)
               <button class="nav-link {{ $i == $activeTab ? 'active' : '' }} " id="v-pills-{{$i}}-tab" data-bs-toggle="pill" data-bs-target="#v-pills-{{$i}}" type="button" role="tab" aria-controls="v-pills-{{$i}}" aria-selected="false">Day {{$i}}</button>
            @endfor

         </div>
         <div class="tab-content flex-grow-1" id="v-pills-tabContent">

            @for($i=1;$i<$course->no_of_days+1;$i++)
               
                     <div class="tab-pane fade {{ $i == $activeTab ? 'show active' : '' }}"  id="v-pills-{{$i}}" role="tabpanel" aria-labelledby="v-pills-{{$i}}-tab">
              

                     @if($course->contentExistsForDay($i))

                     @php
                           $content = $course->getContentForDay($i);
                     @endphp

                        <h6>Day details-Day {{$i}}</h6>
                     <div class="table-responsive">
                     <table class="table table-bordered table-striped w-100">
                           <tr>
                              <td class="col-3">Course Name</td>
                              <td class="col">{{$content->course_name}}</td>
                           </tr>
                           <tr>
                              <td>Bible</td>
                              <td>{{$content->bible_name}}</td>
                           </tr>
                           <tr>
                              <td>Text Description</td>
                              <td>{{$content->text_description}}</td>
                           </tr>
                           <tr>
                              <td>Video Links</td>
                              <td> 
                                 @if(count($content->CourseContentVideoLink)>0)
                                       @foreach($content->CourseContentVideoLink as $key=>$value)
                                          <a href="{{ $value['video_spotify_link'] }}" target="_blank">
                                             Click here<br>
                                          </a>
                                       @endforeach
                                 @else
                                    Not Available
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td>Spotify Link</td>
                              <td>
                                 @if(count($content->CourseContentspotifyLink)>0)
                                       @foreach($content->CourseContentspotifyLink as $key=>$value)
                                          <a href="{{ $value['video_spotify_link'] }}" target="_blank">
                                             Click here<br>
                                          </a>
                                       @endforeach
                                 @else
                                    Not Available
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td>Website Link</td>
                              <td>
                                 @if($content->website_link)
                                       <a href="{{$content->website_link}}"target="_blank">
                                 @else
                                    Not Available
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td>Audio File</td>
                              <td>
                                 @if($content->audio_file)
                                       <audio controls><source src="{{asset($content->audio_file)}}" type="audio/mpeg">
                                       Play</audio>
                                 @else
                                    Not Available
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td> Image</td>
                              <td class="image_td">
                                 @if($content->image)
                                 <img class="img-fluid for-light" src="{{ asset($content->image) }}" alt="image.jpg">
                                  @else
                                    Not Available
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td>Documents</td>
                              <td>
                                 @if($content->documents)
                                       <a href="{{asset($content->documents)}}" target="_blank">click here
                                 @else
                                    Not Available
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td  colspan="2" style="text-align: center;">
                                 <a href="{{ route('admin.edit.course.content',['content_id'=>$content->id]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2">Edit course content</button></a>

                                 <a href="{{ route('admin.view.course.content.verse',['content_id'=>$content->id]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2">View Sections</button></a>
                              </td>
                           </tr>
                     </table>
                     </div>

                     @else
                        <a href="{{ route('admin.add.course.content',['course_id'=>$course->id,'day'=>$i]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2">Add Course Content</button></a>
                     @endif
               </div>
            @endfor
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