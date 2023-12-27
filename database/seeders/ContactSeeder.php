<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

use App\Models\Company;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $companies = Company::all();
        // $faker = Faker::create();
        // $contacts=[];

        // foreach ($companies as $key => $company) {

        //     foreach (range(1, mt_rand(5,15)) as $index) {
        //         $contact =[
        //             'first_name' => $faker->firstName(),
        //             'last_name' => $faker->lastName(),
        //             'phone' => $faker->phoneNumber(),
        //             'email' => $faker->email(),
        //             'address' => $faker->address(),
        //             'company_id' => $company->id,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ];
        //         $contacts [] = $contact;    
        //     }
        // }

        // // create multiple not allowed so we use insert
        // Contact::insert($contacts);

        Contact::factory()->count(100)->create();
    }
}
