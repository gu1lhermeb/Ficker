<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Type;
use App\Models\PaymentMethod;
use App\Models\Category;
use App\Models\Card;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function login(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
    }

    public static function creditCardTestSetup(): void
    {
        Self::login();
        
        Type::factory()->create([
            'id' => 2,
        ]);
        Category::factory()->create([
            'id' => 1
        ]);
        PaymentMethod::factory()->create([
            'id' => 4,
        ]);
        Card::factory()->create([
            'id' => 1
        ]);
    }

    public static function OutgoingTestSetup(): void
    {
        Self::login();

        Type::factory()->create([
            'id' => 2
        ]);
        Category::factory()->create([
            'id' => 1
        ]);
        PaymentMethod::factory()->create([
            'id' => 1
        ]);
    }

    public static function IncomingTestSetup(): void
    {
        Self::login();

        Type::factory()->create([
            'id' => 1
        ]);
        Category::factory()->create([
            'id' => 1
        ]);
    }
}
