<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Models\InventoryLine;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    // middleware auth
    public function __construct()
    {
        $this->middleware('auth');
    }

    // get units page
    public function index()
    {
        $units = Unit::all();
        return view('units.index')->with('units', $units);
    }

    // get unit create page
    public function create()
    {
        return view('units.create');
    }

    // post method to store
    public function store(Request $request)
    {
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        };

        $newUnit = new Unit;
        $newUnit->code = $request->code;
        $newUnit->name = $request->name;
        $newUnit->save();

        return back()->with('success', true);
    }

    // get detail page
    public function detail($id)
    {
        $unit = Unit::find($id);

        return view('units.detail')->with('unit', $unit);
    }

    // post update page
    public function update($id, Request $request)
    {
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        };

        $newUnit = Unit::find($id);
        $newUnit->code = $request->code;
        $newUnit->name = $request->name;
        $newUnit->save();

        return back();
    }

    // delete unit

    public function destroy ($id) {
        if (InventoryLine::where('unit_id', $id)->first()) {
            return back()->with('error', true);
        }

        Unit::find($id)->delete();
        return redirect('/units')->with('delete', true);
    }

    // helper function for validation
    private function validateInput(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', Rule::unique('units')->ignore($request->id)],
            'name' => 'required',
        ]);

        return $validator;
    }
}
