@extends('admin.layout.layout')
  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Categories</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            @if (Session::has('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success:</strong> {{ Session::get('success_message') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @endif
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Categories</h3>
                @if($categoryModule['edit_access'] == 1 || $categoryModule['full_access'] == 1)
                    <a style="max-width:150px; float:right" href="{{ url('admin/add-category') }}" class="btn btn-block btn-primary">
                        Add Category
                    </a>
                @endif
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="categories" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($categories as $category)
                      <tr>
                        <td>{{ $category['id'] }}</td>
                        <td>{{ $category['category_name'] }}</td>
                        <td>{{ date('Y-m-d', strtotime($category['created_at'])) }}</td>
                        <td>{{ date('Y-m-d', strtotime($category['updated_at'])) }}</td>
                        <td>
                            @if($categoryModule['full_access'] == 1 || $categoryModule['edit_access'] == 1)
                                @if($category['status'] == 1)
                                <a class="updateCategoryStatus" id="category-{{ $category['id'] }}" category_id="{{ $category['id'] }}" href="javascript:void(0)">
                                    <i class="fas fa-toggle-on" status="Active"></i>
                                </a>
                                @else
                                <a class="updateCategoryStatus" id="category-{{ $category['id'] }}" category_id="{{ $category['id'] }}" style="color:grey" href="javascript:void(0)">
                                    <i class="fas fa-toggle-off" status="Inactive"></i>
                                </a>
                                @endif
                                &nbsp;&nbsp;
                            @endif
                            
                            <!-- @if($categoryModule['full_access'] == 1)
                            <a href="{{ url('admin/delete-category/'.$category['id']) }}" class="confirmDelete" name="category" title="Delete Category" style="color:#007bff">
                                <i class="fas fa-trash"></i>
                            </a>
                            &nbsp;&nbsp;
                            @endif -->
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->
  @endsection