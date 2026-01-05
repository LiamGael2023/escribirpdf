<?php

require_once __DIR__ . '/../vendor/autoload.php';

use EscribirPDF\PDFEditor;

/**
 * Ejemplo: Diferentes tipos de códigos QR y códigos de barras
 */

try {
    $editor = new PDFEditor();
    $editor->createNewPDF('P', 'A4');

    // Título
    $editor->addText(
        'Catálogo de Códigos QR y Códigos de Barras',
        50, 15,
        16,
        'helvetica',
        'B',
        [0, 0, 128],
        'C',
        110
    );

    // ===== SECCIÓN: CÓDIGOS QR =====
    $editor->addText('Códigos QR:', 20, 30, 12, 'helvetica', 'B');

    // QR Code 1: URL
    $editor->addQRCode('https://github.com/LiamGael2023', 20, 40, 25);
    $editor->addText('URL/Link', 20, 67, 8, 'helvetica', '', [0, 0, 0], 'L', 25);

    // QR Code 2: Texto
    $editor->addQRCode('Información de producto #12345', 60, 40, 25);
    $editor->addText('Texto', 60, 67, 8, 'helvetica', '', [0, 0, 0], 'L', 25);

    // QR Code 3: Email
    $editor->addQRCode('mailto:info@ejemplo.com', 100, 40, 25);
    $editor->addText('Email', 100, 67, 8, 'helvetica', '', [0, 0, 0], 'L', 25);

    // QR Code 4: Teléfono
    $editor->addQRCode('tel:+34123456789', 140, 40, 25);
    $editor->addText('Teléfono', 140, 67, 8, 'helvetica', '', [0, 0, 0], 'L', 25);

    // QR Code 5: WiFi
    $wifiData = 'WIFI:T:WPA;S:NombreRed;P:Contraseña123;;';
    $editor->addQRCode($wifiData, 175, 40, 25);
    $editor->addText('WiFi', 175, 67, 8, 'helvetica', '', [0, 0, 0], 'L', 25);

    // ===== SECCIÓN: CÓDIGOS DE BARRAS =====
    $editor->addText('Códigos de Barras:', 20, 85, 12, 'helvetica', 'B');

    $yPos = 95;

    // CODE128
    $editor->addText('CODE128:', 20, $yPos, 9, 'helvetica', 'B');
    $editor->addBarcode('ABC123456789', 70, $yPos, 60, 12, 'CODE128');
    $yPos += 20;

    // EAN13
    $editor->addText('EAN13:', 20, $yPos, 9, 'helvetica', 'B');
    $editor->addBarcode('5901234123457', 70, $yPos, 60, 12, 'EAN13');
    $yPos += 20;

    // EAN8
    $editor->addText('EAN8:', 20, $yPos, 9, 'helvetica', 'B');
    $editor->addBarcode('12345670', 70, $yPos, 40, 12, 'EAN8');
    $yPos += 20;

    // UPC-A
    $editor->addText('UPC-A:', 20, $yPos, 9, 'helvetica', 'B');
    $editor->addBarcode('012345678905', 70, $yPos, 60, 12, 'UPC');
    $yPos += 20;

    // CODE39
    $editor->addText('CODE39:', 20, $yPos, 9, 'helvetica', 'B');
    $editor->addBarcode('PRODUCT-123', 70, $yPos, 60, 12, 'CODE39');
    $yPos += 20;

    // CODE93
    $editor->addText('CODE93:', 20, $yPos, 9, 'helvetica', 'B');
    $editor->addBarcode('CODE93-TEST', 70, $yPos, 60, 12, 'CODE93');
    $yPos += 20;

    // ===== SECCIÓN: CASOS DE USO =====
    $editor->addText('Casos de Uso Comunes:', 20, $yPos + 10, 12, 'helvetica', 'B');
    $yPos += 25;

    $casosUso = [
        "• Etiquetas de productos con QR para información adicional",
        "• Códigos de barras EAN13 para sistemas de punto de venta",
        "• Códigos QR para compartir WiFi en oficinas",
        "• Códigos CODE128 para seguimiento de inventario",
        "• Códigos UPC para productos comerciales",
        "• Códigos QR para enlaces a sitios web o redes sociales",
    ];

    foreach ($casosUso as $caso) {
        $editor->addText($caso, 20, $yPos, 9);
        $yPos += 7;
    }

    // Pie de página
    $editor->addText(
        'Generado con EscribirPDF - ' . date('d/m/Y H:i:s'),
        20, 275,
        7,
        'helvetica',
        'I',
        [128, 128, 128],
        'L'
    );

    // Guardar
    $outputPath = __DIR__ . '/../output/catalogo_codigos.pdf';
    $editor->save($outputPath);

    echo "✓ PDF generado exitosamente: $outputPath\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
