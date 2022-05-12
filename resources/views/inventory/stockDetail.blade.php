@extends('layouts.inventory')

@section('content')
    <div>
        <h2 class="h1 text-primary p-0 m-0">Product </h2>
    </div>
    <hr>


    <div>
        <table class="h5">
            <tr>
                <td>Product Code:</td>
                <td>{{ $product['code'] }}</td>
            </tr>
            <tr>
                <td>Product Name:</td>
                <td>{{ $product['name'] }}</td>
            </tr>
            <tr>
                <td>Product Unit:</td>
                <td>{{ $product['unit'] }}</td>
            </tr>
        </table>

    </div>

    <div>
        <table class="table table-secondary">
            <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Document No</th>
                    <th scope="col">Description</th>
                    <th scope="col" class="text-end">In</th>
                    <th scope="col" class="text-end">Out</th>
                    <th scope="col" class="text-end">Balance</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $line)
                    <tr class={{ $line['type_id'] === 1 ? "table-success" : "table-danger"}}>
                        <td>{{ $line['date'] }}</td>
                        <td>{{ $line['doc_no'] }}</td>
                        <td>{{ $line['description'] }}</td>
                        <td class="text-end">{{ $line['type_id'] === 1 ? number_format($line['qty']) : 0 }}</td>
                        <td class="text-end">{{ $line['type_id'] === 2 ? number_format($line['qty']) : 0 }}</td>
                        <td class="text-end">{{ number_format($line['bal']) }}</td>
                        <td class="text-start">
                            <a href="{{ url("/inventory/update/" . $line['inventory_id']) }}" class="btn btn-sm btn-success">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-4 offset-4">
        {{ $data->links() }}
    </div>
@endsection
