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
<h3>Edit Notifications</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="card">
      
      <div class="card-body">
         <div class="row">
            <div class="col-md-12">
               <form action="{{route('admin.update.notification')}}" method="Post" 
                  enctype="multipart/form-data">
                  <input type="hidden" name="id" value="{{$notification->id}}">
                  @csrf
                  <div class="notification-sec d-flex flex-wrap">
                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Title<span class="text-red">*</span></label>
                            <input type="text" class="form-control" name="title" required
                            value="{{$notification->title}}">
                         </div>
                     </div>
                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Description</label>
                            <input type="text" class="form-control" name="description" value="{{$notification->description}}">
                         </div>
                     </div>
                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Redirection</label>
                            <input type="text" class="form-control inp_pd" name="redirection" value="{{$notification->redirection}}">
                         </div>
                     </div>

                     <div class="col-lg-6">
                         <div class="form-group">
                            <label for="">Type<span class="text-red">*</span></label>
                            <select class="js-data-example-ajax form-select" id="type" name="type" required>
                               @foreach($notification_type as $nt)
                                  @if($notification->type == $nt->id)
                                        <option value="{{ $nt->id }}" selected>
                                          {{ $nt->type_name }}</option>
                                  @else
                                        <option value="{{ $nt->id }}">
                                           {{ $nt->type_name }}</option>
                                  @endif
                                @endforeach
                            </select>
                         </div>
                     </div>

                      <div class="col-lg-12 file_data">
                         <div class="form-group">
                           <label for="">Image/File</label>
                           <p>Current File: <a href="{{ asset($notification->data) }}" target="_blank">View File</a></p>
                            <input type="file" class="form-control" name="file">
                         </div>
                     </div>

                     <div class="col-lg-12 text_data">
                         <div class="form-group">
                            <label for="">Content(text/links)</label>
                           <textarea  class="form-control" rows="3" name="data">{{$notification->data}}</textarea>
                         </div>
                     </div>

                     <div class="col-12">
                            <div class="encounter-btn d-flex justify-content-center mt-4">
                                 <div class="encounter-btn d-flex justify-content-center mt-4">
                                 <a class="btn btn-pill btn-danger btn-lg" onclick="window.location='{{ route('admin.notification.list') }}'">Cancel</a>
                                <button class="btn btn-pill btn-success btn-lg ms-3" type="submit" data-bs-original-title="" title="">Submit</button>
                            </div>
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

   function toggleFields(type) {
        const $fileData = $('.file_data');
        const $textData = $('.text_data');

        if (type == 1 || type == 2) {
            $fileData.show();
            $textData.hide();

        } else {
            $fileData.hide();
            $textData.show();

        }
   }

   const initialType = $('#type').val();
   toggleFields(initialType);

   $('#type').on('change', function () {
        toggleFields($(this).val());
   });

</script>
@endsection