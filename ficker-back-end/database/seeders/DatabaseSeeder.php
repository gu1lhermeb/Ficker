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
                'description' => 'Mastercard',
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

        $types = [
            [
                'description' => 'Entrada'
            ],
            [
                'description' => 'Saída'
            ],
            [
                'description' => 'Cartão de crédito'
            ]
        ];

        collect($types)->each( function($type) {

            \App\Models\Type::create($type);

        });

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
        ]);

        $card = Card::factory()->create();

        Category::create([
            'category_description' => 'Lazer',
            'type_id' => 1
        ]);

        Category::create([
            'category_description' => 'Alimentação',
            'type_id' => 2
        ]);

        Transaction::create([
            'user_id' => $admin->id,
            'description' => 'Salário',
            'date' => '2023-01-03',
            'type_id' => 1,
            'value' => 1500,
            'category_id' => 1,
        ]);

        Transaction::create([
            'user_id' => $admin->id,
            'description' => 'Compra na Adidas',
            'date' => '2023-01-03',
            'type_id' => 3,
            'value' => 300,
            'category_id' => 2,
            'card_id' => $card->id,
        ]);
    }
}
