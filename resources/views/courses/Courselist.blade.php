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
      @foreach($courses as $key=>$value)
         <div class="col-sm-12 col-lg-4">
            <div class="card course-bible">
               <div class="image d-flex justify-content-center" style="height:140px;overflow: hidden;">
                  @if($value->thumbnail && file_exists(public_path($value->thumbnail)))
                        <img class="img-fluid for-light"src="{{ asset($value->thumbnail) }}" alt="" 
                        style="width: 100%; height: auto; object-fit: cover;">
                  @else
                        <img class="img-fluid for-light"src="{{ asset('assets/images/course1.jpg') }}" alt="">
                  @endif
               </div>
               <div class="card-body">
                  <h4>{{$value->course_name}}</h4>
                  <p>{{$value->bible_name}}</p>
                  <div class="info d-flex justify-content-between">
                     <div class="days d-flex align-items-center">
                        <div class="image">
                           <img class="img-fluid for-light"
                              src="{{ asset('assets/images/calendar.png') }}" alt="">
                        </div>
                        <h3><span>No of Days</span>{{$value->no_of_days}}</h3>
                     </div>
                     <div class="course-creator text-right">
                        <h5>Course Creator</h5>
                        <p>{{$value->course_creator}}</p>
                     </div>
                  </div>
                  <div class="view-course d-flex align-items-center justify-content-between mt-3">
                     <a href="{{ route('admin.course.details',[$value->id]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">View Course </button>
                     </a>
                     <div class="media-body text-end mt-3">
                     <label class="switch">
                     <input type="checkbox" {{ $value->status=='Active' ? 'checked' : '' }} 
                     onChange="suspend({{ $value->id }},'{{ $value->status }}')">
                     <span class="switch-state"></span>
                     </label>

                  </div>
                  </div>
               </div>
            </div>
         </div>
      @endforeach
      
   </div>
</div>
@endsection
@section('script')

<script type="text/javascript">

      function suspend(id,status){

         if(status=='Active'){
            msg = 'Are you sure? Suspend this course?';
         }else{
            msg = 'Are you sure? Activate this course?';
         }
         if (confirm(msg) == true) {
             var id = id;
             $.ajax({
                 type:"POST",
                 url: "{{ route('admin.course.status.change') }}",
                 data: { _token : "<?= csrf_token() ?>",
                         id     : id
                 },
                 dataType: 'json',
                 success: function(res){
                     if (res.success== true){
                        if(res.status=='Active'){
                           $.notify({
                              title:'User',
                              message:'Course Successfully Activated'
                              },
                              {
                                 type:'primary',
                                 offset:{
                                   x:155,
                                   y:140
                                 },
                                 animate:{
                                   enter:'animated fadeIn',
                                   exit:'animated fadeOut'
                               }
                           
                           });
                        }else{
                           $.notify({
                              title:'User',
                              message:'Course Successfully Suspended'
                              },
                              {
                                 type:'primary',
                                 offset:{
                                   x:155,
                                   y:140
                                 },
                                 animate:{
                                   enter:'animated fadeIn',
                                   exit:'animated fadeOut'
                               }
                           
                           });
                        }

                     }else{
                        toastr.error(res.msg)
                     }
                 },
                 error: function(xhr, status, error) {
                     console.error('AJAX request failed:', status, error);
                     alert('Failed to delete family. Please try again later.');
                 }
             });
         }else{
            location.reload()

         }
      }
 
</script>
@endsection