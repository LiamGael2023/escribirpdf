<?php

require_once __DIR__ . '/../vendor/autoload.php';

use EscribirPDF\PDFEditor;

/**
 * Ejemplo completo: Cargar un PDF existente y agregar QR, código de barras, imagen y etiquetas
 */

try {
    // Crear instancia del editor
    $editor = new PDFEditor();

    // OPCIÓN 1: Cargar un PDF existente
    // Descomenta la siguiente línea y proporciona la ruta a tu PDF
    // $editor->loadPDF(__DIR__ . '/../uploads/tu_documento.pdf');

    // OPCIÓN 2: Crear un nuevo PDF desde cero
    $editor->createNewPDF('P', 'A4'); // Portrait, tamaño A4

    // Agregar un título
    $editor->addText(
        'Documento PDF con Códigos y Etiquetas',
        50, 20,
        20,              // Tamaño de fuente
        'helvetica',     // Familia de fuente
        'B',             // Bold
        [0, 0, 255],     // Color RGB: Azul
        'C',             // Centrado
        110              // Ancho
    );

    // Agregar un código QR
    echo "Agregando código QR...\n";
    $editor->addQRCode(
        'https://github.com/LiamGael2023/escribirpdf', // Datos a codificar
        20,    // Posición X
        40,    // Posición Y
        35     // Tamaño
    );

    // Agregar etiqueta para el QR
    $editor->addText(
        'Código QR',
        15, 77,
        10,
        'helvetica',
        '',
        [0, 0, 0],
        'C',
        45
    );

    // Agregar un código de barras
    echo "Agregando código de barras...\n";
    $editor->addBarcode(
        '123456789012',  // Datos del código de barras
        70,              // Posición X
        50,              // Posición Y
        70,              // Ancho
        20,              // Alto
        'CODE128'        // Tipo de código de barras
    );

    // Agregar etiqueta para el código de barras
    $editor->addText(
        'Código de Barras: 123456789012',
        70, 72,
        9,
        'helvetica',
        '',
        [0, 0, 0],
        'L',
        70
    );

    // Agregar una imagen (si tienes una imagen disponible)
    // Descomenta las siguientes líneas y proporciona la ruta a tu imagen
    /*
    echo "Agregando imagen...\n";
    $editor->addImage(
        __DIR__ . '/../uploads/tu_imagen.jpg',
        150,   // Posición X
        40,    // Posición Y
        40,    // Ancho
        30     // Alto
    );
    */

    // Agregar texto informativo
    $editor->addText(
        'Información del Producto',
        20, 95,
        14,
        'helvetica',
        'B',
        [255, 0, 0],     // Rojo
        'L'
    );

    // Agregar texto multilínea
    $textoDescripcion = "Este es un ejemplo de cómo utilizar la librería EscribirPDF para cargar documentos PDF de cualquier tamaño y agregar diferentes elementos como códigos QR, códigos de barras, imágenes y etiquetas de texto.\n\nPuedes personalizar las posiciones, tamaños, colores y estilos de todos los elementos.";

    $editor->addMultiLineText(
        $textoDescripcion,
        20,    // X
        110,   // Y
        170,   // Ancho
        5,     // Alto de línea
        10,    // Tamaño de fuente
        'helvetica',
        'J'    // Justificado
    );

    // Agregar información adicional en el pie de página
    $editor->addText(
        'Generado con EscribirPDF - ' . date('d/m/Y H:i:s'),
        20, 270,
        8,
        'helvetica',
        'I',
        [128, 128, 128],  // Gris
        'L'
    );

    // Guardar el PDF
    $outputPath = __DIR__ . '/../output/documento_completo.pdf';
    $editor->save($outputPath);

    echo "\n✓ PDF guardado exitosamente en: $outputPath\n";
    echo "✓ Total de páginas: " . $editor->getPageCount() . "\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
