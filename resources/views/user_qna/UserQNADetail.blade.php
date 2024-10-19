@extends('layouts.simple.master')
@section('title', 'Creative Card')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
<style type="text/css">
   .pd_setup{
      padding: 8px 0px 1px 15px;
   }
</style>
@endsection
@section('breadcrumb-title')
<h3>User QNA</h3>
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
            <div class=" d-flex justify-content-left">
               <h4 style="padding:10px">Question & Answer</h4>
            </div>
            <div class="card-body">
               <div class="info d-flex justify-content-between flex-wrap">
                  <div class="days d-flex align-items-center">
                        Status : <h5 class="pd_setup"> {{$User_QNA->status}}</h5>
                     </div>
                     <div class="course-action d-flex flex-wrap align-items-center">
                     <button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-toggle="modal" data-bs-target="#UpdateAnswer" id="updateActionButton" question_id="{{$User_QNA->id}}" >Update Answer </button>
                  </div>
               </div>
               <br>
               <div class="info d-flex justify-content-between flex-wrap">
                     <div class="days d-flex align-items-center">
                        User : <h5 class="pd_setup"> {{$User_QNA->user_name}}</h5>
                     </div>
                     <div class="days d-flex align-items-center">
                        Phone : <h5 class="pd_setup">
                           {{$User_QNA->UserDetails()->country_code}} 
                           {{$User_QNA->UserDetails()->phone}}
                        </h5>
                     </div>
                     <div class="days d-flex align-items-center">
                        Email : <h5 class="pd_setup"> {{$User_QNA->UserDetails()->email}}</h5>
                     </div>

                     
               </div>
               <br><br>
               <h5> "{{$User_QNA->question}}?"</h5><br>
               <div  class="row">
                  <div class="col-md-1"></div>
                  <div class="col-md-10 ">
                     Answer/Response<br><br>
                     <h6 style="background:#f7f8f9; padding: 30px;"> {{$User_QNA->answer}}</h6>
                  </div>
                  <div class="col-md-1"></div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="UpdateAnswer" tabindex="-1" role="dialog" aria-labelledby="UpdateAnswer" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 650px !important;"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User QNA</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" novalidate="" action="{{ route('admin.update.user_qna.answer') }}" method="Post">
                    <div class="modal-body">
                    @csrf
                    <input type="hidden" name="user_qna_id" value="">
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                          <label class="form-label" for="validationCustom01">Question</label>
                          <div id="user_qna_question"></div>
                          <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-12">
                          <label class="form-label" for="validationCustom02">Answer <span style="color:red"> * </span></label>
                           <textarea class="form-control" id="user_qna_answer" name="user_qna_answer" rows="8" cols="50" required></textarea><br>
                          <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" onclick="window.location='{{ route('admin.user_qna') }}'">Close</button>
                        <button class="btn btn-success" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
   </div>
@endsection
@section('script')


<script type="text/javascript">

   $(document).ready(function() {
      $('#updateActionButton').on('click', function(event) {

      var questionId = $(this).attr('question_id');
      var url = '/get_user_qna/' + questionId;
      $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('#user_qna_question').text(data.question);
                $('#user_qna_answer').val(data.answer);
                $('input[name="user_qna_id"]').val(questionId);
            },
            error: function(xhr, status, error) {
              console.error('AJAX Error:', status, error);
            }
      });
      });
   });
 
</script>
@endsection