@extends('layouts.simple.master')
@section('title', 'Creative Card')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Courses</h3>
@endsection
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="new-question d-flex justify-content-end mb-4">
         <a href="{{ route('admin.add.course') }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">Add Course</button>
         </a>
      </div>
      <div class="col-sm-12 col-lg-4">
         <div class="card course-bible">
            <div class="image d-flex justify-content-center">
               <img class="img-fluid for-light"
                  src="{{ asset('assets/images/course1.jpg') }}" alt="">
            </div>
            <div class="card-body">
               <h4>Course Title Goes here</h4>
               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.</p>
               <div class="info d-flex justify-content-between">
                  <div class="days d-flex align-items-center">
                     <div class="image">
                        <img class="img-fluid for-light"
                           src="{{ asset('assets/images/calendar.png') }}" alt="">
                     </div>
                     <h3><span>No of Days</span>8</h3>
                  </div>
                  <div class="course-creator text-right">
                     <h5>Course Creator</h5>
                     <p>Fr Daniel Poovannathil</p>
                  </div>
               </div>
               <div class="view-course d-flex align-items-center justify-content-between mt-3">
                  <a href="{{ route('admin.course.details') }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">View Course ></button>
                  </a>
                  <div class="media-body text-end icon-state mt-3">
                  <label class="switch">
                  <input type="checkbox" checked=""><span class="switch-state"></span>
                  </label>
               </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-12 col-lg-4">
         <div class="card course-bible">
            <div class="image d-flex justify-content-center">
               <img class="img-fluid for-light"
                  src="{{ asset('assets/images/course1.jpg') }}" alt="">
            </div>
            <div class="card-body">
               <h4>Course Title Goes here</h4>
               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.</p>
               <div class="info d-flex justify-content-between">
                  <div class="days d-flex align-items-center">
                     <div class="image">
                        <img class="img-fluid for-light"
                           src="{{ asset('assets/images/calendar.png') }}" alt="">
                     </div>
                     <h3><span>No of Days</span>8</h3>
                  </div>
                  <div class="course-creator text-right">
                     <h5>Course Creator</h5>
                     <p>Fr Daniel Poovannathil</p>
                  </div>
               </div>
               <div class="view-course d-flex align-items-center justify-content-between mt-3">
                  <a href="{{ route('admin.course.details') }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">View Course ></button>
                  </a>
                  <div class="media-body text-end icon-state mt-3">
                  <label class="switch">
                  <input type="checkbox" checked=""><span class="switch-state"></span>
                  </label>
               </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-12 col-lg-4">
         <div class="card course-bible">
            <div class="image d-flex justify-content-center">
               <img class="img-fluid for-light"
                  src="{{ asset('assets/images/course1.jpg') }}" alt="">
            </div>
            <div class="card-body">
               <h4>Course Title Goes here</h4>
               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the.</p>
               <div class="info d-flex justify-content-between">
                  <div class="days d-flex align-items-center">
                     <div class="image">
                        <img class="img-fluid for-light"
                           src="{{ asset('assets/images/calendar.png') }}" alt="">
                     </div>
                     <h3><span>No of Days</span>8</h3>
                  </div>
                  <div class="course-creator text-right">
                     <h5>Course Creator</h5>
                     <p>Fr Daniel Poovannathil</p>
                  </div>
               </div>
               <div class="view-course d-flex align-items-center justify-content-between mt-3">
                  <a href="{{ route('admin.course.details') }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">View Course ></button>
                  </a>
                  <div class="media-body text-end icon-state mt-3">
                  <label class="switch">
                  <input type="checkbox" checked=""><span class="switch-state"></span>
                  </label>
               </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('script')
@endsection