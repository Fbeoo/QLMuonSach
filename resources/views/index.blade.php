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
<div class="col-10" style="margin: auto; padding-top: 50px">
    <div class="card card-primary">
        <div class="card-body">
            <div>
                <div style="position: relative; padding-bottom: 30px">
                    <label style="font-size: 30px">Tác giả</label>
                    <a href="{{route('allAuthor')}}" style="color:black ;position: absolute; right: 0px; cursor: pointer;font-size: 18px">Xem thêm
                        <i class="fas fa-chevron-right"></i></a>
                </div>
                <div class="filter-container p-0 row">
                    @foreach($authors as $author)
                        <div class="filtr-item col-sm-2" style="rgb(233, 233, 233); text-align: center;">
                            <div>
                                <a href="{{route('bookOfAuthor',['authorId'=>$author->id])}}" class="author_image" style="width: 200px; height: 200px; margin: auto; display: flex; justify-content: center;">
                                    <img src="{{asset('storage/'.$author->author_image)}}" style="cursor: pointer;border-radius: 100px;max-width: 100%; max-height: 100%; object-fit: cover">
                                </a>
                                <div class="book_info" style="height: 80px; margin-top: 20px">
                                    <h6 style="font-weight: bold" class="book_name"><a href="{{route('bookOfAuthor',['authorId'=>$author->id])}}" style="cursor: pointer">{{$author->author_name}}</a></h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-10" style="margin: auto; padding-top: 50px; padding-bottom: 100px">
    <div class="card card-primary">
        <div class="card-body">
            <div>
                <div style="position: relative; padding-bottom: 30px">
                    <label style="font-size: 30px">Một số sách</label>
                    <a href="{{route('allBook')}}" style="color:black ;position: absolute; right: 0px; cursor: pointer;font-size: 18px">Xem thêm
                        <i class="fas fa-chevron-right"></i></a>
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
                                <a type="button" class="btn btn-block btn-primary" href="{{route('detail_book',['id'=>$book->id])}}">Chi tiết</a>
                            </div>
                            </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@include('layout.footer')
