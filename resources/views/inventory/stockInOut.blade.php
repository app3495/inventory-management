@extends('layouts.inventory')

@section('content')
    <div>
        <h2 class="h1 text-primary p-0 m-0">{{ $header }}</h2>
    </div>
    <hr>

    @if (Session::has('delete'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Succesfully deleted record!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="d-flex justify-content-start my-3 mb-3">
        <a href="{{ url("/inventory/create/$type_id") }}" class="btn btn btn-primary ">New {{ $header }}</a>
    </div>


    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Document No</th>
                    <th scope="col">Description</th>
                    <th scope="col" class="text-end">Total Quantity</th>
                    <th scope="col" class="text-end">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventories as $item)
                    <tr>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->doc_no }}</td>
                        <td>{{ $item->description }}</td>
                        <td class="text-end">{{ number_format($item->qty) }}</td>
                        <td class="text-end">{{ number_format($item->total) }}</td>
                        <td class="text-center">
                            <a href="{{ url("/inventory/update/$item->id") }}" class="btn btn-sm btn-success">Detail</a>
                            <a class="btn btn-sm btn-danger" onclick="{{ 'deleteConfirm(' . $item->id . ')' }}">
                                Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <form action={{ url('/inventory/delete/') }} method="post" id="delete-form" >
        @csrf
    </form>

    <div class="col-6 offset-3">
        {{ $inventories->links() }}
    </div>

    <script>
        function deleteConfirm(id) {
            if (confirm("Are you sure?")) {
                let form = document.getElementById('delete-form');
                route = form.getAttribute("action") + "/" + id;
                form.setAttribute('action', route);
                event.preventDefault();
                form.submit();
            }
        }
    </script>
@endsection
