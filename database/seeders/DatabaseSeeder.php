<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Visitadores;
use App\Models\Clientes;
use App\Models\Avaluos;
use App\Models\InformacionVisita;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create();
        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        Visitadores::factory()->count(10)->create();
        Clientes::factory()->count(1)->create();
        Avaluos::factory()->count(1)->create();
        InformacionVisita::factory()->count(10)->create();
        $informacionVisita = \App\Models\InformacionVisita::factory()->create();
    
    }
}
