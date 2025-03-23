<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\Plantilla;
use App\Models\RegistroFotografico;

class ExcelExportController extends Controller
{
    public function generarExcel($plantillaId)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        // Obtener datos de la plantilla
        $plantilla = Plantilla::with(['informacionVisita.avaluo.cliente'])
            ->findOrFail($plantillaId);

        $numeroAvaluo = $plantilla->informacionVisita->avaluo->numero_avaluo ?? 'Sin número de avalúo';
        $logoCliente = $plantilla->informacionVisita->avaluo->cliente->logo ?? null;
        $imagenes = RegistroFotografico::where('plantilla_id', $plantillaId)
            ->orderBy('orden', 'asc')
            ->get();

        // 📌 Configuración de columnas y filas
        $columnaIzquierda = 'B'; // Ahora comienza en B
        $columnaDerecha = 'N'; // Espacio de 2 columnas entre imágenes (M y X son espacios)
        $filaInicial = 4;
        $contador = 0;
        $imagenesPorPagina = 6;
        $paginaActual = 1;

        // 📌 Función para crear una nueva hoja con el encabezado
        $crearNuevaHoja = function ($spreadsheet, $pagina, $numeroAvaluo, $logoCliente) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle("Página {$pagina}");

            // 📌 Configurar columnas (A - X)
            foreach (range('A', 'X') as $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth(3.80);
            }

            // 📌 Configurar filas
            $sheet->getRowDimension(1)->setRowHeight(19.5);
            $sheet->getRowDimension(2)->setRowHeight(19.5);
            for ($i = 3; $i <= 100; $i++) {
                $sheet->getRowDimension($i)->setRowHeight(12.45);
            }

            // 📌 Encabezado
            $sheet->setCellValue('A1', 'ANEXO No. 2 (ESTUDIO FOTOGRÁFICO)');
            $sheet->setCellValue('A2', "AVALÚO No. {$numeroAvaluo}");

            // 📌 Unir celdas del título y logo (A - P para título, Q - X para logo)
            $sheet->mergeCells('A1:P1');
            $sheet->mergeCells('A2:P2');
            $sheet->mergeCells('Q1:X2'); // Espacio del logo

