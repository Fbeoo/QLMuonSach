@include('layout.header')
<x-sidebar/>
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
</style>
<h1>Home</h1>
<div class="col-10" style="margin: auto">
    <div class="card card-primary">
        <div class="card-body">
            <div>
                <div class="filter-container p-0 row">
                    @if(!$books->items())
                        <h4 style="margin: auto">Hiện đang không có sách</h4>
                    @endif
                    @foreach($books as $book)
                        <div class="filtr-item col-sm-3" style="border: 1px solid rgb(233, 233, 233); text-align: center;">
                            <div style="padding: 30px">
                                <div class="book_image" style="width: 200px;height: 300px;margin: auto">
                                    <img src="{{asset('storage/'.$book->thumbnail)}}" style="width: 100%;height: 100%;object-fit: cover">
                                </div>
                                <div class="book_info" style="height: 80px; margin-top: 20px">
                                    <h6 class="book_name"><a>{{$book->name}}</a></h6>
                                    <div class="rent_price" style="font-size: 16px;color: #fe4c50;font-weight: 600">{{number_format($book->price_rent, 0, ',', '.')}}VNĐ /1 tháng</div>
                                </div>
                                <a type="button" class="btn btn-block btn-primary" href="{{route('detail_book',['id'=>$book->id])}}">Chi tiết</a>
                            </div>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@if($books->items())
    <div class="pagination">
        <ul id="ulPagination">
            @for($i = 1; $i <= $books->lastPage(); $i++)
                <li>
                    <a href="{{ route('home', ['page' => $i]) }}">{{ $i }}</a>
                </li>
            @endfor
        </ul>
    </div>
@endif
@include('layout.footer')
