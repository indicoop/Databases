<?php

namespace Database\Seeders;

use App\Models\Cooperative;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Role::factory(4)->create();
        // User::factory(40)->create();
        // LoanType::factory(10)->create();
        // Loan::factory(100)->create();
        // Cooperative::factory(40)->create();

        ProductCategory::factory(10)->create();
    }
}
