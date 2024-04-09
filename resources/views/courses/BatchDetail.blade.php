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
<h3>Batch Details</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="display" id="data-source-1" style="width:100%">
                     <thead>
                        <tr>
                           <th>Batch Id</th>
                           <th>User Name</th>
                           <th>Completed Status</th>
                           <th>Start Date</th>
                           <th>Progress</th>
                           <th>Action</th>
                           <!-- <th>Status</th> -->
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>52</td>
                           <td>Tiger Nixon</td>
                           <td>Ongoing</td>
                           <td>21-02-2024</td>
                           <td>Progress Status</td>
                           <td>
                              <ul class="action">
                                 <li class="edit"> <a href="editdailybibleverse"><i class="icon-pencil-alt"></i></a>
                                 </li>
                                 <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                              </ul>
                           </td>
                           <!--  <td>
                              <div class="media-body text-end icon-state">
                                 <label class="switch">
                                 <input type="checkbox" checked=""><span class="switch-state"></span>
                                 </label>
                              </div>
                           </td> -->
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