# EscribirPDF

Sistema PHP para cargar documentos PDF de cualquier tamaño y agregar códigos QR, códigos de barras, imágenes y etiquetas de texto.

## Características

- ✅ **Cargar PDFs existentes** de cualquier tamaño
- ✅ **Crear PDFs nuevos** desde cero
- ✅ **Códigos QR** con múltiples tipos de datos (URLs, texto, email, teléfono, WiFi)
- ✅ **Códigos de barras** (CODE128, EAN13, EAN8, UPC-A, CODE39, CODE93)
- ✅ **Imágenes** en varios formatos
- ✅ **Etiquetas de texto** con personalización completa
- ✅ **Texto multilínea** con alineación y formato
- ✅ **Manejo de múltiples páginas**

## Requisitos

- PHP >= 7.4
- Composer (gestor de dependencias de PHP)

## ⚠️ INSTALACIÓN IMPORTANTE

### ⚙️ Paso 1: Instalar Composer (si no lo tienes)

**Windows (XAMPP/WAMP):**
1. Descarga: https://getcomposer.org/Composer-Setup.exe
2. Durante la instalación, selecciona: `C:\xampp\php\php.exe`
3. Verifica: `composer --version`

**Linux/Mac:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 🚀 Paso 2: Instalar las Dependencias

**OBLIGATORIO** - Ejecuta esto en la raíz del proyecto:

```bash
composer install
```

Este comando descargará todas las librerías necesarias (TCPDF, php-qrcode, php-barcode-generator).

### ✅ Paso 3: Verificar la Instalación

```bash
php verificar_instalacion.php
```

Si todo está bien, verás: `✅ ¡TODO ESTÁ CORRECTO!`

**📖 Más detalles:** Ver [INSTALACION.md](INSTALACION.md)

---

### 🔧 Instalación Sin Composer (Alternativa)

Si **no puedes instalar Composer**, tienes dos opciones:

#### Opción A: Descarga Automática
```bash
php descargar_dependencias.php
php crear_autoload_manual.php
```

#### Opción B: Descarga Manual
Descarga desde GitHub:
- **TCPDF:** https://github.com/tecnickcom/TCPDF/releases/tag/6.6.5
- **PHP-QRCode:** https://github.com/chillerlan/php-qrcode/releases/tag/4.3.4
- **PHP-Settings:** https://github.com/chillerlan/php-settings-container/releases/tag/2.1.4
- **Barcode:** https://github.com/picqer/php-barcode-generator/releases/tag/v2.4.0

**📋 Guía completa:** Ver [INSTALACION_MANUAL.md](INSTALACION_MANUAL.md)

## Uso Rápido

### Crear un PDF nuevo con códigos y etiquetas

```php
<?php
require_once 'vendor/autoload.php';

use EscribirPDF\PDFEditor;

$editor = new PDFEditor();
$editor->createNewPDF('P', 'A4');

// Agregar código QR
$editor->addQRCode('https://ejemplo.com', 20, 40, 30);

// Agregar código de barras
$editor->addBarcode('123456789012', 70, 50, 60, 15, 'CODE128');

// Agregar texto
$editor->addText('Mi Documento', 50, 20, 16, 'helvetica', 'B', [0, 0, 255]);

// Guardar
$editor->save('output/mi_documento.pdf');
```

### Cargar un PDF existente y modificarlo

```php
<?php
require_once 'vendor/autoload.php';

use EscribirPDF\PDFEditor;

$editor = new PDFEditor();
$editor->loadPDF('uploads/documento_original.pdf');

// Agregar código QR en la página 1
$editor->addQRCode('https://ejemplo.com/producto/123', 160, 10, 30, 1);

// Agregar sello
$editor->addText('REVISADO', 160, 45, 16, 'helvetica', 'B', [255, 0, 0], 'C', 40, 1);

// Guardar
$editor->save('output/documento_modificado.pdf');
```

## Documentación de la API

### Constructor

```php
$editor = new PDFEditor();
```

### Cargar/Crear PDF

#### `loadPDF(string $pdfPath): bool`
Carga un PDF existente.

```php
$editor->loadPDF('ruta/al/documento.pdf');
```

#### `createNewPDF(string $orientation = 'P', $size = 'A4'): void`
Crea un nuevo PDF vacío.

