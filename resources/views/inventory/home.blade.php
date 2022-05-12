@extends('layouts.inventory')

@section('content')
    <div>
        <h2 class="text-primary">{{ $header }}</h2>
    </div>
    <hr>

    <div>
        <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col" class="text-end">Quantity</th>
                <th scope="col" class="text-center">Unit</th>
                <th style="width: 40px"></th>
            </tr>
            </thead>
            <tbody>
                @foreach ($data as $product)
                    <tr>
                        <td>{{ $product['id']+1 }}</td>
                        <td>{{ $product['code'] }}</td>
                        <td>{{ $product['name'] }}</td>
                        <td class="text-end">{{ number_format($product['qty']) }}</td>
                        <td class="text-center">{{ $product['unit'] }}</td>
                        <td class="text-center">
                            <a href="{{ url("/inventory/stockBalance/detail/" . $product['product_id'] . "/" . $product['unit_id']) }}" class="btn btn-sm btn-success">Detail</a>
                        </td>
                     </tr>
                @endforeach

            </tbody>
        </table>

        <div class="col-6 offset-3">
            {{ $data->links() }}
        </div>
    </div>

    {{-- <div>
        updating
    </div> --}}

@endsection
