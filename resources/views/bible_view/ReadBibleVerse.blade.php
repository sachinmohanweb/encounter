@extends('layouts.simple.master')
@section('title', 'Creative Card')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
<style type="text/css">
   
   .statement_scroll {
       min-height: 250px !important;
       max-height: 250px !important;
       overflow-y: auto;
   }

   .align_center{
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
   }
</style>
@endsection
@section('breadcrumb-title')
<h3>Bible Verses</h3>
@endsection
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="new-question d-flex justify-content-end mb-4">
        @if(Session::has('success'))
            <div class="alert alert-success" style="width: 500px;">
               <ul>
                  <li>{!! Session::get('success') !!}</li>
               </ul>
            </div>
          @endif
          @if (Session::has('error'))
            <div class="alert alert-danger" style="width: 500px;">
               <ul>
                  <li>{!! Session::get('error') !!}</li>
               </ul>
            </div>
         @endif
         @if($errors->any())
            <h6 style="color:red;padding: 20px 0px 0px 30px;style=width: 500px;">{{$errors->first()}}</h6>
         @endif
      </div>

       <div class="col-sm-12 col-lg-12">
            <div class="card course-bible">
               <div class="card-body" style="padding: 30px 30px 15px 30px !important;">
                 <div class="row align_center" >
                    <h4>{{$chapter_details->bible->bible_name}} - {{$chapter_details->testament->testament_name}}</h4>
                    <h5>{{$chapter_details->book->book_name}} - {{$chapter_details->chapter->chapter_name}}</h5>
                 </div> 
               </div>
            </div>
         </div>
      @foreach($verses as $key=>$value)
         <div class="col-sm-12 col-lg-6">
            <div class="card course-bible">
               <div class="card-body statement_scroll">
                  @if($chapter_details->chapter->chapter_name=='ആമുഖം')
                     <h5>{{$chapter_details->chapter->chapter_name}} </h5>
                  @else
                     <h5>Verse : {{$value->statement_no}} </h5>
                  @endif
                  @if($value->statement_heading)
                     <h5>{{$value->statement_heading}}</p>
                  @endif
                  <div class="info d-flex justify-content-between" style="margin-top: 20px;">
                     <div class="text-right">
                        <p style="font-weight:600">{{$value->statement_text}}</p>
                     </div>
                  </div>
                  <div class="view-course d-flex align-items-center justify-content-between mt-3">
 
                     <a ><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">Edit  </button></a>
                  </div>
               </div>
            </div>
         </div>
      @endforeach

      
   </div>
   </div>

@endsection
@section('script')

@endsection