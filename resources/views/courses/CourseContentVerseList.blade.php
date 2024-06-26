@extends('layouts.simple.master')
@section('title', 'Creative Card')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Courses Content Verses</h3>
@endsection
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="new-question d-flex justify-content-end mb-4">
         <a href="{{ route('admin.add.content.verses',['content_id'=>$content->id]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">Add New Verse</button>
         </a>
      </div>
      @foreach($courseVerses as $key=>$value)
         <div class="col-sm-12 col-lg-4">
            <div class="card course-bible">
               <div class="card-body">
                  <table>
                      <tr>
                        <td  colspan="2" style="text-align: center;">
                              <h6>Verse {{$key+1}}<h6>
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
                        <td>Verse From</td>
                        <td>:</td>
                        <td>{{$value->verse_from_name}}</td>
                     </tr>
                     <tr>
                        <td>Verse To</td>
                        <td>:</td>
                        <td>{{$value->verse_to_name}}</td>
                     </tr>
                     <tr>
                        <td  colspan="2" style="text-align: center;">
                          
                           <div class="view-course d-flex align-items-center justify-content-between mt-3">
                              <a href="{{  route('admin.edit.content.verses',['verse_id'=>$value->id]) }}">
                                 <button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">Edit Verse</button>
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
 
</script>
@endsection