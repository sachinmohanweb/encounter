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
<h3>Notification</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="new-question d-flex justify-content-end mb-4">
            <a href="{{ route('admin.add.notification') }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">Add Notifications</button>
            </a>
         </div>
         <div class="card">
            <div class="card-body">
               @if(Session::has('success'))
                  <div class="alert alert-success" style="width: 500px;">
                     <ul>
                        <li>{!! Session::get('success') !!}</li>
                     </ul>
                  </div>
                @endif
                @if (Session::has('error'))
                  <div class="alert alert-danger" style="width: 500px;">
                     <ul>
                        <li>{!! Session::get('error') !!}</li>
                     </ul>
                  </div>
               @endif
               @if($errors->any())
                  <h6 style="color:red;padding: 20px 0px 0px 30px;style=width: 500px;">{{$errors->first()}}</h6>
               @endif
               <div class="table-responsive">
                  <table class="display" id="notifications_data" style="width:100%">
                     <thead>
                        <tr>
                           <th>Title</th>
                           <th>Type</th>
                           <th>Data</th>
                           <th>Description</th>
                           <th>Redirection</th>
                           <th>Action</th>
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
   
      $('#notifications_data').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
            url: "{{ route('admin.notification.datatable') }}",
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
            } 
         },
          columns: [
              { data: 'title', name: 'title', width:'15%'},     
              { data: 'type', name: 'type' ,width:'25%'},
              { data: 'data', name: 'data' ,width:'25%'},
              { data: 'description', name: 'description' , width:'25%' , orderable: true},
              { data: 'redirection', name: 'redirection' ,width:'25%'},
              { data: 'action', name: 'action', orderable: false},
          ],
      });
   });
         
   function remove(id){

      msg = 'Are you sure? Delete this notification?';

      if (confirm(msg) == true) {
          var id = id;
          $.ajax({
              type:"POST",
              url: "{{ route('admin.delete.notification') }}",
              data: { _token : "<?= csrf_token() ?>",
                  id     : id
              },
              dataType: 'json',
              success: function(res){
                  var oTable = $('#notifications_data').dataTable();
                  if (res.status=='success'){
                        $.notify({
                           title:'Notification',
                           message:'Notification Successfully deleted'
                           },
                           {
                              type:'primary',
                              offset:{
                                x:35,
                                y:230
                              },
                              animate:{
                                enter:'animated fadeIn',
                                exit:'animated fadeOut'
                            }
                        });
                     table = $('#notifications_data').DataTable();
                     table.ajax.reload(null, false);
                  }else{
                        $.notify({
                           title:'Notification',
                           message:'Notification Not deleted'
                           },
                           {
                              type:'danger',
                              offset:{
                                x:35,
                                y:230
                              },
                              animate:{
                                enter:'animated fadeIn',
                                exit:'animated fadeOut'
                            }
                        
                        });
                  }
              },
              error: function(xhr, status, error) {
                  console.error('AJAX request failed:', status, error);
                  alert('Failed to delete notification. Please try again later.');
              }
          });
      }else{
         table = $('#notifications_data').DataTable();
         table.ajax.reload(null, false);
      }
   }
 
</script>

@endsection