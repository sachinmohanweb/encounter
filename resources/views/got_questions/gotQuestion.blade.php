@extends('layouts.simple.master')
@section('title', 'Default')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
<style type="text/css">
   @media (min-width: 700px) {
    .modal-dialog {
        max-width: 600px;
        margin: 1.75rem auto;
    }
    @media (min-width: 900px) {
    .modal-dialog {
        max-width: 800px;
        margin: 1.75rem auto;
    }
}
</style>
@endsection
@section('breadcrumb-title')
<h3>Got Questions</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="new-question d-flex justify-content-end mb-4">
            <a href="{{ route('admin.add.GotQuestion') }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">New Question</button>
            </a>
         </div>
         <div class="card">
            @if (Session::has('success'))
              <div class="alert alert-success">
                 <ul>
                    <li>{!! Session::get('success') !!}</li>
                 </ul>
              </div>
            @endif
            @if (Session::has('error'))
              <div class="alert alert-danger">
                 <ul>
                    <li>{!! Session::get('error') !!}</li>
                 </ul>
              </div>
            @endif
            @if($errors->any())
               <h6 style="color:red;padding: 20px 0px 0px 30px;">{{$errors->first()}}</h6>
            @endif
            <div class="card-body">
               <div class="table-responsive">
                  <table class="display" id="got_question_data" style="width:100%">
                     <thead>
                        <tr>
                           <th>Category</th>
                           <th>Sub Category</th>
                           <th>Questions</th>
                           <th>Answer</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Answer Modal -->
<div class="modal fade" id="answerModal" tabindex="-1" role="dialog" aria-labelledby="answerModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="answerModalLabel">Read More</h5>
            
            </button>
         </div>
         <div class="modal-body" style="height: 500px;overflow-y: scroll;">
            <!-- Full answer will be shown here -->
         </div>
         <div class="modal-footer">
            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
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
   
      $('#got_question_data').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
            url: "{{ route('admin.gotquestion.datatable') }}",
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
            } 
         },
          columns: [
              { data: 'category', name: 'category' , orderable: false},
              { data: 'sub_category', name: 'sub_category' , orderable: false },
              { data: 'question', name: 'question'},     
              { data: 'answer', name: 'answer' },
              { data: 'action', name: 'action', orderable: false},
          ],
      });

      $(document).on('click', '.view-more', function() {
         var fullAnswer = $(this).data('answer');
         $('#answerModal .modal-body').html(fullAnswer);
         $('#answerModal').modal('show');
      });
   });
         
   function remove(id){

      msg = 'Are you sure? Delete this user?';

      if (confirm(msg) == true) {
          var id = id;
          $.ajax({
              type:"POST",
              url: "{{ route('admin.delete.GotQuestion') }}",
              data: { _token : "<?= csrf_token() ?>",
                  id     : id
              },
              dataType: 'json',
              success: function(res){
                  var oTable = $('#got_question_data').dataTable();
                  if (res.status=='success'){
                        $.notify({
                           title:'Notification',
                           message:'Question & answer Successfully deleted'
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
                     table = $('#got_question_data').DataTable();
                     table.ajax.reload(null, false);
                  }else{
                        $.notify({
                           title:'Notification',
                           message:'Question & answer Not deleted'
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
         table = $('#got_question_data').DataTable();
         table.ajax.reload(null, false);
      }
   }
 
</script>

@endsection