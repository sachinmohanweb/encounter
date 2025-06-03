@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Add Course Content Verse</h3>
@endsection
@section('breadcrumb-items')
<li class="breadcrumb-item">Course Content Section</li>
<li class="breadcrumb-item active">Add Course Content Section</li>
@endsection
@section('content')
<div class="container-fluid">
   <div class="card">
      <div class="card-body">

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

         <div class="row">
            <div class="col-md-12">
               <div class="date-picker">
                  <form class="theme-form" action="{{route('admin.save.content.verses')}}" method="Post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="course_content_id" value="{{$content_id}}">
                     <div class="verse d-flex flex-wrap">
                        <div class="col-lg-1 col-md-1 col-12">
                           <div class="form-group">
                              <label for="">Day No</label>

                              <br>
                              <button class="butn_disabled nav-link">{{$content['day']}}</button>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Bible</label>
                              <button class="butn_disabled nav-link">{{$course->bible_name}}</button>

                           </div>
                        </div>
                         <div class="col-lg-4 col-md-4 col-12">
                           <div class="form-group">
                              <label for="">Testament<span style="color:red">*</span></label>
                              <select class="js-data-example-ajax form-select" id="testament" name="testament" required 
                              value="{{old('testament')}}"></select>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                           <div class="form-group">
                              <label for="">Book<span style="color:red">*</span></label>
                              <select class="js-data-example-ajax form-select" id="book" name="book" required></select>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                           <div class="form-group">
                              <label for="">Chapter<span style="color:red">*</span></label>
                              <select class="js-data-example-ajax form-select" id="chapter" name="chapter" required></select>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                           <div class="form-group">
                              <label for="">Section From<span style="color:red">*</span></label>
                              <select class="js-data-example-ajax form-select" id="verse_no_s" name="verse_from" required></select>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                           <div class="form-group">
                              <label for="">Section To<span style="color:red">*</span></label>
                              <select class="js-data-example-ajax form-select" id="verse_no_l" name="verse_to" required></select>
                           </div>
                        </div>
                       
                        <div class="col-12">
                           <div class="encounter-btn d-flex justify-content-center mt-4">
                              <button class="btn btn-pill btn-danger btn-lg" type="button" data-bs-original-title="" title="" 
                              onclick="window.location='{{ route('admin.course.details',[ $course->id]) }}'">
                              Cancel</button>
                              <button class="btn btn-pill btn-success btn-lg ms-3" type="submit" data-bs-original-title="" title="">Submit</button>
                           </div>
                        </div>
                     </div>

                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('script')
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/notify/index.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>

<script type="text/javascript">

    $('#testament').on('change', function () {
        $('#book').val(null).trigger('change'); 
        $('#chapter').val(null).trigger('change');
        $('#verse_no_s').val(null).trigger('change');
        $('#verse_no_l').val(null).trigger('change');
    });

    $('#book').on('change', function () {
        $('#chapter').val(null).trigger('change');
        $('#verse_no_s').val(null).trigger('change');
        $('#verse_no_l').val(null).trigger('change');
    });

    $('#chapter').on('change', function () {
        $('#verse_no_s').val(null).trigger('change');
        $('#verse_no_l').val(null).trigger('change');
    });


   $('#testament').select2({
         placeholder: "Select Testament",

         ajax: {
             url: "<?= url('get_testament_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     search_tag: data.term,
                     bible_id  : '<?= $course->bible_id ?>',

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

     $('#book').select2({
         placeholder: "Select Book",
         ajax: {
             url: "<?= url('get_book_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     search_tag: data.term,
                     testament_id:$('#testament').val(),
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

     $('#chapter').select2({
         placeholder: "Select Chapter",
         ajax: {
             url: "<?= url('get_chapter_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     search_tag: data.term,
                     book_id:$('#book').val(),

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

     $('#verse_no_s').select2({
         placeholder: "Select Verse",
         ajax: {

             url: "<?= url('get_verse_no_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     search_tag: data.term,
                     chapter_id:$('#chapter').val(),

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

     $('#verse_no_l').select2({
         placeholder: "Select Verse",
         ajax: {

             url: "<?= url('get_verse_no_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     search_tag: data.term,
                     chapter_id:$('#chapter').val(),
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