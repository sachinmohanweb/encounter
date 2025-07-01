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
<h3>Daily Bible Verse</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="new-question d-flex justify-content-end mb-4">
            <a href="{{ route('admin.add.daily.bible.verse') }}"><button class="btn btn-pill btn-info-gradien pt-2 pb-2" type="button" data-bs-original-title="" title="">Add Daily Bible Verse & Themes</button>
            </a>
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
                           <th>Bible</th>
                           <th>Testament</th>
                           <th>Book</th>
                           <th>Chapter</th>
                           <th>Verses</th>
                           <th style="width:90px">Date</th>
                           <th>Theme</th>
                           <th>Action</th>
                           <th>Status</th>
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
   
      $('#bible_verse_datatable').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
            url: "{{ route('admin.bible_verse.datatable') }}",
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
            } 
         },
          columns: [
              { data: 'bible', name: 'bible'},     
              { data: 'testament', name: 'testament' , orderable: true},
              { data: 'book', name: 'book' , orderable: true},
              { data: 'chapter', name: 'chapter' },
              { data: 'verse', name: 'verse'},
              { data: 'date', name: 'date'},
              { data: 'theme', name: 'theme'},
              { data: 'action', name: 'action', orderable: false},
              { data: 'status', name: 'status', orderable: false},
          ],
      });
   });
         
   function remove(id){

      msg = 'Are you sure? Delete this bible verse?';

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

   function suspend(id,status){

      if(status==1){
         msg = 'Are you sure? Deactivate this bibe verse?';
      }else{
         msg = 'Are you sure? Activate this bibe verse?';
      }
      if (confirm(msg) == true) {
          var id = id;
          $.ajax({
              type:"POST",
              url: "{{ route('admin.bible.verse.status.change') }}",
              data: { _token : "<?= csrf_token() ?>",
                      id     : id
              },
              dataType: 'json',
              success: function(res){
                  var oTable = $('#bible_verse_datatable').dataTable();
                  if (res.success== true){
                     if(res.status==1){
                        $.notify({
                           title:'Bibe verse',
                           message:'Bibe verse Successfully Activated'
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
                           title:'Bibe verse',
                           message:'Bibe verse Successfully Suspended'
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
         table = $('#bible_verse_datatable').DataTable();
         table.ajax.reload(null, false);
      }
   }
 
</script>


@endsection