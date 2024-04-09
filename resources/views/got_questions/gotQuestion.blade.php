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
<h3>Got Questions</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="new-question d-flex justify-content-end mb-4">
            <a href="{{ route('admin.gotquestionanswer') }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">New Question</button>
            </a>
         </div>
         <div class="card">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="display" id="data-source-1" style="width:100%">
                     <thead>
                        <tr>
                           <th>Questions</th>
                           <th>Category</th>
                           <th>Sub Category</th>
                           <th>Answer</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>The Bible is a collection of religious  dolor?</td>
                           <td>The Bible is a collection of religious</td>
                           <td>The Bible is a collection of religious texts?</td>
                           <td>
                              Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus provident repellat
                           </td>
                           <td>
                              <ul class="action">
                                 <li class="edit"> <a href="#"><i class="icon-pencil-alt"></i></a>
                                 </li>
                                 <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                              </ul>
                           </td>
                        </tr>
                        <tr>
                           <td>The Bible is a collection of religious  dolor?</td>
                           <td>The Bible is a collection of religious</td>
                           <td>The Bible is a collection of religious texts?</td>
                           <td>
                              Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus provident repellat
                           </td>
                           <td>
                              <ul class="action">
                                 <li class="edit"> <a href="#"><i class="icon-pencil-alt"></i></a>
                                 </li>
                                 <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                              </ul>
                           </td>
                        </tr>
                        <tr>
                           <td>The Bible is a collection of religious  dolor?</td>
                           <td>The Bible is a collection of religious</td>
                           <td>The Bible is a collection of religious texts?</td>
                           <td>
                              Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus provident repellat
                           </td>
                           <td>
                              <ul class="action">
                                 <li class="edit"> <a href="#"><i class="icon-pencil-alt"></i></a>
                                 </li>
                                 <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                              </ul>
                           </td>
                        </tr>
                        <tr>
                           <td>The Bible is a collection of religious  dolor?</td>
                           <td>The Bible is a collection of religious</td>
                           <td>The Bible is a collection of religious texts?</td>
                           <td>
                              Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus provident repellat
                           </td>
                           <td>
                              <ul class="action">
                                 <li class="edit"> <a href="#"><i class="icon-pencil-alt"></i></a>
                                 </li>
                                 <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                              </ul>
                           </td>
                        </tr>
                        <tr>
                           <td>The Bible is a collection of religious  dolor?</td>
                           <td>The Bible is a collection of religious</td>
                           <td>The Bible is a collection of religious texts?</td>
                           <td>
                              Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus provident repellat
                           </td>
                           <td>
                              <ul class="action">
                                 <li class="edit"> <a href="#"><i class="icon-pencil-alt"></i></a>
                                 </li>
                                 <li class="delete"><a href="#"><i class="icon-trash"></i></a></li>
                              </ul>
                           </td>
                        </tr>
                        <tr>
                           <td>The Bible is a collection of religious  dolor?</td>
                           <td>The Bible is a collection of religious</td>
                           <td>The Bible is a collection of religious texts?</td>
                           <td>
                              Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus provident repellat
                           </td>
                           <td>
                              <ul class="action">
                                 <li class="edit"> <a href="#"><i class="icon-pencil-alt"></i></a>
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