- **$orientation**: 'P' (Portrait/Vertical) o 'L' (Landscape/Horizontal)
- **$size**: 'A4', 'LETTER', o array [ancho, alto] en mm

```php
$editor->createNewPDF('P', 'A4');
```

### Agregar Códigos QR

#### `addQRCode(string $data, int $x, int $y, int $size = 30, int $page = null): bool`

```php
// QR con URL
$editor->addQRCode('https://ejemplo.com', 20, 40, 30);

// QR con email
$editor->addQRCode('mailto:info@ejemplo.com', 60, 40, 25);

// QR con teléfono
$editor->addQRCode('tel:+34123456789', 100, 40, 25);

// QR con WiFi
$editor->addQRCode('WIFI:T:WPA;S:MiRed;P:MiPassword;;', 140, 40, 25);

// QR en página específica
$editor->addQRCode('Datos', 20, 40, 30, 2); // Página 2
```

### Agregar Códigos de Barras

#### `addBarcode(string $data, int $x, int $y, int $width = 50, int $height = 15, string $type = 'CODE128', int $page = null): bool`

**Tipos soportados:**
- `CODE128` - Alfanumérico (por defecto)
- `EAN13` - 13 dígitos
- `EAN8` - 8 dígitos
- `UPC` - UPC-A, 12 dígitos
- `CODE39` - Alfanumérico
- `CODE93` - Alfanumérico

```php
// CODE128
$editor->addBarcode('ABC123456789', 20, 50, 60, 15, 'CODE128');

// EAN13
$editor->addBarcode('5901234123457', 20, 70, 60, 15, 'EAN13');

// UPC-A
$editor->addBarcode('012345678905', 20, 90, 60, 15, 'UPC');
```

### Agregar Imágenes

#### `addImage(string $imagePath, int $x, int $y, int $width = 0, int $height = 0, int $page = null): bool`

```php
// Con tamaño específico
$editor->addImage('ruta/imagen.jpg', 100, 50, 40, 30);

// Tamaño automático (mantiene proporción)
$editor->addImage('ruta/imagen.png', 100, 50);

// En página específica
$editor->addImage('ruta/imagen.jpg', 100, 50, 40, 30, 2);
```

### Agregar Texto

#### `addText(string $text, int $x, int $y, int $fontSize = 12, string $fontFamily = 'helvetica', string $fontStyle = '', array $color = [0,0,0], string $align = 'L', int $width = 0, int $page = null): bool`

**Parámetros:**
- **$fontStyle**: '' (normal), 'B' (negrita), 'I' (cursiva), 'BI' (negrita cursiva)
- **$color**: Array RGB [R, G, B] (0-255)
- **$align**: 'L' (izquierda), 'C' (centro), 'R' (derecha)
- **$width**: Ancho del área de texto (0 = sin límite)

```php
// Texto simple
$editor->addText('Hola Mundo', 20, 50);

// Texto con formato
$editor->addText(
    'IMPORTANTE',
    20, 50,
    16,              // Tamaño
    'helvetica',     // Fuente
    'B',             // Negrita
    [255, 0, 0],     // Color rojo
    'C',             // Centrado
    100              // Ancho
);

// Texto en página específica
$editor->addText('Texto', 20, 50, 12, 'helvetica', '', [0,0,0], 'L', 0, 2);
```

#### `addMultiLineText(string $text, int $x, int $y, int $width, int $height, int $fontSize = 10, string $fontFamily = 'helvetica', string $align = 'L', int $page = null): bool`

Para texto largo con múltiples líneas:

```php
$texto = "Este es un texto largo que se dividirá automáticamente en múltiples líneas según el ancho especificado.";

$editor->addMultiLineText(
    $texto,
    20,        // X
    100,       // Y
    170,       // Ancho
    5,         // Alto de línea
    10,        // Tamaño de fuente
    'helvetica',
    'J'        // Justificado
);
```

### Manejo de Páginas

#### `getPageCount(): int`
Obtiene el número total de páginas.

```php
$totalPages = $editor->getPageCount();
```

#### `addPage(string $orientation = 'P', $size = 'A4'): void`
Agrega una nueva página.

```php
$editor->addPage('P', 'A4');
```

### Guardar el PDF

#### `save(string $outputPath, string $mode = 'F'): mixed`

