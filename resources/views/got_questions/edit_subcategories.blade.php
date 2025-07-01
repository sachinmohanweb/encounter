@extends('layouts.simple.master')
@section('title', 'Default')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
<style type="text/css">
    .pd_sub{
        padding: 0.630rem .75rem !important;
    }
</style>
@endsection
@section('breadcrumb-title')
<h3>Edit Sub Category</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
    <div class="col-sm-12">
         
        <div class="card">
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


            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="needs-validation" novalidate="" action="{{route('admin.update.GQSubCategory')}}" method="Post">
                            <input type="hidden" name="id" value="{{$sub_cat->id}}">
                            @csrf
                            <div class="notification-sec d-flex flex-wrap">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label" for="validationCustom01">Category</label>
                                        <select class="js-data-example-ajax form-select category" name="cat_id" required>
                                             @foreach($cats as $cat)
                                                 @if($cat->id == $sub_cat->cat_id)
                                                       <option value="{{ $cat->id }}" selected>
                                                         {{ $cat->name }}</option>
                                                 @else
                                                       <option value="{{ $cat->id }}"
                                                         {{ $cat->name }}</option>
                                                 @endif
                                               @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label" for="validationCustom01">Sub Category Name
                                        </label>
                                        <input class="form-control pd_sub" id="name" name="name" type="text" required value="{{$sub_cat->name}}">
                                        <div class="valid-feedback">Looks good!</div>

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
   
     
      $('.category').select2({
         placeholder: "Select category",
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
   });

  
</script>

@endsection