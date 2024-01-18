@include('admin.layout.header')
@include('admin.layout.sidebar')
<p>{{var_dump(session()->get('admin'))}}</p>
@include('admin.layout.footer')
