<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\InventoryLine;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // middleware auth
    public function __construct()
    {
        $this->middleware('auth');
    }

    // get product list page
    public function index()
    {
        $products = Product::all();
        return view('products.index')->with('products', $products);
    }

    // get product create form page
    public function create()
    {
        return view('products.create');
    }

    // store new product to db
    public function store(Request $request)
    {
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        };

        $newProduct = new Product;
        $newProduct->code = $request->code;
        $newProduct->name = $request->name;
        $newProduct->description = $request->description;
        $newProduct->save();

        return back()->with('success', true);
    }

    // get detail product page
    public function detail($id)
    {
        $product = Product::find($id);
        return view('products.detail')->with('product', $product);
    }

    // update detail product into db
    public function update($id, Request $request)
    {
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        };

        $product = Product::find($id);
        $product->code = $request->code;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->save();

        return back();
    }

    // delete detail product from db
    public function destroy($id)
    {
        if (InventoryLine::where('product_id', $id)->first()) {
            return back()->with('error', true);
        }

        Product::find($id)->delete();
        return redirect('/products')->with('delete', true);
    }

    // helper function for validation
    private function validateInput(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', Rule::unique('products')->ignore($request->id)],
            'name' => 'required',
            'description' => 'required',
        ]);

        return $validator;
    }
}
