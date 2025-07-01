@extends('layouts.simple.master')
@section('title', 'Default')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
<style type="text/css">
   .table-one table {
    min-width: 100px !important;
   }
</style>
@endsection
@section('breadcrumb-title')
<h3>User Notes</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-body">
               <div class="table-responsive table-one">
                  <table class="display" id="user_notes_data" style="width:100%">
                     <thead>
                        <tr>
                           <th>User</th>
                           <th>Tag</th>
                           <!-- <th>Catgeory</th>
                           <th>Sub Category</th>-->
                           <th>Note</th>
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
   
      $('#user_notes_data').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
            url: "{{ route('admin.user.notes.datatable') }}",
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
            } 
         },
          columns: [
              { data: 'user', name: 'user',width: '15%'},     
              { data: 'tag', name: 'tag' ,width: '15%'},
              // { data: 'category', name: 'category' },
              // { data: 'sub_category', name: 'sub_category' },
              { data: 'note_text', name: 'note' , width: '55%'},
              { data: 'action', name: 'action', orderable: false},
          ],
      });
   });
         
   function suspend(id,status){

      if(status==1){
         msg = 'Are you sure? Deactive this user note?';
      }else{
         msg = 'Are you sure? Activate this user note?';
      }
      if (confirm(msg) == true) {
          var id = id;
          $.ajax({
              type:"POST",
              url: "{{ route('admin.user.notes.status.change') }}",
              data: { _token : "<?= csrf_token() ?>",
                      id     : id
              },
              dataType: 'json',
              success: function(res){
                  var oTable = $('#user_notes_data').dataTable();
                  if (res.success== true){
                     if(res.status==1){
                        $.notify({
                           title:'User',
                           message:'User Note Successfully Activated'
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
                           message:'User Note Successfully Suspended'
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
         table = $('#user_notes_data').DataTable();
         table.ajax.reload(null, false);
      }
   }
 
</script>
@endsection
