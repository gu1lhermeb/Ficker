<?php

namespace Tests\Unit\Transaction;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Installment;

class IncomingTransactionTest extends TestCase
{
    use RefreshDatabase;

    // Store tests ----------------------------------------------------------------------------------------

    public function test_users_can_store_an_incoming_transaction_with_existing_category(): void
    {
        TestCase::transactionStoreTestSetup(1, 1);

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

    public function test_users_can_store_an_incoming_transaction_with_a_new_category(): void
    {
        TestCase::transactionStoreTestSetup(1, 1);

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

    public function test_users_cannot_store_an_incoming_transaction_without_a_category(): void
    {
        TestCase::transactionStoreTestSetup(1, 1);

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

    public function test_users_cannot_store_an_incoming_transaction_without_a_type(): void
    {
        TestCase::transactionStoreTestSetup(1, 1);

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

    public function test_users_cannot_store_an_incoming_transaction_without_a_description(): void
    {
        TestCase::transactionStoreTestSetup(1, 1);

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

    public function test_users_cannot_store_an_incoming_transaction_without_a_value(): void
    {
        TestCase::transactionStoreTestSetup(1, 1);

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

    public function test_users_cannot_store_an_incoming_transaction_without_a_date(): void
    {
        TestCase::transactionStoreTestSetup(1, 1);

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

    public function test_users_cannot_store_an_incoming_transaction_with_a_payment_method(): void
    {
        TestCase::transactionStoreTestSetup(1, 1);

        $this->post('/api/transaction/store',[
            'type_id' => 1,
            'category_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
            'payment_method_id' => 1,
        ]);

        $errors = session('errors')->get('payment_method_id')[0];
    
        $this->assertEquals($errors,"O campo método de pagamento é proibido quando tipo for 1.");
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_store_an_incoming_transaction_with_a_card(): void
    {
        TestCase::transactionStoreTestSetup(1, 1);

        $this->post('/api/transaction/store',[
            'type_id' => 1,
            'category_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
            'card_id' => 1,
        ]);

        $errors = session('errors')->get('card_id')[0];
    
        $this->assertEquals($errors,'O campo cartão é proibido exceto quando método de pagamento for 4.');
        $this->assertEquals(0, count(Transaction::all()));
    }

    public function test_users_cannot_store_an_incoming_transaction_with_installments(): void
    {
        TestCase::transactionStoreTestSetup(1, 1);

        $this->post('/api/transaction/store',[
            'type_id' => 1,
            'category_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
            'installments' => 2,
        ]);

        $errors = session('errors')->get('installments')[0];
    
        $this->assertEquals($errors,'O campo parcelas é proibido exceto quando método de pagamento for 4.');
        $this->assertEquals(0, count(Transaction::all()));
        $this->assertEquals(0, count(Installment::all()));

    }

    // Update tests ============================================================================================

    public function test_users_can_update_the_description_of_an_incoming_transaction(): void
    {
        TestCase::transactionUpdateTestSetup(1, 1);

        $this->put('/api/transaction/update',[
            'id' => 1,
            'transaction_description' => 'Burger King',
        ]);

        $this->assertEquals(0, session('errors'));
        $this->assertEquals('Burger King', Transaction::find(1)->transaction_description);
    }

    public function test_users_can_update_the_value_of_an_incoming_transaction(): void
    {
        TestCase::transactionUpdateTestSetup(1, 1);

        $this->put('/api/transaction/update',[
            'id' => 1,
            'transaction_value' => 50.99,
        ]);

        $this->assertEquals(0, session('errors'));
        $this->assertEquals(50.99, Transaction::find(1)->transaction_value);
    }

    public function test_users_can_update_the_date_of_an_incoming_transaction(): void
    {
        TestCase::transactionUpdateTestSetup(1, 1);

        $this->put('/api/transaction/update',[
            'id' => 1,
            'date' => "2023-10-10",
        ]);

        $this->assertEquals(0, session('errors'));
        $this->assertEquals("2023-10-10", Transaction::find(1)->date);
    }

    public function test_users_can_update_the_category_of_an_incoming_transaction(): void
    {
        TestCase::transactionUpdateTestSetup(1, 1);

        $this->put('/api/transaction/update',[
            'id' => 1,
            'category_id' => 2,
        ]);

        $this->assertEquals(0, session('errors'));
        $this->assertEquals(2, Transaction::find(1)->category_id);
    }

}
