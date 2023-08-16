<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Transaction;
use App\Models\Category;

class TransactionTest extends TestCase
{
    
    use RefreshDatabase;

    public function test_users_can_create_new_transaction_with_existing_category(): void
    {

        $size = count(Transaction::all());

        $category = Category::create([
            'category_description' => 'lalalalalala',
        ]);

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/transaction',[
            'user_id' => '2',
            'category_id' => $category->id,
            'description' => 'Mc Donalds',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size + 1, count(Transaction::all()));
    }

    public function test_users_can_create_transaction_with_new_category(): void
    {

        $size = count(Transaction::all());

        $this->post('/api/register',[
            'name' => 'viado',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/transaction',[
            'user_id' => '2',
            'category_id' => '0',
            'category_description' => 'lalalalala',
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size + 1, count(Transaction::all()));
        $this->assertEquals($size + 1, count(Category::all()));
    }
}
