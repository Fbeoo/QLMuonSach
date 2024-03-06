{{--@include('layout.header')--}}
{{--<x-sidebar/>--}}
@extends('layout.layout')

@section('title')
    {{$authorAndBookInfo[0]->authorInfo->author_name}}
@endsection

@section('content')
<div class="col-10" style="margin: auto; padding-top: 50px">
{{--    <div class="card card-primary">--}}
{{--        <div class="card-body">--}}
            <div class="row">
                <div class="col-md-4">
                    <div style="width: 200px; height: 200px; margin: auto">
                        <img style="width: 100%; height: 100%; border-radius: 200px" src="{{asset('storage/'.$authorAndBookInfo[0]->authorInfo->author_image)}}">
                    </div>
                </div>
                <div class="col-md-8">
                    <h1 style="font-size: 30px; font-weight: bold">{{$authorAndBookInfo[0]->authorInfo->author_name}}</h1>
                </div>
            </div>
{{--        </div>--}}
{{--    </div>--}}
</div>

<div class="col-10" style="margin: auto; padding-top: 50px">
    <div class="card card-primary">
        <div class="card-body">
            <div>
                <div class="filter-container p-0 row">
                    @foreach($authorAndBookInfo as $info)
                        <div class="filtr-item col-sm-3" style="border: 1px solid rgb(233, 233, 233); text-align: center;">
                            <div style="padding: 30px">
                                <div class="book_image" style="width: 200px;height: 300px;margin: auto;display: flex;justify-content: center;align-items: center">
                                    <img src="{{asset('storage/'.$info->book->thumbnail)}}" style="max-width: 100%;max-height:100%;object-fit: cover">
                                </div>
                                <div class="book_info" style="height: 80px; margin-top: 20px">
                                    <h6 style="font-weight: bold" class="book_name"><a>{{$info->book->name}}</a></h6>
                                    <div class="rent_price" style="font-size: 16px;color: #fe4c50;font-weight: 600">{{number_format($info->book->price_rent, 0, ',', '.')}}VNĐ /1 tháng</div>
                                </div>
                                <a type="button" class="btn btn-block btn-primary" href="{{route('detail_book',['id'=>$info->book->id])}}">Chi tiết</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{--@include('layout.footer')--}}
