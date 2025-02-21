<?php

namespace Database\Factories;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InformacionVisita>
 */
class InformacionVisitaFactory extends Factory
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
            'avaluo_id' => \App\Models\Avaluos::factory(), // Relación con el modelo Avaluos, pero si se crean 10 informacion_visita realaciona 10 avaluos y los crea nuevos
            'visitador_id' => \App\Models\Visitadores::factory(), // Relación con el modelo Visitadores, pero si se crean 10 informacion_visita realaciona 10 visitadores y los crea nuevos
            'celular' => $this->faker->phoneNumber, // Genera un número de teléfono aleatorio
            'direccion' => $this->faker->address, // Genera una dirección aleatoria
            'ciudad' => $this->faker->city, // Genera una ciudad aleatoria
            'fecha_visita' => $this->faker->dateTimeThisYear(), // Genera una fecha aleatoria
            'observaciones' => $this->faker->sentence, // Genera una oración aleatoria
        ];
    }
}
