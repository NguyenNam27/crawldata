@extends('layouts.main')
@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                QUẢN LÝ SẢN PHẨM BTP <a href="{{route('add-product-original')}}" class="btn bg-purple btn-flat"><i
                        class="fa fa-plus"></i> Thêm sản phẩm</a>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-user"></i> QUẢN LÝ SẢN PHẨM </a></li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Danh Sách sản các sản phẩm</h3>
                        </div>
                        <?php
                        $message = Session::get('message');
                        if($message){
                            echo '<h3 class="text-alert" style="color: red">' .$message. '</h3>';
                            Session::put('message',null);
                        }
                        ?>
                        <div class="box-body">
                            <table class="table table-border">
                                <tr>
                                    <th style="width:10px">STT</th>
                                    <th style="width:10px">Mã code</th>
                                    <th >Danh mục</th>

                                    <th>Tên</th>
                                    <th>Giá niêm yết</th>
                                    <th>Link</th>
                                    <th>Ngày tạo</th>
                                    <th>Tình Trạng</th>
                                    <th>Thao tác</th>
                                </tr>
                                @foreach($listProductOriginal as $key => $item)
                                    <tr class="item-{{ $item->id }}">
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->code_product_original }}</td>
                                        <td>{{ $item->category_id }}</td>

                                        <td>{{ $item->name_product_original }}</td>
                                        <td>{{ number_format($item->price_product_original)  }}đ</td>
                                        <td><a href="{{ $item->url_product_original }}"> {{ $item->url_product_original }} </a></td>

                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ ($item->status==1) ? 'Hiển thị' : 'Không Hiển thị' }}</td>
                                        <td>
{{--                                            {{URL::to('edit-category/'.$item->id)}}--}}
                                            <a href="{{URL::to('edit-product-original/'.$item->id)}}"
                                               class="btn btn-warning btn-edit"><i class="fa fa-pencil"></i></a>
                                            <a onclick="return confirm('Bạn có chắc là muốn xóa danh mục này ko?')" class="btn btn-danger btn-delete" href="{{URL::to('delete-product-original/'.$item->id)}}">
                                                <i class="fa fa-trash"></i></a>
{{--                                            {{URL::to('delete-category/'.$item->id)}}--}}
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
