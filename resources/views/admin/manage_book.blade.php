@include('admin.layout.header')
@include('admin.layout.sidebar')
<a type="button" class="btn btn-block btn-success" style="margin-left: auto; width: 10%" href="{{route('addBookPage')}}">Thêm mới</a>
<div class="card-body">
    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4"><div class="row"><div class="col-sm-12 col-md-6"></div><div class="col-sm-12 col-md-6"></div></div><div class="row"><div class="col-sm-12"><table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                    <thead>
                    <tr><th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Id</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Book Name</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Thumbnail</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Year Publish</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">Price Rent</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Weight</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Total Page</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Quantity</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Status</th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">Action</th></tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                            <tr class="odd">
                            <td class="dtr-control sorting_1" tabindex="0">{{$book->id}}</td>
                            <td>{{$book->name}}</td>
                            <td style="width: 100px; height: 100px">
                                <img src="{{asset('dist/img/'.$book->thumbnail)}}" style="max-width: 100%">
                            </td>
                            <td>{{$book->year_publish}}</td>
                            <td>{{$book->price_rent}}</td>
                            <td>{{$book->weight}}</td>
                            <td>{{$book->total_page}}</td>
                            <td>{{$book->quantity}}</td>
                            @if($book->status === 1)
                                <td style="color: green">Available</td>
                            @endif
                            @if($book->status === 0)
                                <td style="color:red">Lock</td>
                            @endif
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning">Action</button>
                                    <button type="button" class="btn btn-warning dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="">
                                        <a class="dropdown-item" href="{{route('editBookPage',['bookId'=>$book->id])}}">Change</a>
                                        @if($book->status === 1)
                                            <form action="{{route('lockBook',['bookId' => $book->id])}}" method="POST">
                                                @method('put')
                                                @csrf
                                                <input type="submit" class="dropdown-item" value="Lock">
                                            </form>
                                        @else
                                            <form action="{{route('unlockBook',['bookId' => $book->id])}}" method="POST">
                                                @method('put')
                                                @csrf
                                                <input type="submit" class="dropdown-item" value="Unlock">
                                            </form>
                                        @endif

                                    </div>
                                </div>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    </div>
@include('admin.layout.footer')
