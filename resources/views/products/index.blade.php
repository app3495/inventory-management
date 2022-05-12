@extends('layouts.inventory')

@section('content')
    <div>
        <h2 class="text-primary">Products</h2>
    </div>
    <hr>

    <div class="d-flex justify-content-start my-3">
        <a href="{{ url('/products/create') }}" class="btn btn btn-primary ">Add Product</a>
    </div>

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong class="h5">Product update successfully...</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('delete'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong class="h5">Product delete successfully...</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong class="h5">Can't Delete this product because this product has trascation in Stock In or Stock Out.</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th style="width: 200px"></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $counter = 1;
                @endphp
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td class="text-center">
                            <a href="{{ url('/products/' . $product->id) }}" class="btn btn-sm btn-success">Detail</a>
                            <form action={{ url('/products/delete/' . $product->id) }} method="post"
                                class="d-inline" onsubmit="return confirm('Do you want to delete?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
