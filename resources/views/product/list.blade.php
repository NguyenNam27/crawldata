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
                                <form action="{{route('find')}}" method="POST" class="navbar-form" name="search">
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
                                    <th>Code product </th>
                                    <th>Name </th>
                                    <th>Price Cost</th>
                                    <th>Price partner </th>
                                    <th>Result </th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created_at</th>
                                    <th>Thao Tác</th>
                                </tr>
                                @foreach($product as $key => $item)
                                    <tr class="item-{{ $item->id }}">
                                        <td>{{ $key+1 }}</td>
                                        <td>1</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->price_cost }}</td>
                                        <td>{{$item->price_partner}}</td>
                                        <td>{{$item->result}}</td>
                                        <td><a href="{{ $item->category_id }}"> {{ $item->category_id }} </a></td>
                                        <td> {{ ($item->status == 1) ? 'Chưa so sánh' : 'Đã so sánh' }} </td>
                                        <td> {{ $item->created_at }}</td>
                                        <td>
                                            <a href=""
                                               class="btn btn-warning btn-edit"><i class="fa fa-pencil"></i></a>
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
@section('my_js')
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#search').keyup(function () {
                var search = $('#search').val();
                if (search == "") {
                    // $("#memList").html("");
                    // $('#result').hide();
                    console.log('có')
                } else {
                    {{--$.get("{{ route('search') }}",{search:search}, function(data){--}}
                    {{--    $('#memList').empty().html(data);--}}
                    {{--    $('#result').show();--}}
                    {{--})--}}
                    console.log('no')
                }
            });
        });

    </script>
@endsection
