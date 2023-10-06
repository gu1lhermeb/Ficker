<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Transaction;
use App\Models\Installment;
use App\Models\Category;
use App\Models\Card;
use App\Models\Flag;
use App\Models\PaymentMethod;
use App\Models\Type;
use App\Models\User;

class TransactionTest extends TestCase
{
    
    use RefreshDatabase;

    // public function test_users_can_create_a_transaction_with_existing_category(): void
    // {

    //     $user = User::factory()->create();

    //     $this->post('/api/login', [
    //         'email' => $user->email,
    //         'password' => 'password'
    //     ]);

    //     $type = Type::factory()->create();
    //     $category = Category::factory()->create();
    //     $payment_method = PaymentMethod::factory()->create();

    //     $size = count(Transaction::all());

    //     $this->post('/api/transaction/store',[
    //         'category_id' => $category->id,
    //         'type_id' => $type->id,
    //         'payment_method_id' => $payment_method,
    //         'transaction_description' => 'Mc Donalds',
    //         'date' => '2023-10-06',
    //         'transaction_value' => 50.99
    //     ]);

    //     $this->assertEquals($size + 1, count(Transaction::all()));

    //     $errors = session('errors');
    //     $this->assertEquals(0, $errors);
    // }

    public function test_users_can_create_a_transaction_with_new_category(): void
    {

        $user = User::factory()->create();
        
        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $type = Type::factory()->create();

        $size = count(Transaction::all());

        $this->post('/api/transaction',[
            'category_id' => 0,
            'category_description' => 'PEPE',
            'description' => 'GUIGUI',
            'date' => '2023-01-03',
            'type_id' => $type->id,
            'value' => 50.00,
        ]);

        $this->assertEquals($size + 1, count(Transaction::all()));
        $this->assertEquals($size + 1, count(Category::all()));

        $errors = session('errors');
        $this->assertEquals(0, $errors);
    }

    public function test_users_can_create_a_transaction_with_new_category_for_registered_credit_card(): void
    {

        $user = User::factory()->create();
        $type = Type::factory()->create([
            'id' => 3
        ]);
        
        $flag = Flag::factory()->create();

        $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $card = Card::factory()->create([
            'user_id' => $user->id,
            'flag_id' => $flag->id
        ]);

        $size = count(Transaction::all());

        $this->post('/api/transaction',[
            'category_id' => 0,
            'card_id' => $card->id,
            'category_description' => 'lalalalala',
            'description' => 'lala dodo',
            'date' => '2023-01-03',
            'type_id' => $type->id,
            'value' => 50.00,
            'installments' => 3
        ]);

        $this->assertEquals($size + 1, count(Transaction::all()));
        $this->assertEquals($size + 1, count(Category::all()));
        $this->assertEquals(3, count(Installment::all()));

        $errors = session('errors');
        $this->assertEquals(0, $errors);

    }

    // public function test_users_can_create_a_transaction_with_existing_category_for_registered_credit_card(): void
    // {

    //     $user = User::factory()->create();
    //     $flag = Flag::factory()->create();
    //     $type = Type::factory()->create([
    //         'id' => 3
    //     ]);
    //     $category = Category::factory()->create([
    //         'type_id' => $type->id
    //     ]);

    //     $this->post('/api/login', [
    //         'email' => $user->email,
    //         'password' => 'password'
    //     ]);

    //     $card = Card::factory()->create([
    //         'user_id' => $user->id,
    //         'flag_id' => $flag->id
    //     ]);

    //     $size = count(Transaction::all());

    //     $this->post('/api/transaction',[
    //         'category_id' => $category->id,
    //         'card_id' => $card->id,
    //         'description' => 'lala dodo',
    //         'date' => '2023-01-03',
    //         'type_id' => 3,
    //         'value' => 50.00,
    //         'installments' => 3
    //     ]);

    //     $this->assertEquals($size + 1, count(Transaction::all()));
    //     $this->assertEquals(3, count(Installment::all()));

    //     $errors = session('errors');
    //     $this->assertEquals(0, $errors);

    // }

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
            'type_id' => 1,
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
            'type_id' => 1,
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
            'type_id' => 1,
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
            'type_id' => 1,
        ]);

        $this->assertEquals($size, count(Transaction::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('value')[0],"O campo value é obrigatório.");
    }

    public function test_users_can_not_create_a_transaction_without_a_type_id(): void
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
    
        $this->assertEquals($errors->get('type_id')[0],"O campo type id é obrigatório.");
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
            'type_id' => 1,
            'value' => 50.00
        ]);

        $this->assertEquals($size, count(Category::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('category_description')[0],"O campo category description é obrigatório.");
    }
}