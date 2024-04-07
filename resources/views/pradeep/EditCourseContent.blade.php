@extends('layouts.simple.master')
@section('title', 'Date Picker')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
@endsection
@section('breadcrumb-title')
<h3>Edit Course Content</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="card">
      <div class="card-body">
         <div class="row">
            <div class="col-md-12">
               <div class="date-picker">
                  <form class="theme-form">
                     <div class="book-from d-flex flex-wrap position-relative">
                        <div class="col-lg-3 col-md-6 col-12">
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
                        <div class="col-lg-3 col-md-3 col-12">
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
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Verses From*</label>
                              <select class="form-select" aria-label="Default select example">
                                 <option selected>1</option>
                                 <option value="1">2</option>
                                 <option value="2">3</option>
                                 <option value="3">4</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Verses To*</label>
                              <select class="form-select" aria-label="Default select example">
                                 <option selected>1</option>
                                 <option value="1">2</option>
                                 <option value="2">3</option>
                                 <option value="3">4</option>
                              </select>
                           </div>
                        </div>
                        <ul class="action">
                                    <li class="add"><i class="fa fa-plus-square-o"></i>
                                    </li>
                                    <li class="delete"><i class="icon-trash"></i></li>
                                 </ul>
                     </div>
                     <div class="verse d-flex flex-wrap">
                        <div class="col-lg-6 col-md-3 col-12">
                           <div class="form-group">
                              <label for="">Text Description*</label>
                              <textarea name="" id="" rows="2" class="form-control"></textarea>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Video Links</label>
                              <div class="add-link d-flex align-items-center">
                                 <input type="text"  class="form-control">
                                 <ul class="action">
                                    <li class="add"><i class="fa fa-plus-square-o"></i>
                                    </li>
                                    <li class="delete"><i class="icon-trash"></i></li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for="">  Audio File</label>
                              <div class="add-link d-flex align-items-center">
                                 <input class="form-control" type="file" id="formFile">
                                 <ul class="action">
                                    <li class="add"><i class="fa fa-plus-square-o"></i>
                                    </li>
                                    <li class="delete"><i class="icon-trash"></i></li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Spotify Link</label>
                              <div class="add-link d-flex align-items-center">
                                 <input type="text"  class="form-control">
                                 <ul class="action">
                                    <li class="add"><i class="fa fa-plus-square-o"></i>
                                    </li>
                                    <li class="delete"><i class="icon-trash"></i></li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Website Links</label>
                              <div class="add-link d-flex align-items-center">
                                 <input type="text"  class="form-control">
                                 <ul class="action">
                                    <li class="add"><i class="fa fa-plus-square-o"></i>
                                    </li>
                                    <li class="delete"><i class="icon-trash"></i></li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Image</label>
                              <input class="form-control" type="file" id="formFile">
                           </div>
                        </div>
                        <div class="col-lg-6 col-12">
                           <div class="form-group">
                              <label for=""> Documents</label>
                              <input class="form-control" type="file" id="formFile">
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