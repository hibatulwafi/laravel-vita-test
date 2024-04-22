<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Address;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
  
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            $customer = Customer::create([
                'name' => $faker->name,
                'photo' => $faker->imageUrl(),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'gender' => $faker->randomElement(['male', 'female']),
                'birthdate' => $faker->date($format = 'Y-m-d', $max = '2000-01-01'),
            ]);

            Address::create([
                'customer_id' => $customer->id,
                'receiver_name' => $faker->name,
                'address_name' => $faker->streetAddress,
                'address_details' => $faker->secondaryAddress,
                'phone' => $faker->phoneNumber,
                'postal_code' => $faker->postcode,
            ]);
        }

        $this->command->info('50 data pelanggan dan alamatnya berhasil ditambahkan.');
    }
}
