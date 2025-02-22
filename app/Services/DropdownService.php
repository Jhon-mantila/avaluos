<?php

namespace App\Services;

class DropdownService
{
    public function list_tipos_avaluos()
    {
        return [
            'Residencial' => 'Residencial',
            'Comercial' => 'Comercial',
            'Industrial' => 'Industrial',
            'Terreno' => 'Terreno',
        ];
    }

    public function list_estados()
    {
        return [
            'Nuevo' => 'Nuevo',
            'Pendiente' => 'Pendiente',
            'En Proceso' => 'En Proceso',
            'Completado' => 'Completado',
            'Cancelado' => 'Cancelado',
        ];
    }



    // Agrega más métodos para otras listas desplegables
}