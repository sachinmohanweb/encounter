@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Course Content</h3>
@endsection
@section('content')
<div class="container-fluid">
   <div class="card course-content">
      <div class="d-flex align-items-start">
         <div class="nav d-block nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">

            @for($i=1;$i<$course->no_of_days+1;$i++)
               <button class="nav-link " id="v-pills-{{$i}}-tab" data-bs-toggle="pill" data-bs-target="#v-pills-{{$i}}" type="button" role="tab" aria-controls="v-pills-{{$i}}" aria-selected="false">Day {{$i}}</button>
            @endfor

         </div>
         <div class="tab-content" id="v-pills-tabContent">
            @for($i=1;$i<$course->no_of_days+1;$i++)
               @if($i==1)
                     <div class="tab-pane fade show active " id="v-pills-{{$i}}" role="tabpanel" aria-labelledby="v-pills-{{$i}}-tab">
               @else
                     <div class="tab-pane fade show " id="v-pills-{{$i}}" role="tabpanel" aria-labelledby="v-pills-{{$i}}-tab">
               @endif

                     @if($course->contentExistsForDay($i))
                        <ul class="action">   
                           <li class="edit"> 
                              <a href="{{ route('admin.edit.course.content',['content_id'=>$course->CourseContents[$i-1]->id]) }}">
                                 <i class="fas fa-pencil-alt"></i>
                              </a>
                            </li>                  
                        </ul>
                        <table>
                           <tr>
                              <td>Course Name</td>
                              <td>{{$course->CourseContents[$i-1]->course_name}}</td>
                           </tr>
                           <tr>
                              <td>Bible</td>
                              <td>{{$course->CourseContents[$i-1]->bible_name}}</td>
                           </tr>
                           <tr>
                              <td>Testament</td>
                              <td>{{$course->CourseContents[$i-1]->testament_name}}</td>
                           </tr>
                           <tr>
                              <td>Book</td>
                              <td>{{$course->CourseContents[$i-1]->book_name}}</td>
                           </tr>
                           <tr>
                              <td>Chapter</td>
                              <td>{{$course->CourseContents[$i-1]->chapter_name}}</td>
                           </tr>
                           <tr>
                              <td>Verse from</td>
                              <td>{{$course->CourseContents[$i-1]->verse_from_name}}</td>
                           </tr>
                           <tr>
                              <td>Verse to</td>
                              <td>{{$course->CourseContents[$i-1]->verse_to_name}}</td>
                           </tr>
                           <tr>
                              <td>Text Description</td>
                              <td>{{$course->CourseContents[$i-1]->text_description}}</td>
                           </tr>
                           <tr>
                              <td>Video Links</td>
                              <td> 
                                 @if($course->CourseContents[$i-1]->video_link)
                                 <a href="{{$course->CourseContents[$i-1]->video_link}}"
                                 target="_blank">
                                 @endif
                              Click here </td>
                           </tr>
                           <tr>
                              <td>Audio File</td>
                              <td>
                                 @if($course->CourseContents[$i-1]->audio_file)
                                 <a href="{{$course->CourseContents[$i-1]->audio_file}}"
                                 target="_blank">
                                 @endif
                              Click here</td>
                           </tr>
                           <tr>
                              <td>Spotify Link</td>
                              <td>
                                 @if($course->CourseContents[$i-1]->spotify_link)
                                 <a href="{{$course->CourseContents[$i-1]->spotify_link}}"
                                 target="_blank">
                                 @endif
                              Click here</td>
                           </tr>
                           <tr>
                              <td>Website Link</td>
                              <td>
                                 @if($course->CourseContents[$i-1]->website_link)
                                 <a href="{{$course->CourseContents[$i-1]->website_link}}"
                                 target="_blank">
                                 @endif
                              Click here</td>
                           </tr>
                           <tr>
                              <td> Image</td>
                              <td><img class="img-fluid for-light"
                           src="{{ asset($course->CourseContents[$i-1]->image) }}" alt="image.jpg" style="width:150px;"></td>
                           </tr>
                           <tr>
                              <td>Documents</td>
                              <td>
                                 @if($course->CourseContents[$i-1]->documents)
                                 <a href="{{$course->CourseContents[$i-1]->documents}}"
                                 target="_blank">
                                 @endif
                              Click here</td>
                           </tr>
                        </table>
                     @else
                        <ul class="action">   
                           <li class="edit"> 
                              <a href="{{ route('admin.add.course.content',['course_id'=>$course->id,'day'=>$i]) }}">
                                 <i class="fas fa-pencil-alt"></i>
                              </a>
                            </li>                  
                        </ul>
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