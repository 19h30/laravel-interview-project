@extends('admin.layout.layout')
  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Staffs</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Staffs</li>
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
                <h3 class="card-title">Staffs</h3>
                @if($staffModule['full_access'] == 1)
                    <a style="max-width:150px; float:right" href="{{ url('admin/add-edit-staff') }}" class="btn btn-block btn-primary">
                        Add Staff
                    </a>
                @endif
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="staffs" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($staffs as $staff)
                      <tr>
                        <td>{{ $staff['id'] }}</td>
                        <td>{{ $staff['name'] }}</td>
                        <td>{{ $staff['mobile'] }}</td>
                        <td>{{ $staff['email'] }}</td>
                        <td>{{ $staff['status'] }}</td>
                        <td>{{ date('Y-m-d', strtotime($staff['created_at'])) }}</td>
                        <td>{{ date('Y-m-d', strtotime($staff['updated_at'])) }}</td>
                        <td>
                            @if($staffModule['full_access'] == 1)
                                @if($staff['status'] == 1)
                                <a class="updateStaffStatus" id="staff-{{ $staff['id'] }}" staff_id="{{ $staff['id'] }}" href="javascript:void(0)">
                                    <i class="fas fa-toggle-on" status="Active"></i>
                                </a>
                                @else
                                <a class="updateStaffStatus" id="staff-{{ $staff['id'] }}" staff_id="{{ $staff['id'] }}" style="color:grey" href="javascript:void(0)">
                                    <i class="fas fa-toggle-off" status="Inactive"></i>
                                </a>
                                @endif
                                &nbsp;&nbsp;
                            @endif
                            @if($staffModule['full_access'] == 1)
                            <a href="{{ url('admin/add-edit-staff/'.$staff['id']) }}" style="color:#007bff">
                                <i class="fas fa-edit"></i>
                            </a>
                            &nbsp;&nbsp;
                            @endif
                            @if($staffModule['full_access'] == 1)
                            <a href="{{ url('admin/delete-staff/'.$staff['id']) }}" class="confirmDelete" name="staff" title="Delete Staff" style="color:#007bff">
                                <i class="fas fa-trash"></i>
                            </a>
                            &nbsp;&nbsp;
                            @endif
                            @if($staffModule['full_access'] == 1)
                            <a href="{{ url('admin/update-role/'.$staff['id']) }}" style="color:#007bff">
                                <i class="fas fa-unlock"></i>
                            </a>
                            @endif
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