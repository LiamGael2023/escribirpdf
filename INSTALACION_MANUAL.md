# INSTALACIÓN MANUAL (Sin Composer)

Si no puedes instalar Composer, sigue esta guía para descargar las librerías manualmente.

## 📥 Descargar las Librerías

### Opción 1: Descarga Directa desde GitHub

#### 1. TCPDF (Generación de PDFs)
- **Descargar:** https://github.com/tecnickcom/TCPDF/archive/refs/heads/main.zip
- **Extraer en:** `vendor/tecnickcom/tcpdf/`

**Pasos:**
```
1. Descarga el ZIP desde el enlace
2. Extrae el contenido
3. Crea la carpeta: C:\xampp\htdocs\escribirpdf\vendor\tecnickcom\tcpdf\
4. Copia todo el contenido de la carpeta extraída (TCPDF-main) dentro de tcpdf\
```

#### 2. PHP-QRCode (Códigos QR)
- **Descargar:** https://github.com/chillerlan/php-qrcode/archive/refs/tags/4.4.2.zip
- **Extraer en:** `vendor/chillerlan/php-qrcode/`

**Pasos:**
```
1. Descarga el ZIP desde el enlace
2. Extrae el contenido
3. Crea la carpeta: C:\xampp\htdocs\escribirpdf\vendor\chillerlan\php-qrcode\
4. Copia todo el contenido de la carpeta extraída (php-qrcode-4.4.2) dentro de php-qrcode\
```

#### 3. PHP Settings Container (Dependencia de QRCode)
- **Descargar:** https://github.com/chillerlan/php-settings-container/archive/refs/tags/3.0.1.zip
- **Extraer en:** `vendor/chillerlan/php-settings-container/`

**Pasos:**
```
1. Descarga el ZIP desde el enlace
2. Extrae el contenido
3. Crea la carpeta: C:\xampp\htdocs\escribirpdf\vendor\chillerlan\php-settings-container\
4. Copia todo el contenido de la carpeta extraída (php-settings-container-3.0.1) dentro de php-settings-container\
```

#### 4. PHP Barcode Generator (Códigos de Barras)
- **Descargar:** https://github.com/picqer/php-barcode-generator/archive/refs/tags/v2.4.0.zip
- **Extraer en:** `vendor/picqer/php-barcode-generator/`

**Pasos:**
```
1. Descarga el ZIP desde el enlace
2. Extrae el contenido
3. Crea la carpeta: C:\xampp\htdocs\escribirpdf\vendor\picqer\php-barcode-generator\
4. Copia todo el contenido de la carpeta extraída dentro de php-barcode-generator\
```

---

## 📁 Estructura Final Esperada

Después de descargar todo, tu carpeta debe verse así:

```
C:\xampp\htdocs\escribirpdf\
├── vendor/
│   ├── tecnickcom/
│   │   └── tcpdf/
│   │       ├── tcpdf.php
│   │       ├── config/
│   │       ├── fonts/
│   │       └── ... (otros archivos)
│   │
│   ├── chillerlan/
│   │   ├── php-qrcode/
│   │   │   ├── src/
│   │   │   └── ... (otros archivos)
│   │   │
│   │   └── php-settings-container/
│   │       ├── src/
│   │       └── ... (otros archivos)
│   │
│   ├── picqer/
│   │   └── php-barcode-generator/
│   │       ├── src/
│   │       └── ... (otros archivos)
│   │
│   └── autoload.php  ← Este archivo lo crearás con el script
│
├── src/
├── examples/
└── ... (otros archivos del proyecto)
```

---

## 🔧 Configurar el Autoloader Manual

Copia y ejecuta este script para crear el archivo de autoload:

```bash
php crear_autoload_manual.php
```

Este script creará automáticamente el archivo `vendor/autoload.php` necesario.

---

## ✅ Verificar la Instalación

Ejecuta:
```bash
php verificar_instalacion.php
```

Si todo está correcto, verás: `✅ ¡TODO ESTÁ CORRECTO!`

---

## 🚀 Ejecutar los Ejemplos

Una vez configurado, puedes ejecutar:

```bash
php examples/ejemplo_completo.php
php examples/tipos_codigos.php
```

---

## 📝 Notas Importantes

1. **Versiones:** Las versiones indicadas son las compatibles. Si descargas versiones más nuevas, pueden funcionar pero no está garantizado.

2. **Permisos:** Asegúrate de que la carpeta `vendor/` tenga permisos de lectura.

3. **Actualizaciones:** Con instalación manual, debes actualizar las librerías manualmente descargando nuevas versiones.

4. **Recomendación:** Si es posible, instala Composer. Es mucho más fácil y mantenible a largo plazo.

---

## 🔗 Enlaces de Descarga Rápidos

### Opción 2: Descargar Todo de una Vez

**Archivo ZIP con todas las librerías:**
No disponible, pero puedes descargar este proyecto completo con las dependencias ya incluidas desde:

⚠️ **NOTA:** Por políticas de GitHub, no se suben las dependencias al repositorio. Debes descargarlas según las instrucciones anteriores.

---

## ❓ Problemas Comunes

### Error: "Class 'TCPDF' not found"
**Solución:** Verifica que `vendor/tecnickcom/tcpdf/tcpdf.php` existe y contiene la clase TCPDF.

### Error: "Class 'chillerlan\QRCode\QRCode' not found"
**Solución:**
1. Verifica que descargaste tanto `php-qrcode` como `php-settings-container`
2. Ejecuta: `php crear_autoload_manual.php`

### Los códigos de barras no se generan
**Solución:** Verifica que `vendor/picqer/php-barcode-generator/src/` contiene los archivos PHP de la librería.

---

## 💡 Alternativa: Usar Composer Portable (Sin instalación)

Si no quieres instalar Composer en el sistema:

1. Descarga `composer.phar`: https://getcomposer.org/composer.phar
2. Colócalo en la raíz del proyecto
3. Ejecuta: `php composer.phar install`

Esto instalará las dependencias sin necesidad de instalar Composer globalmente.
