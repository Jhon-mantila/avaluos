<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Visitadores;
use App\Models\Clientes;
use App\Models\Avaluos;
use App\Models\Contacto;
use App\Models\InformacionVisita;
use App\Models\Plantilla;
use App\Models\RegistroFotografico;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        //$user = \App\Models\User::factory()->create();
        $user = User::factory()->create([
            'name' => 'Jhon Mantilla',
            'email' => 'jhon.e.mantilla@gmail.com',
        ]);

        Visitadores::factory()->count(10)->create();
        Clientes::factory()->count(1)->create();

        // 👉 Guardar directamente el avalúo creado
        $avaluo = Avaluos::factory()->create();

        InformacionVisita::factory()->count(10)->create();
        $informacionVisita = \App\Models\InformacionVisita::factory()->create();

        Plantilla::factory()->count(5)->create([
            'informacion_visita_id' => $informacionVisita->id,
            'user_id' => $user->id,
        ]);

        RegistroFotografico::factory()->count(5)->create([
            'plantilla_id' => \App\Models\Plantilla::inRandomOrder()->first()->id,
            //'user_id' => $user->id,
        ]);
        //php artisan db:seed --class=RolesAndPermissionsSeeder
        // Llamar al seeder de roles y permisos
        $this->call(RolesAndPermissionsSeeder::class);

        $contactos = \App\Models\Contacto::factory()->count(3)->create();
        \App\Models\AvaluoContacto::factory()->count(3)->create([
            'avaluo_id' => $avaluo->id,
        ]);
    
    }
}
