<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Deal;
use App\Models\DealPrice;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Admin::create([
            "email"=>"admin@admin.com",
            "password"=>"123456"
        ]);
    }
}
