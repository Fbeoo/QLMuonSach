{{--@include('layout.header')--}}
{{--<x-sidebar/>--}}
@extends('title')
    Tất cả sách
@endsection

@section('content')
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
<div class="col-10" style="margin: auto; padding-top: 50px">
    <div class="card card-primary">
        <div class="card-body">
            <div>
                <div class="row">
                    <div class="col-6" style="position: relative; padding-bottom: 30px">
                        <label style="font-size: 30px">Tất cả sách</label>
                    </div>
                    <div class="col-6 d-flex align-items-center justify-content-end">
                        <label style="margin-right: 10px; margin-top: 8px">Sắp xếp theo</label>
                        <select name="sortBook" id="sortBook" class="form-control custom-select" style="width: 40%;">
                            @if($type === 'allBook')
                                <option value="{{route('allBook')}}">Mặc định</option>
                                <option value="{{route('sortAllBook',['typeSort' => 'priceAsc'])}}">Giá thấp - cao</option>
                                <option value="{{route('sortAllBook',['typeSort' => 'priceDesc'])}}">Giá cao - thấp</option>
                            @elseif($type === 'bookOfCategory')
                                <option value="{{route('getBookByCategory',['categoryId' => $categoryId])}}">Mặc định</option>
                                <option value="{{route('sortBookOfCategory',['categoryId' => $categoryId, 'typeSort' => 'priceAsc'])}}">Giá thấp - cao</option>
                                <option value="{{route('sortBookOfCategory',['categoryId' => $categoryId, 'typeSort' => 'priceDesc'])}}">Giá cao - thấp</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="filter-container p-0 row">
                    @foreach($books as $book)
                        <div class="filtr-item col-sm-3" style="border: 1px solid rgb(233, 233, 233); text-align: center;">
                            <div style="padding: 30px">
                                <div class="book_image" style="width: 200px;height: 300px;margin: auto;display: flex;justify-content: center;align-items: center">
                                    <img src="{{asset('storage/'.$book->thumbnail)}}" style="max-width: 100%;max-height:100%;object-fit: cover">
                                </div>
                                <div class="book_info" style="height: 80px; margin-top: 20px">
                                    <h6 style="font-weight: bold" class="book_name"><a>{{$book->name}}</a></h6>
                                    <div class="rent_price" style="font-size: 16px;color: #fe4c50;font-weight: 600">{{number_format($book->price_rent, 0, ',', '.')}}VNĐ /1 tháng</div>
                                </div>
                                <a style="color: black" type="button" class="btn btn-block btn-primary" href="{{route('detail_book',['id'=>$book->id])}}">Chi tiết</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pagination" style="align-items: center; justify-content: center">
    <ul id="ulPagination">
        @for($i = 1; $i <= $books->lastPage(); $i++)
            <li>
                <a href="{{$books->url($i)}}">{{ $i }}</a>
            </li>
        @endfor
    </ul>
</div>
<script>
    document.getElementById('sortBook').addEventListener('change',function () {
        window.location.href = document.getElementById('sortBook').value
    })
</script>
@endsection
{{--@include('layout.footer')--}}
