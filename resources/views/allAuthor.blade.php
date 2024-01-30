@include('layout.header');
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
                    <label style="font-size: 30px">Tất cả tác giả</label>
                </div>
                <div class="filter-container p-0 row">
                    @foreach($authors as $author)
                        <div class="filtr-item col-sm-3" style="rgb(233, 233, 233); text-align: center;">
                            <div>
                                <a href="{{route('bookOfAuthor',['authorId'=>$author->id])}}" class="author_image" style="width: 200px; height: 200px; margin: auto; display: flex; justify-content: center;">
                                    <img src="{{asset('storage/'.$author->author_image)}}" style="cursor: pointer;border-radius: 100px;max-width: 100%; max-height: 100%; object-fit: cover">
                                </a>
                                <div class="book_info" style="height: 80px; margin-top: 20px">
                                    <h6 style="font-weight: bold" class="book_name"><a href="{{route('bookOfAuthor',['authorId'=>$author->id])}}" style="cursor: pointer; color: black">{{$author->author_name}}</a></h6>
                                </div>
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
        @for($i = 1; $i <= $authors->lastPage(); $i++)
            <li>
                <a href="{{ route('allAuthor', ['page' => $i]) }}">{{ $i }}</a>
            </li>
        @endfor
    </ul>
</div>
@include('layout.footer');
