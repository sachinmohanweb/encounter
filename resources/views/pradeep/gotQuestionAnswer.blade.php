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
<h3>Daily Bible Verse</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         
         <div class="card">
            <div class="card-body">
                <form action="" class="got-question-answer">
                    <div class="form-froup mb-3">
                       <label for="">Question</label>
                       <input type="text" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                       <select class="form-select" aria-label="Default select example">
  <option selected>Select Category</option>
  <option value="1">Category One</option>
  <option value="2">Category Two</option>
  <option value="3">Category Three</option>
</select>
                    </div>
                    <div class="form-group mb-3">
                       <select class="form-select" aria-label="Default select example">
  <option selected>Select Sub Category</option>
  <option value="1">Sub Category One</option>
  <option value="2">Sub Category Two</option>
  <option value="3">Sub Category Three</option>
</select>
                    </div>
                     <div class="form-froup mb-3">
                       <label for="">Answer</label>
                       <textarea name="" id="" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group d-flex justify-content-end">
                       <button class="btn btn-primary active" type="button" data-bs-original-title="" title="">Submit</button>
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
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>   <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection