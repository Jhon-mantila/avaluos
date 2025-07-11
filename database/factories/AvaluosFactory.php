<?php

namespace Database\Factories;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Avaluos>
 */
class AvaluosFactory extends Factory
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
            'numero_avaluo' => $this->faker->randomNumber(5), // Genera un número aleatorio de 5 dígitos
            'cliente_id' => \App\Models\Clientes::factory(), // Relación con el modelo Clientes, pero si se crean 10 avaluos realaciona 10 clientes y los crea nuevos
            'estado' => $this->faker->randomElement(['Nuevo', 'Sin Coordinar', 'Agendado', 'Visita Realizada', 'Realizando Informe', 'Cancelado','Completado']),//$this->faker->word, // Genera una palabra aleatoria
            'tipo_avaluo' => $this->faker->randomElement(['PH', 'NPH']),//$this->faker->word, // Genera una palabra aleatoria
            'direccion' => $this->faker->address, // Genera una dirección aleatoria
            'ciudad' => $this->faker->city, // Genera una ciudad aleatoria
            'departamento' => $this->faker->state, // Genera un departamento aleatorio
            'uso' => $this->faker->randomElement(['Residencial', 'Comercial', 'Industrial', 'Dotacional']), // Genera un uso aleatorio
            'valor_comercial_estimado' => $this->faker->randomNumber(7), // Genera un número aleatorio de 7 dígitos
            'observaciones' => $this->faker->sentence, // Genera una oración aleatoria
            
        ];
    }
}
