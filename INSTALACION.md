# GUÍA DE INSTALACIÓN RÁPIDA

## ⚠️ ANTES DE USAR - REQUISITOS OBLIGATORIOS

### 1. Instalar Composer (si no lo tienes)

#### Windows (XAMPP/WAMP):
1. Descarga Composer: https://getcomposer.org/Composer-Setup.exe
2. Ejecuta el instalador
3. Cuando pregunte por PHP, selecciona: `C:\xampp\php\php.exe`
4. Completa la instalación
5. Abre CMD y verifica: `composer --version`

#### Linux/Mac:
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### 2. Instalar las Dependencias del Proyecto

**OBLIGATORIO** - Abre la terminal en la carpeta del proyecto y ejecuta:

```bash
composer install
```

Esto descargará las librerías necesarias:
- ✅ TCPDF (manipulación de PDFs)
- ✅ php-qrcode (códigos QR)
- ✅ php-barcode-generator (códigos de barras)

Verás una carpeta `vendor/` creada con todas las dependencias.

### 3. Verificar la Instalación

Ejecuta el script de verificación:

```bash
php verificar_instalacion.php
```

### 4. Ejecutar los Ejemplos

Una vez instaladas las dependencias:

```bash
# Ejemplo completo
php examples/ejemplo_completo.php

# Catálogo de códigos
php examples/tipos_codigos.php
```

Los PDFs generados aparecerán en la carpeta `output/`

---

## ❌ Solución de Errores Comunes

### Error: "Failed to open stream: No such file or directory vendor/autoload.php"
**Solución:** No has ejecutado `composer install`. Hazlo desde la raíz del proyecto.

### Error: "composer: command not found"
**Solución:** Composer no está instalado o no está en el PATH. Instálalo siguiendo el Paso 1.

### Error al generar códigos QR o de barras
**Solución:** Las dependencias no se instalaron correctamente. Ejecuta:
```bash
composer update
```

---

## 📦 Estructura después de la instalación:

```
escribirpdf/
├── vendor/                    ← Se crea con "composer install"
│   ├── tecnickcom/tcpdf/
│   ├── chillerlan/php-qrcode/
│   └── picqer/php-barcode-generator/
├── src/
├── examples/
├── uploads/
├── output/
└── composer.json
```

---

## 🚀 Inicio Rápido (después de instalar)

```php
<?php
require_once 'vendor/autoload.php';
use EscribirPDF\PDFEditor;

$editor = new PDFEditor();
$editor->createNewPDF('P', 'A4');
$editor->addQRCode('https://ejemplo.com', 20, 40, 30);
$editor->addBarcode('123456789012', 70, 50, 60, 15);
$editor->addText('Mi Documento', 50, 20, 16);
$editor->save('output/mi_pdf.pdf');
echo "✓ PDF creado en output/mi_pdf.pdf\n";
```

---

## 💡 Nota Importante

**NO** subas la carpeta `vendor/` a Git. Ya está en `.gitignore`.
Cada desarrollador debe ejecutar `composer install` en su máquina.
