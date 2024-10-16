@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Add Daily Bible Verse</h3>
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
                  <form class="theme-form" action="{{route('admin.update.DailyBibleVerse',[])}}" method="Post"
                  enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="id" value="{{$verse->id}}">
                     <div class="verse d-flex flex-wrap">
                        <div class="col-lg-3 col-md-6 col-12">
                           <div class="form-group">
                              <label for="">Bible</label>
                              <input type="hidden" name="bible_id" value="{{$default_bible_id}}">
                              <button class="butn_disabled nav-link" style="width: 100%;">{{$default_bible['bible_name']}}</button>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                             <label for="">Testament<span style="color:red">*</span></label>
                              <select class="js-data-example-ajax form-select" id="testament" name="testament_id" required>
                                 @foreach($testaments as $testament_value)
                                     @if($verse->testament_id == $testament_value->testament_id)
                                           <option value="{{ $testament_value->testament_id }}" selected>
                                             {{ $testament_value->testament_name }}</option>
                                     @else
                                           <option value="{{ $testament_value->testament_id }}">
                                              {{ $testament_value->testament_name }}</option>
                                     @endif
                                   @endforeach 
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                             <label for="">Book<span style="color:red">*</span></label>
                              <select class="js-data-example-ajax form-select" id="book" name="book_id" required>
                                 @foreach($books as $book_value)
                                     @if($verse->book_id == $book_value->book_id)
                                           <option value="{{ $book_value->book_id }}" selected>
                                             {{ $book_value->book_name }}</option>
                                     @else
                                           <option value="{{ $book_value->book_id }}">
                                              {{ $book_value->book_name }}</option>
                                     @endif
                                   @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Chapter<span style="color:red">*</span></label>
                              <select class="js-data-example-ajax form-select" id="chapter" name="chapter_id" required>
                                 @foreach($chapters as $chapter)
                                     @if($verse->chapter_id == $chapter->chapter_id)
                                           <option value="{{ $chapter->chapter_id }}" selected>
                                             {{ $chapter->chapter_name }}</option>
                                     @else
                                           <option value="{{ $chapter->chapter_id }}">
                                              {{ $chapter->chapter_name }}</option>
                                     @endif
                                   @endforeach 

                              </select>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Verse<span style="color:red">*</span></label>
                              <select class="js-data-example-ajax form-select" id="verse" name="verse_id" required>
                                  @foreach($verses as $verse_data)
                                     @if($verse->verse_id == $verse_data->statement_id)
                                           <option value="{{ $verse_data->statement_id }}" selected>
                                             {{ $verse_data->statement_no }}</option>
                                     @else
                                           <option value="{{ $verse_data->statement_id }}">
                                              {{ $verse_data->statement_no }}</option>
                                     @endif
                                   @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-4 col-12">
                           <div class="form-group">
                              <label for="">Date</label>
                              <div class="input-group">
                                 <input class="datepicker-here form-control digits" type="text" data-language="en" 
                                 name="date" value="{{$verse->date}}">
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4 col-12"> 
                            <div class="form-group">
                            <label for="">Theme</label>
                              <select class="js-data-example-ajax form-select" id="theme" name="theme_id">
                                 @foreach($themes as $theme)
                                     @if($verse->theme_id == $theme->id)
                                           <option value="{{ $theme->id }}" selected>
                                             {{ $theme->name }}</option>
                                     @else
                                           <option value="{{ $theme->id }}">
                                              {{ $theme->name }}</option>
                                     @endif
                                   @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-12">
                            <div class="encounter-btn d-flex justify-content-center mt-4">
                                <button class="btn btn-pill btn-danger btn-lg" type="button" onclick="window.location='{{ route('admin.daily.bible.verse') }}'">Cancel</button>
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
<div class="modal fade" id="addThemeModal" tabindex="-1" role="dialog" aria-labelledby="addThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="top:150px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addThemeModalLabel">Add New Theme</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="addThemeForm">
                    <div class="form-group">
                        <label for="new_theme_name">Theme</label>
                        <input type="text" class="form-control" id="new_theme_name" name="name" required>
                    </div><br>
                    <button type="submit" class="btn btn-primary">Save Theme</button>
                </form>
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
                     bible_id  : '<?= $default_bible_id?>',

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

     $('#verse').select2({
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

     $('#theme').select2({
         placeholder: "Select Theme",
         ajax: {

             url: "<?= url('get_bible_verse_theme_list') ?>",
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
                 let results = data.results;

                results.push({
                    id: 'add_new_theme',
                    text: 'Add New Theme',
                    disabled: false 
                });

                return {
                    results: results,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
             },
             cache: true
         }
     });

     $('#theme').on('select2:select', function(e) {
        var selectedValue = e.params.data.id;
        if (selectedValue === 'add_new_theme') {
            $('#addThemeModal').modal('show');
            $('#theme').val(null).trigger('change');
        }
    });

    $('#addThemeForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#new_theme_name').val();
        $.ajax({
            url: "<?= url('storebibleversetheme') ?>",
            method: 'POST',
            data: {
                _token: "<?= csrf_token() ?>",
                name  : name
            },
            success: function(response) {
                console.log(response)
                if (response.messsage) {
                    var newOption = new Option(response.theme.name, response.theme.id, true, true);
                    $('#theme').append(newOption).trigger('change');
                    $('#addThemeModal').modal('hide');
                    $('#new_theme_name').val('');
                } else {
                    alert(response.message);
                }
            }
        });
    });
</script>
@endsection