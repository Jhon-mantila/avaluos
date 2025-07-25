<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Avaluos;
use App\Models\Contacto;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AvaluoContacto>
 */
class AvaluoContactoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'avaluo_id' => Avaluos::inRandomOrder()->first()?->id ?? Avaluos::factory(),
            'contacto_id' => Contacto::inRandomOrder()->first()?->id ?? Contacto::factory(),
            'fecha_asignacion' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'observaciones' => $this->faker->sentence(),
        ];
    }
}
