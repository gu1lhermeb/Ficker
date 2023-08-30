<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {

        $flags = [

            [
                'description' => 'Mastercard'
            ],

            [
                'description' => 'Visa'
            ],

            [
                'description' => 'Hipercard'
            ],

            [
                'description' => 'Elo'
            ],

            [
                'description' => 'Alelo'
            ],

            [
                'description' => 'American Express'
            ],

            [
                'description' => 'Diners Club'
            ],

        ];

        collect($flags)->each( function($flag) {

            \App\Models\Flag::create($flag);

        });

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
        ]);

        $card = Card::create([
            'user_id' => $admin->id,
            'flag_id' => 4,
            'description' => 'Cartão Nubank',
            'expiration' => 1,
        ]);

        Category::create([
            'category_description' => 'Entrada',
        ]);

        Category::create([
            'category_description' => 'Saida',
        ]);

        Transaction::create([
            'user_id' => $admin->id,
            'description' => 'Salário',
            'date' => '2023-01-03',
            'type' => 'Entrada',
            'value' => 1500,
            'category_id' => 1,
            'description' => 'Entrada'
        ]);

        Transaction::create([
            'user_id' => $admin->id,
            'description' => 'Compra na Adidas',
            'date' => '2023-01-03',
            'type' => 'Saida',
            'value' => 300,
            'category_id' => 2,
            'description' => 'Saida',
            'card_id' => $card->id,
        ]);
    }
}