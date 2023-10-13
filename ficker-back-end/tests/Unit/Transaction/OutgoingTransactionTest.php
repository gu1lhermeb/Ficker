<?php

namespace Tests\Unit\Transaction;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Type;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\PaymentMethod;

class OutgoingTransactionTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_users_can_create_an_outgoing_transaction_with_existing_category(): void
    {
        $size = count(Transaction::all());

        $user = User::factory()->create();
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $type = Type::factory()->create([
            'id' => 2
        ]);
        $payment_method = PaymentMethod::factory()->create();
        $category = Category::factory()->create();

        $this->post('/api/transaction/store',[
            'category_id' => $category->id,
            'type_id' => $type->id,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
            'payment_method_id' => $payment_method->id
        ]);

        $this->assertEquals($size + 1, count(Transaction::all()));

        $errors = session('errors');
        $this->assertEquals(0, $errors);
    }

    public function test_users_can_create_an_outgoing_transaction_with_a_new_category(): void
    {
        $size = count(Transaction::all());

        $user = User::factory()->create();
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $type = Type::factory()->create([
            'id' => 2
        ]);
        $payment_method = PaymentMethod::factory()->create();

        $this->post('/api/transaction/store',[
            'category_id' => 0,
            'category_description' => 'Comida',
            'type_id' => $type->id,
            'transaction_description' => 'Mc Donalds',
            'transaction_value' => 50.99,
            'date' => date('Y-m-d'),
            'payment_method_id' => $payment_method->id
        ]);

        $this->assertEquals($size + 1, count(Category::all()));
        $this->assertEquals($size + 1, count(Transaction::all()));

        $errors = session('errors');
        $this->assertEquals(0, $errors);
    }

    public function test_users_can_not_create_an_outgoing_transaction_without_a_category(): void
    {

        $size = count(Transaction::all());

        $user = User::factory()->create();
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $type = Type::factory()->create([
            'id' => 2
        ]);
        $payment_method = PaymentMethod::factory()->create();
        
        $this->post('/api/transaction/store',[
            'type_id' => $type->id,
            'transaction_description' => 'CURSO DE LARAVEL',
            'transaction_value' => 500.00,
            'date' => date('Y-m-d'),
            'payment_method_id' => $payment_method->id  
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('category_id')[0],"O campo category id é obrigatório.");
    }

    public function test_users_can_not_create_an_outgoing_transaction_without_a_type(): void
    {

        $size = count(Transaction::all());

        $user = User::factory()->create();
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $category = Category::factory()->create();
        $payment_method = PaymentMethod::factory()->create();
        
        $this->post('/api/transaction/store',[
            'category_id' => $category->id,
            'transaction_description' => 'CURSO DE LARAVEL',
            'transaction_value' => 500.00,
            'date' => date('Y-m-d'),
            'payment_method_id' => $payment_method->id
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('type_id')[0],"O campo type id é obrigatório.");
    }

    public function test_users_can_not_create_an_outgoing_transaction_without_a_description(): void
    {

        $size = count(Transaction::all());

        $user = User::factory()->create();
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $type = Type::factory()->create([
            'id' => 2
        ]);
        $category = Category::factory()->create();
        $payment_method = PaymentMethod::factory()->create();
        
        $this->post('/api/transaction/store',[
            'category_id' => $category->id,
            'type_id' => $type->id,
            'transaction_value' => 500.00,
            'date' => date('Y-m-d'),
            'payment_method_id' => $payment_method->id
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('transaction_description')[0],"Informe uma descrição para a transação.");
    }

    public function test_users_can_not_create_an_outgoing_transaction_without_a_value(): void
    {
        $size = count(Transaction::all());

        $user = User::factory()->create();
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $type = Type::factory()->create([
            'id' => 2
        ]);
        $category = Category::factory()->create();
        $payment_method = PaymentMethod::factory()->create();
        
        $this->post('/api/transaction/store',[
            'category_id' => $category->id,
            'type_id' => $type->id,
            'transaction_description' => 'CURSO DE PHPUNIT',
            'date' => date('Y-m-d'),
            'payment_method_id' => $payment_method->id
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('transaction_value')[0],"O campo transaction value é obrigatório.");
    }

    public function test_users_can_not_create_an_outgoing_transaction_without_a_date(): void
    {
        $size = count(Transaction::all());

        $user = User::factory()->create();
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $type = Type::factory()->create([
            'id' => 2
        ]);
        $category = Category::factory()->create();
        $payment_method = PaymentMethod::factory()->create();
        
        $this->post('/api/transaction/store',[
            'category_id' => $category->id,
            'type_id' => $type->id,
            'transaction_description' => 'CURSO DE PHPUNIT',
            'transaction_value' => 60.5,
            'payment_method_id' => $payment_method->id
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('date')[0],"O campo data é obrigatório.");
    }

    public function test_users_can_not_create_an_outgoing_transaction_without_a_payment_method(): void
    {
        $size = count(Transaction::all());

        $user = User::factory()->create();
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $type = Type::factory()->create([
            'id' => 2
        ]);
        $category = Category::factory()->create();
        
        $this->post('/api/transaction/store',[
            'category_id' => $category->id,
            'type_id' => $type->id,
            'transaction_description' => 'CURSO DE PHPUNIT',
            'transaction_value' => 60.5,
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('payment_method_id')[0],"É necessário informar um método de pagamento para transações de saída.");
    }
}
