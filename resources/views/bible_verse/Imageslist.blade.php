@extends('layouts.simple.master')
@section('title', 'Creative Card')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Daily Verse Images</h3>
@endsection
@section('content')
<div class="container-fluid">
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
   <div class="row">
      <div class="new-question d-flex justify-content-end mb-4">
            <button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" 
            data-bs-toggle="modal" data-bs-target="#AddBanner" id="updateActionButton">Add Image 
            </button>
      </div>
      @foreach($images as $key=>$value)
         <div class="col-sm-12 col-lg-4">
            <div class="card course-bible">
               <div class="image d-flex justify-content-center" style="height:250px;overflow: hidden;">
                  @if($value->path && file_exists(public_path($value->thumbnail)))
                        <img class="img-fluid for-light"src="{{ asset($value->path) }}" alt="" 
                        style="width: 100%; height: auto; object-fit: cover;">
                  @else
                        <img class="img-fluid for-light"src="{{ asset('assets/images/course1.jpg') }}" alt="">
                  @endif
               </div>
               <div class="card-body" style="padding: 10px 0px 0px 20px;;">
                  <h4>{{$value->title}}</h4>
                  <div class="view-course d-flex align-items-center justify-content-between ">
                     <div class="media-body text-end mt-3">
                        <label class="switch">
                           <input type="checkbox" {{ $value->status=='Active' ? 'checked' : '' }} 
                           onChange="selectImage({{ $value->id }},'{{ $value->status }}')">
                           <span class="switch-state"></span>
                        </label>

                     </div>
                     <div class="media-body text-end mt-3">
                         <button class="btn btn-danger" onClick="remove({{ $value->id }})">Delete</button>
                     </div>
                  </div>
                  
               </div>
            </div>
         </div>
      @endforeach
      
   </div>
</div>

<div class="modal fade" id="AddBanner" tabindex="-1" role="dialog" aria-labelledby="AddBanner" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 650px !important;"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daily Verse Image</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form
                  action="{{ route('admin.add.daily_verse_bg') }}" method="Post" enctype="multipart/form-data">
                    <div class="modal-body">
                    @csrf
                        <div class="row g-3 mb-3">
                           <div class="form-group">
                              <label for="">Title<span style="color:red">*</span></label>
                              <input type="text" placeholder="Title" name="title" class="form-control" required>
                           </div>
                        </div>
                        <div class="row g-3 mb-3">
                           <div class="form-group">
                              <label for="">Image File<span style="color:red">* Image size must be 400 * 180 pixels for optimal display</span></label>
                              <input type="file" placeholder="Image File" name="file" class="form-control" required>
                           </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" onclick="window.location='{{ route('admin.app_banners.list') }}'">
                        Close</button>
                        <button class="btn btn-success" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
   </div>


@endsection
@section('script')

<script type="text/javascript">

      function selectImage(id,status){

        if(status=='Active'){
            msg = 'Are you sure? Reject this image?';
         }else{
            msg = 'Are you sure? Select this image?';
         }
         if (confirm(msg) == true) {
             var id = id;
             $.ajax({
                 type:"POST",
                 url: "{{ route('admin.select.daily_verse_bg') }}",
                 data: { _token : "<?= csrf_token() ?>",
                         id     : id
                 },
                 dataType: 'json',
                 success: function(res){
                     if (res.success== true){
                        if(res.status=='Active'){
                           $.notify({
                              title:'Image',
                              message:'Image Successfully Selected'
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
                              title:'Image',
                              message:'Image Rjected'
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

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                     }else{
                        toastr.error(res.msg)
                     }
                 },
                 error: function(xhr, status, error) {
                     console.error('AJAX request failed:', status, error);
                     alert('Failed to select image. Please try again later.');
                 }
             });
         }else{
            location.reload()

         }
      }
   function remove(id){

      msg = 'Are you sure? Delete this image?';

      if (confirm(msg) == true) {
          var id = id;
          $.ajax({
              type:"POST",
              url: "{{ route('admin.delete.daily_verse_bg') }}",
              data: { _token : "<?= csrf_token() ?>",
                  id     : id
              },
              dataType: 'json',
              success: function(res){
                  if (res.status=='success'){
                        $.notify({
                           title:'Bible verse',
                           message:'Bible verse image Successfully deleted'
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
                           title:'Bible verse',
                           message:'Bible verse image Not deleted'
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
                  setTimeout(function() {
                      location.reload();
                  }, 500);
              },
              error: function(xhr, status, error) {
                  console.error('AJAX request failed:', status, error);
                  alert('Failed to delete image. Please try again later.');
              }
          });
      }
   }
 
</script>
@endsection