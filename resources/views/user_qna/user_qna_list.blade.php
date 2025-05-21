@extends('layouts.simple.master')
@section('title', 'Creative Card')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>User QNA</h3>
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
      @foreach($User_QNA as $key=>$value)
         <div class="col-sm-12 col-lg-4">
            <div class="card course-bible">
               <div class="card-body">
                  <h5>Question</h5>
                  <p class="box_height_scroll">"{{$value->question}}?"</p>
                  <div class="info d-flex justify-content-between">
                     <div class="days d-flex align-items-center">
                        <div class="image">
                           <img class="img-fluid for-light"
                              src="{{ asset('assets/images/calendar.png') }}" alt="">
                        </div>
                        <h3><span>{{$value->status}}</span></h3>
                     </div>
                     <div class="course-creator text-right">
                        <h5>User</h5>
                        <p>{{$value->user_name}}</p>
                     </div>
                  </div>
                  <div class="view-course d-flex align-items-center justify-content-between mt-3">
                     @if($value->status=='Pending')

                     <button class="btn btn-pill btn-info-gradien pt-2 pb-2 updateActionButtonClass" type="button" data-bs-toggle="modal" 
                     data-bs-target="#UpdateAnswer"  question_id="{{$value->id}}" id="updateActionButton">Update Answer </button>
                     @else
                     <a href="{{ route('admin.user_qna.details',[$value->id]) }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">View Answer </button></a>
                     @endif
                  </div>
               </div>
            </div>
         </div>
      @endforeach
   </div>
   </div>
      <div class="new-question d-flex justify-content-center mb-4">
            {{ $User_QNA->links('pagination::bootstrap-4') }}
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
      $('.updateActionButtonClass').on('click', function(event) {
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