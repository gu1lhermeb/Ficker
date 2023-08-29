<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Flag;
use App\Models\Card;

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
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/transaction',[
            'category_id' => $category->id,
            'description' => 'Mc Donalds',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.99
        ]);

        $this->assertEquals($size + 1, count(Transaction::all()));

        $errors = session('errors');
        $this->assertEquals(0, $errors);
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

        $errors = session('errors');
        $this->assertEquals(0, $errors);
    }

    public function test_users_can_create_a_transaction_with_new_category_for_registered_credit_card(): void
    {

        $size = count(Transaction::all());

        $flag = Flag::create([
            'description' => 'Mastercard'
        ]);

        $this->post('/api/register',[
            'name' => 'Teste User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card/',[
            'flag_id' => $flag->id,
            'description' => 'Nubank',
            'expiration' => '15',
        ]);

        $this->post('/api/transaction',[
            'category_id' => '0',
            'card_id' => '2',
            'category_description' => 'lalalalala',
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size + 1, count(Transaction::all()));
        $this->assertEquals($size + 1, count(Category::all()));

        $errors = session('errors');
        $this->assertEquals(0, $errors);

    }

    public function test_users_can_create_a_transaction_with_existing_category_for_registered_credit_card(): void
    {

        $size = count(Transaction::all());

        $category = Category::create([
            'category_description' => 'lalalalalala',
        ]);

        $flag = Flag::create([
            'description' => 'Mastercard'
        ]);

        $this->post('/api/register',[
            'name' => 'Teste User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card/',[
            'flag_id' => $flag->id,
            'description' => 'Nubank',
            'expiration' => '15',
        ]);

        $this->post('/api/transaction',[
            'category_id' => $category->id,
            'card_id' => '3',
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size + 1, count(Transaction::all()));

        $errors = session('errors');
        $this->assertEquals(0, $errors);

    }

    public function test_users_can_not_create_a_transaction_without_a_category(): void
    {

        $size = count(Transaction::all());

        $this->post('/api/register',[
            'name' => 'Teste User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);
        
        $this->post('/api/transaction',[
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('category_id')[0],"O campo category id é obrigatório.");
    }

    public function test_users_can_not_create_a_transaction_without_a_description(): void
    {

        $size = count(Transaction::all());

        $this->post('/api/register',[
            'name' => 'Teste User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);
        
        $this->post('/api/transaction',[
            'category_id' => '1',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('description')[0],"O campo descrição é obrigatório.");
    }

    public function test_users_can_not_create_a_transaction_without_a_date(): void
    {

        $size = count(Transaction::all());

        $this->post('/api/register',[
            'name' => 'Teste User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);
        
        $this->post('/api/transaction',[
            'category_id' => '1',
            'description' => 'lala dodo',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('date')[0],"O campo data é obrigatório.");
    }

    public function test_users_can_not_create_a_transaction_without_a_value(): void
    {

        $size = count(Transaction::all());

        $this->post('/api/register',[
            'name' => 'Teste User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);
        
        $this->post('/api/transaction',[
            'category_id' => '1',
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type' => 'entrada',
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('value')[0],"O campo value é obrigatório.");
    }

    public function test_users_can_not_create_a_transaction_without_a_type(): void
    {

        $size = count(Transaction::all());

        $this->post('/api/register',[
            'name' => 'Teste User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);
        
        $this->post('/api/transaction',[
            'category_id' => '1',
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'value' => 10
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('type')[0],"O campo type é obrigatório.");
    }

    public function test_users_can_not_create_a_transaction_with_new_category_without_category_description(): void
    {

        $size = count(Category::all());

        $this->post('/api/register',[
            'name' => 'Teste User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/transaction',[
            'category_id' => '0',
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type' => 'entrada',
            'value' => 50.00
        ]);

        $this->assertEquals($size, count(Category::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('category_description')[0],"O campo category description é obrigatório.");
    }
}