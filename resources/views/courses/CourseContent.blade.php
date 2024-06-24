<?php
   use App\Models\CourseDayVerse;
?>

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
</style>
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

                     @php
                           $content = $course->getContentForDay($i);
                     @endphp

                        <h6>Day details</h6>
                        <table>
                           <tr>
                              <td>Course Name</td>
                              <td>{{$content->course_name}}</td>
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
                                 @if($content->video_link)
                                 <a href="{{$content->video_link}}"
                                 target="_blank">
                                 @endif
                              Click here </td>
                           </tr>
                           <tr>
                              <td>Audio File</td>
                              <td>
                                 @if($content->audio_file)
                                 <a href="{{$content->audio_file}}"
                                 target="_blank">
                                 @endif
                              Click here</td>
                           </tr>
                           <tr>
                              <td>Spotify Link</td>
                              <td>
                                 @if($content->spotify_link)
                                 <a href="{{$content->spotify_link}}"
                                 target="_blank">
                                 @endif
                              Click here</td>
                           </tr>
                           <tr>
                              <td>Website Link</td>
                              <td>
                                 @if($content->website_link)
                                 <a href="{{$content->website_link}}"
                                 target="_blank">
                                 @endif
                              Click here</td>
                           </tr>
                           <tr>
                              <td> Image</td>
                              <td class="image_td"><img class="img-fluid for-light"
                           src="{{ asset($content->image) }}" alt="image.jpg"></td>
                           </tr>
                           <tr>
                              <td>Documents</td>
                              <td>
                                 @if($content->documents)
                                 <a href="{{$content->documents}}"
                                 target="_blank">
                                 @endif
                              Click here</td>
                           </tr>
                           <tr>
                              <td  colspan="2" style="text-align: center;">
                                 <a href="{{ route('admin.edit.course.content',['content_id'=>$content->id]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2">Edit Course content</button></a>
                              </td>
                           </tr>
                        </table>

                        <h6>Verse details</h6>
                        <a href="{{ route('admin.add.content.verses',['content_id'=>$content->id]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2">Add verse</button></a>

                        <?php
                           $course_day_verses = CourseDayVerse::where('course_content_id',$content->id)->get();
                        ?>

                        @if($course_day_verses)
                              <div>
                                 @foreach($course_day_verses as $key=>$value)
                                    <table>
                                        <tr>
                                          <td  colspan="2" style="text-align: center;">
                                                Verse {{$key+1}}
                                          </td>
                                       </tr>
                                       <tr>
                                          <td>Testament Name</td>
                                          <td>{{$value->testament_name}}</td>
                                       </tr>
                                       <tr>
                                          <td>Book</td>
                                          <td>{{$value->book_name}}</td>
                                       </tr>
                                       <tr>
                                          <td>Chapter</td>
                                          <td>{{$value->chapter_name}}</td>
                                       </tr>
                                       <tr>
                                          <td>Verse From</td>
                                          <td>{{$value->verse_from_name}}</td>
                                       </tr>
                                       <tr>
                                          <td>Verse To</td>
                                          <td>{{$value->verse_to_name}}</td>
                                       </tr>
                                       <tr>
                                          <td  colspan="2" style="text-align: center;">
                                             <a href="{{ route('admin.edit.content.verses',['verse_id'=>$value->id]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2">Edit</button></a>
                                          </td>
                                       </tr>
                                    </table><br>
                                 @endforeach
                              </div>
                        @endif
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