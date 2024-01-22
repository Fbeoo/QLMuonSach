@include('layout.header')
<x-sidebar/>
<div class="card card-solid">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-sm-4">
                <h3 class="d-inline-block d-sm-none">LOWA Men’s Renegade GTX Mid Hiking Boots Review</h3>
                <div class="col-12">
                    <img src="{{asset('dist/img/'.$book->thumbnail)}}" class="book-image" alt="Book Image" style="width: 60%;margin-left: 80px">
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <h3 class="my-3">{{$book->name}}</h3>
                <p>Tác giả : |
                    @foreach($authors as $author)
                        {{$author->author_name}} |
                    @endforeach
                </p>
                <p>Năm xuất bản : {{$book->year_publish}}</p>
                <p>Trọng lượng : {{$book->weight}}g</p>
                <p>Số trang : {{$book->total_page}}</p>

                <hr>

                <div class="bg-gray py-2 px-3 mt-4">
                    <h2 class="mb-0" style="color: red">
                        {{$book->price_rent}} VNĐ / 1 tháng
                    </h2>
                </div>

                <div class="mt-4">
                    <div class="btn btn-primary btn-lg btn-flat">
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
@include('layout.footer')
