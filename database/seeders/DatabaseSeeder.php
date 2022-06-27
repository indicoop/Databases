<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessDetail;
use App\Models\Cooperative;
use App\Models\Courier;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function PHPSTORM_META\map;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
<<<<<<< HEAD
        // Role::factory(4)->create();
        // User::factory(40)->create();
        // LoanType::factory(10)->create();
        // Loan::factory(100)->create();
        // Cooperative::factory(40)->create();
        // ProductCategory::factory(10)->create();
        // Product::factory(100)->create();
        // Product::factory(80)->create();
        // Business::factory(7)->create();
        // BusinessDetail::factory(40)->create();
        // Courier::factory(10)->create();
=======
        Role::factory(4)->create();
        User::factory(40)->create();
        LoanType::factory(10)->create();
        Loan::factory(100)->create();
        Cooperative::factory(40)->create();
        ProductCategory::factory(10)->create();
        Product::factory(100)->create();
        Product::factory(80)->create();
        Business::factory(7)->create();
        BusinessDetail::factory(40)->create();
        Courier::factory(10)->create();
>>>>>>> a5b7f309b3f78885277dbffc02d850186bf63f73

        Transaction::create([
            'product_id' => 1,
            'quantity' => 10,
            'destination_address' => 'Jember',
            'voucher_id' => 1
        ]);

        TransactionDetail::create([
            'transaction_id' => 2,
            'user_id' => 1,
            'courier_id' => 3,
            'cooperative_id' => 1,
            'total_pay' => 1194069,
            'payment_method_id' => 1,
            'status' => 'success',
            'shipping_fee' => 10000,
            'transaction_date' => now()
        ]);
    }
}
