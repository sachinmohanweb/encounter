@extends('layouts.simple.master')
@section('title', 'Creative Card')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
<style>
   ::-webkit-scrollbar {
      width: 8px; /* Set the width of the scrollbar */
   }

   ::-webkit-scrollbar-thumb {
      background-color:#06b0e0a3; /* Scrollbar color */
      border-radius: 20px; /* Round the corners */
   }

   ::-webkit-scrollbar-track {
      background: #f1f1f1; /* Track color */
      border-radius: 8px; /* Round the corners of the track */
   }
   .batch_div{
      height:100px; 
      overflow-y: auto; 
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
      padding:12px
   }

   .custom-order-badge {
       position: absolute;
       top: 8px;
       left: 8px;
       background-color: #007bff;
       color: white;
       padding: 4px 8px;
       border-radius: 4px;
       z-index: 1;
   }
</style>
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
                  @if($value->course_order !== null)
                     <span class="custom-order-badge">Visibility Order: {{ $value->course_order }}</span>
                  @endif
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
                  <div class="batch_div">
                     <h6>Batches - Ends in</h6>
                     <table>
                     @foreach($value->BatchDetails as $key1=>$value1)
                        <tr>
                           <td> {{$key1+1}}. </td>
                           <td style="padding: 5px;">{{$value1->batch_name}} </td>
                           <td> : </td>
                           <td style="padding: 5px;"> {{ \Carbon\Carbon::parse($value1->end_date)->format('d/m/y') }}</td>
                        </tr>
                     @endforeach
                     </table>
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