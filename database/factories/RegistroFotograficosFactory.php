<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegistroFotograficos>
 */
class RegistroFotograficosFactory extends Factory
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
            'informacion_visita_id' => \App\Models\InformacionVisita::inRandomOrder()->first()->id ?? \App\Models\InformacionVisita::factory()->create()->id,
            'foto' => $this->faker->word(),
            'descripcion' => $this->faker->text(),
            'tipo_foto' => $this->faker->word(),
            'orden' => $this->faker->randomNumber(2),
        ];
    }
}
