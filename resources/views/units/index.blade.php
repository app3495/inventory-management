@extends('layouts.inventory')

@section('content')
    <div>
        <h2 class="text-primary">Units</h2>
    </div>
    <hr>

    <div class="d-flex justify-content-start my-3">
        <a href="{{ url('/units/create') }}" class="btn btn btn-primary ">Add Unit</a>
    </div>

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong class="h5">Unit update successfully...</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('delete'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong class="h5">Unit delete successfully...</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong class="h5">Can't Delete this unit because this unit has trascation in Stock In or Stock Out.</strong>
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
                    <th style="width: 200px"></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $counter = 1;
                @endphp
                @foreach ($units as $unit)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $unit->code }}</td>
                        <td>{{ $unit->name }}</td>
                        <td class="text-center">
                            <a href="{{ url('/units/'. $unit->id ) }}" class="btn btn-sm btn-success">Detail</a>
                            <form action="{{ url('/units/delete/'. $unit->id ) }}" method="post" class="d-inline"
                                onsubmit="return confirm('Do you want to delete?');">
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
