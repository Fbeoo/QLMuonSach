@include('layout.header')
<x-sidebar/>
<div class="col-12">
    <div class="card card-primary">
        <div class="card-body">
            <div>
                <div class="filter-container p-0 row">
                    @foreach($books as $book)
                        <div class="filtr-item col-sm-2" style="border: 1px solid rgb(233, 233, 233); text-align: center">
                            <div class="book_image">
                                <img src="{{asset('dist/img/'.$book->thumbnail)}}" style="max-width: 60%">
                            </div>
                            <div class="book_info" style="">
                                <h6 class="book_name"><a>{{$book->name}}</a></h6>
                                <div class="rent_price" style="font-size: 16px;color: #fe4c50;font-weight: 600">{{$book->price_rent}}VNĐ /1 tháng</div>
                            </div>
                            <button type="button" class="btn btn-block btn-primary">Chi tiết</button>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
@include('layout.footer')
