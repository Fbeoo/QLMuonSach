@include('admin.layout.header')
@include('admin.layout.sidebar')
<style>
    .pagination {
        display: flex;
        margin-top: 20px;
    }

    .pagination ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .pagination li {
        display: inline-block;
        margin-right: 5px;
    }

    .pagination li a {
        display: block;
        padding: 8px 12px;
        text-decoration: none;
        background-color: #f2f2f2;
        color: #333;
    }

    .pagination li a:hover {
        background-color: #ddd;
    }

    .pagination li a.active {
        background-color: #4CAF50;
        color: white;
    }


    .loader-container {
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .loader {
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .hidden {
        display: none;
    }


</style>
<a type="button" class="btn btn-block btn-success" style="margin-left: auto; width: 10%; margin-top: 20px" href="{{route('addBookPage')}}">Thêm mới</a>
<div style="display: flex; justify-content: center; align-items: center;width: 80%">
<div style="margin-left: 20px">
    <form id="formFilterBook" enctype="multipart/form-data">
    <div class="input-group" style="margin-top: 20px">
        <input name="name" id="bookSearch" type="search" class="form-control form-control-lg" placeholder="Nhập tên sách để tìm kiếm" style="height: 40px; width: 500px">
        <div class="input-group-append" style="height: 40px">
            <button class="btn btn-default" id="search">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
    <div class="form-group" style="display: inline-grid; margin-top: 20px">
            <div style="display: inline-flex">
                <div style="width: 271px">
                    <label>Năm phát hành</label>
                    <div style="display: inline-flex;">
                        <div>
                            <input name="minYear" class="form-control" type="text" style="width: 100px" id="minYear">
                            <p id="minYearError">

                            </p>
                        </div>
                        <div>
                            <input name="maxYear" class="form-control" type="text" style="width: 100px; margin-left: 30px" id="maxYear">
                            <p id="maxYearError">

                            </p>
                        </div>
                    </div>
                </div>
                <div style="width: 271px; margin-left: 30px">
                    <label>Trạng thái</label>
                    <select name="status" id="filterBookByStatus" class="form-control custom-select">
                        <option value="" selected>Sắp xếp theo trạng thái</option>
                        <option value="1">Bình thường</option>
                        <option value="0">Khóa</option>
                    </select>
                </div>
                <div style="width: 271px; margin-left: 30px">
                    <label>Danh mục</label>
                    <select name="category_parent_id" id="filterBookByCategoryParent" class="form-control custom-select">
                        <option value="" selected>Sắp xếp theo danh mục lớn</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->category_name}}</option>
                        @endforeach
                    </select>
                    <select name="category_children_id" id="filterBookByCategoryChildren" class="form-control custom-select" style="margin-top: 20px">

                    </select>
                </div>
            </div>

    </div>
    </form>

</div>
</div>
<div class="card-body">
    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4"><div class="row"><div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                    <thead>
                    <tr><th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Id</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Tên sách</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Hình ảnh</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Năm phát hành</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">Giá thuê</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Danh mục</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Tác giả</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Số lượng</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Trạng thái</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Hành động</th></tr>
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
                            <td>{{number_format($book->price_rent, 0, ',', '.')}}</td>
                            <td>
                                {{$book->category->category_name}}
                            </td>
                            <td>
                                @foreach ($book->authorBook as $author_book)
                                    {{$author_book->authorInfo->author_name}}
                                @endforeach
                            </td>
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
                                            <button id="actionStatusBook{{$book->id}}" style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#exampleModal" data-id="{{$book->id}}" data-value="Lock">Khóa</button>
                                        @else
                                            <button id="actionStatusBook{{$book->id}}" style="cursor: pointer" class="dropdown-item" data-toggle="modal" data-target="#exampleModal" data-id="{{$book->id}}" data-value="Unlock">Mở khóa</button>
                                        @endif

                                    </div>
                                </div>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination" style="align-items: center; justify-content: center">
                    <ul id="ulPagination">
{{--                        @for($i = 1; $i <= $books->lastPage(); $i++)--}}
{{--                            <li>--}}
{{--                                <a href="{{ route('manageBook', ['page' => $i]) }}">{{ $i }}</a>--}}
{{--                            </li>--}}
{{--                        @endfor--}}
{{--                    </ul>--}}
                </div>
    </div>
            <div id="loaderContainer" class="loader-container hidden">
                <div class="loader"></div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Thông báo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="notification"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
                            <button id="actionStatus" type="button" class="btn btn-primary"></button>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script>

            </script>
            <script src="{{asset('dist/js/phongJs/lockBook.js')}}"></script>
            <script src="{{asset('dist/js/phongJs/manageBook.js')}}"></script>
@include('admin.layout.footer')


