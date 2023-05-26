@extends('layout.main')
@section('content')

    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                QUẢN LÝ SẢN PHẨM
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-user"></i> QUẢN LÝ DANH MỤC </a></li>
            </ol>
        </section>

        <section class="content">
            @if(count($search_product)>0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Kết quả tìm kiếm</h3>
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
                                    @foreach($search_product as $key => $item)
{{--                                        {{dd($search_product)}}--}}
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
                            </div>
                        </div>

                        @else
                            <h3>Không có kết qua</h3>
                        @endif
                    </div>
                </div>
        </section>

    </div>
@endsection
@section('my_js')
    {{--    <script type="text/javascript">--}}
    {{--        $(document).ready(function () {--}}
    {{--            $.ajaxSetup({--}}
    {{--                headers: {--}}
    {{--                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
    {{--                }--}}
    {{--            });--}}

    {{--            $('#search').keyup(function () {--}}
    {{--                var search = $('#search').val();--}}
    {{--                if (search == "") {--}}
    {{--                    // $("#memList").html("");--}}
    {{--                    // $('#result').hide();--}}
    {{--                    console.log('có')--}}
    {{--                } else {--}}
    {{--                    --}}{{--$.get("{{ route('search') }}",{search:search}, function(data){--}}
    {{--                    --}}{{--    $('#memList').empty().html(data);--}}
    {{--                    --}}{{--    $('#result').show();--}}
    {{--                    --}}{{--})--}}
    {{--                    console.log('no')--}}
    {{--                }--}}
    {{--            });--}}
    {{--        });--}}

    {{--    </script>--}}
@endsection
