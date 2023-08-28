@extends('admin.layout.layout')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $title }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/staffs') }}">Staffs</a></li>
              <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                  </div>
                @endif
                @if (Session::has('error_message'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Error:</strong> {{ Session::get('error_message') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif
                <form name="staffForm" id="staffForm" @if(empty($staff['id'])) action="{{ url('admin/add-edit-staff') }}" @else action="{{ url('admin/add-edit-staff/'.$staff['id']) }}" @endif method="post" enctype="multipart/form-data">@csrf
                  <div class="card-body">
                    <div class="form-group col-md-6">
                      <label for="name">Name</label>
                      <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name"
                      @if(!empty($staff['name'])) value="{{ $staff['name'] }}" @endif>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="mobile">Mobile</label>
                      <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile"
                      @if(!empty($staff['mobile'])) value="{{ $staff['mobile'] }}" @endif>
                    </div>
                    @if(empty($staff['id']))
                      <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email"
                        @if(!empty($staff['email'])) value="{{ $staff['email'] }}" @endif>
                      </div>
                      <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password"
                        @if(!empty($staff['password'])) value="{{ $staff['password'] }}" @endif>
                      </div>
                    @endif
                    <div class="form-group col-md-6">
                      <label for="image">Image</label>
                      <input type="file" class="form-control" id="image" name="image">
                      @if(!empty($staff['image']))
                        <a href="{{ url('admin/images/'.$staff['image']) }}" target="_blank">View Photo</a>
                        <input type="hidden" name="current_image" value="{{ $staff['image'] }}">
                      @endif
                    </div>
                  </div>
                  <!-- /.card-body -->

                  <div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection