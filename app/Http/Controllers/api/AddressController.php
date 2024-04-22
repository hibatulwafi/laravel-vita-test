<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $address = Address::create($request->all());
        return response()->json(['address' => $address], 201);
    }

    public function update(Request $request, $id)
    {
        $address = Address::findOrFail($id);
        $address->update($request->all());
        return response()->json(['address' => $address], 200);
    }

    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();
        return response()->json(null, 204);
    }
}
