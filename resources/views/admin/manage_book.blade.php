@include('admin.layout.header')
@include('admin.layout.sidebar')
<a type="button" class="btn btn-block btn-success" style="margin-left: auto; width: 10%" href="{{route('addBookPage')}}">Thêm mới</a>
<div style="margin-left: 20px">
    <div class="form-group" style="display: inline-grid">
{{--            <div style="display: inline-flex">--}}
{{--                <div style="width: 271px">--}}
{{--                    <label>Sắp xếp theo giá thuê</label>--}}
{{--                    <select id="1" class="form-control custom-select">--}}
{{--                        <option selected>Tăng dần</option>--}}
{{--                        <option selected>Giảm dần</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--                <div style="width: 271px; margin-left: 30px">--}}

{{--                </div>--}}
{{--            </div>--}}
        <div class="input-group" style="margin-top: 20px">
            <input id="bookSearch" type="search" class="form-control form-control-lg" placeholder="Nhập tên sách để tìm kiếm" style="height: 40px; width: 500px">
            <div class="input-group-append" style="height: 40px">
                <a class="btn btn-default" id="search">
                    <i class="fa fa-search"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4"><div class="row"><div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                    <thead>
                    <tr><th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Id</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Tên sách</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Hình ảnh</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Năm phát hành</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">giá thuê</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Trọng lượng</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Tổng số trang</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Số lượng</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Trạng thái</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Hành động</th></tr>
                    </thead>
                    <tbody id="showBooks">
                        @foreach($books as $book)
                            <tr class="odd">
                            <td class="dtr-control sorting_1" tabindex="0">{{$book->id}}</td>
                            <td>{{$book->name}}</td>
                            <td style="width: 100px; height: 100px">
                                <img src="{{asset('storage/'.$book->thumbnail)}}" style="max-width: 100%">
                            </td>
                            <td>{{$book->year_publish}}</td>
                            <td>{{$book->price_rent}}</td>
                            <td>{{$book->weight}}</td>
                            <td>{{$book->total_page}}</td>
                            <td>{{$book->quantity}}</td>
                            @if($book->status === 1)
                                <td style="color: green" id="statusBook{{$book->id}}">Bình thường</td>
                            @endif
                            @if($book->status === 0)
                                <td style="color:red" id="statusBook{{$book->id}}">Khóa</td>
                            @endif
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning">Hành động</button>
                                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="{{route('editBookPage',['bookId'=>$book->id])}}">Sửa</a>
                                        @if($book->status === 1)
                                            <a style="cursor: pointer" class="dropdown-item" id="actionStatusBook{{$book->id}}" onclick="lockBook({{$book->id}})">Khóa</a>
                                        @else
                                            <a style="cursor: pointer" class="dropdown-item" id="actionStatusBook{{$book->id}}" onclick="unlockBook({{$book->id}})">Mở khóa</a>
                                        @endif

                                    </div>
                                </div>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    </div>
            <script src="{{asset('dist/js/phongJs/lockBook.js')}}"></script>
            <script src="{{asset('dist/js/phongJs/manageBook.js')}}"></script>
@include('admin.layout.footer')


