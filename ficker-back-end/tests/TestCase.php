<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Type;
use App\Models\PaymentMethod;
use App\Models\Category;
use App\Models\Card;
use App\Models\Flag;    

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function testLogin(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
    }

    public function testSetup(): void
    {
        Type::factory()->create();
        PaymentMethod::factory()->create();
        Category::factory()->create();
        Card::factory()->create();
        Flag::factory()->create();
    }

}
