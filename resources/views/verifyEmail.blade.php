<p>Hello {{$user->name}}</p>
<p><a href="{{url('/verify/email/'.$user->remember_token)}}">Bấm vào đây</a> để có thể kích hoạt tài khoản</p>
