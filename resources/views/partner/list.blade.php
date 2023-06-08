@extends('layouts.main')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                DANH SÁCH QUẢN LÝ ĐỐI TÁC <a href="{{route('partner.create')}}" class="btn bg-purple btn-flat"><i class="fa fa-plus"></i> Thêm đối tác</a>

            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-user"></i> QUẢN LÝ ĐỐI TÁC </a></li>
            </ol>
        </section>

        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="box-header with-border">

                            <h3 class="box-title">Danh Sách đối tác</h3>

                        </div>
{{--                        @if(session()->has('success'))--}}
{{--                            <div class="alert alert-success">{{session()->get('success')}}</div>--}}
{{--                        @endif--}}


                        <div class="box-body">
                            <table class="table table-border">
                                <tbody>
                                <tr>
                                    <th style="width: 10px">STT</th>
                                    <th>Name</th>
                                    <th>Url</th>
                                    <th>Value</th>
                                    <th>created_at</th>
                                    <th>Thao tác</th>
                                </tr>
{{--                                    @foreach($data as $key => $item)--}}
{{--                                        <tr class="item-{{ $item->id }}">--}}
{{--                                            <td>{{ $key +1}}</td>--}}
{{--                                            <td>{{ $item->brand_name }}</td>--}}
{{--                                            <td>{{ strip_tags($item->brand_desc) }}</td>--}}
{{--                                            <td>{{ $item->brand_status }}</td>--}}
{{--                                            <td>{{ $item->created_at }}</td>--}}
{{--                                            <td>--}}
{{--                                                <a  href="" class="btn btn-warning btn-edit"><i class="fa fa-pencil"></i></a>--}}
{{--                                                <button data-id="" class="btn btn-danger btn-delete"><i class="fa fa-trash"></i></button>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer clearfix">
{{--                            {{ $data->links() }}--}}
                        </div>
                    </div>
                </div>

            </div>


        </section>
@endsection
