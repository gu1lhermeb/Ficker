<?php

namespace Tests\Unit\Transaction;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Installment;

class OutgoingTransactionTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_users_can_store_an_outgoing_transaction_with_existing_category(): void
    {
        TestCase::transactionStoreTestSetup(2, 1);

        $this->post('/api/transaction/store',[
            'category_id' => 1,
            'type_id' => 2,
            'payment_method_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors');

        $this->assertEquals(0, $errors);
        $this->assertEquals(1, count(Transaction::all()));
    }

    public function test_users_can_store_an_outgoing_transaction_with_a_new_category(): void
    {
        TestCase::transactionStoreTestSetup(2, 1);

        $this->post('/api/transaction/store',[
            'category_id' => 0,
            'category_description' => 'Comida',
            'type_id' => 2,
            'payment_method_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors');

        $this->assertEquals(0, $errors);
        $this->assertEquals(2, count(Category::all())); // 1 from setup + 1 from test
        $this->assertEquals(1, count(Transaction::all()));
    }

    public function test_users_cannot_store_an_outgoing_transaction_without_a_category(): void
    {
        TestCase::transactionStoreTestSetup(2, 1);

        $this->post('/api/transaction/store',[
            'type_id' => 2,
            'payment_method_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors')->get('category_id')[0];

        $this->assertEquals($errors,"O campo categoria é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_store_an_outgoing_transaction_without_a_type(): void
    {
        TestCase::transactionStoreTestSetup(2, 1);

        $this->post('/api/transaction/store',[
            'category_id' => 1,
            'payment_method_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors')->get('type_id')[0];

        $this->assertEquals($errors,"O campo tipo é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_store_an_outgoing_transaction_without_a_description(): void
    {
        TestCase::transactionStoreTestSetup(2, 1);

        $this->post('/api/transaction/store',[
            'type_id' => 2,
            'category_id' => 1,
            'payment_method_id' => 1,
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors')->get('transaction_description')[0];

        $this->assertEquals($errors,"O campo descrição é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_store_an_outgoing_transaction_without_a_value(): void
    {
        TestCase::transactionStoreTestSetup(2, 1);

        $this->post('/api/transaction/store',[
            'type_id' => 2,
            'category_id' => 1,
            'payment_method_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors')->get('transaction_value')[0];

        $this->assertEquals($errors,'Informe o valor da transação.');
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_store_an_outgoing_transaction_without_a_date(): void
    {
        TestCase::transactionStoreTestSetup(2, 1);

        $this->post('/api/transaction/store',[
            'type_id' => 2,
            'category_id' => 1,
            'payment_method_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
        ]);

        $errors = session('errors')->get('date')[0];

        $this->assertEquals($errors,"O campo data é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_store_an_outgoing_transaction_without_a_payment_method(): void
    {
        TestCase::transactionStoreTestSetup(2, 1);

        $this->post('/api/transaction/store',[
            'type_id' => 2,
            'category_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
        ]);

        $errors = session('errors')->get('payment_method_id')[0];

        $this->assertEquals($errors,'É necessário informar um método de pagamento para esse tipo de transação.');
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_store_an_outgoing_transaction_with_a_card(): void
    {
        TestCase::transactionStoreTestSetup(2, 1);

        $this->post('/api/transaction/store',[
            'type_id' => 2,
            'category_id' => 1,
            'payment_method_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
            'card_id' => 1,
        ]);

        $errors = session('errors')->get('card_id')[0];
    
        $this->assertEquals($errors,"O campo cartão é proibido exceto quando método de pagamento for 4.");
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_store_an_outgoing_transaction_with_installments(): void
    {
        TestCase::transactionStoreTestSetup(2, 1);

        $this->post('/api/transaction/store',[
            'type_id' => 2,
            'category_id' => 1,
            'payment_method_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
            'installments' => 2,
        ]);

        $errors = session('errors')->get('installments')[0];
    
        $this->assertEquals($errors,"O campo parcelas é proibido exceto quando método de pagamento for 4.");
        $this->assertEquals(0, count(Transaction::all()));
        $this->assertEquals(0, count(Installment::all()));

    }
}
