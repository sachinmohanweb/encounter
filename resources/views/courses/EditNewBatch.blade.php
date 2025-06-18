@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Edit New Batch</h3>
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
                  <form class="theme-form" action="{{route('admin.update.batch')}}" method="Post" enctype="multipart/form-data">
                     @csrf
                     <input type="hidden" name="id" value="{{$batch->id}}">
                     <input type="hidden" name="course_id" value="{{$batch->course_id}}">
                     <div class="verse d-flex align-items-center flex-wrap">
                        <div class="col-md-4 col-12">
                           <div class="form-group">
                              <label for=""> Batch Name*</label>
                              <input type="text" placeholder="Batch Name" class="form-control"
                              name="batch_name" value="{{$batch->batch_name}}" required>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="form-group">
                              <label for=""> Start Date*</label>
                              <div class="input-group">
                                 <!-- datepicker-here -->
                              <input class="form-control digits" type="date" data-language="en" id="start_date" 
                              name="start_date" value="{{$batch->start_date}}" required>
                           </div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="form-group">
                              <label for=""> End Date*</label>
                              <div class="input-group">
                              <input class="form-control digits" type="date" data-language="en" id="end_date" 
                              name="end_date" value="{{$batch->end_date}}" readonly  required>
                           </div>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="form-group">
                              <label for=""> Last Date for Enrollment*</label>
                              <div class="input-group">
                              <input class="form-control digits" type="date" data-language="en"  name="last_date" 
                              value="{{$batch->last_date}}"  required>
                           </div>
                           </div>
                        </div>
                        <div class="col-lg-4 col-12">
                           <div class="form-group ps-5">
                              <label for=""> Date Visibility*</label>
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="date_visibility" 
                                 id="flexRadioDefault1" value="1"  <?php if ($batch->date_visibility == 1) echo ' checked'; ?>>
                                 <label class="form-check-label" for="flexRadioDefault1">
                                 Show
                                 </label>
                              </div>
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="date_visibility" 
                                 id="flexRadioDefault2" value="2" <?php if ($batch->date_visibility == 2) echo ' checked'; ?>>
                                 <label class="form-check-label" for="flexRadioDefault2">
                                 Hide
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4 col-12">
                           <div class="form-group ps-5">
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="status" 
                                 id="flexRadioDefault1" value="1" <?php if ($batch->status == 'Active') echo ' checked'; ?>>
                                 <label class="form-check-label" for="flexRadioDefault1">
                                 Enable
                                 </label>
                              </div>
                              <div class="form-check">
                                 <input class="form-check-input" type="radio" name="status" id="flexRadioDefault2" value="2" <?php if ($batch->status == 'Suspended') echo ' checked'; ?>>
                                 <label class="form-check-label" for="flexRadioDefault2">
                                 disable
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="col-12">
                           <div class="encounter-btn d-flex justify-content-center mt-4">
                              <button class="btn btn-pill btn-danger btn-lg" type="button" data-bs-original-title="" title="" onclick="window.location='{{ route('admin.course.details',[$batch->course_id]) }}'">Cancel</button>
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

<script>
   $(document).ready(function() {
      $('#start_date').on('change', function() {
        var startDate = $(this).val();
        if (startDate) {
            var start = new Date(startDate);
            var daysToAdd = parseInt("<?php echo ($course->no_of_days-1);?>");
            var endDate = new Date(start);
            endDate.setDate(start.getDate() + daysToAdd);

            var endDateFormatted = endDate.toISOString().split('T')[0];
            $('#end_date').val(endDateFormatted);
        } else {
            $('#end_date').val('');
        }
    });
});
</script>
@endsection