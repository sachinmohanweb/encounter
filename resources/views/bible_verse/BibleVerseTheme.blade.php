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
<h3>Bible Verse Themes</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="new-question d-flex justify-content-end mb-4">
            <button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" 
            data-bs-toggle="modal" data-bs-target="#addThemeModal">Add Bible Verse Theme
            </button>   
         </div>
         <div class="card">
            <div class="card-body">
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
               <div class="table-responsive">
                  <table class="display" id="bible_verse_datatable" style="width:100%">
                     <thead>
                        <tr>
                           <th>Sl.</th>
                           <th>Theme</th>
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


<div class="modal fade" id="addThemeModal" tabindex="-1" role="dialog" aria-labelledby="addThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="top:150px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addThemeModalLabel">Add New Theme</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form id="addThemeForm">
                    <div class="form-group">
                        <label for="new_theme_name">Theme</label>
                        <input type="text" class="form-control" id="new_theme_name" name="name" required>
                    </div><br>
                    <button type="submit" class="btn btn-primary">Save Theme</button>
                </form>
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
   
      $('#bible_verse_datatable').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
            url: "{{ route('admin.bible_verse.theme.datatable') }}",
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
            } 
         },
          columns: [
              { data: 'id', name: 'id'},     
              { data: 'name', name: 'theme'},
              { data: 'action', name: 'action', orderable: false},
          ],
      });
   });

   $('#addThemeForm').on('submit', function(e) {
        e.preventDefault();
        var name = $('#new_theme_name').val();
        $.ajax({
            url: "<?= url('storebibleversetheme') ?>",
            method: 'POST',
            data: {
                _token: "<?= csrf_token() ?>",
                name  : name
            },
            success: function(response) {
                if (response.success) {
                    var newOption = new Option(response.theme_name, response.theme_id, true, true);
                    $('#theme').append(newOption).trigger('change');
                    $('#addThemeModal').modal('hide');
                    $('#new_theme_name').val('');
                } else {
                    alert(response.message);
                }
            }
        });
    });
         
   function remove(id){

      msg = 'Are you sure? Delete this bible verse theme?';

      if (confirm(msg) == true) {
          var id = id;
          $.ajax({
              type:"POST",
              url: "{{ route('admin.delete.DailyBibleVerse') }}",
              data: { _token : "<?= csrf_token() ?>",
                  id     : id
              },
              dataType: 'json',
              success: function(res){
                  var oTable = $('#bible_verse_datatable').dataTable();
                  if (res.status=='success'){
                        $.notify({
                           title:'Bible verse',
                           message:'Bible verse Successfully deleted'
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
                     table = $('#bible_verse_datatable').DataTable();
                     table.ajax.reload(null, false);
                  }else{
                        $.notify({
                           title:'Bible verse',
                           message:'Bible verse Not deleted'
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
         table = $('#bible_verse_datatable').DataTable();
         table.ajax.reload(null, false);
      }
   }
</script>


@endsection