@extends('layouts.inventory')

@section('content')
    <div class="container">

        <div class="">
            <h1 class="h1 text-bold text-primary">Detail Product</h1>
        </div>
        <hr>

        <div class="mt-0 mb-4">
            <a href="{{ url('/products/create') }}" class="btn btn btn-primary ">New</a>

            @if ($errors->any())
                <button type="button" class="btn btn-success mx-1 d-none" id="editButton">Edit</button>
                <a href="{{ url('/products/' . $product->id) }}" class="btn btn-success mx-1 " id="saveButton" onclick="event.preventDefault();
                            document.getElementById('data-form').submit();">Save</a>
            @else
                <button type="button" class="btn btn-success mx-1" id="editButton">Edit</button>
                <a href="{{ url('/products/' . $product->id) }}" class="btn btn-success mx-1 d-none" id="saveButton"
                    onclick="event.preventDefault();
                            document.getElementById('data-form').submit();">Save</a>
            @endif


            <a href="{{ url('/products') }}" class="btn px-3 btn-secondary mx-1">List</a>

            <form action={{ url('/products/delete/' . $product->id) }} method="post" class="d-inline"
                onsubmit="return confirm('Do you want to delete?');">
                @csrf
                <button type="submit" class="btn btn-danger mx-1">Delete</button>
            </form>
        </div>

        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong class="h5">Can't Delete this product because this product has trascation in Stock In or
                    Stock Out.</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mx-auto my-5" style="width: 30rem;">
            <form action="{{ url('/products/' . $product->id) }}" method="post" id="data-form">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <div class="card-header">
                    <h3 class="h1 text-center text-bold">Product Detail Info</h3>
                </div>
                <div class="card-body p-3">
                    <div class="form-group">
                        <label for="code">Code:</label>
                        <input type="text" class="form-control" name="code" value="{{ old('code', $product->code)}}" readonly>
                        @if ($errors->has('code'))
                            <small class="text-danger">{{ $errors->first('code') }}</small>
                        @endif
                    </div>
                    <div class="form-group my-3">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}" readonly>
                        @if ($errors->has('name'))
                            <small class="text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                    <div class="form-group my-3">
                        <label for="description">Description:</label>
                        <textarea name="description" class="form-control" readonly>{{ old('description', $product->description) }}</textarea>
                        @if ($errors->has('description'))
                            <small class="text-danger">{{ $errors->first('description') }}</small>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $("#editButton").click(function() {
            $(this).addClass("d-none");
            $('#saveButton').removeClass("d-none");
            $('input[name="code"]').removeAttr("readonly");
            $('input[name="name"]').removeAttr("readonly");
            $('textarea[name="description"]').removeAttr("readonly");
        });

        var errors = {{ $errors->any() ? 1 : 0  }} ;
        if (errors) {
            $('input[name="code"]').removeAttr("readonly");
            $('input[name="name"]').removeAttr("readonly");
            $('textarea[name="description"]').removeAttr("readonly");
        }
    </script>
@endsection
