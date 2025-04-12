<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\Plantilla;
use App\Models\RegistroFotografico;
use Illuminate\Support\Facades\Log; // Asegúrate de tener este use arriba


class ExcelExportController extends Controller
{
    
    public function generarExcel($plantillaId)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Estudio Fotográfico");

        // Configuración de página
        $pageSetup = $sheet->getPageSetup();
        $pageSetup->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $pageSetup->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER);
        $pageSetup->setFitToPage(true);
        $pageSetup->setFitToWidth(1);
        $pageSetup->setFitToHeight(0);
        $pageSetup->setHorizontalCentered(true);
        $pageSetup->setVerticalCentered(false);
        

        $sheet->getPageMargins()
            ->setTop(0.4)
            ->setRight(0.5)
            ->setLeft(0.5)
            ->setBottom(0.4)
            ->setHeader(0.2)
            ->setFooter(0.2);

        // Configurar encabezados y pies de página
        /*$sheet->getHeaderFooter()
            ->setOddHeader('&C&Avalúo No. ' . ($numeroAvaluo ?? ''))
            ->setOddFooter('&CPágina &P de &N');*/

        // Obtener datos de la plantilla
        $plantilla = Plantilla::with(['informacionVisita.avaluo.cliente'])
            ->findOrFail($plantillaId);

        $numeroAvaluo = $plantilla->informacionVisita->avaluo->numero_avaluo ?? 'Sin número de avalúo';
        $logoCliente = $plantilla->informacionVisita->avaluo->cliente->logo ?? null;
        $imagenes = RegistroFotografico::where('plantilla_id', $plantillaId)
            ->orderBy('orden', 'asc')
            ->get();

        // Configuración de columnas y filas
        $columnaIzquierda = 'B';
        $columnaDerecha = 'N';
        $contador = 0;
        $filaInicial = 4;
        $maxFilasPorPagina = 55;
        $ultimaFilaEncabezado = 1;
        //$esPrimerEncabezado = true;
        $saltosPagina = []; // Para almacenar los saltos de página
        $areasImpresion = []; // Array para almacenar todas las áreas de impresión
        $filasEncabezadoGlobal = [];
        // Configurar columnas (A - X)
        foreach (range('A', 'X') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(3.80);
        }

        // Configurar filas
        $sheet->getRowDimension(1)->setRowHeight(19.5);
        $sheet->getRowDimension(2)->setRowHeight(19.5);
        for ($i = 3; $i <= 100; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(12.75);
        }

        // Función para insertar encabezado use (&$areasImpresion, $maxFilasPorPagina)
        $insertarEncabezado = function ($sheet, $fila, $numeroAvaluo, $logoCliente) use (&$filasEncabezadoGlobal) {

            $filasEncabezadoGlobal[] = $fila;
            $filasEncabezadoGlobal[] = $fila + 1;
            // Aplicar altura de fila a los encabezados
            $sheet->getRowDimension($fila)->setRowHeight(19.5);
            $sheet->getRowDimension($fila + 1)->setRowHeight(19.5);

            // Encabezado
            $sheet->setCellValue("A{$fila}", 'ANEXO No. 2 (ESTUDIO FOTOGRÁFICO)');
            $sheet->setCellValue("A" . ($fila + 1), "AVALÚO No. {$numeroAvaluo}");
            $sheet->mergeCells("A{$fila}:P{$fila}");
            $sheet->mergeCells("A" . ($fila + 1) . ":P" . ($fila + 1));
            $sheet->mergeCells("Q{$fila}:X" . ($fila + 1));

            // Insertar logo
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

            // Estilos
            $sheet->getStyle("A{$fila}:A" . ($fila + 1))->applyFromArray([
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // 🔹 Línea divisoria entre texto y logo
            $sheet->getStyle("P{$fila}:P" . ($fila + 1))->applyFromArray([
                'borders' => [
                    'right' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);
            
            // Bordes
            $sheet->getStyle("A{$fila}:X" . ($fila + 1))->applyFromArray([
                'borders' => [
                    'outline' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '00000000']],
                ],
            ]);

            // Establecer área de impresión para esta página // Ahora agregamos al array:
            //$areasImpresion[] = "A{$fila}:X" . ($fila + 53);
        };

        // Insertar el primer encabezado
        $inicioArea = 1;
        $insertarEncabezado($sheet, 1, $numeroAvaluo, $logoCliente);

        foreach ($imagenes as $index => $imagen) {
            // Verificar si necesitamos un nuevo encabezado (nueva página)
            if (($filaInicial - $ultimaFilaEncabezado) >= ($maxFilasPorPagina - 17)) {
                // Agregar salto de página ANTES del nuevo encabezado
                $saltosPagina[] = $filaInicial - 1; // Salto antes del encabezado

                // Guardar área de impresión antes del nuevo encabezado
                $areasImpresion[] = "A{$inicioArea}:X" . ($filaInicial - 1);
                $inicioArea = $filaInicial;
                
                // Insertar nuevo encabezado
                $insertarEncabezado($sheet, $filaInicial, $numeroAvaluo, $logoCliente);
                $ultimaFilaEncabezado = $filaInicial;
                $filaInicial += 3;
                
            }

            // Insertar imágenes
            $imgPath = public_path("storage/{$imagen['imagen']}");
            
            if (file_exists($imgPath)) {
                $columna = ($contador % 2 == 0) ? $columnaIzquierda : $columnaDerecha;
                $rangoColumnas = "{$columna}{$filaInicial}:" . chr(ord($columna) + 9) . ($filaInicial + 12);
                $sheet->mergeCells($rangoColumnas);

                // Tamaños para imágenes
                //$tamanioHorizontal = ['width' => 90, 'height' => 170];
                //$tamanioVertical = ['width' => 60, 'height' => 190];

                list($imgWidth, $imgHeight) = getimagesize($imgPath);
                $isHorizontal = $imgWidth > $imgHeight;
                \Log::info("IMAGEN: {}" .$imagen['title'] . ' HORIZONTAL ' . $isHorizontal);
                \Log::info("ANCHO" . $imgWidth . ' ALTO ' . $imgHeight);
                /*$newWidth = $isHorizontal ? $tamanioHorizontal['width'] : $tamanioVertical['width'];
                $newHeight = $isHorizontal ? $tamanioHorizontal['height'] : $tamanioVertical['height'];*/
                // Dimensiones máximas permitidas (ajústalas si quieres)
                $maxWidth = $isHorizontal ? 90 : 60;
                $maxHeight = $isHorizontal ? 150 : 170;
                // 🔄 Calcular escala para mantener proporción
                $scale = max($maxWidth / $imgWidth, $maxHeight / $imgHeight);

                $newWidth = $imgWidth * $scale;
                $newHeight = $imgHeight * $scale;
                
                // Limitar por si se pasa del espacio real (puede ocurrir con imágenes muy pequeñas)
                $newWidth = min($newWidth, $maxWidth);
                $newHeight = min($newHeight, $maxHeight - 10);

                $espacioAncho = 95;
                $espacioAlto = 195;

                $offsetX = ($espacioAncho - $newWidth) / 2;
                $offsetY = max(0, ($espacioAlto - $newHeight) / 2);//$offsetY = ($espacioAlto - $newHeight) / 2;
                //$offsetX = $isHorizontal ? $offsetX : $offsetX + 60;

                // Insertar imagen
                $drawing = new Drawing();
                $drawing->setName('Imagen');
                $drawing->setDescription($imagen['title']);
                $drawing->setPath($imgPath);
                $drawing->setWidth($newWidth);
                $drawing->setHeight($newHeight);
                $drawing->setResizeProportional(false); // <--- importante para que respete tamaño fijo
                $drawing->setCoordinates($columna . $filaInicial);
                $drawing->setOffsetX($offsetX);
                $drawing->setOffsetY($offsetY);
                $drawing->setWorksheet($sheet);

                /*$imageResource = null;
                $extension = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        $imageResource = imagecreatefromjpeg($imgPath);
                        break;
                    case 'png':
                        $imageResource = imagecreatefrompng($imgPath);
                        break;
                    case 'gif':
                        $imageResource = imagecreatefromgif($imgPath);
                        break;
                }

                if ($imageResource) {
                    $memoryDrawing = new MemoryDrawing();
                    $memoryDrawing->setName('Imagen');
                    $memoryDrawing->setDescription($imagen['title']);
                    $memoryDrawing->setImageResource($imageResource);
                    $memoryDrawing->setRenderingFunction(MemoryDrawing::RENDERING_JPEG);
                    $memoryDrawing->setMimeType(MemoryDrawing::MIMETYPE_DEFAULT);
                    $memoryDrawing->setHeight($newHeight);
                    $memoryDrawing->setCoordinates($columna . $filaInicial);
                    $memoryDrawing->setOffsetX($offsetX);
                    $memoryDrawing->setOffsetY($offsetY);
                    $memoryDrawing->setWorksheet($sheet);
                }*/

                // Insertar título
                $filaTituloInicio = $filaInicial + 14;
                $filaTituloFin = $filaTituloInicio + 1;
                
                $rangoTitulo = "{$columna}{$filaTituloInicio}:" . chr(ord($columna) + 9) . "{$filaTituloFin}";
                $sheet->mergeCells($rangoTitulo);
                $sheet->setCellValue("{$columna}{$filaTituloInicio}", $imagen['title']);

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
                        
                $contador++;

                if ($contador % 2 == 0) {
                    $filaInicial += 17;
                }
            }
        }
        // Y al final del foreach
        $ultimaFilaContenido = isset($filaTituloFin) ? $filaTituloFin + 1 : $filaInicial - 1;
        if ($ultimaFilaContenido > $inicioArea) {
            $areasImpresion[] = "A{$inicioArea}:X{$ultimaFilaContenido}";
        }
        // 🔧 Ajustar última área de impresión a la última fila usada para evitar desbordes
        if (!empty($areasImpresion)) {
            $ultimaAreaIndex = count($areasImpresion) - 1;

            // Obtener inicio del rango (ej: "A4") y reconstruir el nuevo área
            $inicioArea = explode(':', $areasImpresion[$ultimaAreaIndex])[0];
            $ultimaFilaUsada = isset($filaTituloFin) && $filaTituloFin > 0 ? $filaTituloFin + 1 : $filaInicial;
            $areasImpresion[$ultimaAreaIndex] = "{$inicioArea}:X{$ultimaFilaUsada}";
        }
        // Aplicar todos los saltos de página al final
        foreach ($saltosPagina as $filaSalto) {
            $sheet->setBreak("A{$filaSalto}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
        }

        
        foreach ($areasImpresion as $area) {
            \Log::info("Área de impresión: {$area}");

            if (preg_match('/^[A-Z]+(\d+):[A-Z]+(\d+)$/', $area, $matches)) {
                $inicio = (int)$matches[1];
                $fin = (int)$matches[2];
        
                \Log::info("✅ Coincidencia encontrada. Fila inicio: {$inicio}, fila fin: {$fin}");
        
                // Asegurar que las filas existen
                foreach (range($inicio, $fin) as $i) {
                    if (!in_array($i, $filasEncabezadoGlobal)) {
                        $sheet->getRowDimension($i)->setRowHeight(12.75);
                    }
                }
        
                // Aplicar borde inferior
                $sheet->getStyle("A{$fin}:X{$fin}")->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                        'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ]);

                // Borde izquierdo
                $sheet->getStyle("A{$inicio}:A{$fin}")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ]);

                // Borde derecho
                $sheet->getStyle("X{$inicio}:X{$fin}")->applyFromArray([
                    'borders' => [
                        'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '00000000'],
                        ],
                    ],
                ]);
        
                \Log::info("🟢 Borde inferior aplicado a A{$fin}:X{$fin}");
            } else {
                \Log::info("❌ Área de impresión no coincide con el patrón esperado: {$area}");
            }
        }

        // Configurar TODAS las áreas de impresión al final
        if (!empty($areasImpresion)) {
            //$sheet->getPageSetup()->setPrintArea(implode(',', $areasImpresion));
            $ultimaFilaUsada = $sheet->getHighestRow();
            $spreadsheet->garbageCollect(); // Limpia rangos no usados internamente
            $sheet->getPageSetup()->setPrintArea("A1:X{$ultimaFilaContenido}");

            // Opcional: resetear altura de filas posteriores
            $maxFila = $sheet->getHighestRow();
            for ($i = $ultimaFilaContenido + 1; $i <= $maxFila; $i++) {
                $sheet->getRowDimension($i)->setRowHeight(-1); // -1 restaura a altura predeterminada
            }
        }
    
        
        // Guardar y descargar el archivo
        $writer = new Xlsx($spreadsheet);
        $fileName = "Avaluo_{$numeroAvaluo}.xlsx";
        $filePath = public_path("storage/{$fileName}");
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}