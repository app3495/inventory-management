@extends('layouts.inventory')

@section('content')
    <div class="container">

        <div class="">
            <h1 class="h1 text-bold text-primary">New Product</h1>
        </div>
        <hr>

        <div class="">
            <a href="{{ url('/products') }}" class="btn btn btn-success text-white mx-1">Back To List</a>
        </div>


        @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong class="h5">Product create successfully...</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <div class="card mx-auto my-5" style="width: 30rem;">
            <form action="{{ url('/products/create') }}" method="post">
                @csrf
                <div class="card-header">
                    <h3 class="h1 text-center text-bold">New Products</h3>
                </div>
                <div class="card-body p-3">
                    <div class="form-group">
                        <label for="code">Code:</label>
                        <input type="text" class="form-control" name="code" value="{{ old('code') }}">
                        @if ($errors->has('code'))
                            <small class="text-danger">{{ $errors->first('code') }}</small>
                        @endif
                    </div>
                    <div class="form-group my-3">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <small class="text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                    <div class="form-group my-3">
                        <label for="description">Description:</label>
                        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                            <small class="text-danger">{{ $errors->first('description') }}</small>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Create</button>
                </div>

            </form>
        </div>
    </div>
@endsection
