<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;
use App\Models\Plantilla;
use App\Models\RegistroFotografico;

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
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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

        // set text shadow effect
        $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        $imagenes = RegistroFotografico::where('plantilla_id', $plantillaId)
        ->orderBy('orden', 'asc')
        ->get();
    
    $columnaIzquierda = 15; // Posición X de la primera columna
    $columnaDerecha = 110; // Posición X de la segunda columna
    $posicionY = 40; // Posición inicial Y
    $anchoImagen = 80; // Ancho de la imagen
    $altoImagen = 60; // Alto de la imagen
    $contador = 0; // Contador de imágenes en la página
    
    foreach ($imagenes as $index => $imagen) {
        $imgPath = public_path("storage/{$imagen['imagen']}");
    
        if (file_exists($imgPath)) {
            // Calcular la posición X de la imagen
            $posX = ($contador % 2 == 0) ? $columnaIzquierda : $columnaDerecha;
    
            // Insertar la imagen
            $pdf->Image($imgPath, $posX, $posicionY, $anchoImagen, $altoImagen, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
    
            // Posición del título debajo de la imagen
            $pdf->SetXY($posX, $posicionY + $altoImagen + 2);
            $pdf->Cell($anchoImagen, 10, $imagen['title'] . $imagen['orden'], 0, 0, 'C');
    
            // Incrementar el contador
            $contador++;
    
            // Si ya se llenaron las 2 columnas en una fila, mover la posición Y para la siguiente fila
            if ($contador % 2 == 0) {
                $posicionY += $altoImagen + 20; // Ajustar espacio entre filas
            }
    
            // Si ya se llenaron las 6 imágenes en la página, agregar una nueva página
            if ($contador == 6) {
                $pdf->AddPage();
                $posicionY = 40; // Reiniciar la posición Y
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
