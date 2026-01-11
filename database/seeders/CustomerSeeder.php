<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user sales (asumsi role = sales)
        $salesUsers = User::role('sales')->pluck('id');

        if ($salesUsers->isEmpty()) {
            $this->command->warn(
                'No users with role "sales" found. Customers will not be assigned.'
            );
        }

        Customer::factory()
            ->count(50)
            ->create([
                'created_by' => $salesUsers->random() ?? null,
            ]);

        // $customers = [
        //     [
        //         'uid' => 'CUST-' . strtoupper(Str::random(6)),
        //         'name' => 'PT Sumber Makmur',
        //         'company' => 'PT Sumber Makmur',
        //         'email' => 'info@sumbermakmur.co.id',
        //         'phone' => '081234567890',
        //         'type' => 'lead',
        //         'source' => 'website',
        //     ],
        //     [
        //         'uid' => 'CUST-' . strtoupper(Str::random(6)),
        //         'name' => 'CV Tani Jaya',
        //         'company' => 'CV Tani Jaya',
        //         'email' => 'admin@tanijaya.id',
        //         'phone' => '081298765432',
        //         'type' => 'prospect',
        //         'source' => 'referral',
        //     ],
        //     [
        //         'uid' => 'CUST-' . strtoupper(Str::random(6)),
        //         'name' => 'UD Maju Bersama',
        //         'company' => 'UD Maju Bersama',
        //         'email' => 'contact@majubersama.id',
        //         'phone' => '082112223333',
        //         'type' => 'customer',
        //         'source' => 'cold_call',
        //     ],
        //     [
        //         'uid' => 'CUST-' . strtoupper(Str::random(6)),
        //         'name' => 'PT Agro Sejahtera',
        //         'company' => 'PT Agro Sejahtera',
        //         'email' => 'hello@agrosejahtera.co.id',
        //         'phone' => '085677889900',
        //         'type' => 'customer',
        //         'source' => 'expo',
        //     ],
        // ];

        // foreach ($customers as $data) {
        //     Customer::create([
        //         'uid' => $data['uid'],
        //         'name' => $data['name'],
        //         'company_name' => $data['company'],
        //         'email' => $data['email'],
        //         'phone' => $data['phone'],
        //         'type' => $data['type'],
        //         'source' => $data['source'],
        //         'created_by' => $salesUsers->random() ?? null,
        //     ]);
        // }

        // // Tambahan dummy random (20 data)
        // Customer::factory()->count(20)->create([
        //     'created_by' => $salesUsers->random() ?? null,
        // ]);


    }
}
