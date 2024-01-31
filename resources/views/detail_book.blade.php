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
                <h3 class="my-3" style="font-weight: bold">{{$book->name}}</h3>
                <p style="font-size: 20px">Tác giả : {{$book->authorBook[0]->authorInfo->author_name}}

                </p>
                <p style="font-size: 20px">Năm xuất bản : {{$book->year_publish}}</p>
                <p style="font-size: 20px">Trọng lượng : {{$book->weight}}g</p>
                <p style="font-size: 20px">Số trang : {{$book->total_page}}</p>
                <p style="font-size: 20px">Số lượng đang có trong kho : {{$numberBookAvailable}}</p>

                <hr>

                <div class="bg-gray py-2 px-3 mt-4">
                    <h2 class="mb-0" style="color: red; font-weight: bold">
                        {{number_format($book->price_rent, 0, ',', '.')}}VNĐ /1 ngày
                    </h2>
                </div>
                <div class="mt-4">
                    <div class="btn btn-primary btn-lg btn-flat" data-toggle="modal" data-target="#exampleModal">
                        <i class="fas fa-cart-plus fa-lg mr-2"></i>
                        Thuê
                    </div>
                    <form id="formAddToCart" style="display:inline;">
                        <input type="hidden" name="bookId" value="{{$book->id}}">
                        <a class="btn btn-default btn-lg btn-flat" id="addToCart">
                            <i class="fas fa-heart fa-lg mr-2"></i>
                            Thêm vào giỏ
                        </a>
                    </form>
                </div>

            </div>
        </div>
        <div class="row mt-4">
            <nav class="w-100">
                <div class="nav nav-tabs" id="product-tab" role="tablist">
                    <a class="nav-item nav-link active" id="product-desc-tab">Mô tả</a>
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
                <h5 style="font-weight: bold" class="modal-title" id="exampleModalLabel">Thời gian thuê và số lượng</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formRentBook">
                    <div class="form-group">
                        <input type="hidden" name="bookId" value="{{$book->id}}">
                        <input type="hidden" name="price" value="{{$book->price_rent}}">
                        <input type="hidden" name="numberBookAvailable" value="{{$numberBookAvailable}}">
                        <div class="row">
                            <div class="col-4">
                                <label for="inputProjectLeader">Mượn tới</label>
                            </div>
                            <div class="col-8">
                                <input type="text" name="dateRent"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label for="inputProjectLeader">Số lượng</label>
                            </div>
                            <div class="col-8">
                                <input type="number" name="quantityRent" min="1" max="{{$numberBookAvailable}}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
                <a type="button" class="btn btn-primary" id="rent">Thuê</a>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
    $(function() {
        $('input[name="dateRent"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'),10),
            locale: {
                format: 'DD/MM/Y'
            }
        });
    });
</script>
@include('layout.footer')
<script src="{{asset('dist/js/phongJs/rentBook.js')}}"></script>
