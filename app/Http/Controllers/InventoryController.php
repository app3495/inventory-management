<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Price;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\InventoryLine;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // create section

    public function create($type_id)
    {
        $products = Product::all();
        $units = Unit::all();

        if ($type_id === "1") {
            $header = "Stock In";
        }
        elseif ($type_id === "2") {
            $header = "Stock Out";
        }

        return view('inventory.create', [
            'products' => $products,
            'units' => $units,
            'header' => $header,
            'type_id' => $type_id,
        ]);
    }

    public function createDb ($type_id, Request $request)
    {
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // save inventory into db
        $inventory = new Inventory;
        $inventory->date = $request->date;
        $inventory->type_id = $type_id;
        $inventory->description = $request->description;
        $inventory->doc_no = $request->doc_no;
        $inventory->save();

        // save inventory_lines into db
        for ($i = 1; $i <= count($request->code); $i++)
        {
            $product_id = Product::where('code', '=', $request->code[$i])->first();

            $inventory_line[$i] = new InventoryLine;
            $inventory_line[$i]->inventory_id = $inventory->id;
            $inventory_line[$i]->product_id = $product_id->id;
            $inventory_line[$i]->qty = $request->qty[$i];
            $inventory_line[$i]->unit_id = $request->unit[$i];
            $inventory_line[$i]->price = $request->price[$i];
            $inventory_line[$i]->save();
        }

        return back()->with(['success' => '1']);
    }

    // Read Section
    public function index(Request $request)
    {
        $data = DB::table('inventory_lines')
                        ->select('inventories.*', 'products.code', 'products.name', 'inventory_lines.qty',
                            'inventories.type_id','inventory_lines.product_id', 'inventory_lines.unit_id',
                            'inventory_lines.price', 'units.name as unit')
                        ->leftjoin ('inventories', 'inventory_lines.inventory_id', '=', 'inventories.id')
                        ->leftjoin ('products', 'inventory_lines.product_id', '=', 'products.id')
                        ->leftjoin ('units',  'inventory_lines.unit_id', '=', 'units.id')
                        ->orderBy('inventory_lines.product_id')
                        ->orderBy('inventory_lines.unit_id', 'desc')
                        ->get()
                        ->toArray();
        $res = [];

        foreach ($data as $item)
        {
            for ($i = 0; $i < count($data); $i++ )
            {
                if (! array_key_exists($i, $res))
                {
                    $res[$i] = [
                        'id' => $i,
                        'product_id' => $item->product_id,
                        'unit_id' => $item->unit_id,
                        'qty' => 0,
                        'code' => $item->code,
                        'name' => $item->name,
                        'unit' => $item->unit,
                    ];

                    if ($item->type_id === 1 )
                    {
                        $res[$i]['qty'] += $item->qty;
                    }

                    elseif ($item->type_id === 2 )
                    {
                        $res[$i]['qty'] -= $item->qty;
                    }

                    break;
                }

                if ( $res[$i]['product_id'] === $item->product_id && $res[$i]['unit_id'] === $item->unit_id )
                {
                    if ($item->type_id === 1 )
                    {
                        $res[$i]['qty'] += $item->qty;
                    }

                    elseif ($item->type_id === 2 )
                    {
                        $res[$i]['qty'] -= $item->qty;
                    }

                    break;
                }
            }
        }

        $result = $this->paginate($items = $res, $perPage = 10, null, ['path' => url('inventory/stockBalance')] );

        return view('inventory.home', ['data' => $result, 'id' => 0, 'header' => 'Stock Balance']);
    }

    public function stockIn(Request $request)
    {
        $data = $this->getInventory($request, 1);

        return view('inventory.stockInOut', [
            'inventories' => $data,
            'header' => "Stock In",
            'type_id' => 1,
        ]);
    }

    public function stockOut(Request $request)
    {
        $data = $this->getInventory($request, 2);

        return view('inventory.stockInOut', [
            'inventories' => $data,
            'header' => "Stock Out",
            'type_id' => 2,
        ]);
    }

    public function detail ($id) {
        $data = DB::table ('inventory_lines')
                    ->select ('inventories.doc_no', 'inventories.date', 'inventories.description as main_description',
                        'products.code', 'products.name', 'products.description as product_description', 'inventory_lines.qty',
                        'inventory_lines.price', 'units.code as unit', 'inventory_lines.inventory_id', 'inventories.type_id',
                        DB::raw('inventory_lines.qty * inventory_lines.price as total'))
                    ->leftjoin ('inventories', 'inventory_lines.inventory_id', '=', 'inventories.id')
                    ->leftjoin ('products', 'inventory_lines.product_id', '=', 'products.id')
                    ->leftjoin ('units',  'inventory_lines.unit_id', '=', 'units.id')
                    ->where('inventory_lines.inventory_id', '=', $id )
                    ->get();

        $products = Product::all();
        $units = Unit::all();


        if ($data[0]->type_id == 1) {
            $header = "Stock In";
        }elseif ($data[0]->type_id == 2) {
            $header = "Stock Out";
        }

        return view('inventory.detail',[
            'header' => $header,
            'inventory' => $data,
            'products' => $products,
            'units' => $units,
        ]);
    }

    public function delete ($id)
    {
        $type_id = Inventory::where('id', $id)->first()->type_id;

        Inventory::where('id', $id)->delete();
        InventoryLine::where('inventory_id', $id)->delete();

        if ($type_id === 1) return redirect('/inventory/stockIn')->with('delete', '1');
        if ($type_id === 2) return redirect('/inventory/stockOut')->with('delete', '1');
    }


    public function updateDb($id, Request $request)
    {
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $inventory = Inventory::find($id);
        $inventory->doc_no = $request->doc_no;
        $inventory->date = $request->date;
        $inventory->description = $request->description;
        $inventory->save();

        $old_lines = InventoryLine::select('id')->where('inventory_id', '=', $id)->get()->toArray();


        // save inventory_line
        for ($i=1; $i <= count($request->code); $i++)
        {
            $line_id = array_shift($old_lines);

            $product_id = Product::where('code', '=', $request->code[$i])->first();

            $inventory_line = $line_id ? InventoryLine::find($line_id['id']) : new InventoryLine;
            $inventory_line->inventory_id = $id;
            $inventory_line->product_id = $product_id->id;
            $inventory_line->qty = $request->qty[$i];
            $inventory_line->unit_id = $request->unit[$i];
            $inventory_line->price = $request->price[$i];
            $inventory_line->save();
        }

        // delete inventory_line
        for ($i=1; $i <= count($old_lines); $i++)
        {
            $removed_id = array_shift($old_lines);

            InventoryLine::where('id', $removed_id['id'])->delete();
        }

        return back();
    }

    public function stockBalanceDetail($product_id, $product_unit) {
        $data = InventoryLine::select('*', 'units.name as unit', 'products.code as code',
                                'products.name as name', 'inventories.description as description')
                            ->where('product_id', '=',  $product_id)
                            ->where('unit_id', '=', $product_unit)
                            ->leftJoin('inventories', 'inventory_lines.inventory_id', '=', 'inventories.id' )
                            ->leftJoin('products', 'inventory_lines.product_id', '=', 'products.id')
                            ->leftJoin('units', 'inventory_lines.unit_id', '=', 'units.id')
                            ->get()
                            ->toArray();

        $bal = 0;
        $count = count($data);
        for ($i=0; $i < $count; $i++) {
            if ($data[$i]['type_id'] === 1) {
                $bal += $data[$i]['qty'];
            }
            elseif ($data[$i]['type_id'] === 2) {
                $bal -= $data[$i]['qty'];
            }
            $data[$i]['bal'] = $bal;
        }

        $product["code"] = $data[0]['code'];
        $product["name"] = $data[0]['name'];
        $product["unit"] = $data[0]['unit'];

        $result = $this->paginate($items = $data, $perPage = 10, null,
            ['path' => url("inventory/stockBalance/detail/$product_id/$product_unit")] );


        return view('inventory.stockDetail')->with([
            'data' => $result,
            'product' => $product,
        ]);
    }

    private function getInventory(Request $request, $id)
    {
        $data = DB::table('inventory_lines')
                        ->select( 'inventories.id','inventories.date','inventories.doc_no',
                            'inventories.description', 'inventory_lines.inventory_id',
                            DB::raw('SUM(inventory_lines.qty) as qty'),
                            DB::raw('SUM(inventory_lines.qty * inventory_lines.price) as total'),)
                        ->leftjoin ('inventories', 'inventory_lines.inventory_id', '=', 'inventories.id')
                        ->groupBy ('inventory_lines.inventory_id')
                        ->where ('inventories.type_id', '=', $id )
                        ->orderBy ('inventories.created_at', 'desc')
                        ->orderBy('inventory_lines.id', 'desc')
                        ->paginate(10);
        $data->appends($request->all());
        return $data;
    }

    private function validateInput(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doc_no' => 'required',
            'date' => 'required',
            'description' => 'required',
            'code.*' => 'required|exists:App\Models\Product,code',
            'price.*' => 'required|numeric|gte:0',
            'unit.*' => 'required|numeric|gt:0',
            'qty.*' => 'required|numeric|gt:0',
        ]);

        return $validator;
    }


    private function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
