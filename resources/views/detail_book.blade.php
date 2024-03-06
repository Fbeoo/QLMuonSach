{{--@include('layout.header')--}}
{{--<x-sidebar/>--}}
@extends('layout.layout')

@section('title')
    {{$book->name}}
@endsection

@section('content')
<style>
    .loader-container {
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .loader {
        border: 16px solid #f3f3f3;
        border-top: 16px solid #3498db;
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .hidden {
        display: none;
    }


    .pull-right{
        float:right;
    }
    .pull-left{
        float:left;
    }
    #fbcomment{
        background:#fff;
        border: 1px solid #dddfe2;
        border-radius: 3px;
        color: #4b4f56;
        padding:50px;
    }
    .header_comment{
        font-size: 14px;
        overflow: hidden;
        border-bottom: 1px solid #e9ebee;
        line-height: 25px;
        margin-bottom: 24px;
        padding: 10px 0;
    }
    .sort_title{
        color: #4b4f56;
    }
    .sort_by{
        background-color: #f5f6f7;
        color: #4b4f56;
        line-height: 22px;
        cursor: pointer;
        vertical-align: top;
        font-size: 12px;
        font-weight: bold;
        vertical-align: middle;
        padding: 4px;
        justify-content: center;
        border-radius: 2px;
        border: 1px solid #ccd0d5;
    }
    .count_comment{
        font-weight: 600;
    }
    .body_comment{
        padding: 0 8px;
        font-size: 14px;
        display: block;
        line-height: 25px;
        word-break: break-word;
    }
    .avatar_comment{
        display: block;
    }
    .avatar_comment img{
        height: 48px;
        width: 48px;
    }
    .box_comment{
        display: block;
        position: relative;
        line-height: 1.358;
        word-break: break-word;
        border: 1px solid #d3d6db;
        word-wrap: break-word;
        background: #fff;
        box-sizing: border-box;
        cursor: text;
        font-family: Helvetica, Arial, sans-serif;
        font-size: 16px;
        padding: 0;
    }
    .box_comment textarea{
        min-height: 40px;
        padding: 12px 8px;
        width: 100%;
        border: none;
        resize: none;
    }
    .box_comment textarea:focus{
        outline: none !important;
    }
    .box_comment .box_post{
        border-top: 1px solid #d3d6db;
        background: #f5f6f7;
        padding: 8px;
        display: block;
        overflow: hidden;
    }
    .box_comment label{
        display: inline-block;
        vertical-align: middle;
        font-size: 11px;
        color: #90949c;
        line-height: 22px;
    }
    .box_comment button{
        margin-left:8px;
        background-color: #4267b2;
        border: 1px solid #4267b2;
        color: #fff;
        text-decoration: none;
        line-height: 22px;
        border-radius: 2px;
        font-size: 14px;
        font-weight: bold;
        position: relative;
        text-align: center;
    }
    .box_comment button:hover{
        background-color: #29487d;
        border-color: #29487d;
    }
    .box_comment .cancel{
        margin-left:8px;
        background-color: #f5f6f7;
        color: #4b4f56;
        text-decoration: none;
        line-height: 22px;
        border-radius: 2px;
        font-size: 14px;
        font-weight: bold;
        position: relative;
        text-align: center;
        border-color: #ccd0d5;
    }
    .box_comment .cancel:hover{
        background-color: #d0d0d0;
        border-color: #ccd0d5;
    }
    .box_comment img{
        height:16px;
        width:16px;
    }
    .box_result{
        margin-top: 24px;
    }
    .box_result .result_comment h4{
        font-weight: 600;
        white-space: nowrap;
        color: #365899;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
        line-height: 1.358;
        margin:0;
    }
    .box_result .result_comment{
        display:block;
        overflow:hidden;
        padding: 0;
    }
    .child_replay{
        border-left: 1px dotted #d3d6db;
        margin-top: 12px;
        list-style: none;
        padding:0 0 0 8px
    }
    .reply_comment{
        margin:12px 0;
    }
    .box_result .result_comment p{
        margin: 4px 0;
        text-align:justify;
    }
    .box_result .result_comment .tools_comment{
        font-size: 12px;
        line-height: 1.358;
    }
    .box_result .result_comment .tools_comment a{
        color: #4267b2;
        cursor: pointer;
        text-decoration: none;
    }
    .box_result .result_comment .tools_comment span{
        color: #90949c;
    }
    .body_comment .show_more, .body_comment .show_less{
        background: #3578e5;
        border: none;
        box-sizing: border-box;
        color: #fff;
        font-size: 14px;
        margin-top: 24px;
        padding: 12px;
        text-shadow: none;
        width: 100%;
        font-weight:bold;
        position: relative;
        text-align: center;
        vertical-align: middle;
        border-radius: 2px;
    }
</style>
<div class="card card-solid" style="width: 90%; margin: auto">
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

                <hr>

                @if(\Illuminate\Support\Facades\Auth::user())
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
                @else
                    <div>
                        <p style="font-size: 20px">Hãy <a href="{{route('login')}}"><span>đăng nhập</span></a> để mượn sách
                    </div>
                @endif

            </div>
        </div>
        <div class="row mt-4">
            <nav class="w-100">
                <div class="nav nav-tabs" id="product-tab" role="tablist">
                    <a class="nav-item nav-link active" id="product-desc-tab">Mô tả</a>
                </div>
            </nav>
            <div class="tab-content p-3" id="nav-tabContent" style="text-align: justify">
                <div class="tab-pane fade show active">{{$book->description}}</div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- Contenedor Principal -->
<div style="width: 90%; margin: 10px auto">
    <div class="col-md-12" id="fbcomment">
        <div class="header_comment">
            <div class="row">
                <div class="col-md-6 text-left">
{{--                    <span class="count_comment">264235 Comments</span>--}}
                </div>
            </div>
        </div>

        <div class="body_comment">
            @if(\Illuminate\Support\Facades\Auth::user())
                <form id="formCommentBook">
                    <div class="row" style="width: 80%; margin: auto">
                        <div class="avatar_comment col-md-1">
                            <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg" alt="avatar"/>
                        </div>
                        <div class="box_comment col-md-11">
                            <textarea name="content" class="commentar" placeholder="Nhập bình luận ..."></textarea>
                            <input id="bookId" type="hidden" name="bookId" value="{{$book->id}}">
                            <div class="box_post">
                                {{--                        <div class="pull-left">--}}
                                {{--                            <input type="checkbox" id="post_fb"/>--}}
                                {{--                            <label for="post_fb">Also post on Facebook</label>--}}
                                {{--                        </div>--}}
                                <div class="pull-right">
                                    {{--						  <span>--}}
                                    {{--							<img src="https://static.xx.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg" alt="avatar" />--}}
                                    {{--							<i class="fa fa-caret-down"></i>--}}
                                    {{--						  </span>--}}
                                    <a type="button" class="btn btn-primary" id="post">Bình luận</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
            <div class="row" style="width: 80%; margin: auto">
                <ul id="list_comment" class="col-md-12">
                    <!-- Start List Comment 1 -->
                    @foreach($book->commentBook as $comment)
                        <li class="box_result row">
                            <div class="avatar_comment col-md-1">
                                <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg" alt="avatar"/>
                            </div>
                            <div class="result_comment col-md-11">
                                <h4>{{$comment->user->name}}</h4>
                                <p>{{$comment->content}}</p>
                                {{--                            <div class="tools_comment">--}}
                                {{--                                <a class="like" href="#">Like</a>--}}
                                {{--                                <span aria-hidden="true"> · </span>--}}
                                {{--                                <a class="replay" href="#">Reply</a>--}}
                                {{--                                <span aria-hidden="true"> · </span>--}}
                                {{--                                <i class="fa fa-thumbs-o-up"></i> <span class="count">1</span>--}}
                                {{--                                <span aria-hidden="true"> · </span>--}}
                                {{--                                <span>26m</span>--}}
                                {{--                            </div>--}}
                                {{--                            <ul class="child_replay">--}}
                                {{--                                <li class="box_reply row">--}}
                                {{--                                    <div class="avatar_comment col-md-1">--}}
                                {{--                                        <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg" alt="avatar"/>--}}
                                {{--                                    </div>--}}
                                {{--                                    <div class="result_comment col-md-11">--}}
                                {{--                                        <h4>Sugito</h4>--}}
                                {{--                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's.</p>--}}
                                {{--                                        <div class="tools_comment">--}}
                                {{--                                            <a class="like" href="#">Like</a>--}}
                                {{--                                            <span aria-hidden="true"> · </span>--}}
                                {{--                                            <a class="replay" href="#">Reply</a>--}}
                                {{--                                            <span aria-hidden="true"> · </span>--}}
                                {{--                                            <i class="fa fa-thumbs-o-up"></i> <span class="count">1</span>--}}
                                {{--                                            <span aria-hidden="true"> · </span>--}}
                                {{--                                            <span>26m</span>--}}
                                {{--                                        </div>--}}
                                {{--                                        <ul class="child_replay"></ul>--}}
                                {{--                                    </div>--}}
                                {{--                                </li>--}}
                                {{--                            </ul>--}}
                            </div>
                        </li>
                    @endforeach
{{--                    <li class="box_result row">--}}
{{--                        <div class="avatar_comment col-md-1">--}}
{{--                            <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg" alt="avatar"/>--}}
{{--                        </div>--}}
{{--                        <div class="result_comment col-md-11">--}}
{{--                            <h4>Nath Ryuzaki</h4>--}}
{{--                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's.</p>--}}
{{--                            <div class="tools_comment">--}}
{{--                                <a class="like" href="#">Like</a>--}}
{{--                                <span aria-hidden="true"> · </span>--}}
{{--                                <a class="replay" href="#">Reply</a>--}}
{{--                                <span aria-hidden="true"> · </span>--}}
{{--                                <i class="fa fa-thumbs-o-up"></i> <span class="count">1</span>--}}
{{--                                <span aria-hidden="true"> · </span>--}}
{{--                                <span>26m</span>--}}
{{--                            </div>--}}
{{--                            <ul class="child_replay">--}}
{{--                                <li class="box_reply row">--}}
{{--                                    <div class="avatar_comment col-md-1">--}}
{{--                                        <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg" alt="avatar"/>--}}
{{--                                    </div>--}}
{{--                                    <div class="result_comment col-md-11">--}}
{{--                                        <h4>Sugito</h4>--}}
{{--                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's.</p>--}}
{{--                                        <div class="tools_comment">--}}
{{--                                            <a class="like" href="#">Like</a>--}}
{{--                                            <span aria-hidden="true"> · </span>--}}
{{--                                            <a class="replay" href="#">Reply</a>--}}
{{--                                            <span aria-hidden="true"> · </span>--}}
{{--                                            <i class="fa fa-thumbs-o-up"></i> <span class="count">1</span>--}}
{{--                                            <span aria-hidden="true"> · </span>--}}
{{--                                            <span>26m</span>--}}
{{--                                        </div>--}}
{{--                                        <ul class="child_replay"></ul>--}}
{{--                                    </div>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </li>--}}

{{--                    <!-- Start List Comment 2 -->--}}
{{--                    <li class="box_result row">--}}
{{--                        <div class="avatar_comment col-md-1">--}}
{{--                            <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yi/r/odA9sNLrE86.jpg" alt="avatar"/>--}}
{{--                        </div>--}}
{{--                        <div class="result_comment col-md-11">--}}
{{--                            <h4>Gung Wah</h4>--}}
{{--                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's.</p>--}}
{{--                            <div class="tools_comment">--}}
{{--                                <a class="like" href="#">Like</a>--}}
{{--                                <span aria-hidden="true"> · </span>--}}
{{--                                <a class="replay" href="#">Reply</a>--}}
{{--                                <span aria-hidden="true"> · </span>--}}
{{--                                <i class="fa fa-thumbs-o-up"></i> <span class="count">1</span>--}}
{{--                                <span aria-hidden="true"> · </span>--}}
{{--                                <span>26m</span>--}}
{{--                            </div>--}}
{{--                            <ul class="child_replay"></ul>--}}
{{--                        </div>--}}
{{--                    </li>--}}
                </ul>
{{--                <button class="show_more" type="button">Load 42 more comments</button>--}}
{{--                <button class="show_less" type="button" style="display:none"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...</button>--}}
            </div>
        </div>
    </div>
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
                <form id="formRentBook" action="{{route('confirmRentBook')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="bookId" value="{{$book->id}}">
                        <input type="hidden" name="typeRent" value="rentSingleBook">
                        <input type="hidden" name="numberBookAvailable" value="{{$numberBookAvailable}}">
                        <div class="row">
                            <div class="col-4">
                                <label for="inputProjectLeader">Mượn tới</label>
                            </div>
                            <div class="col-8">
                                <input type="text" name="dateRent"/>

                            </div>
                        </div>
                        <p id="dateRentError">

                        </p>
                        <div class="row">
                            <div class="col-4">
                                <label for="inputProjectLeader">Số lượng</label>
                            </div>
                            <div class="col-8">
                                <input type="number" name="quantityRent">
                            </div>
                        </div>
                        <p id="quantityRentError">

                        </p>
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
<div id="loaderContainer" class="loader-container hidden">
    <div class="loader"></div>
</div>
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
{{--@include('layout.footer')--}}
<script src="{{asset('dist/js/phongJs/commentBook.js')}}"></script>
<script src="{{asset('dist/js/phongJs/rentBook.js')}}"></script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
