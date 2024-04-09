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
<h3>User Profile</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-body">
               <div class="table-responsive table-one">
                  <table class="display" id="data-source-1" style="width:100%">
                     <thead>
                        <tr>
                           <th>User ID</th>
                           <th>User Name</th>
                           <th>Email ID</th>
                           <th>Gender</th>
                           <th>Age</th>
                           <th>Location</th>
                           <th>Device Type</th>
                           <th>IP</th>
                           <th>App Usage</th>
                           <th>Browser</th>
                           <th>Last Accessed Date&Time</th>
                           <th>Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>1</td>
                           <td><a  href="{{ route('admin.user.lms') }}">Tiger Nixon</a></td>
                           <td>a.satou@datatables.net</td>
                           <td>Male</td>
                           <td>45</td>
                           <td>Ettumanoor</td>
                           <td>Macbook Os 2233</td>
                           <td>192.254.21.23</td>
                           <td>Angelica Ramos</td>
                           <td>Safari</td>
                           <td>12-25-2022/22:00PM</td>
                          
                           <td>
                              <div class="media-body text-end icon-state">
                                 <label class="switch">
                                 <input type="checkbox" checked=""><span class="switch-state"></span>
                                 </label>
                              </div>
                           </td>
                        </tr>
                        <tr>
                           <td>2</td>
                           <td>Tiger</td>
                           <td>Nixon</td>
                           <td>a.satou@datatables.net</td>
                           <td>Male</td>
                           <td>45</td>
                           <td>Ettumanoor</td>
                           <td>Macbook Os 2233</td>
                           <td>192.254.21.23</td>
                           <td>Angelica Ramos</td>
                           <td>Safari</td>
                           <td>12-25-2022/22:00PM</td>
                          
                           <td>
                              <div class="media-body text-end icon-state">
                                 <label class="switch">
                                 <input type="checkbox" checked=""><span class="switch-state"></span>
                                 </label>
                              </div>
                           </td>
                        </tr>
                        <tr>
                           <td>3</td>
                           <td>Tiger</td>
                           <td>Nixon</td>
                           <td>a.satou@datatables.net</td>
                           <td>Male</td>
                           <td>45</td>
                           <td>Ettumanoor</td>
                           <td>Macbook Os 2233</td>
                           <td>192.254.21.23</td>
                           <td>Angelica Ramos</td>
                           <td>Safari</td>
                           <td>12-25-2022/22:00PM</td>
                          
                           <td>
                              <div class="media-body text-end icon-state">
                                 <label class="switch">
                                 <input type="checkbox"><span class="switch-state"></span>
                                 </label>
                              </div>
                           </td>
                        </tr>
                        <tr>
                           <td>4</td>
                           <td>Tiger</td>
                           <td>Nixon</td>
                           <td>a.satou@datatables.net</td>
                           <td>Male</td>
                           <td>45</td>
                           <td>Ettumanoor</td>
                           <td>Macbook Os 2233</td>
                           <td>192.254.21.23</td>
                           <td>Angelica Ramos</td>
                           <td>Safari</td>
                           <td>12-25-2022/22:00PM</td>
                          
                           <th>
                              <div class="media-body text-end icon-state">
                                 <label class="switch">
                                 <input type="checkbox" checked=""><span class="switch-state"></span>
                                 </label>
                              </div>
                           </th>
                        </tr>
                        <tr>
                           <td>5</td>
                           <td>Tiger</td>
                           <td>Nixon</td>
                           <td>a.satou@datatables.net</td>
                           <td>Male</td>
                           <td>45</td>
                           <td>Ettumanoor</td>
                           <td>Macbook Os 2233</td>
                           <td>192.254.21.23</td>
                           <td>Angelica Ramos</td>
                           <td>Safari</td>
                           <td>12-25-2022/22:00PM</td>
                          
                           <td>
                              <div class="media-body text-end icon-state">
                                 <label class="switch">
                                 <input type="checkbox"><span class="switch-state"></span>
                                 </label>
                              </div>
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