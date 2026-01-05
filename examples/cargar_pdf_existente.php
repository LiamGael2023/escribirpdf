<?php

require_once __DIR__ . '/../vendor/autoload.php';

use EscribirPDF\PDFEditor;

/**
 * Ejemplo: Cargar un PDF existente y agregarle elementos
 *
 * Este ejemplo muestra cómo cargar un documento PDF de cualquier tamaño
 * y agregarle códigos QR, códigos de barras, imágenes y etiquetas
 */

try {
    // Crear instancia del editor
    $editor = new PDFEditor();

    // Ruta al PDF que quieres modificar
    $pdfPath = __DIR__ . '/../uploads/documento_original.pdf';

    // Verificar si el archivo existe
    if (!file_exists($pdfPath)) {
        die("✗ Error: El archivo PDF no existe en: $pdfPath\n" .
            "  Por favor, coloca un PDF en la carpeta 'uploads' y actualiza la ruta.\n");
    }

    echo "Cargando PDF existente...\n";
    $editor->loadPDF($pdfPath);
    echo "✓ PDF cargado exitosamente\n";
    echo "✓ Total de páginas: " . $editor->getPageCount() . "\n\n";

    // Agregar código QR en la primera página (esquina superior derecha)
    echo "Agregando código QR en página 1...\n";
    $editor->addQRCode(
        'https://ejemplo.com/producto/12345',
        160,   // Posición X (esquina superior derecha)
        10,    // Posición Y
        30,    // Tamaño
        1      // Página 1
    );

    // Agregar código de barras en la primera página
    echo "Agregando código de barras en página 1...\n";
    $editor->addBarcode(
        '9876543210123',
        20,              // Posición X
        10,              // Posición Y
        60,              // Ancho
        15,              // Alto
        'EAN13',         // Tipo de código de barras
        1                // Página 1
    );

    // Agregar etiqueta/sello en la primera página
    echo "Agregando etiqueta...\n";
    $editor->addText(
        'REVISADO',
        160, 45,
        16,
        'helvetica',
        'B',
        [255, 0, 0],     // Rojo
        'C',
        40,
        1                // Página 1
    );

    // Si el PDF tiene múltiples páginas, agregar numeración
    $totalPages = $editor->getPageCount();
    if ($totalPages > 1) {
        echo "Agregando numeración de páginas...\n";
        for ($page = 1; $page <= $totalPages; $page++) {
            $editor->addText(
                "Página $page de $totalPages",
                170, 285,
                8,
                'helvetica',
                '',
                [128, 128, 128],
                'R',
                30,
                $page
            );
        }
    }

    // Agregar fecha y hora en el pie de la última página
    $editor->addText(
        'Modificado: ' . date('d/m/Y H:i:s'),
        20, 285,
        8,
        'helvetica',
        'I',
        [128, 128, 128],
        'L',
        0,
        $totalPages
    );

    // Guardar el PDF modificado
    $outputPath = __DIR__ . '/../output/documento_modificado.pdf';
    $editor->save($outputPath);

    echo "\n✓ PDF modificado guardado exitosamente en: $outputPath\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "  Traza: " . $e->getTraceAsString() . "\n";
}
