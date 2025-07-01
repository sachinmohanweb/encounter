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
<h3>General Reference/Commentary </h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="new-question d-flex justify-content-end mb-4">
            <a href="{{ route('admin.add.gereralreference') }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">Add General Reference</button>
            </a>
         </div>
         <div class="card">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="display g-table" id="data-source-1" style="width:100%">
                     <thead>
                        <tr>
                           <th>Course Id</th>
                           <th>Book</th>
                           <th>Chapter</th>
                           <th>Verse from</th>
                           <th>Verse to</th>
                           <th style="min-width: 200px;">Text Description</th>
                           <th>Video Links</th>
                           <th>Audio File</th>
                           <th>Spotify Link</th>
                           <th>Website Link</th>
                           <th>Image</th>
                           <th>Documents</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>1</td>
                           <td>Genesis</td>
                           <td>01</td>
                           <td>05</td>
                           <td>25</td>
                           <td>Lorem ipsum dolor sit amet consectetur adipisicing.</td>
                           <td>http://template.test/assets/css/cascade.css</td>
                           <td>http://template.test/assets/css/cascade.css</td>
                           <td>http://template.test/assets/css/cascade.css</td>
                           <td>http://template.test/assets/css/cascade.css</td>
                           <td><img class="img-fluid for-light"
                  src="{{ asset('assets/images/course1.jpg') }}" alt="" style="width:150px;"></td>
                  <td>File wil Goes Here</td>
                           <td>
                              <ul class="action">
                                 <li class="edit"> <a href="{{ route('admin.edit.gereralreference') }}"><i class="icon-pencil-alt"></i></a>
                                 </li>
                                 <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                              </ul>
                           </td>
                        </tr>
                  
                     </tbody>
                  </table>
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
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>   <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
@endsection