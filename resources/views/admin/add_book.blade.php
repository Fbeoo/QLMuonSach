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
        <form action="{{route('addBook')}}" method="POST">
            @csrf
            <input type="hidden" name="bookId">
            <div class="form-group">
                @error('bookImage')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="exampleInputFile"Book Image</label>
                <div class="input-group">
                    <div class="custom-file" style="display: table">
                        <input type="hidden" name="bookImage" id="bookImage">
                        <input type="file" id="imageInput">
                        <img id="previewImage" src="{{asset('dist/img/'.old('bookImage'))}}" alt="Preview Image" style="width: 5%">
                    </div>
                </div>
            </div>
            <div class="form-group">
                @error('bookName')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputName">Book Name</label>
                <input type="text" id="inputName" class="form-control" name="bookName" value="{{ old('bookName') }}">
            </div>
            <div class="form-group">
                @error('yearPublish')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputClientCompany">Year Publish</label>
                <input type="text" id="inputClientCompany" class="form-control" name="yearPublish" value="{{ old('yearPublish') }}">
            </div>
            <div class="form-group">
                @error('priceRent')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputProjectLeader">Price Rent</label>
                <input type="text" id="inputProjectLeader" class="form-control" name="priceRent" value="{{ old('priceRent') }}">
            </div>
            <div class="form-group">
                @error('weight')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputProjectLeader">Weight</label>
                <input type="text" id="inputProjectLeader" class="form-control" name="weight" value="{{ old('weight') }}">
            </div>
            <div class="form-group">
                @error('totalPage')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputProjectLeader">Total Page</label>
                <input type="text" id="inputProjectLeader" class="form-control" name="totalPage" value="{{ old('totalPage') }}">
            </div>
            <div class="form-group">
                @error('quantity')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputProjectLeader">Quantity</label>
                <input type="text" id="inputProjectLeader" class="form-control" name="quantity" value="{{ old('quantity') }}">
            </div>
            <div class="form-group" style="display: inline-grid">
                @error('categoryChildren')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputStatus">Category</label>
                <div style="display: inline-flex">
                    <select id="inputCategoryParent" class="form-control custom-select" style="width: 500px">
                        <option selected>Select one</option>
                        @foreach($categoryParents as $categoryParent)
                            <option id="{{$categoryParent->id}}">{{$categoryParent->category_name}}</option>
                        @endforeach
                    </select>
                    <select id="inputCategoryChildren" class="form-control custom-select" name="categoryChildren">

                    </select>
                </div>
            </div>
            <div class="form-group">
                @error('bookDescription')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label for="inputDescription">Project Description</label>
                <textarea id="inputDescription" class="form-control" rows="4" name="bookDescription">{{ old('bookDescription') }}</textarea>
            </div>
            <input type="submit" value="Add">
        </form>
    </div>
    <!-- /.card-body -->
</div>
@include('admin.layout.footer')
<script src="{{asset('dist/js/phongJs/editBook.js')}}"></script>

