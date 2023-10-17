<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RegistrationTest extends TestCase

{
    use RefreshDatabase;

    public function test_users_can_not_register_without_a_name(): void
    {
        $size = count(User::all());

        $this->post('/api/register', [
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->assertEquals($size, count(User::all()));
    }

    public function test_users_can_not_register_without_an_email(): void
    {
        $size = count(User::all());

        $this->post('/api/register', [
            'name' => 'Test User',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->assertEquals($size, count(User::all()));
    }

    public function test_users_can_not_register_without_a_password(): void
    {
        $size = count(User::all());

        $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'testemail@test.com',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->assertEquals($size, count(User::all()));
    }

    public function test_users_can_not_register_without_confirming_password(): void
    {
        $size = count(User::all());

        $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
        ]);

        $this->assertEquals($size, count(User::all()));
    }

    public function test_users_can_not_register_with_different_passwords(): void
    {
        $size = count(User::all());

        $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest2'
        ]);

        $this->assertEquals($size, count(User::all()));
    }

    public function test_users_can_not_register_with_invalid_password(): void
    {
        $size = count(User::all());

        $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'testemail@test.com',
            'password' => '1234567',
            'password_confirmation' => '1234567'
        ]);

        $this->assertEquals($size, count(User::all()));
    }

    public function test_users_can_not_register_with_an_invalid_email(): void
    {
        $size = count(User::all());

        $this->post('/api/register', [
            'name' => 'Test User',
            'email' => '@testemail_test',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->assertEquals($size, count(User::all()));
    }

    public function test_users_can_not_register_with_an_existing_email(): void
    {
        User::create([
            'name' => 'Test User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $size = count(User::all());

        $this->post('/api/register', [
            'name' => 'Test User',
            'email' => 'testemail@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        $this->assertEquals($size, count(User::all()));
    }
}
