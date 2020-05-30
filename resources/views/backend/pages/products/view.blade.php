@extends('backend.layouts.default_layout')
@section('title') Products @parent @endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>รายละเอียดสินค้า</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{url('backend/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">{{$product->product_name}}</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <a name="" id="" class="btn btn-warning" href="{{ route('products.index') }}" role="button">
                    <i class="fas fa-arrow-alt-left"></i> &nbsp;กลับ
                </a>
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
                    <i class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body ">
            <div class="pl-5 pt-2">
                <h2>{{$product->product_name}}</h2>
                <hr class="m-0 p-0">
            </div>
            <div class="row">
                <div class="col-md-4 text-center p-5">
                <img src="{{ URL::asset('upload/products/'.$product->product_image)}}" alt="">
                </div>
                <div class="col-md-8">

                    <table class="table borderless mt-5">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-primary">หมวดหมู่</td>
                                <td>{{$product->product_category}}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-primary">รายละเอียด</td>
                                <td>{!!$product->product_detail!!}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-primary">บาร์โค้ด</td>
                                <td>{{$product->product_barcode}}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-primary">จำนวน</td>
                                <td>{{number_format($product->product_qty,0)}}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-primary">สถานะสินค้า</td>
                                <td class="fsize-20">
                                    {!!config('global.pro_status')[$product->product_status]!!}
                                    {{-- @if ($product->product_status == "1")
                                    <span class="badge badge-success fsize-20">In stock</span>
                                    @else
                                    <span class="badge badge-danger fsize-20">Out of stock</span>
                                    @endif --}}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-primary">ราคา</td>
                                <td>
                                    <h3 class="font-weight-bold">{{number_format($product->product_price,2)}}</h3>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->


</section>
@endsection