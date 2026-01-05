<?php
/**
 * Script para crear el archivo autoload.php manual
 * Ejecuta este script después de descargar las librerías manualmente
 */

echo "========================================\n";
echo "  CREAR AUTOLOAD MANUAL\n";
echo "========================================\n\n";

$vendorDir = __DIR__ . '/vendor';
$autoloadFile = $vendorDir . '/autoload.php';

// Verificar que existe el directorio vendor
if (!is_dir($vendorDir)) {
    echo "❌ Error: El directorio 'vendor/' no existe.\n";
    echo "   Créalo primero: mkdir vendor\n";
    exit(1);
}

// Verificar que existen las librerías
$libraries = [
    'TCPDF' => 'tecnickcom/tcpdf/tcpdf.php',
    'PHP-QRCode' => 'chillerlan/php-qrcode/src',
    'PHP-Settings-Container' => 'chillerlan/php-settings-container/src',
    'PHP-Barcode-Generator' => 'picqer/php-barcode-generator/src',
];

echo "Verificando librerías descargadas...\n";
$faltantes = [];
foreach ($libraries as $name => $path) {
    $fullPath = $vendorDir . '/' . $path;
    if (file_exists($fullPath)) {
        echo "✓ $name encontrado\n";
    } else {
        echo "✗ $name NO encontrado en: $path\n";
        $faltantes[] = $name;
    }
}

if (!empty($faltantes)) {
    echo "\n❌ Faltan las siguientes librerías:\n";
    foreach ($faltantes as $lib) {
        echo "   • $lib\n";
    }
    echo "\nConsulta INSTALACION_MANUAL.md para descargarlas.\n";
    exit(1);
}

echo "\n✓ Todas las librerías están presentes\n\n";

// Crear el contenido del autoload.php
$autoloadContent = <<<'PHP'
<?php
/**
 * Autoloader manual para EscribirPDF
 * Generado automáticamente
 */

// TCPDF
require_once __DIR__ . '/tecnickcom/tcpdf/tcpdf.php';

// Autoloader para chillerlan/php-settings-container
spl_autoload_register(function ($class) {
    $prefix = 'chillerlan\\Settings\\';
    $baseDir = __DIR__ . '/chillerlan/php-settings-container/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Autoloader para chillerlan/php-qrcode
spl_autoload_register(function ($class) {
    $prefix = 'chillerlan\\QRCode\\';
    $baseDir = __DIR__ . '/chillerlan/php-qrcode/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Autoloader para Picqer\Barcode
spl_autoload_register(function ($class) {
    $prefix = 'Picqer\\Barcode\\';
    $baseDir = __DIR__ . '/picqer/php-barcode-generator/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Autoloader para EscribirPDF (tu proyecto)
spl_autoload_register(function ($class) {
    $prefix = 'EscribirPDF\\';
    $baseDir = dirname(__DIR__) . '/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

PHP;

// Escribir el archivo
echo "Creando vendor/autoload.php...\n";
file_put_contents($autoloadFile, $autoloadContent);

if (file_exists($autoloadFile)) {
    echo "✓ Archivo autoload.php creado exitosamente\n\n";

    // Verificar que el autoload funciona
    echo "Verificando que el autoload funciona...\n";
    require_once $autoloadFile;

    $errores = [];

    // Verificar TCPDF
    if (class_exists('TCPDF')) {
        echo "✓ TCPDF cargado correctamente\n";
    } else {
        $errores[] = "TCPDF no se pudo cargar";
    }

    // Verificar QRCode
    if (class_exists('chillerlan\QRCode\QRCode')) {
        echo "✓ QRCode cargado correctamente\n";
    } else {
        $errores[] = "QRCode no se pudo cargar";
    }

    // Verificar Barcode
    if (class_exists('Picqer\Barcode\BarcodeGeneratorPNG')) {
        echo "✓ BarcodeGenerator cargado correctamente\n";
    } else {
        $errores[] = "BarcodeGenerator no se pudo cargar";
    }

    // Verificar PDFEditor
    if (class_exists('EscribirPDF\PDFEditor')) {
        echo "✓ PDFEditor cargado correctamente\n";
    } else {
        $errores[] = "PDFEditor no se pudo cargar";
    }

    if (empty($errores)) {
        echo "\n========================================\n";
        echo "✅ ¡CONFIGURACIÓN EXITOSA!\n";
        echo "========================================\n\n";
        echo "Ya puedes usar la librería. Prueba ejecutando:\n";
        echo "  php examples/ejemplo_completo.php\n";
        echo "  php examples/tipos_codigos.php\n\n";
    } else {
        echo "\n❌ Errores al cargar clases:\n";
        foreach ($errores as $error) {
            echo "   • $error\n";
        }
        echo "\nRevisa que las librerías estén en las rutas correctas.\n";
    }

} else {
    echo "❌ Error: No se pudo crear el archivo autoload.php\n";
    exit(1);
}
