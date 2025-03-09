<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;
use App\Models\Plantilla;
use App\Models\RegistroFotografico;

class CustomPDF extends TCPDF {

    protected $numeroAvaluo;
    protected $logoCliente;

    public function setHeaderDataCustom($numeroAvaluo, $logoCliente) {
        $this->numeroAvaluo = $numeroAvaluo;
        $this->logoCliente = $logoCliente;
    }
    // Sobrescribir el método Header()
    public function Header() {
        // Definir la posición y tamaño del rectángulo
        $this->SetXY(10, 5); // Posición X, Y
        $this->Cell(190, 20, '', 1, 1, 'C'); // Borde del header
        
        // 🔹 Dibujar línea divisoria entre las columnas
        $this->Line(115, 5, 115, 25); // (x1, y1, x2, y2)

        // 🔹 Dibujar líneas verticales en los lados izquierdo y derecho
        $this->Line(10, 5, 10, 287);  // Izquierda (de header a footer)
        $this->Line(200, 5, 200, 287); // Derecha (de header a footer)

        // Configurar la fuente para el texto del header
        $this->SetFont('helvetica', 'B', 10);

        // Columna 1 (Texto)
        $this->SetXY(15, 10); // Ajustar posición dentro del borde
        $this->MultiCell(100, 10, "ANEXO No. 2 (ESTUDIO FOTOGRÁFICO)\nAVALÚO No. {$this->numeroAvaluo}", 0, 'C');

        // Columna 2 (Logo)
        $this->SetXY(130, 7);
        $logoPath = public_path("storage/{$this->logoCliente}");// Ruta del logo
        if (file_exists($logoPath)) {
            $this->Image($logoPath, 140, 8, 40, 15); // X, Y, Ancho (Alto se ajusta automáticamente)
        }
    }

    // 🔹 Sobrescribir el método Footer() - Footer Personalizado
    public function Footer() {
        // Posicionar el footer en la parte inferior
        $this->SetY(-20);

        // 🔹 Dibujar línea final del marco
        $this->Line(10, 287, 200, 287); // Línea horizontal final del marco

        // 🔹 Configurar fuente y texto del footer
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, 0, 'C');
    }
}

class PDFController extends Controller
{
    public function generarPDF($plantillaId){

        //============================================================+
        // File name   : example_001.php
        // Begin       : 2008-03-04
        // Last Update : 2013-05-14
        //
        // Description : Example 001 for TCPDF class
        //               Default Header and Footer
        //
        // Author: Nicola Asuni
        //
        // (c) Copyright:
        //               Nicola Asuni
        //               Tecnick.com LTD
        //               www.tecnick.com
        //               info@tecnick.com
        //============================================================+

        /**
         * Creates an example PDF TEST document using TCPDF
         * @package com.tecnick.tcpdf
         * @abstract TCPDF - Example: Default Header and Footer
         * @author Nicola Asuni
         * @since 2008-03-04
         */

        // Include the main TCPDF library (search for installation path).
        //require_once('tcpdf_include.php');

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 001');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        // Crear instancia del PDF con la clase personalizada
        $pdf = new CustomPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $query = Plantilla::with([
            'informacionVisita.avaluo.cliente' // Incluir la relación con el cliente dentro del avalúo
        ])
        ->where('id', $plantillaId); // Filtrar por el ID de la plantilla
        $plantilla = $query->first(); // Ejecutar la consulta
        
        // Obtener los datos
        $numeroAvaluo = $plantilla->informacionVisita->avaluo->numero_avaluo ?? 'Sin número de avalúo';
        $logoCliente = $plantilla->informacionVisita->avaluo->cliente->logo ?? null;
        // Mostrar en pantalla
        /*dd([
            'Número de Avalúo' => $numeroAvaluo,
            'Logo del Cliente' => $logoCliente,
        ]);*/
        
        // Pasar los datos al header
        $pdf->setHeaderDataCustom($numeroAvaluo, $logoCliente);
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        //$pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 0, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 14, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();


    $imagenes = RegistroFotografico::where('plantilla_id', $plantillaId)
    ->orderBy('orden', 'asc')
    ->get();
    
    $columnaAncho = 80; // Ancho total de cada columna (ajustado para centrar)
    $columnaIzquierda = 15; // Posición X de la primera columna
    $columnaDerecha = 110; // Posición X de la segunda columna
    $posicionY = 30; // Posición inicial Y
    $anchoTitle = 80; // Ancho de la imagen
    //$altoImagen = 60; // Alto de la imagen
    $contador = 0; // Contador de imágenes en la página
    
    foreach ($imagenes as $index => $imagen) {
        $imgPath = public_path("storage/{$imagen['imagen']}");
    
        if (file_exists($imgPath)) {
            // Obtener dimensiones de la imagen
            list($width, $height) = getimagesize($imgPath);

            // Definir tamaño según la orientación
            if ($width > $height) {
                // Imagen horizontal
                $anchoImagen = 80;
                $altoImagen = 60;
            } else {
                // Imagen vertical
                $anchoImagen = 50;
                $altoImagen = 60;
            }
            // Determinar la columna
            $columnaX = ($contador % 2 == 0) ? $columnaIzquierda : $columnaDerecha;

            // Calcular la posición X para centrar la imagen dentro de la columna
            $posX = $columnaX + (($columnaAncho - $anchoImagen) / 2);
    
            // Insertar la imagen
            $pdf->Image($imgPath, $posX, $posicionY, $anchoImagen, $altoImagen, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
            
            // Calcular la posición X de la imagen
            $posX = ($contador % 2 == 0) ? $columnaIzquierda : $columnaDerecha;
            // Posición del título debajo de la imagen
            $pdf->SetXY($posX, $posicionY + $altoImagen + 2);
            $pdf->Cell($anchoTitle, 10, $imagen['title'], 1, 1, 'C', 0, '', 1);
    
            // Incrementar el contador
            $contador++;
    
            // Si ya se llenaron las 2 columnas en una fila, mover la posición Y para la siguiente fila
            if ($contador % 2 == 0) {
                $posicionY += $altoImagen + 20; // Ajustar espacio entre filas
            }
    
            // Si ya se llenaron las 6 imágenes en la página, agregar una nueva página
            if ($contador == 6) {
                $pdf->AddPage();
                $posicionY = 30; // Reiniciar la posición Y
                $contador = 0; // Reiniciar el contador
            }
        }
    }
        // ---------------------------------------------------------

        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->Output('example_001.pdf', 'I');

        //============================================================+
        // END OF FILE
        //============================================================+

    }
}
