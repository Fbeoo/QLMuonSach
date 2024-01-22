@include('admin.layout.header')
@include('admin.layout.sidebar')
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">General</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body" style="display: block;">
        <form action="{{route('editBook')}}" method="POST">
            @method('put')
            @csrf
            <input type="hidden" value="{{$book->id}}" name="bookId">
            <div class="form-group">
                <label for="exampleInputFile"Book Image</label>
                <div class="input-group">
                    <div class="custom-file" style="display: table">
                        <input type="hidden" name="bookImage" id="bookImage">
                        <input type="file" id="imageInput">
                        <img id="previewImage" src="{{asset('dist/img/'.$book->thumbnail)}}" alt="Preview Image" style="width: 5%">
                    </div>
                </div>
            </div>
            <div class="form-group">
                @error('bookName')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputName">Book Name</label>
                <input type="text" id="inputName" class="form-control" value="{{$book->name}}" name="bookName">
            </div>
            <div class="form-group">
                @error('yearPublish')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputClientCompany">Year Publish</label>
                <input type="text" id="inputClientCompany" class="form-control" value="{{$book->year_publish}}" name="yearPublish">
            </div>
            <div class="form-group">
                @error('priceRent')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputProjectLeader">Price Rent</label>
                <input type="text" id="inputProjectLeader" class="form-control" value="{{$book->price_rent}}" name="priceRent">
            </div>
            <div class="form-group">
                @error('weight')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputProjectLeader">Weight</label>
                <input type="text" id="inputProjectLeader" class="form-control" value="{{$book->weight}}" name="weight">
            </div>
            <div class="form-group">
                @error('totalPage')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputProjectLeader">Total Page</label>
                <input type="text" id="inputProjectLeader" class="form-control" value="{{$book->total_page}}" name="totalPage">
            </div>
            <div class="form-group">
                @error('quantity')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputProjectLeader">Quantity</label>
                <input type="text" id="inputProjectLeader" class="form-control" value="{{$book->quantity}}" name="quantity">
            </div>
            <div class="form-group" style="display: inline-grid">
                @error('categoryChildren')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputStatus">Category</label>
                <div style="display: inline-flex">
                    <select id="inputCategoryParent" class="form-control custom-select" style="width: 500px">
                        @foreach($categoryParents as $categoryParent)
                            @if($categoryOfBook->category_parent_id === $categoryParent->id)
                                <option selected id="{{$categoryParent->id}}">{{$categoryParent->category_name}}</option>
                            @else
                                <option id="{{$categoryParent->id}}">{{$categoryParent->category_name}}</option>
                            @endif

                        @endforeach
                    </select>
                    <select id="inputCategoryChildren" class="form-control custom-select" name="categoryChildren">
                        @foreach($categoryChildren as $categoryChild)
                            @if($categoryOfBook->id === $categoryChild->id)
                                <option value="{{$categoryChild->id}}" selected id="{{$categoryChild->id}}">{{$categoryChild->category_name}}</option>
                            @else
                                <option value="{{$categoryChild->id}}" id="{{$categoryChild->id}}">{{$categoryChild->category_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                @error('bookDescription')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputDescription">Project Description</label>
                <textarea id="inputDescription" class="form-control" rows="4" name="bookDescription">{{$book->description}}</textarea>
            </div>
            <input type="submit" value="Save change">
        </form>
    </div>
    <!-- /.card-body -->
</div>
@include('admin.layout.footer')
<script src="{{asset('dist/js/phongJs/editBook.js')}}"></script>
