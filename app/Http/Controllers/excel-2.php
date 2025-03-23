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
        //$spreadsheet->removeSheetByIndex(0);
        // 📌 Crear la hoja de trabajo principal
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Estudio Fotográfico");
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
        $contador = 0;
        $filaInicial = 4;
        $maxFilasPorPagina = 55; // 🔹 Máximo de filas antes de repetir encabezado
        $ultimaFilaEncabezado = 1; // Guarda la última fila donde se insertó el encabezado
        $esPrimerEncabezado = true; // Variable para saber si es el primer encabezado



            // 📌 Configurar columnas (A - X)
            foreach (range('A', 'X') as $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth(3.80);
            }

            // 📌 Configurar filas
            $sheet->getRowDimension(1)->setRowHeight(19.5);
            $sheet->getRowDimension(2)->setRowHeight(19.5);
            for ($i = 3; $i <= 100; $i++) {
                $sheet->getRowDimension($i)->setRowHeight(12.75);
            }


        // 📌 Función para insertar encabezado
        $insertarEncabezado = function ($sheet, $fila, $numeroAvaluo, $logoCliente) {

            // Aplicar altura de fila a los encabezados
            $sheet->getRowDimension($fila)->setRowHeight(19.5);
            $sheet->getRowDimension($fila + 1)->setRowHeight(19.5);
            // 📌 Encabezado
            $sheet->setCellValue("A{$fila}", 'ANEXO No. 2 (ESTUDIO FOTOGRÁFICO)');
            $sheet->setCellValue("A" . ($fila + 1), "AVALÚO No. {$numeroAvaluo}");
            $sheet->mergeCells("A{$fila}:P{$fila}");
            $sheet->mergeCells("A" . ($fila + 1) . ":P" . ($fila + 1));
            $sheet->mergeCells("Q{$fila}:X" . ($fila + 1));

            // 📌 Insertar logo
            if ($logoCliente) {
                $logoPath = public_path("storage/{$logoCliente}");
                if (file_exists($logoPath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Logo Cliente');
                    $drawing->setPath($logoPath);
                    $drawing->setHeight(45);
                    $drawing->setCoordinates("Q{$fila}");
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(5);
                    $drawing->setWorksheet($sheet);
                }
            }

            // 📌 Estilos
            $sheet->getStyle("A{$fila}:A" . ($fila + 1))->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 📌 Bordes
            $sheet->getStyle("A{$fila}:X" . ($fila + 1))->applyFromArray([
                'borders' => [
                    'outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '00000000']],
                ],
            ]);
        };

        // 📌 Insertar el primer encabezado
        $insertarEncabezado($sheet, 1, $numeroAvaluo, $logoCliente);

        foreach ($imagenes as $index => $imagen) {
            \Log::info("Última fila de encabezado antes: " . $ultimaFilaEncabezado);
            \Log::info('Fila inicial antes de procesar imagen: ' . $filaInicial);
            // 📌 Insertar encabezado en las filas 1, 58, 115, 172...
            
            /*$filasEncabezado = [55, 89, 110]; // Puedes agregar más si hay más páginas
            if (in_array($filaInicial, $filasEncabezado)) {
                $insertarEncabezado($sheet, $filaInicial, $numeroAvaluo, $logoCliente);
                \Log::info("Se insertó encabezado en la fila: " . $filaInicial);
                $filaInicial += 4; // Saltar línea vacía después del encabezado
            }*/

            // 📌 Si se alcanzó el límite de filas desde el último encabezado, insertar uno nuevo
            if (($filaInicial - $ultimaFilaEncabezado) >= ($maxFilasPorPagina - 17)) {
                $insertarEncabezado($sheet, $filaInicial, $numeroAvaluo, $logoCliente);
                \Log::info("Se insertó encabezado en la fila: " . $filaInicial);
                //$ultimaFilaEncabezado = $filaInicial; // Actualizar la última fila de encabezado
                //$filaInicial += 4; // Saltar espacio después del encabezado
                    // 📌 Si es el primer encabezado, mantener la separación normal; si no, solo agregar 1 fila
                // 📌 Ahora siempre dejamos 3 filas antes del próximo encabezado
                $filaInicial += 3;
                
                $ultimaFilaEncabezado = $filaInicial; // Actualizar la última fila de encabezado

            }

            // 📌 Insertar imágenes
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
                \Log::info('***Final de cada titulo: ****' . $filaTituloFin);
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
    
            }

            \Log::info('Fila inicial después de insertar imagen: ' . ($filaInicial)); // Suponiendo que ocupas 13 filas por imagen
            \Log::info("Última fila de encabezado actualizada: " . $ultimaFilaEncabezado);
        } //foreach
        \Log::info('--------------------------------------');
        \Log::info("\n\n\n\n\n\n\n");
        // 📌 Aplicar los bordes también en la última hoja
        $filaFinal = $filaTituloFin;

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

        // Guardar y descargar el archivo
        $writer = new Xlsx($spreadsheet);
        $fileName = "Avaluo_{$numeroAvaluo}.xlsx";
        $filePath = public_path("storage/{$fileName}");
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
   }
}
