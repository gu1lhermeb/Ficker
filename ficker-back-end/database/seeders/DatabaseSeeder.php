<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\Spending;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // Níveis de usuário

        $levels = [
            [
                'level_description' => 'Padawan',
                'level_xp' => 0
            ],
            [
                'level_description' => 'Ficker Knight',
                'level_xp' => 125
            ],
            [
                'level_description' => 'Ficker Master',
                'level_xp' => 250
            ],
            [
                'level_description' => 'Ficker Grand Master',
                'level_xp' => 500
            ],
        ];

        collect($levels)->each( function($level) {

            \App\Models\Level::factory()->create($level);

        });

        $missions = [
            [
                'mission_description' => 'Adicionar transação de entrada',
                'mission_xp' => 25
            ],
            [
                'mission_description' => 'Adicionar transação de saída',
                'mission_xp' => 25
            ],
            [
                'mission_description' => 'Adicionar cartão de crédito',
                'mission_xp' => 25
            ],
            [
                'mission_description' => 'Adicionar transação de cartão de crédito',
                'mission_xp' => 25
            ],
            [
                'mission_description' => 'Criar nova categoria',
                'mission_xp' => 25
            ],
            [
                'mission_description' => 'Finalizar um mês com orçamento dentro do gasto planejado',
                'mission_xp' => 100
            ],
        ];

        collect($missions)->each( function($mission) {

            \App\Models\Mission::factory()->create($mission);

        });

        //Usuário

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),

        ]);

        // Bandeiras de cartão de crédito

        $flags = [

            [
                'flag_description' => 'Mastercard',
            ],

            [
                'flag_description' => 'Visa'
            ],

            [
                'flag_description' => 'Hipercard'
            ],

            [
                'flag_description' => 'Elo'
            ],

            [
                'flag_description' => 'Alelo'
            ],

            [
                'flag_description' => 'American Express'
            ],

            [
                'flag_description' => 'Diners Club'
            ],

        ];

        collect($flags)->each( function($flag) {

            \App\Models\Flag::create($flag);

        });

        // Tipos de transação

        $types = [
            [
                'type_description' => 'Entrada'
            ],
            [
                'type_description' => 'Saída'
            ]
        ];

        collect($types)->each( function($type) {

            \App\Models\Type::create($type);

        });

        // Cartão de crédito

        $card = Card::factory()->create();

        // Categorias

        $categories = [
            [
                'category_description' => 'Lazer'
            ],
            [
                'category_description' => 'Alimentação'
            ],
            [
                'category_description' => 'Filhos'
            ],
            [
                'category_description' => 'Valorant'
            ],
        ];

        collect($categories)->each( function($category) {

            \App\Models\Category::factory()->create($category);

        });

        // Métodos de pagamento

        $payment_methods = [
            [
                'payment_method_description' => 'Dinheiro'
            ],
            [
                'payment_method_description' => 'Pix'
            ],
            [
                'payment_method_description' => 'Cartão de débito'
            ],
            [
                'payment_method_description' => 'Cartão de crédito'
            ]
        ];

        collect($payment_methods)->each( function($payment_method) {

            \App\Models\PaymentMethod::factory()->create($payment_method);

        });

        // Transações

        // Transaction::factory()->create();
        // Transaction::factory()->create([
        //     'type_id' => 1,
        //     'payment_method_id' => null,
        // ]);

        // Gasto planejado

        // Spending::factory()->create();

    }
}