<p>Hello {{$user->name}}</p>
<p><a href="{{route('redirectToPageResetPassword',['token'=>$user->remember_token])}}">Bấm vào đây</a> để có thể thay đổi mật khẩu</p>
