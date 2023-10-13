<?php

namespace Tests\Unit\Transaction;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Installment;

class CreditCardTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_create_a_credit_card_transaction_with_existing_category(): void
    {
        TestCase::creditCardTestSetup();

        $this->post('/api/transaction/store',[
            'type_id' => 2,
            'payment_method_id' => 4,
            'category_id' => 1,
            'card_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
            'installments' => 2
        ]);

        $errors = session('errors');
        $this->assertEquals(0, $errors);
        $this->assertEquals(1, count(Transaction::all()));
        $this->assertEquals(2, count(Installment::all()));
    }

    public function test_users_can_create_a_credit_card_transaction_with_a_new_category(): void
    {
        TestCase::creditCardTestSetup();

        $this->post('/api/transaction/store',[
            'category_id' => 0,
            'category_description' => 'Comida',
            'type_id' => 2,
            'card_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
            'payment_method_id' => 4,
            'installments' => 2
        ]);

        $errors = session('errors');
        $this->assertEquals(0, $errors); 
        $this->assertEquals(2, count(Category::all())); // 1 from setup + 1 from test
        $this->assertEquals(1, count(Transaction::all()));
        $this->assertEquals(2, count(Installment::all()));
    }

    public function test_users_can_not_create_a_credit_card_transaction_without_a_category(): void
    {
        TestCase::creditCardTestSetup();
        
        $this->post('/api/transaction/store',[
            'type_id' => 2,
            'card_id' => 1,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
            'payment_method_id' => 4,
            'installments' => 2
        ]);

        $errors = session('errors');
        $this->assertEquals($errors->get('category_id')[0],"O campo category id é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
        $this->assertEquals(0, count(Installment::all()));
    }

    public function test_users_can_not_create_a_credit_card_transaction_without_a_type(): void
    {
        TestCase::creditCardTestSetup();
        
        $this->post('/api/transaction/store',[
            'category_id' => 1,
            'payment_method_id' => 4,
            'card_id' => 1,
            'transaction_description' => 'CURSO DE LARAVEL',
            'transaction_value' => 500.00,
            'date' => date('Y-m-d'),
            'installments' => 2
        ]);

        $errors = session('errors');
        $this->assertEquals($errors->get('type_id')[0],"O campo type id é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
        $this->assertEquals(0, count(Installment::all()));
    }

    public function test_users_can_not_create_a_credit_card_transaction_without_a_description(): void
    {
        TestCase::creditCardTestSetup();
        
        $this->post('/api/transaction/store',[
            'category_id' => 1,
            'type_id' => 2,
            'payment_method_id' => 4,
            'card_id' => 1,
            'transaction_value' => 500.00,
            'date' => date('Y-m-d'),
            'installments' => 2
        ]);

        $errors = session('errors');
        $this->assertEquals($errors->get('transaction_description')[0],"O campo descrição é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
        $this->assertEquals(0, count(Installment::all()));
    }

    public function test_users_can_not_create_a_credit_card_transaction_without_a_value(): void
    {
        TestCase::creditCardTestSetup();
        
        $this->post('/api/transaction/store',[
            'category_id' => 1,
            'type_id' => 2,
            'payment_method_id' => 4,
            'card_id' => 1,
            'transaction_description' => 'CURSO DE LARAVEL',
            'date' => date('Y-m-d'),
            'installments' => 2
        ]);

        $errors = session('errors');
        $this->assertEquals($errors->get('transaction_value')[0],"Informe o valor da transação.");
        $this->assertEquals(0, count(Transaction::all()));
        $this->assertEquals(0, count(Installment::all()));
    }

    public function test_users_can_not_create_a_credit_card_transaction_without_a_date(): void
    {
        TestCase::creditCardTestSetup();
        
        $this->post('/api/transaction/store',[
            'category_id' => 1,
            'type_id' => 2,
            'payment_method_id' => 4,
            'card_id' => 1,
            'transaction_description' => 'CURSO DE LARAVEL',
            'transaction_value' => 500.00,
            'installments' => 2        
        ]);

        $errors = session('errors');
        $this->assertEquals($errors->get('date')[0],"O campo data é obrigatório.");
        $this->assertEquals(0, count(Transaction::all()));
        $this->assertEquals(0, count(Installment::all()));
    }

    public function test_users_can_not_create_a_credit_card_transaction_without_a_payment_method(): void
    {
        
        TestCase::creditCardTestSetup();
        
        $this->post('/api/transaction/store',[
            'category_id' => 1,
            'type_id' => 2,
            'card_id' => 1,
            'transaction_description' => 'CURSO DE LARAVEL',
            'transaction_value' => 500.00,
            'date' => date('Y-m-d'),
            'installments' => 2
        ]);

        $errors = session('errors');
        $this->assertEquals($errors->get('payment_method_id')[0],"É necessário informar um método de pagamento para esse tipo de transação.");
        $this->assertEquals(0, count(Transaction::all()));
        $this->assertEquals(0, count(Installment::all()));
    }

    public function test_users_can_not_create_a_credit_card_transaction_without_a_card(): void
    {
        TestCase::creditCardTestSetup();
        
        $this->post('/api/transaction/store',[
            'category_id' => 1,
            'type_id' => 2,
            'payment_method_id' => 4,
            'transaction_description' => 'CURSO DE LARAVEL',
            'transaction_value' => 500.00,
            'date' => date('Y-m-d'),
            'installments' => 2
        ]);

        $errors = session('errors');

        $this->assertEquals($errors->get('card_id')[0],"É necessário informar um cartão de crédito para esse tipo de transação.");
        $this->assertEquals(0, count(Transaction::all()));
        $this->assertEquals(0, count(Installment::all()));
    }
}
