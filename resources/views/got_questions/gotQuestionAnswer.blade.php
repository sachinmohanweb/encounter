@extends('layouts.simple.master')
@section('title', 'Default')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Got Questions</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         
         <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('admin.store.GotQuestion')}}" method="Post" enctype="multipart/form-data" 
                  class="got-question-answer">
                     @csrf
                    <div class="form-froup mb-3">
                       <label for="">Question <span style="color:red">*</span> </label>
                       <!-- <input type="text" class="form-control" name="question" required> -->
                       <textarea class="form-control tinyeditor" id="question_cnt" name="question" 
                       rows="10" placeholder="{{ __('Question..') }}" required>
                       {{ old('question') }}
                       </textarea>
                    </div>
                    <div class="form-group mb-3">
                       <label for="">Category <span style="color:red">*</span> </label>
                        <select class="js-data-example-ajax form-select" id="category_id" name="category_id" required></select>
                     </div>
                     <div class="form-group mb-3">
                       <label for="">Sub Category <span style="color:red">*</span> </label>
                        <select class="js-data-example-ajax form-select" id="sub_category_id" name="sub_category_id" required></select>
                    </div>
                     <div class="form-froup mb-3">
                       <label for="">Answer <span style="color:red">*</span> </label>
                       <!-- <textarea rows="5" class="form-control" name="answer" required></textarea> -->
                       <textarea class="form-control tinyeditor" id="answer_cnt" name="answer" 
                       rows="10" placeholder="{{ __('Answer..') }}" >
                           {{ old('answer') }}
                       </textarea>
                    </div>
                    <div class="form-group d-flex justify-content-end">
                       <button class="btn btn-primary active" type="submit" data-bs-original-title="" title="">Submit</button>
                    </div>
                </form>
            </div>
         </div>
      </div>
   </div>
</div>

@endsection
@section('script')
<script src="{{ asset('assets/js/clock.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
<script src="{{ asset('assets/js/height-equal.js') }}"></script>
<script src="{{ asset('assets/js/animation/wow/wow.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

<script type="text/javascript">

   $('#category_id').select2({
         placeholder: "Select Category",

         ajax: {
             url: "<?= url('get_gq_category_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     search_tag: data.term,
                 };
             },
             processResults: function(data, params) {
                 params.page = params.page || 1;
                 return {
                     results: data.results,
                     pagination: { more: (params.page * 30) < data.total_count }
                 };
             },
             cache: true
         }
     });

     $('#sub_category_id').select2({
         placeholder: "Select Sub Category",
         ajax: {
             url: "<?= url('get_gq_subcategory_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     search_tag: data.term,
                     category_id:$('#category_id').val(),
                 };
             },
             processResults: function(data, params) {
                 params.page = params.page || 1;
                 return {
                     results: data.results,
                     pagination: { more: (params.page * 30) < data.total_count }
                 };
             },
             cache: true
         }
     });

</script>
@endsection