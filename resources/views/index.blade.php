@include('layout.header')
<x-sidebar/>
<h1>Home</h1>
<div class="col-10" style="margin: auto">
    <div class="card card-primary">
        <div class="card-body">
            <div>
                <div class="filter-container p-0 row">
                    @foreach($books as $book)
                        <div class="filtr-item col-sm-3" style="border: 1px solid rgb(233, 233, 233); text-align: center;">
                            <div style="padding: 30px">
                                <div class="book_image">
                                    <img src="{{asset('dist/img/'.$book->thumbnail)}}" style="max-width: 60%; padding: 20px">
                                </div>
                                <div class="book_info" style="height: 80px">
                                    <h6 class="book_name"><a>{{$book->name}}</a></h6>
                                    <div class="rent_price" style="font-size: 16px;color: #fe4c50;font-weight: 600">{{$book->price_rent}}VNĐ /1 tháng</div>
                                </div>
                                <button type="button" class="btn btn-block btn-primary">Chi tiết</button>
                            </div>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.footer')
