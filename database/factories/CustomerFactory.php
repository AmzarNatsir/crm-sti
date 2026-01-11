<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Customer::class;
    public function definition(): array
    {
        return [
            'uid' => 'CUST-' . strtoupper(Str::random(6)),
            'name' => $this->faker->name(),
            'company_name' => $this->faker->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'type' => $this->faker->randomElement(['lead', 'prospect', 'customer']),
            'source' => $this->faker->randomElement([
                'website',
                'referral',
                'cold_call',
                'expo',
                'whatsapp',
            ]),
            'created_by' => null,
        ];
    }
}
