@extends('layouts.inventory')

@section('content')
    <div class="container">

        <div class="">
            <h1 class="h1 text-bold text-primary">Detail Unit</h1>
        </div>
        <hr>

        <div class="mt-0 mb-4">
            <a href="{{ url('/units/create') }}" class="btn btn btn-primary ">New</a>

            <button type="button" class="btn btn-success mx-1" id="editButton">Edit</button>

            <a href="{{ url('/products/' . $unit->id) }}" class="btn btn-success mx-1 d-none" id="saveButton"
                onclick="event.preventDefault(); document.getElementById('data-form').submit();">Save</a>

            <a href="{{ url('/products') }}" class="btn px-3 btn-secondary mx-1">List</a>

            <form action={{ url('/products/delete/' . $unit->id) }} method="post" class="d-inline"
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
            <form action="{{ url('/units/' . $unit->id) }}" method="post" id="data-form">
                @csrf
                <input type="hidden" name="id" value="{{ $unit->id }}">
                <div class="card-header">
                    <h3 class="h1 text-center text-bold">Product Detail Info</h3>
                </div>
                <div class="card-body p-3">
                    <div class="form-group">
                        <label for="code">Code:</label>
                        <input type="text" class="form-control" name="code" value="{{ old('code', $unit->code) }}"
                            readonly>
                        @if ($errors->has('code'))
                            <small class="text-danger">{{ $errors->first('code') }}</small>
                        @endif
                    </div>
                    <div class="form-group my-3">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $unit->name) }}"
                            readonly>
                        @if ($errors->has('name'))
                            <small class="text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $("#editButton").click(function() {
            $("#editButton").addClass("d-none");
            $('#saveButton').removeClass("d-none");
            $('input[name="code"]').removeAttr("readonly");
            $('input[name="name"]').removeAttr("readonly");
        });

        var errors = {{ $errors->any() ? 1 : 0 }};
        if (errors) {
            $('#editButton').removeClass("d-none");
            $('#saveButton').addClass("d-none");
            $('input[name="code"]').removeAttr("readonly");
            $('input[name="name"]').removeAttr("readonly");
        }
    </script>
@endsection
