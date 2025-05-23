@extends('layouts.simple.master')
@section('title', 'Creative Card')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3 style="margin-bottom: 10px !important;">Courses Content Sections</h3>
      <a href="{{ route('admin.course.details',['id'=>$course->id]) }}" ><button class="btn btn-pill btn-info-gradien pt-2 pb-2">View Course</button> </a>
      <a href="{{ route('admin.course.content',['id'=>$course->id,'active_tab' => $content->day]) }}" ><button class="btn btn-pill btn-info-gradien pt-2 pb-2">View Course Content </button></a>
@endsection
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="new-question d-flex justify-content-end mb-4">
         <a href="{{ route('admin.add.content.verses',['content_id'=>$content->id]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">Add New Section</button>
         </a>
      </div>
      @foreach($courseVerses as $key=>$value)
         <div class="col-sm-12 col-lg-4">
            <div class="card course-bible">
               <div class="card-body">
                  <table>
                      <tr>
                        <td  colspan="2" style="text-align: center;">
                              <h6>Section {{$key+1}}<h6>
                        </td>
                     </tr>
                     <tr>
                        <td>Testament</td>
                        <td>:</td>
                        <td>{{$value->testament_name}}</td>
                     </tr>
                     <tr>
                        <td>Book</td>
                        <td>:</td>
                        <td>{{$value->book_name}}</td>
                     </tr>
                     <tr>
                        <td>Chapter</td>
                        <td>:</td>
                        <td>{{$value->chapter_name}}</td>
                     </tr>
                     <tr>
                        <td>Section From</td>
                        <td>:</td>
                        <td>{{$value->verse_from_name}}</td>
                     </tr>
                     <tr>
                        <td>Section To</td>
                        <td>:</td>
                        <td>{{$value->verse_to_name}}</td>
                     </tr>
                     <tr style="text-align: center;">
                        <td  colspan="2" style="text-align: center;">
                          
                           <div class="view-course d-flex align-items-center justify-content-between mt-3">
                              <a href="{{  route('admin.edit.content.verses',['verse_id'=>$value->id]) }}">
                                 <button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">Edit</button>
                              </a>   
                           </div>

                           <div class="view-course d-flex align-items-center justify-content-between mt-3"> 
                              <a href="#">
                                 <button class="btn btn-pill btn-danger-gradien pt-2 pb-2" type="button" data-bs-original-title="" title=""  onClick="remove({{ $value->id }})">delete</button>
                              </a>
                           </div>
                        </td>
                     </tr>
                  </table><br>                  
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


   function remove(id){

      msg = 'Are you sure? Delete this section?';

      if (confirm(msg) == true) {
          var id = id;
          $.ajax({
              type:"POST",
              url: "{{ route('admin.delete.content.verses') }}",
              data: { _token : "<?= csrf_token() ?>",
                  id     : id
              },
              dataType: 'json',
              success: function(res){
                  if (res.status=='success'){
                        $.notify({
                           title:'Notification',
                           message:'Section Successfully deleted'
                           },
                           {
                              type:'primary',
                              offset:{
                                x:35,
                                y:230
                              },
                              animate:{
                                enter:'animated fadeIn',
                                exit:'animated fadeOut'
                            }
                        });
                  }else if (res.status=='Forbidden'){
                        $.notify({
                           title:'Notification',
                           message:'Unable to delete this section — a related batch has already commenced.'
                           },
                           {
                              type:'primary',
                              offset:{
                                x:35,
                                y:230
                              },
                              animate:{
                                enter:'animated fadeIn',
                                exit:'animated fadeOut'
                            }
                        });

                  }else{
                        $.notify({
                           title:'Notification',
                           message:'Section Not deleted'
                           },
                           {
                              type:'danger',
                              offset:{
                                x:35,
                                y:230
                              },
                              animate:{
                                enter:'animated fadeIn',
                                exit:'animated fadeOut'
                            }
                        
                        });
                  }
              },
              error: function(xhr, status, error) {
                  console.error('AJAX request failed:', status, error);
                  alert('Failed to delete notification. Please try again later.');
              }
          });
      }
   }
 
</script>
@endsection