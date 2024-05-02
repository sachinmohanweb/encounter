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
      <div class="col-12">
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
         <div class="card course-bible">
            <div class="image d-flex justify-content-center">
                  @if($course->thumbnail && file_exists(public_path($course->thumbnail)))
                         <img class="img-fluid for-light" src="{{ asset($course->thumbnail) }}" alt="" 
                         style="width:1200px; height: 370px;">
                  @else
                        <img class="img-fluid for-light" src="{{ asset('assets/images/course1.jpg') }}" alt="" style="width:100%;">
                  @endif
            </div>
            <div class="card-body">
               <h4>{{$course->course_name}}</h4>
               <p>{{$course->description}}</p>
               <div class="info d-flex justify-content-between flex-wrap">
                  <div class="days d-flex align-items-center">
                     <div class="image">
                        <img class="img-fluid for-light"
                           src="{{ asset('assets/images/calendar.png') }}" alt="">
                     </div>
                     <h3><span>No of Days</span>{{$course->no_of_days}}</h3>
                  </div>
                  <div class="course-creator text-right">
                     <h5>Course Creator</h5>
                     <p>{{$course->course_creator}}</p>
                  </div>
                  <div class="course-action d-flex flex-wrap align-items-center">
                  <a href="{{ route('admin.course.content',[$course->id]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">Course Content ></button>
                  </a>
                   <ul class="action ms-5">
                     <li class="edit"> <a href="{{ route('admin.edit.course',[$course->id]) }}"><i class="fas fa-pencil-alt"></i></a>
                     </li>
                     <!-- <li class="delete"><a href="#"><i class="icon-trash"></i></a></li> -->
                  </ul>
               </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-12">
      <div class="bible-batches mt-3">
         <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
               <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#Batches" type="button" role="tab" aria-controls="home" aria-selected="true">Batches</button>
            </li>
            <li class="nav-item" role="presentation">
               <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#Essentials" type="button" role="tab" aria-controls="profile" aria-selected="false">Enrollment</button>
            </li>
         </ul>
         <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="Batches" role="tabpanel" aria-labelledby="home-tab">
               <div class="new-question d-flex justify-content-end mb-4">
            <a href="{{ route('admin.new.batch') }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">New Batch</button>
            </a>
         </div>
               <div class="card">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="display" id="data-source-1" style="width:100%">
                           <thead>
                              <tr>
                                 <th>Course Id</th>
                                 <th>Batch Name</th>
                                 <th>Start date</th>
                                 <th>End Date</th>
                                 <th>Last Date for Enrollment</th>
                                 <th>Action</th>
                                 <th class="text-end">Status</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>1</td>
                                 <td><a href="{{ asset('batchdetail') }}">Batch Name</a></td>
                                 <td>2-2-2024</td>
                                 <td>4-5-2024</td>
                                 <td>3-2-2024</td>
                                 <td>
                                    <ul class="action">
                                       <li class="edit"> <a href="{{route('admin.edit.batch')}}"><i class="icon-pencil-alt"></i></a>
                                       </li>
                                       <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                                    </ul>
                                 </td>
                                 <td>
                                    <div class="media-body text-end icon-state">
                                       <label class="switch">
                                       <input type="checkbox" checked=""><span class="switch-state"></span>
                                       </label>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td>2</td>
                                 <td>Lorem Epsum</td>
                                 <td>5-5-2024</td>
                                 <td>12-9-2024</td>
                                 <td>3-2-2024</td>
                                 <td>
                                    <ul class="action">
                                       <li class="edit"> <a href="editbatch"><i class="icon-pencil-alt"></i></a>
                                       </li>
                                       <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                                    </ul>
                                 </td>
                                 <td>
                                    <div class="media-body text-end icon-state">
                                       <label class="switch">
                                       <input type="checkbox" checked=""><span class="switch-state"></span>
                                       </label>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td>3</td>
                                 <td>Lorem Epsum</td>
                                 <td>6-9-2024</td>
                                 <td>12-11-2024</td>
                                 <td>9-10-2024</td>
                                 <td>
                                    <ul class="action">
                                       <li class="edit"> <a href="editbatch"><i class="icon-pencil-alt"></i></a>
                                       </li>
                                       <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                                    </ul>
                                 </td>
                                 <td>
                                    <div class="media-body text-end icon-state">
                                       <label class="switch">
                                       <input type="checkbox" checked=""><span class="switch-state"></span>
                                       </label>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td>4</td>
                                 <td>Lorem Epsum</td>
                                 <td>2-2-2024</td>
                                 <td>4-5-2024</td>
                                 <td>3-2-2024</td>
                                 <td>
                                    <ul class="action">
                                       <li class="edit"> <a href="editbatch"><i class="icon-pencil-alt"></i></a>
                                       </li>
                                       <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                                    </ul>
                                 </td>
                                 <td>
                                    <div class="media-body text-end icon-state">
                                       <label class="switch">
                                       <input type="checkbox" checked=""><span class="switch-state"></span>
                                       </label>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
            <div class="tab-pane fade" id="Essentials" role="tabpanel" aria-labelledby="profile-tab">
               
               <div class="card">
                  <div class="card-body">
                     <div class="table-responsive">
                        <table class="display" id="data-source-1" style="width:100%">
                           <thead>
                              <tr>
                                 <th>Course Id</th>
                                 <th>User Name</th>
                                 <th>Batch ID</th>
                                 <th>Start date</th>
                                 <th>End Date</th>
                                 <th>Progress</th>
                                 <th>Completed Status</th>
                                 <th>Action</th>
                                 <th class="text-end">Status</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <td>1</td>
                                 <td>Tiger Nixon</td>
                                 <td>S12365</td>
                                 <td>2-2-2024</td>
                                 <td>4-5-2024</td>
                                 <td>Progress Status</td>
                                 <td>Ongoing</td>
                                 <td>
                                    <ul class="action">
                                       <li class="edit"> <a href="editbatch"><i class="icon-pencil-alt"></i></a>
                                       </li>
                                       <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                                    </ul>
                                 </td>
                                 <td>
                                    <div class="media-body text-end icon-state">
                                       <label class="switch">
                                       <input type="checkbox" checked=""><span class="switch-state"></span>
                                       </label>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td>2</td>
                                 <td>Tiger Nixon</td>
                                 <td>S12365</td>
                                 <td>2-2-2024</td>
                                 <td>4-5-2024</td>
                                 <td>Progress Status</td>
                                 <td>Ongoing</td>
                                 <td>
                                    <ul class="action">
                                       <li class="edit"> <a href="editbatch"><i class="icon-pencil-alt"></i></a>
                                       </li>
                                       <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                                    </ul>
                                 </td>
                                 <td>
                                    <div class="media-body text-end icon-state">
                                       <label class="switch">
                                       <input type="checkbox" checked=""><span class="switch-state"></span>
                                       </label>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td>3</td>
                                 <td>Tiger Nixon</td>
                                 <td>S12365</td>
                                 <td>2-2-2024</td>
                                 <td>4-5-2024</td>
                                 <td>Progress Status</td>
                                 <td>Ongoing</td>
                                 <td>
                                    <ul class="action">
                                       <li class="edit"> <a href="editbatch"><i class="icon-pencil-alt"></i></a>
                                       </li>
                                       <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                                    </ul>
                                 </td>
                                 <td>
                                    <div class="media-body text-end icon-state">
                                       <label class="switch">
                                       <input type="checkbox" checked=""><span class="switch-state"></span>
                                       </label>
                                    </div>
                                 </td>
                              </tr>
                              <tr>
                                 <td>4</td>
                                 <td>Tiger Nixon</td>
                                 <td>S12365</td>
                                 <td>2-2-2024</td>
                                 <td>4-5-2024</td>
                                 <td>Progress Status</td>
                                 <td>Ongoing</td>
                                 <td>
                                    <ul class="action">
                                       <li class="edit"> <a href="editbatch"><i class="icon-pencil-alt"></i></a>
                                       </li>
                                       <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                                    </ul>
                                 </td>
                                 <td>
                                    <div class="media-body text-end icon-state">
                                       <label class="switch">
                                       <input type="checkbox" checked=""><span class="switch-state"></span>
                                       </label>
                                    </div>
                                 </td>
                              </tr>
                            
                           </tbody>
                        </table>
                     </div>
                  </div>
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