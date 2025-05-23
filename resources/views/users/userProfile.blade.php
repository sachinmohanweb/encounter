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
                  <table class="display" id="users_data" style="width:100%">
                     <thead>
                        <tr>
                           <th>User ID</th>
                           <th>Image</th>
                           <th>User Name</th>
                           <th>Email ID</th>
                           <th>Gender</th>
                           <th>Age</th>
                           <th>Location</th>
                           <th>Tiemzone</th>
                           <th>Country Code</th>
                           <!-- <th>Device Type</th>
                           <th>IP</th>
                           <th>App Usage</th>
                           <th>Browser</th>
                           <th>Last Accessed Date&Time</th> -->
                           <th>Status</th>
                        </tr>
                     </thead>
             
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
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

<script>
   $(document).ready( function () {
   
      $('#users_data').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
            url: "{{ route('admin.users.datatable') }}",
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
            } 
         },
          columns: [
              { data: 'id', name: 'id'},     
              { data: 'image', name: 'image'},     
              { data: 'user_full_name', name: 'user_full_name' , orderable: true},
              { data: 'email', name: 'email' },
              { data: 'gender', name: 'gender' },
              { data: 'age', name: 'age' },
              { data: 'location', name: 'location' },
              { data: 'timezone',  name: 'timezone' },
              { data: 'country_code', name: 'country_code' },
              // { data: 'device_type', name: 'device_type' },
              // { data: 'ip', name: 'ip' },
              // { data: 'app_usage', name: 'app_usage' },
              // { data: 'browser', name: 'browser' },
              // { data: 'last_accessed', name: 'last_accessed' },
              { data: 'action', name: 'action', orderable: false},
          ],
      });
   });
         
   function suspend(id,status){

      if(status==1){
         msg = 'Are you sure? Suspend this user?';
      }else{
         msg = 'Are you sure? Activate this user?';
      }
      if (confirm(msg) == true) {
          var id = id;
          $.ajax({
              type:"POST",
              url: "{{ route('admin.user.status.change') }}",
              data: { _token : "<?= csrf_token() ?>",
                      id     : id
              },
              dataType: 'json',
              success: function(res){
                  var oTable = $('#users_data').dataTable();
                  if (res.success== true){
                     if(res.status==1){
                        $.notify({
                           title:'User',
                           message:'User Account Successfully Activated'
                           },
                           {
                              type:'primary',
                              offset:{
                                x:35,
                                y:170
                              },
                              animate:{
                                enter:'animated fadeIn',
                                exit:'animated fadeOut'
                            }
                        
                        });
                     }else{
                        $.notify({
                           title:'User',
                           message:'User Account Successfully Suspended'
                           },
                           {
                              type:'primary',
                              offset:{
                                x:35,
                                y:170
                              },
                              animate:{
                                enter:'animated fadeIn',
                                exit:'animated fadeOut'
                            }
                        
                        });
                     }

                  }else{
                     toastr.error(res.msg)
                  }
              },
              error: function(xhr, status, error) {
                  console.error('AJAX request failed:', status, error);
                  alert('Failed to delete family. Please try again later.');
              }
          });
      }else{
         table = $('#users_data').DataTable();
         table.ajax.reload(null, false);
      }
   }
 
</script>
@endsection
