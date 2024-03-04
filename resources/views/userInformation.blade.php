{{--@include('layout.header')--}}
{{--<x-sidebar/>--}}
@extends('layout.layout')

@section('title')
    Thông tin cá nhân
@endsection

@section('content')
<div class="card" style="width: 50%; margin: auto; padding-top: 20px">
    <div class="card-body row">
        <div class="col-6" style="margin: auto">
            <form id="formEditUserProfile">
                <input type="hidden" name="userId" value="{{session()->get('user')->id}}">
            <div class="form-group">
                <label for="inputName">Họ và tên</label>
                <input type="text" name="name" class="form-control" value="{{session()->get('user')->name}}">
                <p id="nameError">

                </p>
            </div>
            <div class="form-group">
                <label for="inputEmail">Mail</label>
                <input type="email" name="mail" class="form-control" value="{{session()->get('user')->mail}}" disabled>
            </div>
            <div class="form-group">
                <label for="inputDob">Ngày sinh</label>
                <input type="text" name="dob" value="{{date('d/m/Y', strtotime(session()->get('user')->dob))}}" class="form-control"/>
                <p id="dobError">

                </p>
            </div>
            <div class="form-group">
                <label for="inputAddress">Địa chỉ</label>
                <input type="text" name="address" class="form-control" value="{{session()->get('user')->address}}">
                <p id="addressError">

                </p>
            </div>
            <div class="form-group">
                <div style="float: right">
                    <input id="saveChange" type="submit" class="btn btn-primary" value="Lưu thay đổi">
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('input[name="dob"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minYear: 1901,
            maxYear: parseInt(moment().format('YYYY'),10),
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
    });
</script>
<script src="{{asset('dist/js/phongJs/userProfile.js')}}"></script>
{{--@include('layout.footer')--}}
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
