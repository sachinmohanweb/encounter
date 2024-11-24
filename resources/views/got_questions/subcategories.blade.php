@extends('layouts.simple.master')
@section('title', 'Default')
@section('css')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cascade.css') }}">
<style type="text/css">
    .pd_sub{
        padding: 0.630rem .75rem !important;
    }
</style>
@endsection
@section('breadcrumb-title')
<h3>Sub Categories</h3>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row widget-grid">
      <div class="col-sm-12">
         <div class="new-question d-flex justify-content-end mb-4">
            <a class="purchase-btn btn btn-primary btn-hover-effect f-w-500" data-bs-toggle="modal" data-bs-target="#AddGQSubCategoryModal" >Add New Sub Category</a>
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
                  <table class="display" id="gq_sub_categories_data" style="width:100%">
                     <thead>
                        <tr>
                           <th>Id</th>
                           <th>Category</th>
                           <th>SubCategory</th>
                           <th>Status</th>
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

<div class="modal fade" id="AddGQSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="AddGqSubCategoryModalArea" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 650px !important;"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sub Category Details</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" novalidate="" action="{{route('admin.store.GQSubCategory')}}" method="Post">
                    <div class="modal-body">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                         <div class="form-group">
                          <label class="form-label" for="validationCustom01">Category</label>
                           <select class="js-data-example-ajax form-select category" name="cat_id" required></select>

                           </div>
                          <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label" for="validationCustom01">Sub Category Name</label>
                          <input class="form-control pd_sub" id="name" name="name" type="text" required>
                          <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" onclick="window.location='{{ route('admin.gq.subcategories') }}'">Close</button>
                        <button class="btn btn-success" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="EditGQSubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="AddGqSubCategoryModalArea" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 650px !important;"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sub Category Details</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="needs-validation" novalidate="" action="{{route('admin.update.GQSubCategory')}}" method="Post">
                    <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id" id='id_edit'>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                         <div class="form-group">
                          <label class="form-label" for="validationCustom01">Category</label>
                           <select class="js-data-example-ajax form-select category" id ="cat_edit" name="cat_id" required></select>

                           </div>
                          <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label" for="validationCustom01">Sub Category Name</label>
                          <input class="form-control pd_sub" id="edit_name" name="name" type="text" required>
                          <div class="valid-feedback">Looks good!</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" onclick="window.location='{{ route('admin.gq.subcategories') }}'">Close</button>
                        <button class="btn btn-success" type="submit">Save</button>
                    </div>
                </form>
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
   
      $('#gq_sub_categories_data').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
            url: "{{ route('admin.subcategories.datatable') }}",
            type: 'POST',
            headers: {
                  'X-CSRF-TOKEN': "{{ csrf_token() }}"
            } 
         },
          columns: [
              { data: 'id', name: 'id' , orderable: false},
              { data: 'category', name: 'category' , orderable: false },
              { data: 'name', name: 'name' , orderable: false },
              { data: 'status', name: 'status'},     
              { data: 'action', name: 'action', orderable: false},
          ],
      });
      $('.category').select2({
         placeholder: "Select category",
         ajax: {
             url: "<?= url('get_gq_category_list') ?>",
             dataType: 'json',
             method: 'post',
             delay: 250,

              data: function(data) {
                 return {
                     _token    : "<?= csrf_token() ?>",
                     search_tag: data.term,
                 };
             },
             processResults: function(data, params) {
                 params.page = params.page || 1;
                 return {
                     results: data.results,
                     pagination: { more: (params.page * 30) < data.total_count }
                 };
             },
             cache: true
         }
     });
   });

   function remove(id){

      msg = 'Are you sure? Delete this subcategory?';

      if (confirm(msg) == true) {
          var id = id;
          $.ajax({
              type:"POST",
              url: "{{ route('admin.delete.GQSubCategory') }}",
              data: { _token : "<?= csrf_token() ?>",
                  id     : id
              },
              dataType: 'json',
              success: function(res){
                  var oTable = $('#gq_sub_categories_data').dataTable();
                  if (res.status=='success'){
                        $.notify({
                           title:'Sub Category',
                           message:'Sub Category Successfully deleted'
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
                     table = $('#gq_sub_categories_data').DataTable();
                     table.ajax.reload(null, false);

                  }else if (res.status=='Forbidden'){

                    $.notify({
                           title:'Category',
                           message:'Subcategory deletion no allowed'
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


                  }else{
                        $.notify({
                           title:'Sub Category',
                           message:'Sub Category Not deleted'
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
         table = $('#gq_sub_categories_data').DataTable();
         table.ajax.reload(null, false);
      }
   }
</script>

@endsection