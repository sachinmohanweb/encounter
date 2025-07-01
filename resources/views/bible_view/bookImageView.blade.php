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
<h3>Book Thumb Images</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="new-question d-flex justify-content-end mb-4">
            <a class="purchase-btn btn btn-primary btn-hover-effect f-w-500" data-bs-toggle="modal" data-bs-target="#AddBookThumbModal" >Add Book Thumbnails</a>
         </div>
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

               <div class="row" style="margin-bottom: 30px;">

                  <div class="col-lg-4 col-md-6 col-12">
                     <div class="form-group">
                        <label for="">Bible</label>
                        <select class="js-data-example-ajax form-select table_change" id="bible" name="bible_id" required></select>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-3 col-12">
                     <div class="form-group">
                       <label for="">Testament</label>
                        <select class="js-data-example-ajax form-select table_change" id="testament" name="testament_id" required></select>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-3 col-12">
                     <div class="form-group">
                       <label for="">Book</label>
                        <select class="js-data-example-ajax form-select table_change" id="book" name="book_id" required></select>
                     </div>
                  </div>
               </div>   

               <div class="table-responsive">
                  <table class="display" id="chapterimage_view_datatable" style="width:100%">
                     <thead>
                        <tr>
                           <th>Bible</th>
                           <th>Testament</th>
                           <th>Book</th>
                           <th>Image</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="AddBookThumbModal" tabindex="-1" role="dialog" aria-labelledby="AddBookThumbModalArea" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 650px !important;"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Book Thumbnails</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" novalidate="" action="{{route('admin.save.book.image')}}" method="Post" enctype="multipart/form-data">
                    <div class="modal-body">
                          @csrf
                          <div class="row g-3 mb-3">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="form-label" for="validationCustom01">Bible</label>
                                    <select class="js-data-example-ajax form-select table_change" id="bible_image" name="bible_id" required></select>

                                 </div>
                                 <div class="valid-feedback">Looks good!</div>
                              </div>

                              <div class="col-md-4">
                                 <div class="form-group">
                                   <label class="form-label" for="validationCustom01">Testament</label>
                                    <select class="js-data-example-ajax form-select table_change" id="testament_image" name="testament_id" required></select>
                                 </div>
                                 <div class="valid-feedback">Looks good!</div>
                              </div>

                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label class="form-label" for="validationCustom01">Book</label>
                                    <select class="js-data-example-ajax form-select table_change" id="book_image" name="book_id" required></select>
                                 </div>
                                <div class="valid-feedback">Looks good!</div>
                              </div>
                               <div class="col-md-12">
                                 <div class="form-group">
                                    <label class="form-label" for="validationCustom01">Image</label>
                                    <input type="file" name="image" required >
                                 </div>
                                <div class="valid-feedback">Looks good!</div>
                              </div>
                          </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" onclick="window.location='{{ route('admin.gq.subcategories') }}'">Close</button>
                        <button class="btn btn-success" type="submit">Save</button>
                     </div>
               </form>
            </div>
         </div>
      </div>


@endsection
@section('script')
<!-- <script src="{{ asset('assets/js/clock.js') }}"></script> -->
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
<script>
   $(document).ready( function () {

      $('#bible').select2({
         placeholder: "Select Bible",

         ajax: {
             url: "<?= url('get_bible_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     type      : 'all',
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
                     type      : 'all',
                     search_tag: data.term,
                     bible_id  : $('#bible').val(),

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
                     type      : 'all',
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
   
      var dataTable = $('#chapterimage_view_datatable').DataTable({
         processing: true,
         serverSide: true,
         searching : false,

         ajax: {
            url: "{{ route('admin.bookimage_view.datatable') }}",
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: function(d) {
               d.bible_id     = $('#bible').val();
               d.testament_id = $('#testament').val();
               d.book_id      = $('#book').val();
            } 
         },
         lengthMenu: [
            [50, 100, 250, 500],
            [50, 100, 250, 500] // Labels
         ],
          columns: [
              { data: 'bible', name: 'bible'},     
              { data: 'testament', name: 'testament' , orderable: true},
              { data: 'book', name: 'book' , orderable: true},
              { data: 'image', name: 'image',className: "text-center"},
              { data: 'action', name: 'action', orderable: false},
          ],
      });

      $('.table_change').change(function(){ 
          dataTable.draw(); 
      });


      $('#bible_image').select2({
         placeholder: "Select Bible",

         ajax: {
             url: "<?= url('get_bible_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     type      : 'book_image',
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

      $('#testament_image').select2({
         placeholder: "Select Testament",

         ajax: {
             url: "<?= url('get_testament_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     type      : 'book_image',
                     search_tag: data.term,
                     bible_id  : $('#bible_image').val(),

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

     $('#book_image').select2({
         placeholder: "Select Book",
         ajax: {
             url: "<?= url('get_book_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     type      : 'book_image',
                     search_tag: data.term,
                     testament_id:$('#testament_image').val(),
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

   });
 
</script>


@endsection