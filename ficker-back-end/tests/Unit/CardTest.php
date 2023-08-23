<?php

namespace Tests\Unit;

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

        Flag::create([
            'description' => 'Mastercard'
        ]);

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => '1',
            'description' => 'Nubank',
            'expiration' => '15',
            'best_day' => '1'
        ]);


        $this->assertEquals($size + 1, count(Card::all()));
    }

    public function test_users_can_not_register_a_credit_card_without_a_flag(): void
    {
        $size = count(Card::all());

        Flag::create([
            'description' => 'Mastercard'
        ]);

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            // 'flag_id' => '1',
            'description' => 'Nubank',
            'expiration' => '15',
            'best_day' => '1'
        ]);

        $this->assertEquals($size, count(Card::all()));
    }

    public function test_users_can_not_register_a_credit_card_without_a_description(): void
    {
        $size = count(Card::all());

        Flag::create([
            'description' => 'Mastercard'
        ]);

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => '1',
            'expiration' => '15',
            'best_day' => '1'
        ]);

        $this->assertEquals($size, count(Card::all()));
    }

    public function test_users_can_not_register_a_credit_card_without_an_expiration(): void
    {
        $size = count(Card::all());

        Flag::create([
            'description' => 'Mastercard'
        ]);

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => '1',
            'description' => 'Nubank',
            'best_day' => '1'
        ]);

        $this->assertEquals($size, count(Card::all()));
    }

    public function test_users_can_not_register_a_credit_card_without_a_best_day(): void
    {
        $size = count(Card::all());

        Flag::create([
            'description' => 'Mastercard'
        ]);

        $this->post('/api/register',[
            'name' => 'Kenji',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->post('/api/card', [
            'flag_id' => '1',
            'description' => 'Nubank',
            'expiration' => '30',
        ]);

        $this->assertEquals($size, count(Card::all()));
    }
}
