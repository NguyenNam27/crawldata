@extends('layouts.main')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                QUẢN LÝ SẢN PHẨM <a href="" class="btn bg-purple btn-flat"><i
                        class="fa fa-plus"></i> sản phẩm</a>

            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-user"></i> QUẢN LÝ DANH MỤC </a></li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            {{--                            <h3 class="box-title">Danh Sách Sản Phẩm</h3>--}}
                            <div class="col-sm-3 col-md-3">
                                <form action="{{route('list')}}" method="GET" class="navbar-form" name="search">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search Product"
                                               name="search" id="search">
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><i
                                                    class="glyphicon glyphicon-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-sm-3 col-md-3">
                                    <div class="input-group">
                                        <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                                            <option selected>--Chọn site gốc--</option>
                                            <option value="1">Junger</option>
                                            <option value="">Hawonkoo</option>
                                            <option value="3">PoongsanKorea</option>
                                            <option value="3">Boss</option>
                                        </select>
                                    </div>
                            </div>

                            <div class="col-sm-3 col-md-3">
                                    @csrf
                                    <div class="input-group">
                                        <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                                            <option selected>--Lọc sản phẩm so sánh--</option>
                                            <option value="1">Junger</option>
                                            <option value="2">Hawonkoo</option>
                                            <option value="3">PoongsanKorea</option>
                                            <option value="3">Boss</option>
                                        </select>
                                    </div>
                            </div>

                            <div class="col-sm-3 col-md-3">
                                <a href="{{ route('run-command') }}" class="btn btn-primary">Chạy lệnh</a>
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-border">
                                <tr>
                                    <th style="width:10px">STT</th>
                                    <th>Mã Sản Phẩm</th>
                                    <th>Tên Sản phẩm</th>
                                    <th>Giá Niêm Yết</th>
                                    <th>Ngày Tạo Dữ Liệu</th>
                                    @for($i = 0; $i < $count_site; $i++)
                                        <th> Trang Khác</th>
                                    @endfor
                                </tr>
                                @foreach($products as $key => $product)
                                    <tr>
                                        <td>{{ $key+1 }}</td>

                                        <td>{{ $product['code_product'] }}</td>
                                        <td>
                                            <a href="{{ $product['link_product'] }}"> {{ $product['name'] }} </a>
                                        </td>
                                        <td>{{ number_format($product['original_price']) }}đ</td>

                                        <td> {{ $product['created_at'] }}</td>
                                        @php
                                            $expect_sites = []
                                        @endphp

                                        @for($i = 0; $i < $count_site; $i++)
                                            @if(isset($product['items'][$i]))

                                                <td>
                                                    <a href="{{ $product['items'][$i]['link_product'] }}"> {{ $product['items'][$i]['category_id'] }} </a>
                                                    <p>Giá niêm yết: {{ number_format($product['items'][$i]['price']) }}đ</p>
                                                    <p>Giá chênh
                                                        lệch: {{ number_format($product['items'][$i]['price_diff']) }}</p>
                                                </td>
                                                @php
                                                    $expect_sites[] = $product['items'][$i]['category_id']
                                                @endphp
                                            @endif
                                        @endfor

                                        @foreach($sites as $site)
                                            {{--                                            @if($expect_site !== $site)--}}
                                            @if(!in_array($site, $expect_sites))
                                                <td>
                                                    <a href="{{ $site }}"> {{ $site }} </a>
                                                    <p>Giá niêm yết: Không tồn tại</p>
                                                    <p>Giá chênh lệch: không tồn tại </p>
                                                </td>
                                            @endif
                                            {{--                                            @endif--}}
                                        @endforeach
                                    </tr>
                                @endforeach

                            </table>
                            <div class="item-paginate" style="border: 1px">
                                <a class="pace-item" href="">1</a>
                                <a class="pace-item" href="">2</a>
                                <a class="pace-item" href="">3</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

