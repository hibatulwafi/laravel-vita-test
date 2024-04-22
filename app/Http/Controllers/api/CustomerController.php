<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Address;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('addresses')->orderBy('created_at', 'desc')->get();
        return response()->json(['customers' => $customers], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string',
            'gender' => 'required|string|in:male,female',
            'birthdate' => 'required|date',
            'addresses.*.receiver_name' => 'required|string',
            'addresses.*.address_name' => 'required|string',
            'addresses.*.address_details' => 'required|string',
            'addresses.*.phone' => 'required|string',
            'addresses.*.postal_code' => 'required|string',
        ]);

        $faker = Faker::create();
        $photoUrl = $faker->imageUrl();
        $validatedData['photo'] = $photoUrl;

        $customer = Customer::create($validatedData);

        foreach ($request->addresses as $addressData) {
            $address = new Address($addressData);
            $customer->addresses()->save($address);
        }
        try {
            return response()->json(['customer' => $customer], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data.'], 500);
        }
    }

    public function show($id)
    {
        $customer = Customer::with('addresses')->findOrFail($id);
        return response()->json(['customer' => $customer], 200);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'gender' => 'required|string|in:male,female',
            'birthdate' => 'required|date',
            'addresses.*.receiver_name' => 'required|string',
            'addresses.*.address_name' => 'required|string',
            'addresses.*.address_details' => 'required|string',
            'addresses.*.phone' => 'required|string',
            'addresses.*.postal_code' => 'required|string',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($validatedData);

        foreach ($request->addresses as $addressData) {
            if (isset($addressData['id'])) {
                $address = $customer->addresses()->find($addressData['id']);
                if ($address) {
                    $address->update($addressData);
                } else {
                    $customer->addresses()->create($addressData);
                }
            } else {
                $customer->addresses()->create($addressData);
            }
        }

        return response()->json(['customer' => $customer], 200);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $query = Customer::query();

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');

            $query->where('name', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%")
                ->orWhere('phone', 'like', "%$keyword%")
                ->orWhere('gender', 'like', "%$keyword%")
                ->orWhere('birthdate', 'like', "%$keyword%");
        }

        $customers = $query->get();

        return response()->json(['customers' => $customers], 200);
    }

    public function showAddresses($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $addresses = $customer->addresses;

        return response()->json(['addresses' => $addresses], 200);
    }
}
