@extends('admin.layout.layout')
  @section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Invoices</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Invoices</li>
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
                <h3 class="card-title">Invoices</h3>
                @if($invoiceModule['edit_access'] == 1 || $invoiceModule['full_access'] == 1)
                    <a style="max-width:150px; float:right" href="{{ url('admin/add-edit-invoice') }}" class="btn btn-block btn-primary">
                        Add Invoice
                    </a>
                @endif
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="invoices" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Customer name</th>
                    <th>Products purchased</th>
                    <th>Total amount</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($invoices as $invoice)
                      <tr>
                        <td>{{ $invoice['id'] }}</td>
                        <td>{{ $invoice['customer_name'] }}</td>
                        <td>{{ $invoice['products_purchased'] }}</td>
                        <td>{{ $invoice['total_amount'] }}</td>
                        <td>{{ date('Y-m-d', strtotime($invoice['created_at'])) }}</td>
                        <td>{{ date('Y-m-d', strtotime($invoice['updated_at'])) }}</td>
                        <td>
                            @if($invoiceModule['full_access'] == 1 || $invoiceModule['edit_access'] == 1 || $invoiceModule['view_access'] == 1)
                            <a href="{{ url('admin/view-invoice/'.$invoice['id']) }}" style="color:#007bff">
                                <i class="fas fa-eye"></i>
                            </a>
                            &nbsp;&nbsp;
                            @endif
                            @if($invoiceModule['full_access'] == 1 || $invoiceModule['edit_access'] == 1)
                            <a href="{{ url('admin/add-edit-invoice/'.$invoice['id']) }}" style="color:#007bff">
                                <i class="fas fa-edit"></i>
                            </a>
                            &nbsp;&nbsp;
                            @endif
                            @if($invoiceModule['full_access'] == 1 || $invoiceModule['edit_access'] == 1)
                            <a href="{{ url('admin/delete-invoice/'.$invoice['id']) }}" class="confirmDelete" name="invoice" title="Delete Invoice" style="color:#007bff">
                                <i class="fas fa-trash"></i>
                            </a>
                            &nbsp;&nbsp;
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