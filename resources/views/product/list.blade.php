@extends('layout.main')
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
                                <form action="{{route('home')}}" method="GET" class="navbar-form" name="search">
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

                            <div id="result" class="panel panel-default" style="display:none">
                                <ul class="list-group" id="memList">

                                </ul>
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-border">
                                <tr>
                                    <th style="width:10px">STT</th>
                                    <th>Type Product</th>
                                    <th>Code Product</th>
                                    <th>Name</th>
                                    <th>Price Cost</th>
                                    <th>Created_at</th>
                                    @for($i = 0; $i < $count_site; $i++)
                                        <th> Other site</th>
                                    @endfor
                                </tr>
                                @foreach($products as $key => $product)
                                    <tr>
                                        <td>{{ $key+1 }}</td>

                                        <td>
                                            Loại SP
                                        </td>
                                        <td>codesanpham</td>
                                        <td>{{ $product['name'] }}</td>
                                        <td>{{ number_format($product['original_price']) }}đ</td>

                                        <td> {{ $product['created_at'] }}</td>
                                        @php

                                            $expect_sites = []
                                        @endphp
                                        @for($i = 0; $i < $count_site; $i++)
                                            @if(isset($product['items'][$i]))

                                                <td>
                                                    <a href="{{ $product['items'][$i]['category_id'] }}"> {{ $product['items'][$i]['category_id'] }} </a>
                                                    <p>Giá gốc: {{ number_format($product['items'][$i]['price']) }}đ</p>
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
                                                <td><a href="{{ $site }}"> {{ $site }} </a></td>
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

