<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Transaction;
use App\Models\Category;

class TransactionTest extends TestCase
{
    
    use RefreshDatabase;

    public function test_users_can_create_a_transaction_with_existing_category(): void
    {

        $size = count(Transaction::all());

        $category = Category::create([
            'category_description' => 'lalalalalala',
        ]);

        $this->post('/api/register',[
            'name' => 'Teste User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/transaction',[
            'category_id' => $category->id,
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.99
        ]);

        $this->assertEquals($size + 1, count(Transaction::all()));
    }

    public function test_users_can_create_a_transaction_with_new_category(): void
    {

        $size = count(Transaction::all());

        $this->post('/api/register',[
            'name' => 'Teste User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/transaction',[
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

    public function test_users_can_not_create_a_transaction_without_a_category(): void
    {

        $size = count(Transaction::all());
        
        $this->post('/api/transaction',[
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size, count(Transaction::all()));
    }

    public function test_users_can_not_create_a_transaction_without_a_description(): void
    {

        $size = count(Transaction::all());
        
        $this->post('/api/transaction',[
            'category_id' => '1',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size, count(Transaction::all()));
    }

    public function test_users_can_not_create_a_transaction_without_a_date(): void
    {

        $size = count(Transaction::all());
        
        $this->post('/api/transaction',[
            'category_id' => '1',
            'description' => 'lala dodo',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size, count(Transaction::all()));
    }

    public function test_users_can_not_create_a_transaction_without_a_value(): void
    {

        $size = count(Transaction::all());
        
        $this->post('/api/transaction',[
            'category_id' => '1',
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type' => 'entrada',
        ]);

        $this->assertEquals($size, count(Transaction::all()));
    }

    public function test_users_can_not_create_a_transaction_with_new_category_without_category_description(): void
    {

        $size = count(Category::all());

        $this->post('/api/transaction',[
            'category_id' => '0',
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size, count(Category::all()));
    }
}