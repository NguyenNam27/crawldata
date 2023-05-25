@extends('layout.main')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                QUẢN LÝ DANH MỤC <a href="" class="btn bg-purple btn-flat"><i
                        class="fa fa-plus"></i> Thêm danh mục</a>

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
                            <h3 class="box-title">Danh Sách DANH MỤC</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-border">
                                <tr>
                                    <th style="width:10px">STT</th>
                                    <th>Name</th>
                                    <th>Link</th>
                                    <th>Status</th>
                                    <th>Hành Động</th>
                                </tr>
                                @foreach($category as $key => $item)
                                    <tr class="item-{{ $item->id }}">
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td><a href="{{ $item->url }}"> {{ $item->url }} </a></td>

                                        <td>{{ ($item->status==1) ? 'Hiển thị' : 'Không Hiển thị' }}</td>
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
{{--                                {{ $data->links() }}--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