            // 📌 Insertar logo correctamente dentro del espacio de Q a X
            if ($logoCliente) {
                $logoPath = public_path("storage/{$logoCliente}");
                if (file_exists($logoPath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Logo Cliente');
                    $drawing->setPath($logoPath);
                    $drawing->setHeight(45); // 🔹 Ajuste de altura para que no se desborde
                    $drawing->setCoordinates('Q1');
                    $drawing->setOffsetX(10); // 🔹 Ajuste para centrar dentro del espacio
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                }
            }

            // 📌 Estilos del título
            $sheet->getStyle('A1:A2')->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 📌 Bordes del encabezado
            $sheet->getStyle('A1:X2')->applyFromArray([
                'borders' => [
                    'outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '00000000']],
                    'vertical' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '00000000']],
                ],
            ]);

            return $sheet;
        };

        // 📌 Crear la primera hoja
        $sheet = $crearNuevaHoja($spreadsheet, $paginaActual, $numeroAvaluo, $logoCliente);

        foreach ($imagenes as $index => $imagen) {
            $imgPath = public_path("storage/{$imagen['imagen']}");
        
            if (file_exists($imgPath)) {
                // 📌 Determinar la columna donde va la imagen
                $columna = ($contador % 2 == 0) ? $columnaIzquierda : $columnaDerecha;
        
                // 📌 Definir el rango de celdas que ocupa la imagen (10 columnas, 13 filas)
                $rangoColumnas = "{$columna}{$filaInicial}:" . chr(ord($columna) + 9) . ($filaInicial + 12);
                $sheet->mergeCells($rangoColumnas);
        
                // 📌 Tamaños fijos para imágenes horizontales y verticales
                $tamanioHorizontal = ['width' => 90, 'height' => 170]; // 🔹 Imagen horizontal (más ancha)
                $tamanioVertical = ['width' => 60, 'height' => 190];   // 🔹 Imagen vertical (más alta)
        
                // 📌 Obtener dimensiones originales de la imagen
                list($imgWidth, $imgHeight) = getimagesize($imgPath);
                $isHorizontal = $imgWidth > $imgHeight;
                
                // 📌 Seleccionar el tamaño según la orientación de la imagen
                $newWidth = $isHorizontal ? $tamanioHorizontal['width'] : $tamanioVertical['width'];
                $newHeight = $isHorizontal ? $tamanioHorizontal['height'] : $tamanioVertical['height'];
        
                // 📌 Espacio disponible en la hoja de Excel (10 columnas y 13 filas)
                $espacioAncho = 95;  // 🔹 Espacio de 10 columnas combinadas
                $espacioAlto = 195;  // 🔹 Espacio de 13 filas combinadas
        
                // 📌 Calcular el espacio libre a los lados para centrar la imagen horizontalmente
                $offsetX = ($espacioAncho - $newWidth) / 2;
                $offsetY = ($espacioAlto - $newHeight) / 2;
                
                
                $offsetX = $isHorizontal ? $offsetX   : $offsetX + 60; // 🔹 Ajuste para centrar verticalmente

                //dd($offsetX, $offsetY, $isHorizontal, $newWidth, $newHeight);
                // 📌 Insertar imagen centrada en las 10 columnas y 13 filas
                $drawing = new Drawing();
                $drawing->setName('Imagen');
                $drawing->setDescription($imagen['title']);
                $drawing->setPath($imgPath);
                $drawing->setWidth($newWidth);
                $drawing->setHeight($newHeight);
                $drawing->setCoordinates($columna . $filaInicial);
                $drawing->setOffsetX($offsetX); // 🔹 Centrar en las 10 columnas
                $drawing->setOffsetY($offsetY); // 🔹 Centrar en las 13 filas
                $drawing->setWorksheet($sheet);
        
                // 📌 Espacio entre la imagen y el título (1 fila)
                $filaTituloInicio = $filaInicial + 14;
                $filaTituloFin = $filaTituloInicio + 1; // 🔹 Ahora el título ocupa 2 filas combinadas
        
                // 📌 Insertar título en dos filas combinadas
                $rangoTitulo = "{$columna}{$filaTituloInicio}:" . chr(ord($columna) + 9) . "{$filaTituloFin}";
                $sheet->mergeCells($rangoTitulo);
                $sheet->setCellValue("{$columna}{$filaTituloInicio}", $imagen['title']);
        
                // 📌 Aplicar bordes al título en el rango combinado de 10 columnas y 2 filas
                $sheet->getStyle($rangoTitulo)->applyFromArray([
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '00000000']],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '00000000']],
                        'left' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '00000000']],
                        'right' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '00000000']],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                        
                // 📌 Incrementar contador
                $contador++;
        
                // 📌 Si es la segunda imagen en la fila, mover la fila inicial para la siguiente fila de imágenes
                if ($contador % 2 == 0) {
                    $filaInicial += 17; // **13 filas de imagen + 1 fila de separación + 1 fila de espacio + 2 filas de separación**
                }
        
                // 📌 Si llegamos al máximo de imágenes por página, crear nueva hoja
                if ($contador == $imagenesPorPagina && $index < count($imagenes) - 1) {
                // 📌 Aplicar bordes ANTES de cambiar de página
                $filaFinal = $filaTituloFin + 1; // 🔹 Agregar espacio extra antes del borde final

                    $sheet->getStyle("A1:A{$filaFinal}")->applyFromArray([
                        'borders' => [
                            'left' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '00000000']
                            ],
                        ],
                    ]);

                    $sheet->getStyle("X1:X{$filaFinal}")->applyFromArray([
                        'borders' => [
                            'right' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '00000000']
                            ],
                        ],
                    ]);

                    $sheet->getStyle("A{$filaFinal}:X{$filaFinal}")->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => '00000000']
                            ],
                        ],
                    ]);

                    // 📌 Cambiar de hoja
                    $paginaActual++;
                    $sheet = $crearNuevaHoja($spreadsheet, $paginaActual, $numeroAvaluo, $logoCliente);
                    $filaInicial = 4;
                    $contador = 0;
                }
            }

        } //foreach

        // 📌 Aplicar los bordes también en la última hoja
        $filaFinal = $filaTituloFin + 1; // 🔹 Agregar espacio extra antes del borde final

        $sheet->getStyle("A1:A54")->applyFromArray([
            'borders' => [
                'left' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000']
                ],
            ],
        ]);

        $sheet->getStyle("X1:X54")->applyFromArray([
            'borders' => [
                'right' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000']
                ],
            ],
        ]);

        $sheet->getStyle("A54:X54")->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '00000000']
                ],
            ],
        ]);

        // Guardar y descargar el archivo
        $writer = new Xlsx($spreadsheet);
        $fileName = "Avaluo_{$numeroAvaluo}.xlsx";
        $filePath = public_path("storage/{$fileName}");
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
   }
}
