{{--@include('admin.layout.header')--}}
{{--@include('admin.layout.sidebar')--}}
@extends('layout.layout')

@section('title')
    Lịch trả sách
@endsection

@section('content')
<div  style="height: 800px; width: 80%; margin: auto">
    <div id='calendar' style="max-height: 100%; max-width: 100%"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

    });
</script>
<script src="{{asset('dist/js/phongJs/calendar.js')}}"></script>
{{--@include('admin.layout.footer')--}}
@endsection
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
