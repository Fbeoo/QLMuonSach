@include('layout.header')
<x-sidebar/>
<div class="card card-solid">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-sm-4">
                <h3 class="d-inline-block d-sm-none">LOWA Men’s Renegade GTX Mid Hiking Boots Review</h3>
                <div class="col-12">
                    <img src="{{asset('storage/'.$book->thumbnail)}}" class="book-image" alt="Book Image" style="width: 60%;margin-left: 80px">
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <h3 class="my-3">{{$book->name}}</h3>
                <p>Tác giả : {{$book->authorBook[0]->authorInfo->author_name}}

                </p>
                <p>Năm xuất bản : {{$book->year_publish}}</p>
                <p>Trọng lượng : {{$book->weight}}g</p>
                <p>Số trang : {{$book->total_page}}</p>

                <hr>

                <div class="bg-gray py-2 px-3 mt-4">
                    <h2 class="mb-0" style="color: red">
                        {{number_format($book->price_rent, 0, ',', '.')}}VNĐ /1 tháng
                    </h2>
                </div>

                <div class="mt-4">
                    <div class="btn btn-primary btn-lg btn-flat" data-toggle="modal" data-target="#exampleModal">
                        <i class="fas fa-cart-plus fa-lg mr-2"></i>
                        Thuê
                    </div>

                    <div class="btn btn-default btn-lg btn-flat">
                        <i class="fas fa-heart fa-lg mr-2"></i>
                        Thêm vào giỏ
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-4">
            <nav class="w-100">
                <div class="nav nav-tabs" id="product-tab" role="tablist">
                    <a class="nav-item nav-link active" id="product-desc-tab">Description</a>
                </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent">
                <div class="tab-pane fade show active">{{$book->description}}</div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thời gian thuê và số lượng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control datepicker" id="datepicker" name="datepicker" placeholder="Select date">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
                <button type="button" class="btn btn-primary">Thuê</button>
            </div>
        </div>
    </div>
</div>


@include('layout.footer')
<script src="{{asset('dist/js/phongJs/rentBook.js')}}"></script>
