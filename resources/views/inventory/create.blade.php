@extends('layouts.inventory')

@section('content')

<div class="px-2">

    <div class="mt-0 mb-4">

        @if ($header === "Stock In")
            <a href="{{ url("/inventory/stockIn") }}" class="btn btn btn-success text-white mx-1">Back To List</a>
         @elseif ($header === "Stock Out")
            <a href="{{ url("/inventory/stockOut") }}" class="btn btn btn-success text-white mx-1">Back To List</a>
         @endif

        <a href="{{ url('/inventory/create/'. $type_id)}}" class="btn btn btn-primary mx-1"
            onclick="event.preventDefault();
                document.getElementById('data-form').submit();">Save & Submit</a>
    </div>
    <hr>

    <div class="my-3">
        <h1 class="h1 text-bold text-center text-primary"> {{ $header }} Detail Information</h1>
    </div>

    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong class="h5">{{ $header }} create success...</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif


        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ol>

                    @foreach ($errors->all() as $error)
                        <li class="text-danger">{{ $error }} </li>
                    @endforeach
                </ol>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


    <form action="{{ url('/inventory/create/'. $type_id)}}" method="post" id="data-form">
        @csrf
        <div class="form-group row mb-4" >
            <label for="doc_no" class="col-sm-2 col-form-label">Document No :</label>
            <div class="col-sm-4">
                <input type="text" name="doc_no" id="doc_no" class="form-control" value="{{ old('doc_no') }}" required  >
                @if ($errors->has('doc_no'))
                    <p class="text-danger">{{ $errors->first('doc_no') }}</p>
                @endif
            </div>

            <label for="date" class="col-sm-2 col-form-label" >Date :</label>
            <div class="col-sm-4">
                <input type="date" name="date" id="date" class="form-control" value="{{ old('date') }}" required>
                @if ($errors->has('date'))
                    <p class="text-danger">{{ $errors->first('date') }}</p>
                @endif
            </div>
        </div>

        <div class="form-group row mb-4">
            <label for="description" class="col-sm-2 col-form-label">Description :</label>
            <div class="col-sm-10">
                <textarea type="text" name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                    <p class="text-danger">{{ $errors->first('description') }}</p>
                @endif
            </div>
        </div>

        <div>
            <h2> Detail Product Table</h2>
        </div>

        <datalist id="productCode">
            @foreach ($products as $item)
                <option value="{{ $item->code }}">
            @endforeach
        </datalist>
        <datalist id="productName">
            @foreach ($products as $item)
                <option value="{{ $item->name }}">
            @endforeach
        </datalist>

        <table class="table table-hover mt-3" id="product_table">
            <thead>
                <tr class="text-center">
                    <th scope="col" style="width : 10%;">Product Code</th>
                    <th scope="col" style="width : 20%;">Product Name</th>
                    <th scope="col" style="width : 15%">Unit</th>
                    <th scope="col" style="width : 15%">Price</th>
                    <th scope="col" style="width : 15%">Quantity</th>
                    <th scope="col" style="width : 15%">Amount</th>
                </tr>
            </thead>

            <tbody>
                @if (old ("code") !== null)
                    @for ($i = 1; $i <= count(old("code")) ; $i++)
                        <tr>
                            <td>
                                <input list="productCode" name="code[{{ $i }}]"  id="code[{{ $i }}]" class="form-control" onkeyup="getProductName({{ $i }})" value="{{ old("code.$i") }}" required>
                            </td>
                            <td>
                                <input list="productName" name="name[{{$i}}]" id="name[{{$i}}]" class="form-control" value="{{ old("name.$i") }}" readonly>
                            </td>
                            <td class="text-end">
                                <select name="unit[{{$i}}]" id="unit[{{$i}}]" class="form-select" >
                                    <option>Choose units</option>
                                    @foreach ($units as $item)
                                        @if ( old("unit.$i") ==  $item->id )
                                            <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                        @else
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-end">
                                <input type="number" min="0" name="price[{{$i}}]" id="price[{{$i}}]" class="form-control text-end"
                                    onkeyup="calculate_total()" value="{{ old("price.$i") }}" required>
                            </td>
                            <td class="text-end">
                                <input type="number" min="0" name="qty[{{$i}}]" id="qty[{{$i}}]" class="form-control text-end" onkeyup="calculate_total()" value="{{ old("qty.$i") }}" required >
                            </td>
                            <td class="text-end">
                                <input type="text" name="amount[{{$i}}]" id="amount[{{$i}}]" class="form-control text-end" value="{{ old("amount.$i") }}" readonly>
                            </td>
                        </tr>

                    @endfor
                @else
                    <tr>
                        <td>
                            <input list="productCode" name="code[1]"  id="code[1]" class="form-control" required onkeyup="getProductName(1)">
                        </td>
                        <td>
                            <input list="productName" name="name[1]" id="name[1]" class="form-control" readonly>
                        </td>
                        <td class="text-end">
                            <select name="unit[1]" id="unit[1]" class="form-select" required>
                                <option selected>Choose units</option>
                                @foreach ($units as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="text-end">
                            <input type="number" min="0" name="price[1]" id="price[1]" class="form-control text-end" onkeyup="calculate_total()" required>
                        </td>
                        <td class="text-end">
                            <input type="number" min="0" name="qty[1]" id="qty[1]" class="form-control text-end" onkeyup="calculate_total()" required >
                        </td>
                        <td class="text-end">
                            <input type="text" name="amount[1]" id="amount[1]" class="form-control text-end" readonly>
                        </td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <button class="add-row btn-sm btn-primary me-3" type="button">Add Row</button>
                        <button class="del-row btn-sm btn-danger" type="button">Del Row</button>
                    </td>
                    <td colspan="2" class="text-end fs-3">Total</td>
                    <td class="text-end fs-3" id="totalQty"></td>
                    <td class="text-end fs-3" id="totalAmount"></td>
                </tr>
            </tfoot>
        </table>
    </form>

</div>

<script>

function calculate_total() {
    const Counts = document.querySelectorAll("table tbody tr").length;
    totalQty = 0;
    totalAmount = 0;

    for (i = 1; i <= Counts; i++) {
        qty = document.getElementById("qty[" + i + "]").value;
        price = document.getElementById("price[" + i + "]").value;

        input_qty = parseInt (qty.replace("," , "" ));
        input_price = parseInt (price.replace("," , "" ));

        qty = (isNaN(input_qty) || input_qty < 0) ? 0 : input_qty;
        price = (isNaN(input_price) || input_price <0) ? 0 : input_price;
        amount = qty * price;

        totalQty +=  qty;
        totalAmount += amount;

        document.getElementById("amount[" + i + "]").value = amount.toLocaleString('en-US');
    }

    document.getElementById('totalQty').innerHTML = totalQty.toLocaleString('en-US');
    document.getElementById('totalAmount').innerHTML = totalAmount.toLocaleString('en-US');
};

window.onload = calculate_total();
const data = {!! json_encode($products, JSON_HEX_TAG) !!};

//get product name
function getProductName(i) {
    var searchCode = document.getElementById("code["+ i  +"]").value;

    if (searchCode.length < 1) {
        document.getElementById("name["+ i  +"]").value = "";
    } else {
        for (let product of data) {
            if (product.code === searchCode) {
                document.getElementById("name["+ i  +"]").value = product.name;
                break;
            }
        }
    }
}


$(document).ready(function(){
    // add rows button
    $(".add-row").click(function() {
        let cellCount = $('#product_table tbody tr:first-child td').length;
        let count = $('#product_table tbody tr').length + 1;


        new_tr = $(document.createElement('tr'));

        for (i = 1; i <= cellCount; i++) {
            new_td = $("#product_table tbody tr:first-child td:nth-child(" + i + ")").clone();

            if (new_td.find('input').length){
                new_td_data = new_td.find('input');
                new_td_data.val("");
            } else {
                new_td_data = new_td.find('select');
            }


            if (i == 1) {
                old_keyup_value = new_td_data.attr('onkeyup');
                new_keyup_value = old_keyup_value.slice(0, -3) + "("+ count +")";
                new_td_data.attr('onkeyup', new_keyup_value);
            }

            old_td_name = new_td_data.attr('name');
            new_td_value = old_td_name.slice(0, -3) + "["+ count +"]";
            new_td_data.attr('name', new_td_value);

            old_id = new_td_data.attr('id');
            new_id_value = old_id.slice(0, -3) + "["+ count +"]";
            new_td_data.attr('id', new_td_value);

            new_tr.append(new_td);
        }

        $("table tbody").append(new_tr);
    });

    // delete row button
    $(".del-row").click(function (){
        var rowCount = $('#product_table tbody tr').length;

        if (rowCount <= 1) {
            window.alert("You can't delete all rows in product table.");
        } else {
            $("table tbody tr:last-child").remove();
        }
    })
})

</script>
@endsection

