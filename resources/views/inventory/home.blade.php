@extends('layouts.inventory')

@section('content')
    <div>
        <h2 class="text-primary">{{ $header }}</h2>
    </div>

    <div>
        <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col" class="text-end">Quantity</th>
                <th scope="col" class="text-center">Unit</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($data as $product)
                    <tr>
                        <td>{{ $product['id'] }}</td>
                        <td>{{ $product['code'] }}</td>
                        <td>{{ $product['name'] }}</td>
                        <td class="text-end">{{ number_format($product['qty']) }}</td>
                        <td class="text-center">{{ $product['unit'] }}</td>
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
