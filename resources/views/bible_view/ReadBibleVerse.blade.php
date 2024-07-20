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
                  <div class="view-course d-flex align-items-center justify-content-end mt-3">
 
                     <a ><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" onclick="EditFunc('{{$value->statement_id}}')">Edit  </button></a>
                  </div>
               </div>
            </div>
         </div>
      @endforeach
   </div>
</div>

<div class="modal fade" id="EditBibleVerseModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 650px !important;"> 
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Bible Verse</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form class="needs-validation" id="EditBibleVerseModalForm" novalidate="" method="Post">
            @csrf
            <div class="modal-body">
               <div class="row g-3 mb-3">
                  <div class="col-md-12">
                     <label class="form-label" for="validationCustom02">Verse</label>
                     <textarea name="statement_text" id="statement_text" rows="5" class="form-control" required>{{$value->statement_text}}</textarea>
                     <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="modal-footer">
                     <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" onclick="window.location='{{route('admin.read.bible.view.verse', ['chapter_id' => $value->chapter_id])}}'">Close</button>
                     <button class="btn btn-success" type="submit">Update</button>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
      
@endsection
@section('script')
<script type="text/javascript">
   
function EditFunc(id){
   $.ajax({
       type:"post",
       url: "{{ route('admin.get.holy_statement') }}",
       data: { _token : "<?= csrf_token() ?>",
               id     : id
       },
       dataType: 'json',
       success: function(res){
           $('#statement_id').val(res.statement_id);
           $('#statement_text').val(res.statement_text);
            $('#EditBibleVerseModalForm').attr('action', "{{ url('updateholystatement') }}/" + id);
           $('#EditBibleVerseModal').modal('show');
       },
       error: function(xhr, status, error) {
           console.error('AJAX request failed:', status, error);
           alert('Failed get data. Please try again later.');
       }
   });
}  

</script>
@endsection