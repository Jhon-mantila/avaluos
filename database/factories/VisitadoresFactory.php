<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visitadores>
 */
class VisitadoresFactory extends Factory
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
            'id' =>  (string) Uuid::uuid4(),
            'cobertura' => $this->faker->word, // Genera una palabra aleatoria
            'ciudad' => $this->faker->city, // Genera una ciudad aleatoria
            'telefono' => $this->faker->phoneNumber, // Genera un número de teléfono aleatorio
            'departamento' => $this->faker->state, // Genera un departamento/estado aleatorio
            'active' => $this->faker->boolean, // Genera un valor booleano (true o false)
            'user_id' => \App\Models\User::factory(), // Relación con el modelo User, pero si se crean 10 visitadores realaciona 10 usauruos y los crea
            
        ];
    }
}
