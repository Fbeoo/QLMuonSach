@include('admin.layout.header')
@include('admin.layout.sidebar')
<style>
    label{
        color:#000;
        font-weight:500;
        background:#fff;
        margin-bottom:5px;
        padding:4px;
        border-radius:4px;
    }
    input{
        margin-bottom:10px
    }
    .sub-bg {
        background: url('https://the-french.co.uk/wp-content/uploads/2018/02/111-400x600.jpg')no-repeat;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    .subs-header{
        padding-top: 0px;
        border-bottom: 0px;
    }
    .heading-text h4{
        color: #fff;
        padding: 20px;
        border: 2px solid #d3d3d3;
        display: inline-block;
        border-radius: 20px;
        box-shadow: inset 10px 12px 13px -7px rgba(0,0,0,0.7);
        -moz-box-shadow: inset 10px 12px 13px -7px rgba(0,0,0,0.7);
        -webkit-box-shadow: inset 10px 12px 13px -7px rgba(0,0,0,0.7);
        -o-box-shadow: inset 10px 12px 13px -7px rgba(0,0,0,0.7);
    }
    .heading-text{
        margin-top: 10px;
    }
    .close:not(:disabled):not(.disabled):focus, .close:not(:disabled):not(.disabled):hover {
        outline: none;
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
<div class="card card-primary" style="width: 80%; margin: auto; margin-top: 20px">
    <div class="card-header">
        <h3 class="card-title">Thêm sách</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <form id="formAddBook" enctype="multipart/form-data">
    <div class="card-body" style="display: block;">
        <div style="display: inline-block; width: 45%;">
            <input type="hidden" name="bookId" id="bookId">
            <div class="form-group">
                <label for="inputName">Tên sách</label>
                <input type="text" id="bookName" class="form-control" name="bookName" value="{{ old('bookName') }}">
                <div id="bookNameError">

                </div>
            </div>
            <div class="form-group">
                <label for="inputClientCompany">Năm phát hành</label>
                <input type="text" id="yearPublish" class="form-control" name="yearPublish">
                <div id="yearPublishError">

                </div>
            </div>
            <div class="form-group">
                <label for="inputProjectLeader">Giá thuê</label>
                <input type="text" id="priceRent" class="form-control" name="priceRent" value="{{ old('priceRent') }}">
                <div id="priceRentError">

                </div>
            </div>
        </div>
        <div style="display: inline-block;width: 45%;margin-left: 100px">
            <div class="form-group">
                <label for="inputProjectLeader">Trọng lượng</label>
                <input type="text" id="weight" class="form-control" name="weight" value="{{ old('weight') }}">
                <div id="weightError">

                </div>
            </div>
            <div class="form-group">
                <label for="inputProjectLeader">Tổng số trang</label>
                <input type="text" id="totalPage" class="form-control" name="totalPage" value="{{ old('totalPage') }}">
                <div id="totalPageError">

                </div>
            </div>
            <div class="form-group">
                <label for="inputProjectLeader">Số lượng</label>
                <input type="text" id="quantity" class="form-control" name="quantity" value="{{ old('quantity') }}">
                <div id="quantityError">

                </div>
            </div>
        </div>
        <div style="width: 45%; display: inline-block">
            <div class="form-group" style="display: inline-grid">
                <label for="inputStatus">Danh mục</label>
                <div style="display: inline-flex">
                    <div style="width: 271px">
                        <select id="inputCategoryParent" class="form-control custom-select">
                            <option selected>Chọn danh mục bất kì</option>
                            @foreach($categoryParents as $categoryParent)
                                <option id="{{$categoryParent->id}}">{{$categoryParent->category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 271px; margin-left: 30px">
                        <select id="categoryChildren" class="form-control custom-select" name="categoryChildren">

                        </select>
                    </div>
                </div>
                <div id="categoryChildrenError">

                </div>
            </div>
        </div>
        <div style="width: 45%; display: inline-block; margin-left: 100px">
            <div class="form-group" style="display: inline-grid;">
                <label for="inputStatus">Tác giả</label>
                <div style="display: inline-flex">
                    <div style="width: 300px">
                        <select name="authorId" id="author" class="form-control custom-select">
                            <option value="">Chọn tác giả bất kì</option>
                            @foreach($authors as $author)
                                <option value="{{$author->id}}" id="{{$author->id}}">{{$author->author_name}}</option>
                            @endforeach
                        </select>
                        <div id="authorIdError">

                        </div>
                    </div>
                    <div style="margin-left: 30px">
                        <a class="btn btn-primary" data-toggle="modal" href='#susbc-form'>+</a>
                    </div>
                </div>
            </div>
        </div>
            <div class="form-group">
                <label for="inputDescription">Mô tả</label>
                <textarea id="bookDescription" class="form-control" rows="4" name="description">{{ old('bookDescription') }}</textarea>
                <div id="descriptionError">

                </div>
            </div>
        <div class="form-group">
            <label for="exampleInputFile">Ảnh</label>
            <div class="input-group">
                <div class="custom-file" style="display: table">
{{--                    <input type="hidden" name="bookImage" id="bookImage">--}}
                    <input type="file" id="imageInput" name="thumbnail">
                    <img id="previewImage" src="{{asset('dist/img/'.old('bookImage'))}}" alt="Ảnh sách" style="width: 5%">
                </div>
            </div>
            <div id="thumbnailError">

            </div>
        </div>
            <a type="button" class="btn btn-block btn-success" id="addBook">Thêm mới</a>
    </div>
    </form>
    <!-- /.card-body -->
</div>
<div class="modal fade" id="susbc-form">
    <div class="modal-dialog mb-5 bg-white rounded">
        <div class="modal-content sub-bg">
            <div class="modal-header subs-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <!-- <h4 class="modal-title">Modal title</h4> -->
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h4>Thêm tác giả</h4>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form id="formAddAuthor">
                            <div class="form-group row">
                                <div class="col-md-12 col-xs-12">
                                    <label for="firstName">Tên tác giả</label>
                                    <input type="text" class="form-control" id="authorName" name="authorName">
                                    <div id="authorNameError">

                                    </div>
                                </div>
                                <label style="margin-left: 10px">Ảnh tác giả</label>
                                <div class="custom-file" style="display: table; margin-left: 8px;">
                                    <input type="file" id="imageAuthorInput" name="authorImage">
                                    <img id="previewAuthorImage" alt="Ảnh sách" style="width: 5%">
                                </div>
                            </div>
                            <a id="addAuthor" class="btn btn-primary text-center">Thêm mới</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="loaderContainer" class="loader-container hidden">
    <div class="loader"></div>
</div>


@include('admin.layout.footer')
<script src="{{asset('dist/js/phongJs/addBook.js')}}"></script>

