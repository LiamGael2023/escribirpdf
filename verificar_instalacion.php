<?php
/**
 * Script para verificar que todas las dependencias están instaladas correctamente
 */

echo "\n========================================\n";
echo "  VERIFICACIÓN DE INSTALACIÓN\n";
echo "========================================\n\n";

$errores = [];
$advertencias = [];

// 1. Verificar versión de PHP
echo "1. Verificando versión de PHP...\n";
$phpVersion = phpversion();
echo "   PHP versión: $phpVersion\n";

if (version_compare($phpVersion, '7.4.0', '<')) {
    $errores[] = "PHP 7.4 o superior es requerido. Tienes: $phpVersion";
} else {
    echo "   ✓ Versión de PHP correcta\n";
}

// 2. Verificar Composer
echo "\n2. Verificando Composer...\n";
exec('composer --version 2>&1', $output, $returnCode);
if ($returnCode === 0) {
    echo "   ✓ Composer está instalado\n";
    echo "   " . $output[0] . "\n";
} else {
    $errores[] = "Composer NO está instalado. Instálalo desde https://getcomposer.org/";
}

// 3. Verificar directorio vendor
echo "\n3. Verificando dependencias...\n";
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    $errores[] = "Las dependencias NO están instaladas. Ejecuta: composer install";
    echo "   ✗ Falta el directorio vendor/\n";
} else {
    echo "   ✓ Directorio vendor/ existe\n";

    // Verificar librerías específicas
    require_once __DIR__ . '/vendor/autoload.php';

    // TCPDF
    if (class_exists('TCPDF')) {
        echo "   ✓ TCPDF instalado correctamente\n";
    } else {
        $errores[] = "TCPDF no está disponible";
    }

    // QRCode
    if (class_exists('chillerlan\QRCode\QRCode')) {
        echo "   ✓ php-qrcode instalado correctamente\n";
    } else {
        $errores[] = "php-qrcode no está disponible";
    }

    // Barcode Generator
    if (class_exists('Picqer\Barcode\BarcodeGeneratorPNG')) {
        echo "   ✓ php-barcode-generator instalado correctamente\n";
    } else {
        $errores[] = "php-barcode-generator no está disponible";
    }
}

// 4. Verificar directorios necesarios
echo "\n4. Verificando directorios...\n";
$directorios = ['src', 'examples', 'uploads', 'output'];
foreach ($directorios as $dir) {
    if (is_dir(__DIR__ . '/' . $dir)) {
        echo "   ✓ Directorio $dir/ existe\n";
    } else {
        $advertencias[] = "Directorio $dir/ no existe";
    }
}

// 5. Verificar permisos de escritura
echo "\n5. Verificando permisos de escritura...\n";
if (is_writable(__DIR__ . '/output')) {
    echo "   ✓ Directorio output/ tiene permisos de escritura\n";
} else {
    $advertencias[] = "El directorio output/ NO tiene permisos de escritura";
}

// 6. Verificar extensiones de PHP
echo "\n6. Verificando extensiones de PHP necesarias...\n";
$extensiones = ['gd', 'mbstring', 'zlib'];
foreach ($extensiones as $ext) {
    if (extension_loaded($ext)) {
        echo "   ✓ Extensión $ext habilitada\n";
    } else {
        $advertencias[] = "Extensión $ext NO está habilitada (puede ser necesaria)";
    }
}

// 7. Verificar clase principal
echo "\n7. Verificando clase PDFEditor...\n";
if (file_exists(__DIR__ . '/src/PDFEditor.php')) {
    echo "   ✓ Clase PDFEditor encontrada\n";

    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        if (class_exists('EscribirPDF\PDFEditor')) {
            echo "   ✓ Clase PDFEditor cargada correctamente\n";
        }
    }
} else {
    $errores[] = "Archivo src/PDFEditor.php no encontrado";
}

// RESUMEN
echo "\n========================================\n";
echo "  RESUMEN\n";
echo "========================================\n\n";

if (empty($errores) && empty($advertencias)) {
    echo "✅ ¡TODO ESTÁ CORRECTO!\n\n";
    echo "Ya puedes usar la librería. Prueba ejecutando:\n";
    echo "  php examples/ejemplo_completo.php\n";
    echo "  php examples/tipos_codigos.php\n\n";
} else {
    if (!empty($errores)) {
        echo "❌ ERRORES CRÍTICOS:\n";
        foreach ($errores as $error) {
            echo "   • $error\n";
        }
        echo "\n";

        if (in_array("Las dependencias NO están instaladas. Ejecuta: composer install", $errores)) {
            echo "🔧 ACCIÓN REQUERIDA:\n";
            echo "   Ejecuta en la terminal:\n";
            echo "   composer install\n\n";
        }
    }

    if (!empty($advertencias)) {
        echo "⚠️  ADVERTENCIAS:\n";
        foreach ($advertencias as $advertencia) {
            echo "   • $advertencia\n";
        }
        echo "\n";
    }
}

echo "========================================\n\n";
