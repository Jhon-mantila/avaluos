<?php

namespace App\Services;

class DropdownService
{
    public function list_tipo_documento()
    {
        return [
            'Nit' => 'Nit',
            'CC' => 'Cédula de Ciudadanía',
            'CE' => 'Cédula de extranjería',
            'Pasaporte' => 'Pasaporte',
        ];
    }

    public function list_tipos_avaluos()
    {
        return [
            'Residencial' => 'Residencial',
            'Comercial' => 'Comercial',
            'Industrial' => 'Industrial',
            'Terreno' => 'Terreno',
            'Catastral' => 'Catastral',
            'Rural' => 'Rural',
            'Urbano' => 'Urbano',
            'Apartamento' => 'Apartamento',
        ];
    }

    public function list_estados()
    {
        return [
            'Nuevo' => 'Nuevo',
            'Pendiente' => 'Pendiente',
            'En Proceso' => 'En Proceso',
            'Sin Asignar' => 'Sin Asignar',
            'Completado' => 'Completado',
            'Cancelado' => 'Cancelado',
        ];
    }



    // Agrega más métodos para otras listas desplegables
}