**Modos:**
- `F` - Guardar en archivo (por defecto)
- `I` - Mostrar en navegador (inline)
- `D` - Forzar descarga
- `S` - Retornar como string

```php
// Guardar en archivo
$editor->save('output/documento.pdf');

// Guardar con modo específico
$editor->save('output/documento.pdf', 'F');
```

#### `download(string $filename = 'documento.pdf'): void`
Forzar descarga directa.

```php
$editor->download('mi_documento.pdf');
```

#### `display(string $filename = 'documento.pdf'): void`
Mostrar en navegador.

```php
$editor->display('mi_documento.pdf');
```

#### `getPDFString(): string`
Obtener el PDF como string.

```php
$pdfContent = $editor->getPDFString();
```

## Ejemplos

El directorio `examples/` contiene varios ejemplos completos:

1. **ejemplo_completo.php** - Ejemplo completo con todos los tipos de elementos
2. **cargar_pdf_existente.php** - Cómo cargar y modificar un PDF existente
3. **tipos_codigos.php** - Catálogo de todos los tipos de códigos QR y de barras

### Ejecutar ejemplos

```bash
# Ejemplo completo
php examples/ejemplo_completo.php

# Cargar PDF existente (coloca un PDF en uploads/ primero)
php examples/cargar_pdf_existente.php

# Catálogo de códigos
php examples/tipos_codigos.php
```

## Estructura del Proyecto

```
escribirpdf/
├── src/
│   └── PDFEditor.php       # Clase principal
├── examples/               # Ejemplos de uso
│   ├── ejemplo_completo.php
│   ├── cargar_pdf_existente.php
│   └── tipos_codigos.php
├── uploads/                # PDFs originales
├── output/                 # PDFs generados
├── composer.json           # Dependencias
└── README.md              # Esta documentación
```

## Casos de Uso

### 1. Etiquetado de Productos
```php
$editor->loadPDF('factura.pdf');
$editor->addBarcode($codigoProducto, 20, 50, 60, 15, 'EAN13');
$editor->addQRCode($urlProducto, 160, 50, 30);
$editor->save('factura_etiquetada.pdf');
```

### 2. Sellos y Marcas de Agua
```php
$editor->loadPDF('documento.pdf');
$editor->addText('CONFIDENCIAL', 80, 140, 40, 'helvetica', 'B', [255, 0, 0]);
$editor->save('documento_sellado.pdf');
```

### 3. Códigos de Seguimiento
```php
$editor->loadPDF('pedido.pdf');
$editor->addBarcode($numeroSeguimiento, 20, 10, 70, 15, 'CODE128');
$editor->addText("Seguimiento: $numeroSeguimiento", 20, 27, 8);
$editor->save('pedido_con_seguimiento.pdf');
```

### 4. Información de Contacto
```php
$editor->createNewPDF();
$editor->addQRCode('mailto:contacto@empresa.com', 20, 40, 30);
$editor->addQRCode('tel:+34123456789', 60, 40, 30);
$editor->addText('Escanea para contactar', 20, 75, 10);
$editor->save('tarjeta_contacto.pdf');
```

## Solución de Problemas

### Error: "El archivo PDF no existe"
- Verifica que la ruta al PDF sea correcta
- Usa rutas absolutas o relativas correctas
- Asegúrate de que el archivo tenga permisos de lectura

### Error al guardar el PDF
- Verifica que el directorio de salida exista
- Asegúrate de tener permisos de escritura
- El directorio se crea automáticamente si no existe

### Códigos de barras no se generan
- Verifica que los datos cumplan con el formato requerido:
  - EAN13: exactamente 13 dígitos
  - EAN8: exactamente 8 dígitos
  - UPC-A: exactamente 12 dígitos

### Problemas con imágenes
- Formatos soportados: PNG, JPG, GIF
- Verifica que el archivo de imagen exista
- Asegúrate de que la imagen no esté corrupta

## Dependencias

- **TCPDF** (^6.6) - Generación y manipulación de PDFs
- **chillerlan/php-qrcode** (^4.3) - Generación de códigos QR
- **picqer/php-barcode-generator** (^2.4) - Generación de códigos de barras

## Licencia

MIT

## Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## Autor

LiamGael2023

## Soporte

Para reportar problemas o solicitar nuevas características, por favor abre un issue en GitHub.
