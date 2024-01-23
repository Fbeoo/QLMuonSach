@include('admin.layout.header')
@include('admin.layout.sidebar')
<div class="card card-primary" style="width: 80%; margin: auto; margin-top: 20px">
    <div class="card-header">
        <h3 class="card-title">Thêm sách</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body" style="display: block;">
        <div style="display: inline-block; width: 45%;">
            <input type="hidden" name="bookId" id="bookId">
            <div class="form-group">
                <label for="inputName">Tên sách</label>
                <input type="text" id="bookName" class="form-control" name="bookName" value="{{ old('bookName') }}">
                <div id="nameError">

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
            <div class="form-group" style="display: inline-grid">
                <label for="inputStatus">Danh mục</label>
                <div style="display: inline-flex">
                    <div style="width: 300px">
                        <select id="inputCategoryParent" class="form-control custom-select">
                            <option selected>Chọn danh mục bất kì</option>
                            @foreach($categoryParents as $categoryParent)
                                <option id="{{$categoryParent->id}}">{{$categoryParent->category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="width: 300px; margin-left: 30px">
                        <select id="categoryChildren" class="form-control custom-select" name="categoryChildren">

                        </select>
                    </div>
                </div>
                <div id="categoryError">

                </div>
            </div>
            <div class="form-group">
                <label for="inputDescription">Mô tả</label>
                <textarea id="bookDescription" class="form-control" rows="4" name="bookDescription">{{ old('bookDescription') }}</textarea>
                <div id="descriptionError">

                </div>
            </div>
        <div class="form-group">
            <label for="exampleInputFile">Ảnh</label>
            <div class="input-group">
                <div class="custom-file" style="display: table">
                    <input type="hidden" name="bookImage" id="bookImage">
                    <input type="file" id="imageInput">
                    <img id="previewImage" src="{{asset('dist/img/'.old('bookImage'))}}" alt="Ảnh sách" style="width: 5%">
                </div>
            </div>
            <div id="thumbnailError">

            </div>
        </div>
            <a type="button" class="btn btn-block btn-success" id="addBook">Thêm mới</a>
    </div>
    <!-- /.card-body -->
</div>
@include('admin.layout.footer')
<script src="{{asset('dist/js/phongJs/addBook.js')}}"></script>

