<?php

namespace Tests\Unit\Transaction;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Transaction;


class IncomingTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_an_incoming_transaction_with_existing_category(): void
    {
        TestCase::IncomingTestSetup();

        $this->post('/api/transaction/store',[
            'category_id' => 1,
            'type_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $this->assertEquals(0, session('errors'));
        $this->assertEquals(1, count(Transaction::all()));
    }

    public function test_users_can_create_an_incoming_transaction_with_a_new_category(): void
    {
        TestCase::IncomingTestSetup();

        $this->post('/api/transaction/store',[
            'category_id' => 0,
            'category_description' => 'Alimentação',
            'type_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $this->assertEquals(0, session('errors'));
        $this->assertEquals(1, count(Transaction::all()));
        $this->assertEquals(2, count(Category::all())); // 1 from the setup and 1 from the test
    }

    public function test_users_cannot_create_an_incoming_transaction_without_a_category(): void
    {
        TestCase::IncomingTestSetup();

        $this->post('/api/transaction/store',[
            'type_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors')->get('category_id')[0];
    
        $this->assertEquals($errors,"O campo categoria é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_create_an_incoming_transaction_without_a_type(): void
    {
        TestCase::IncomingTestSetup();

        $this->post('/api/transaction/store',[
            'category_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors')->get('type_id')[0];
    
        $this->assertEquals($errors,"O campo tipo é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_create_an_incoming_transaction_without_a_description(): void
    {
        TestCase::IncomingTestSetup();

        $this->post('/api/transaction/store',[
            'type_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors')->get('category_id')[0];
    
        $this->assertEquals($errors,"O campo categoria é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_create_an_incoming_transaction_without_a_value(): void
    {
        TestCase::IncomingTestSetup();

        $this->post('/api/transaction/store',[
            'type_id' => 1,
            'category_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors')->get('transaction_value')[0];
    
        $this->assertEquals($errors,'Informe o valor da transação.');
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_create_an_incoming_transaction_without_a_date(): void
    {
        TestCase::IncomingTestSetup();

        $this->post('/api/transaction/store',[
            'type_id' => 1,
            'category_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
        ]);

        $errors = session('errors')->get('date')[0];
    
        $this->assertEquals($errors,"O campo data é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
    }
}
