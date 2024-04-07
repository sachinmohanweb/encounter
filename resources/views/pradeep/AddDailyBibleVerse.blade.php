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
         <div class="row">
            <div class="col-md-12">
               <div class="date-picker">
                  <form class="theme-form">
                     <div class="verse d-flex flex-wrap">
                        <div class="col-lg-4 col-md-6 col-12">
                           <div class="form-group">
                              <label for="">Book*</label>
                              <select class="form-select" aria-label="Default select example">
                                 <option selected>Genesis</option>
                                 <option value="1">Exodus</option>
                                 <option value="2">Psalms</option>
                                 <option value="3">Proverbs</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Chapter*</label>
                              <select class="form-select" aria-label="Default select example">
                                 <option selected>1</option>
                                 <option value="1">2</option>
                                 <option value="2">3</option>
                                 <option value="3">4</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Verses*</label>
                              <select class="form-select" aria-label="Default select example">
                                 <option selected>1</option>
                                 <option value="1">2</option>
                                 <option value="2">3</option>
                                 <option value="3">4</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="form-group">
                           <label for="">Date</label>
                           <div class="input-group">
                              <input class="datepicker-here form-control digits" type="text" data-language="en">
                           </div>
                        </div>
                        </div>
                        <div class="col-lg-4 col-12"> 
                            <div class="form-group">
                            <label for="">Season</label>
                            <select class="form-select" aria-label="Default select example">
                                 <option selected>Season Select here</option>
                                 <option value="1">One</option>
                                 <option value="2">Two</option>
                                 <option value="3">Three</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-4 col-12"> 
                            <div class="form-group">
                            <label for="">Trend</label>
                            <select class="form-select" aria-label="Default select example">
                                 <option selected>Season Select here</option>
                                 <option value="1">One</option>
                                 <option value="2">Two</option>
                                 <option value="3">Three</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-4 col-12"> 
                            <div class="form-group">
                            <label for="">Theme</label>
                            <select class="form-select" aria-label="Default select example">
                                 <option selected>Season Select here</option>
                                 <option value="1">One</option>
                                 <option value="2">Two</option>
                                 <option value="3">Three</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-12">
                            <div class="encounter-btn d-flex justify-content-center mt-4">
                                <button class="btn btn-pill btn-danger btn-lg" type="button" data-bs-original-title="" title="">Cancel</button>
                                <button class="btn btn-pill btn-success btn-lg ms-3" type="button" data-bs-original-title="" title="">Submit</button>
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
@endsection