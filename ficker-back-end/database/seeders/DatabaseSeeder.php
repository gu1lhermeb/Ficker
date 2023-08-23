<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
    }
}
