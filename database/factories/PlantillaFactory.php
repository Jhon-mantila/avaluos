<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plantilla>
 */
class PlantillaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'id' => $this->faker->uuid(),
            "nombre_plantilla" => $this->faker->name,
            "informacion_visita_id" =>  \App\Models\InformacionVisita::inRandomOrder()->first()->id ?? \App\Models\InformacionVisita::factory()->create()->id,
            "user_id" =>  \App\Models\User::inRandomOrder()->first()->id ?? \App\Models\User::factory()->create()->id,

        ];
    }
}
