<?php
/**
 * Script para descargar automáticamente las dependencias sin Composer
 * Ejecuta: php descargar_dependencias.php
 */

echo "========================================\n";
echo "  DESCARGA AUTOMÁTICA DE DEPENDENCIAS\n";
echo "========================================\n\n";

// Configuración de las librerías a descargar
$dependencies = [
    [
        'name' => 'TCPDF',
        'url' => 'https://github.com/tecnickcom/TCPDF/archive/refs/heads/main.zip',
        'extractTo' => 'vendor/tecnickcom/tcpdf',
        'zipFolder' => 'TCPDF-main',
    ],
    [
        'name' => 'PHP-QRCode',
        'url' => 'https://github.com/chillerlan/php-qrcode/archive/refs/tags/4.4.2.zip',
        'extractTo' => 'vendor/chillerlan/php-qrcode',
        'zipFolder' => 'php-qrcode-4.4.2',
    ],
    [
        'name' => 'PHP-Settings-Container',
        'url' => 'https://github.com/chillerlan/php-settings-container/archive/refs/tags/3.0.1.zip',
        'extractTo' => 'vendor/chillerlan/php-settings-container',
        'zipFolder' => 'php-settings-container-3.0.1',
    ],
    [
        'name' => 'PHP-Barcode-Generator',
        'url' => 'https://github.com/picqer/php-barcode-generator/archive/refs/tags/v2.4.0.zip',
        'extractTo' => 'vendor/picqer/php-barcode-generator',
        'zipFolder' => 'php-barcode-generator-2.4.0',
    ],
];

$tempDir = sys_get_temp_dir() . '/escribirpdf_deps';

// Verificar si la extensión zip está disponible
if (!class_exists('ZipArchive')) {
    echo "❌ Error: La extensión PHP 'zip' no está habilitada.\n";
    echo "   En XAMPP, edita php.ini y descomenta: extension=zip\n";
    echo "   Luego reinicia Apache.\n\n";
    echo "📌 ALTERNATIVA: Descarga manualmente desde:\n";
    echo "   - TCPDF: https://github.com/tecnickcom/TCPDF/releases\n";
    echo "   - PHP-QRCode: https://github.com/chillerlan/php-qrcode/releases\n";
    echo "   - Settings-Container: https://github.com/chillerlan/php-settings-container/releases\n";
    echo "   - Barcode-Generator: https://github.com/picqer/php-barcode-generator/releases\n\n";
    echo "   Y sigue las instrucciones en INSTALACION_MANUAL.md\n";
    exit(1);
}

// Crear directorio temporal
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}

// Crear directorio vendor
if (!is_dir('vendor')) {
    mkdir('vendor', 0777, true);
}

$errores = [];
$exitos = 0;

foreach ($dependencies as $dep) {
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📦 Procesando: {$dep['name']}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    $zipFile = $tempDir . '/' . basename($dep['url']);

    // Descargar archivo
    echo "⬇  Descargando desde GitHub...\n";
    echo "   URL: {$dep['url']}\n";

    $zipContent = @file_get_contents($dep['url']);

    if ($zipContent === false) {
        echo "❌ Error al descargar {$dep['name']}\n";
        echo "   Intenta descargarlo manualmente desde:\n";
        echo "   {$dep['url']}\n\n";
        $errores[] = $dep['name'];
        continue;
    }

    file_put_contents($zipFile, $zipContent);
    echo "✓  Descargado: " . formatBytes(strlen($zipContent)) . "\n";

    // Extraer archivo
    echo "📂 Extrayendo archivos...\n";
    $zip = new ZipArchive();

    if ($zip->open($zipFile) === true) {
        // Crear directorio de destino
        if (!is_dir($dep['extractTo'])) {
            mkdir($dep['extractTo'], 0777, true);
        }

        // Extraer
        $extractPath = $tempDir . '/' . $dep['name'];
        $zip->extractTo($extractPath);
        $zip->close();

        // Mover archivos del subdirectorio al destino
        $sourceDir = $extractPath . '/' . $dep['zipFolder'];

        if (is_dir($sourceDir)) {
            copyDirectory($sourceDir, $dep['extractTo']);
            echo "✓  Instalado en: {$dep['extractTo']}\n";
            $exitos++;
        } else {
            echo "❌ Error: No se encontró el directorio esperado en el ZIP\n";
            $errores[] = $dep['name'];
        }

        // Limpiar
        @unlink($zipFile);
        deleteDirectory($extractPath);

    } else {
        echo "❌ Error al extraer el archivo ZIP\n";
        $errores[] = $dep['name'];
    }

    echo "\n";
}

// Limpiar directorio temporal
@rmdir($tempDir);

// Resumen
echo "========================================\n";
echo "  RESUMEN\n";
echo "========================================\n\n";

if (empty($errores)) {
    echo "✅ Todas las dependencias se descargaron correctamente ($exitos/$exitos)\n\n";

    echo "🔧 Ahora ejecuta el siguiente comando para configurar el autoloader:\n\n";
    echo "   php crear_autoload_manual.php\n\n";

} else {
    echo "⚠️  Se descargaron $exitos/" . count($dependencies) . " dependencias\n\n";
    echo "❌ Errores con:\n";
    foreach ($errores as $error) {
        echo "   • $error\n";
    }
    echo "\n📖 Consulta INSTALACION_MANUAL.md para descargarlas manualmente.\n\n";
}

echo "========================================\n";

// Funciones auxiliares

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function copyDirectory($source, $dest) {
    if (!is_dir($dest)) {
        mkdir($dest, 0777, true);
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        $destPath = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
        if ($item->isDir()) {
            if (!is_dir($destPath)) {
                mkdir($destPath, 0777, true);
            }
        } else {
            copy($item, $destPath);
        }
    }
}

function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $item) {
        if ($item->isDir()) {
            @rmdir($item->getRealPath());
        } else {
            @unlink($item->getRealPath());
        }
    }

    @rmdir($dir);
}
