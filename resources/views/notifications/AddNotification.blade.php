@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
<style type="text/css">
   .inp_pd{
      padding: .660rem .75rem;
   }
   .text-red {
      color: red;
   }
</style>
@endsection
@section('breadcrumb-title')
<h3>Add Notifications</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="card">
      
      <div class="card-body">
         <div class="row">
            <div class="col-md-12">
               <form action="{{route('admin.store.notification')}}" method="Post" 
                  enctype="multipart/form-data">
                  @csrf
                  <div class="notification-sec d-flex flex-wrap">
                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Title<span class="text-red">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                         </div>
                     </div>
                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Description</label>
                            <input type="text" class="form-control" name="description">
                         </div>
                     </div>
                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Redirection</label>
                            <input type="text" class="form-control inp_pd" name="redirection">
                         </div>
                     </div>

                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Type<span class="text-red">*</span></label>
                            <select class="js-data-example-ajax form-select" id="type" name="type" required></select>
                         </div>
                     </div>

                     <div class="col-lg-12 file_data">
                         <div class="form-group">
                            <label for="">Image/File<span class="text-red">*</span></label>
                            <input type="file" class="form-control" name="file">
                         </div>
                     </div>

                     <div class="col-lg-12 text_data">
                         <div class="form-group">
                            <label for="">Content(text/links)<span class="text-red">*</span></label>
                           <textarea  class="form-control" rows="3" name="data"></textarea>
                         </div>
                     </div>

                     <div class="col-12">
                            <div class="encounter-btn d-flex justify-content-center mt-4">
                                 <a class="btn btn-pill btn-danger btn-lg" onclick="window.location='{{ route('admin.notification.list') }}'">Cancel</a>
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

   $('#type').select2({
      placeholder: "Select type",

      ajax: {
          url: "<?= url('get_notification_type_list') ?>",
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

   $('#type').on('change', function () {
        const selectedType = $(this).val();
        const $fileData = $('.file_data');
        const $textData = $('.text_data');

        if (selectedType == 1 || selectedType == 2) {
            $fileData.show();
            $textData.hide();
            $fileData.find('input[name="file"]').attr('required', true);
            $textData.find('textarea[name="data"]').removeAttr('required');
        } else {
            $fileData.hide();
            $textData.show();
            $textData.find('textarea[name="data"]').attr('required', true);
            $fileData.find('input[name="file"]').removeAttr('required');
        }
   });

   $('.file_data, .text_data').hide();

</script>
@endsection