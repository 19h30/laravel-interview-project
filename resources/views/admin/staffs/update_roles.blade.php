@extends('admin.layout.layout')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Staff</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ url('admin/staffs') }}">Staffs</a></li>\
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
                @if (Session::has('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Success:</strong> {{ Session::get('success_message') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif
                <form name="staffForm" id="staffForm" action="{{ url('admin/update-role/'.$id) }}" method="post">@csrf
                  <input type="hidden" name="staff_id" value="{{ $id }}">
                  @if(!empty($staffRoles))
                    @foreach($staffRoles as $role)
                      @if($role['module'] == 'staff')
                        @if($role['view_access'] == 1)
                          @php $viewStaff = 'checked' @endphp
                        @else
                          @php $viewStaff = '' @endphp
                        @endif

                        @if($role['edit_access'] == 1)
                          @php $editStaff = 'checked' @endphp
                        @else
                          @php $editStaff = '' @endphp
                        @endif

                        @if($role['full_access'] == 1)
                          @php $fullStaff = 'checked' @endphp
                        @else
                          @php $fullStaff = '' @endphp
                        @endif
                      @endif
                      
                      @if($role['module'] == 'category')
                        @if($role['view_access'] == 1)
                          @php $viewCategory = 'checked' @endphp
                        @else
                          @php $viewCategory = '' @endphp
                        @endif

                        @if($role['edit_access'] == 1)
                          @php $editCategory = 'checked' @endphp
                        @else
                          @php $editCategory = '' @endphp
                        @endif

                        @if($role['full_access'] == 1)
                          @php $fullCategory = 'checked' @endphp
                        @else
                          @php $fullCategory = '' @endphp
                        @endif
                      @endif

                      @if($role['module'] == 'product')
                        @if($role['view_access'] == 1)
                          @php $viewProduct = 'checked' @endphp
                        @else
                          @php $viewProduct = '' @endphp
                        @endif

                        @if($role['edit_access'] == 1)
                          @php $editProduct = 'checked' @endphp
                        @else
                          @php $editProduct = '' @endphp
                        @endif

                        @if($role['full_access'] == 1)
                          @php $fullProduct = 'checked' @endphp
                        @else
                          @php $fullProduct = '' @endphp
                        @endif
                      @endif

                      @if($role['module'] == 'invoice')
                        @if($role['view_access'] == 1)
                          @php $viewInvoice = 'checked' @endphp
                        @else
                          @php $viewInvoice = '' @endphp
                        @endif

                        @if($role['edit_access'] == 1)
                          @php $editInvoice = 'checked' @endphp
                        @else
                          @php $editInvoice = '' @endphp
                        @endif

                        @if($role['full_access'] == 1)
                          @php $fullInvoice = 'checked' @endphp
                        @else
                          @php $fullInvoice = '' @endphp
                        @endif
                      @endif
                    @endforeach
                  @endif
                  <div class="card-body">
                    <div class="form-group col-md-6">
                      <label for="staff">Staff management&nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="checkbox" id="staff" name="staff[view]" value="1" @if(isset($viewStaff)) {{ $viewStaff }} @endif>&nbsp;View
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="checkbox" id="staff" name="staff[edit]" value="1" @if(isset($editStaff)) {{ $editStaff }} @endif>&nbsp;View/Edit
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="checkbox" id="staff" name="staff[full]" value="1" @if(isset($fullStaff)) {{ $fullStaff }} @endif>&nbsp;Full
                      &nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="form-group col-md-6">
                      <label for="category">Category management&nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="checkbox" id="category" name="category[view]" value="1" @if(isset($viewCategory)) {{ $viewCategory }} @endif>&nbsp;View
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="checkbox" id="category" name="category[edit]" value="1" @if(isset($editCategory)) {{ $editCategory }} @endif>&nbsp;View/Edit
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="checkbox" id="category" name="category[full]" value="1" @if(isset($fullCategory)) {{ $fullCategory }} @endif>&nbsp;Full
                      &nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="form-group col-md-6">
                      <label for="product">Product management&nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="checkbox" id="product" name="product[view]" value="1" @if(isset($viewProduct)) {{ $viewProduct }} @endif>&nbsp;View
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="checkbox" id="product" name="product[edit]" value="1" @if(isset($editProduct)) {{ $editProduct }} @endif>&nbsp;View/Edit
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="checkbox" id="product" name="product[full]" value="1" @if(isset($fullProduct)) {{ $fullProduct }} @endif>&nbsp;Full
                      &nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="form-group col-md-6">
                      <label for="invoice">Invoice management&nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="checkbox" id="invoice" name="invoice[view]" value="1" @if(isset($viewInvoice)) {{ $viewInvoice }} @endif>&nbsp;View
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="checkbox" id="invoice" name="invoice[edit]" value="1" @if(isset($editInvoice)) {{ $editInvoice }} @endif>&nbsp;View/Edit
                      &nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="checkbox" id="invoice" name="invoice[full]" value="1" @if(isset($fullInvoice)) {{ $fullInvoice }} @endif>&nbsp;Full
                      &nbsp;&nbsp;&nbsp;&nbsp;
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