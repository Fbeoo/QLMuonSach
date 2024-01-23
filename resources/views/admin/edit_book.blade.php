@include('admin.layout.header')
@include('admin.layout.sidebar')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">General</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body" style="display: block;">
            <input type="hidden" value="{{$book->id}}" name="bookId" id="id">
            <div class="form-group">
                <label for="inputName">Tên sách</label>
                <input type="text" id="bookName" class="form-control" value="{{$book->name}}" name="bookName">
                <div id="nameError">

                </div>
            </div>
            <div class="form-group">
                <label for="inputClientCompany">Năm phát hành</label>
                <input type="text" id="yearPublish" class="form-control" value="{{$book->year_publish}}" name="yearPublish">
                <div id="yearPublishError">

                </div>
            </div>
            <div class="form-group">
                <label for="inputProjectLeader">Giá thuê</label>
                <input type="text" id="priceRent" class="form-control" value="{{$book->price_rent}}" name="priceRent">
                <div id="priceRentError">

                </div>
            </div>
            <div class="form-group">
                <label for="inputProjectLeader">Trọng lượng</label>
                <input type="text" id="weight" class="form-control" value="{{$book->weight}}" name="weight">
                <div id="weightError">

                </div>
            </div>
            <div class="form-group">
                <label for="inputProjectLeader">Tổng số trang</label>
                <input type="text" id="totalPage" class="form-control" value="{{$book->total_page}}" name="totalPage">
                <div id="totalPageError">

                </div>
            </div>
            <div class="form-group">
                <label for="inputProjectLeader">Số lượng</label>
                <input type="text" id="quantity" class="form-control" value="{{$book->quantity}}" name="quantity">
                <div id="quantityError">

                </div>
            </div>
            <div class="form-group" style="display: inline-grid">
                <label for="inputStatus">Danh mục</label>
                <div style="display: inline-flex">
                    <select id="inputCategoryParent" class="form-control custom-select" style="width: 500px">
                        @foreach($categoryParents as $categoryParent)
                            @if($categoryOfBook->category_parent_id === $categoryParent->id)
                                <option selected id="{{$categoryParent->id}}">{{$categoryParent->category_name}}</option>
                            @else
                                <option id="{{$categoryParent->id}}">{{$categoryParent->category_name}}</option>
                            @endif

                        @endforeach
                    </select>
                    <select id="categoryChildren" class="form-control custom-select" name="categoryChildren">
                        @foreach($categoryChildren as $categoryChild)
                            @if($categoryOfBook->id === $categoryChild->id)
                                <option value="{{$categoryChild->id}}" selected id="{{$categoryChild->id}}">{{$categoryChild->category_name}}</option>
                            @else
                                <option value="{{$categoryChild->id}}" id="{{$categoryChild->id}}">{{$categoryChild->category_name}}</option>
                            @endif
                        @endforeach
                    </select>
                    <div id="categoryError">

                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputDescription">Mô tả</label>
                <textarea id="bookDescription" class="form-control" rows="4" name="bookDescription">{{$book->description}}</textarea>
                <div id="descriptionError">

                </div>
            </div>
        <div class="form-group">
            <label for="exampleInputFile"Ảnh</label>
            <div class="input-group">
                <div class="custom-file" style="display: table">
                    <input type="hidden" name="bookImage" id="bookImage" value="{{$book->thumbnail}}">
                    <input type="file" id="imageInput">
                    <img id="previewImage" src="{{asset('dist/img/'.$book->thumbnail)}}" alt="Preview Image" style="width: 5%">
                </div>
            </div>
            <div id="thumbnailError">

            </div>
        </div>
        <a type="button" class="btn btn-block btn-success" id="editBook">Lưu thay đổi</a>
    </div>
    <!-- /.card-body -->
</div>
@include('admin.layout.footer')
<script src="{{asset('dist/js/phongJs/editBook.js')}}"></script>
