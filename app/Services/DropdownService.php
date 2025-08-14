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
            'PH' => 'PH',
            'NPH' => 'NPH',
        ];
    }

    public function list_estados()
    {
        return [
            'Nuevo' => 'Nuevo',
            'Sin Coordinar' => 'Sin Coordinar',
            'Agendado' => 'Agendado',
            'Visita Realizada' => 'Visita Realizada',
            'Realizando Informe' => 'Realizando Informe',
            'Completado' => 'Completado',
            'Cancelado' => 'Cancelado',
        ];
    }

    public function list_uso()
    {
        return [
            'Residencial' => 'Residencial',
            'Comercial' => 'Comercial',
            'Industrial' => 'Industrial',
            'Dotacional' => 'Dotacional',
        ];
    }

    public function list_genero()
    {
        return [
            'masculino' => 'Masculino',
            'femenino' => 'Femenino',
            'otro' => 'Otro',
        ];
    }
    // Agrega más métodos para otras listas desplegables
}