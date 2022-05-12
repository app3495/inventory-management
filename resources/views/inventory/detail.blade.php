@extends('layouts.inventory')

@section('content')

    <div class="px-2">

        <div class="my-3">
            <h1 class="h1 text-bold text-primary"> {{ $header }} Detail Information</h1>
        </div>
        <hr>

        <div class="mt-0 mb-4">
            <a href="{{ url('/inventory/create/' . $inventory[0]->type_id) }}" class="btn btn btn-primary ">New</a>

            @if ($errors->any())
                <button class="btn btn-success mx-1 d-none" id="editButton">Edit</button>
                <a href="{{ url('/inventory/edit/' . $inventory[0]->inventory_id) }}" class="btn btn-success mx-1 "
                    id="saveButton" onclick="event.preventDefault();
                        document.getElementById('data-form').submit();">Save</a>
            @else
                <button class="btn btn-success mx-1" id="editButton">Edit</button>
                <a href="{{ url('/inventory/edit/' . $inventory[0]->inventory_id) }}" class="btn btn-success mx-1 d-none"
                    id="saveButton" onclick="event.preventDefault();
                        document.getElementById('data-form').submit();">Save</a>
            @endif


            @if ($header === 'Stock In')
                <a href="{{ url('/inventory/stockIn') }}" class="btn px-3 btn-secondary mx-1">List</a>
            @elseif ($header === 'Stock Out')
                <a href="{{ url('/inventory/stockOut') }}" class="btn px-3 btn-secondary mx-1">List</a>
            @endif

            <a class="btn btn-danger mx-1" onclick="{{ 'deleteConfirm(' . $inventory[0]->inventory_id . ')' }}">Delete</a>

            <form action={{ url('/inventory/delete/') }} method="post" id="delete-form">
                @csrf
            </form>
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


        <form action="{{ url('/inventory/update/' . $inventory[0]->inventory_id) }}" method="post" id="data-form">
            @csrf

            <input type="hidden" name="type_id" value="{{ $inventory[0]->type_id }}">

            <div class="form-group row mb-4">
                <label for="doc_no" class="col-sm-2 col-form-label">Document No :</label>
                <div class="col-sm-4">
                    <input type="text" name="doc_no" id="doc_no" class="form-control"
                        value="{{ old('doc_no', $inventory[0]->doc_no) }}" {{ $errors->any() ? '' : 'readonly' }}>
                    @if ($errors->has('doc_no'))
                        <p class="text-danger">{{ $errors->first('doc_no') }}</p>
                    @endif
                </div>

                <label for="date" class="col-sm-2 col-form-label">Date :</label>
                <div class="col-sm-4">
                    <input type="date" name="date" id="date" class="form-control"
                        value="{{ old('date', $inventory[0]->date) }}" {{ $errors->any() ? '' : 'readonly' }}
                        required>
                    @if ($errors->has('date'))
                        <p class="text-danger">{{ $errors->first('date') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-4">
                <label for="description" class="col-sm-2 col-form-label">Description :</label>
                <div class="col-sm-10">
                    <textarea type="text" name="description" id="description" class="form-control"
                        {{ $errors->any() ? '' : 'readonly' }}>{{ old('description', $inventory[0]->main_description) }}</textarea>
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
                    @if (old('code') !== null)
                        @for ($i = 1; $i <= count(old('code')); $i++)
                            <tr>
                                <td>
                                    <input list="productCode" name="code[{{ $i }}]"
                                        id="code[{{ $i }}]" class="form-control"
                                        onkeyup="getProductName({{ $i }})" value="{{ old("code.$i") }}"
                                        required>
                                </td>
                                <td>
                                    <input list="productName" name="name[{{ $i }}]"
                                        id="name[{{ $i }}]" class="form-control"
                                        value="{{ old("name.$i") }}" readonly>
                                </td>
                                <td class="text-end">
                                    <select name="unit[{{ $i }}]" id="unit[{{ $i }}]"
                                        class="form-select" required>
                                        <option>Choose units</option>
                                        @foreach ($units as $item)
                                            @if (old("unit.$i") == $item->id)
                                                <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                            @else
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-end">
                                    <input type="text" min="0" name="price[{{ $i }}]"
                                        id="price[{{ $i }}]" class="form-control text-end"
                                        onkeyup="calculate_total()" value="{{ old("price.$i") }}" required>
                                </td>
                                <td class="text-end">
                                    <input type="text" min="0" name="qty[{{ $i }}]"
                                        id="qty[{{ $i }}]" class="form-control text-end"
                                        onkeyup="calculate_total()" value="{{ old("qty.$i") }}" required>
                                </td>
                                <td class="text-end">
                                    <input type="text" name="amount[{{ $i }}]" id="amount[{{ $i }}]"
                                        class="form-control text-end" value="{{ old("amount.$i") }}" readonly>
                                </td>
                            </tr>
                        @endfor
                    @else
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($inventory as $item)
                            <tr>
                                <td>
                                    <input list="productCode" name="code[{{ $counter }}]"
                                        id="code[{{ $counter }}]" class="form-control" value="{{ $item->code }}"
                                        onkeyup="getProductName({{ $counter }})" readonly required>
                                </td>
                                <td>
                                    <input list="productName" name="name[{{ $counter }}]"
                                        id="name[{{ $counter }}]" class="form-control" value="{{ $item->name }}"
                                        readonly>

                                </td>
                                <td class="text-end">
                                    <select name="unit[{{ $counter }}]" id="unit[{{ $counter }}]"
                                        class="form-select" disabled>
                                        <option>Choose units</option>
                                        @foreach ($units as $unit)
                                            @if ($item->unit == $unit->code)
                                                <option value="{{ $unit->id }}" selected>{{ $unit->name }}</option>
                                            @else
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-end">
                                    <input type="text" name="price[{{ $counter }}]" id="price[{{ $counter }}]"
                                        class="form-control text-end" onkeyup="calculate_total()"
                                        value="{{ $item->price }}" readonly required>
                                </td>
                                <td class="text-end">
                                    <input type="text" name="qty[{{ $counter }}]" id="qty[{{ $counter }}]"
                                        class="form-control text-end" onkeyup="calculate_total()"
                                        value="{{ $item->qty }}" readonly>
                                </td>
                                <td class="text-end">
                                    <input type="text" name="amount[{{ $counter }}]" id="amount[{{ $counter }}]"
                                        class="form-control text-end" value="{{ number_format($item->total) }}" readonly>
                                </td>
                            </tr>
                            @php
                                $counter++;
                            @endphp
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">

                            @if ($errors->any())
                                <button class="add-row btn-sm btn-primary me-3" type="button">Add Row</button>
                                <button class="del-row btn-sm btn-danger" type="button">Del Row</button>
                            @else
                                <button class="add-row btn-sm btn-primary me-3 d-none" type="button">Add Row</button>
                                <button class="del-row btn-sm btn-danger d-none" type="button">Del Row</button>
                            @endif
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

                input_qty = parseInt(qty.replace(",", ""));
                input_price = parseInt(price.replace(",", ""));

                qty = (isNaN(input_qty) || input_qty < 0) ? 0 : input_qty;
                price = (isNaN(input_price) || input_price < 0) ? 0 : input_price;
                amount = qty * price;

                totalQty += qty;
                totalAmount += amount;

                document.getElementById("amount[" + i + "]").value = amount.toLocaleString('en-US');
            }

            document.getElementById('totalQty').innerHTML = totalQty.toLocaleString('en-US');
            document.getElementById('totalAmount').innerHTML = totalAmount.toLocaleString('en-US');
        };

        window.onload = calculate_total();

        //get product name
        function getProductName(i) {
            var searchCode = document.getElementById("code[" + i + "]").value;
            var data = {!! json_encode($products, JSON_HEX_TAG) !!};

            if (searchCode.length < 1) {
                document.getElementById("name[" + i + "]").value = "";
            } else {
                for (let product of data) {
                    if (product.code === searchCode) {
                        document.getElementById("name[" + i + "]").value = product.name;
                        break;
                    }
                }
            }
        }

        function deleteConfirm(id) {
            if (confirm("Are you sure?")) {
                let form = document.getElementById('delete-form');
                route = form.getAttribute("action") + "/" + id;
                form.setAttribute('action', route);
                event.preventDefault();
                form.submit();
            }
        }

        $(document).ready(function() {
            // add rows button
            $(".add-row").click(function() {
                let cellCount = $('#product_table tbody tr:first-child td').length;
                let count = $('#product_table tbody tr').length + 1;


                new_tr = $(document.createElement('tr'));

                for (i = 1; i <= cellCount; i++) {
                    new_td = $("#product_table tbody tr:first-child td:nth-child(" + i + ")").clone();

                    // new_td_data = new_td.find('input').length ?  new_td.find('input') : new_td.find('select');

                    if (new_td.find('input').length) {
                        new_td_data = new_td.find('input');
                        new_td_data.val("");
                    } else {
                        new_td_data = new_td.find('select');
                    }

                    if (i == 1) {
                        old_keyup_value = new_td_data.attr('onkeyup');
                        new_keyup_value = old_keyup_value.slice(0, -3) + "(" + count + ")";
                        new_td_data.attr('onkeyup', new_keyup_value);
                    }

                    old_td_name = new_td_data.attr('name');
                    new_td_value = old_td_name.slice(0, -3) + "[" + count + "]";
                    new_td_data.attr('name', new_td_value);

                    old_id = new_td_data.attr('id');
                    new_id_value = old_id.slice(0, -3) + "[" + count + "]";
                    new_td_data.attr('id', new_td_value);

                    new_tr.append(new_td);
                }

                $("table tbody").append(new_tr);
            });

            // delete row button
            $(".del-row").click(function() {
                var rowCount = $('#product_table tbody tr').length;

                if (rowCount <= 1) {
                    window.alert("You can't delete all rows in product table.");
                } else {
                    $("table tbody tr:last-child").remove();
                }

                calculate_total();
            })

            //edit button
            $("#editButton").click(function() {
                $(this).addClass("d-none");
                $("#saveButton").removeClass("d-none");
                $("#doc_no").removeAttr("readonly");
                $("#date").removeAttr("readonly");
                $("#description").removeAttr("readonly");

                const Counts = document.querySelectorAll("table tbody tr").length;

                for (i = 1; i <= Counts; i++) {
                    document.getElementById("code[" + i + "]").removeAttribute("readonly");
                    document.getElementById("unit[" + i + "]").removeAttribute("disabled");
                    document.getElementById("price[" + i + "]").removeAttribute("readonly");
                    document.getElementById("qty[" + i + "]").removeAttribute("readonly");
                }

                $(".add-row").removeClass("d-none");
                $(".del-row").removeClass("d-none");

            });

        })
    </script>


@endsection
