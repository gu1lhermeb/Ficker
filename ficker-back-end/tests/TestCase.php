<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Type;
use App\Models\PaymentMethod;
use App\Models\Category;
use App\Models\Card;
use App\Models\Transaction;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function login(): void
    {
        $user = User::factory()->create();
        Auth::login($user);
    }

    public static function transactionStoreTestSetup($type, $payment_method): void
    {
        Self::login();
        
        Type::factory()->create([
            'id' => $type,
        ]);
        Category::factory()->create([
            'id' => 1
        ]);
        PaymentMethod::factory()->create([
            'id' => $payment_method,
        ]);
        Card::factory()->create([
            'id' => 1
        ]);
    }

    public static function transactionUpdateTestSetup($type, $payment_method): void
    {
        Self::login();

        Transaction::factory()->create([
            'id' => 1,
            'category_id' => 1,
            'type_id' => $type,
            'payment_method_id' => $payment_method,
        ]);
    }
}
