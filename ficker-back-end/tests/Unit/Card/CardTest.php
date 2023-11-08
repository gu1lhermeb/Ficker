<?php

namespace Tests\Unit\Card;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Card;
use App\Models\Flag;

class CardTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_register_a_credit_card(): void
    {

        $size = count(Card::all());

        $flag = Flag::create([
            'description' => 'Mastercard'
        ]);

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => $flag->id,
            'description' => 'Nubank',
            'expiration' => 15,
            'closure' => 24
        ]);

        $this->assertEquals($size + 1, count(Card::all()));

        $errors = session('errors');

        $this->assertEquals(0, $errors);
    }

    public function test_users_can_not_register_a_credit_card_without_a_flag(): void
    {
        $size = count(Card::all());

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'description' => 'Nubank',
            'expiration' => '15',
        ]);

        $this->assertEquals($size, count(Card::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('flag_id')[0],"O campo flag id é obrigatório.");
    }

    public function test_users_can_not_register_a_credit_card_without_a_description(): void
    {
        $size = count(Card::all());

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => '1',
            'expiration' => '15',
        ]);

        $this->assertEquals($size, count(Card::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('description')[0],"O campo descrição é obrigatório.");
    }

    public function test_users_can_not_register_a_credit_card_without_an_expiration(): void
    {
        $size = count(Card::all());

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => '1',
            'description' => 'Nubank',
        ]);

        $this->assertEquals($size, count(Card::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('expiration')[0],"O campo expiration é obrigatório.");
    }

    public function test_users_can_not_register_a_credit_card_with_under_minimum_expiration(): void
    {

        $size = count(Card::all());

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => '1',
            'description' => 'Nubank',
            'expiration' => '-5',
        ]);


        $this->assertEquals($size, count(Card::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('expiration')[0],"O campo expiration deve ser pelo menos 1.");
    }

    public function test_users_can_not_register_a_credit_card_with_greather_than_maximum_expiration(): void
    {

        $size = count(Card::all());

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => '1',
            'description' => 'Nubank',
            'expiration' => '35',
        ]);

        $this->assertEquals($size, count(Card::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('expiration')[0],"O campo expiration não pode ser superior a 31.");

    }

    public function test_users_can_not_register_a_credit_card_with_under_minimum_description(): void
    {

        $size = count(Card::all());

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => '1',
            'description' => 'a',
            'expiration' => '31',
        ]);

        $this->assertEquals($size, count(Card::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('description')[0],"O campo descrição deve ter pelo menos 2 caracteres.");

    }

    public function test_users_can_not_register_a_credit_card_with_greater_than_maximum_description(): void
    {

        $size = count(Card::all());

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => '1',
            'description' => 'pqwoeiruqpweoirhqpweihutaoiewgjbaldgbjalbvdlabfdlabfabetkuaebkuvhdsfuabsdkfjhbakdfjhvasdkfhvaksfhvkajdsfhvajs',
            'expiration' => '31',
        ]);

        $this->assertEquals($size, count(Card::all()));

        $errors = session('errors');
    
        $this->assertEquals($errors->get('description')[0],"O campo descrição não pode ser superior a 50 caracteres.");

    }
}
