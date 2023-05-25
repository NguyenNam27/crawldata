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
                                <form class="navbar-form" role="search">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search" name="q">
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="box-body">
                            <table class="table table-border">
                                <tr>
                                    <th style="width:10px">STT</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>link_product</th>
                                    <th>Category</th>
                                    <th>Thao Tác</th>
                                </tr>
                                @foreach($product as $key => $item)
                                    <tr class="item-{{ $item->id }}">
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td><a href="{{ $item->link_product }}"> {{ $item->link_product }} </a></td>
                                        <td><a href="{{ $item->category_id }}"> {{ $item->category_id }} </a></td>
                                        <td>
                                            <a href=""
                                               class="btn btn-warning btn-edit"><i class="fa fa-pencil"></i></a>
                                            <button class="btn btn-danger btn-delete">
                                                <i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                            </table>
                            <div class="box-footer clearfix">
                                {{ $product->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
