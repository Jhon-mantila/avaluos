<?php

namespace Database\Factories;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegistroFotografico>
 */
class RegistroFotograficoFactory extends Factory
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
            'id' => $this->faker->uuid(), //'id' =>  (string) Uuid::uuid4(),
            "plantilla_id" =>  \App\Models\Plantilla::inRandomOrder()->first()->id ?? \App\Models\Plantilla::factory()->create()->id,
            "imagen" => $this->faker->imageUrl(),
            "title" => $this->faker->name,
            "tipo" => $this->faker->mimeType,
            "orden" => $this->faker->numberBetween(1, 10),
            "pagina" => $this->faker->numberBetween(1, 10),
            "posicion" => $this->faker->numberBetween(1, 10),
            "user_id" =>  \App\Models\User::inRandomOrder()->first()->id ?? \App\Models\User::factory()->create()->id,
        ];
    }
}
