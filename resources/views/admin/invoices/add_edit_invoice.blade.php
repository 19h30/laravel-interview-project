<script>
  var getProducts = @json($getProducts);
</script>
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
              <li class="breadcrumb-item"><a href="{{ url('admin/invoices') }}">Invoices</a></li>
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
                <form name="invoiceForm" id="invoiceForm" @if(empty($invoice['id'])) action="{{ url('admin/add-edit-invoice') }}" @else action="{{ url('admin/add-edit-invoice/'.$invoice['id']) }}" @endif method="post">@csrf
                  <div class="card-body">
                    <div class="form-group col-md-6">
                      <label for="customer_name">Customer Name</label>
                      <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter Name" @if(!empty($invoice['customer_name'])) value="{{ $invoice['customer_name'] }}" @endif>
                    </div>

                    <div class="form-group col-md-6">
                      <label>Products</label>
                      <div class="field_wrapper">
                        @if(!empty($invoice['id']) && !empty($invoice['products']))
                          @foreach($invoice['products'] as $index => $invoiceProduct)
                            <div class="input-group form-group">
                              <select class="custom-select form-control" name="product[]" id="product" style="width:75%;">
                                  @foreach($getProducts as $product)
                                      <option value="{{ $product['id'] }}" @if($product['id'] == $invoiceProduct['id']) selected @endif>{{ $product['product_name'] }}</option>
                                  @endforeach
                              </select>
                              <input type="text" class="form-control" name="quantity[]" id="quantity" placeholder="Quantity" value="{{ $invoiceProduct['pivot']['quantity'] }}"/>
                              <div class="input-group-append">
                                <button class="btn btn-outline-secondary add_button" type="button">
                                @if($index == 0)
                                  <i class="fa fa-plus"></i>
                                @else
                                  <i class="fa fa-minus"></i>
                                @endif
                                </button>
                              </div>
                            </div>
                          @endforeach
                        @else
                          <div class="input-group form-group">
                            <select class="custom-select form-control" name="product[]" id="product" style="width:75%;">
                                <option value="">Select</option>
                                @foreach($getProducts as $product)
                                    <option value="{{ $product['id'] }}">{{ $product['product_name'] }}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control" name="quantity[]" id="quantity" placeholder="Quantity" />
                            <div class="input-group-append">
                              <button class="btn btn-outline-secondary add_button" type="button"><i class="fa fa-plus"></i></button>
                            </div>
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
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