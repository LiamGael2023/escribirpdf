<?php

namespace EscribirPDF;

use TCPDF;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Picqer\Barcode\BarcodeGeneratorPNG;

/**
 * Clase para cargar PDFs y agregar códigos QR, códigos de barras, imágenes y etiquetas
 */
class PDFEditor
{
    private $pdf;
    private $sourcePDF;
    private $pageCount;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor('EscribirPDF');
        $this->pdf->SetTitle('Documento PDF Editado');
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetAutoPageBreak(false, 0);
    }

    /**
     * Cargar un PDF existente
     *
     * @param string $pdfPath Ruta al archivo PDF
     * @return bool
     */
    public function loadPDF($pdfPath)
    {
        if (!file_exists($pdfPath)) {
            throw new \Exception("El archivo PDF no existe: $pdfPath");
        }

        $this->sourcePDF = $pdfPath;
        $this->pageCount = $this->pdf->setSourceFile($pdfPath);

        // Importar todas las páginas del PDF original
        for ($i = 1; $i <= $this->pageCount; $i++) {
            $tplId = $this->pdf->importPage($i);
            $size = $this->pdf->getTemplateSize($tplId);

            $this->pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $this->pdf->useTemplate($tplId);
        }

        return true;
    }

    /**
     * Crear un nuevo PDF vacío
     *
     * @param string $orientation Orientación: 'P' (Portrait) o 'L' (Landscape)
     * @param array $size Tamaño de página [ancho, alto] en mm, o 'A4', 'LETTER', etc.
     */
    public function createNewPDF($orientation = 'P', $size = 'A4')
    {
        $this->pdf->AddPage($orientation, $size);
        $this->pageCount = 1;
    }

    /**
     * Agregar código QR al PDF
     *
     * @param string $data Datos a codificar en el QR
     * @param int $x Posición X en mm
     * @param int $y Posición Y en mm
     * @param int $size Tamaño del QR en mm
     * @param int $page Número de página (null = página actual)
     * @return bool
     */
    public function addQRCode($data, $x, $y, $size = 30, $page = null)
    {
        if ($page !== null && $page > 0 && $page <= $this->pageCount) {
            $this->pdf->setPage($page);
        }

        $options = new QROptions([
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L,
            'scale'      => 10,
            'imageBase64' => false,
        ]);

        $qrcode = new QRCode($options);
        $qrImage = $qrcode->render($data);

        // Guardar temporalmente
        $tempFile = sys_get_temp_dir() . '/qr_' . uniqid() . '.png';
        file_put_contents($tempFile, $qrImage);

        // Agregar al PDF
        $this->pdf->Image($tempFile, $x, $y, $size, $size, 'PNG');

        // Limpiar archivo temporal
        @unlink($tempFile);

        return true;
    }

    /**
     * Agregar código de barras al PDF
     *
     * @param string $data Datos a codificar en el código de barras
     * @param int $x Posición X en mm
     * @param int $y Posición Y en mm
     * @param int $width Ancho en mm
     * @param int $height Alto en mm
     * @param string $type Tipo de código de barras (CODE128, EAN13, etc.)
     * @param int $page Número de página (null = página actual)
     * @return bool
     */
    public function addBarcode($data, $x, $y, $width = 50, $height = 15, $type = 'CODE128', $page = null)
    {
        if ($page !== null && $page > 0 && $page <= $this->pageCount) {
            $this->pdf->setPage($page);
        }

        $generator = new BarcodeGeneratorPNG();

        // Tipos de código de barras soportados
        $barcodeTypes = [
            'CODE128' => $generator::TYPE_CODE_128,
            'EAN13' => $generator::TYPE_EAN_13,
            'EAN8' => $generator::TYPE_EAN_8,
            'UPC' => $generator::TYPE_UPC_A,
            'CODE39' => $generator::TYPE_CODE_39,
            'CODE93' => $generator::TYPE_CODE_93,
        ];

        $barcodeType = $barcodeTypes[$type] ?? $generator::TYPE_CODE_128;

        // Generar código de barras
        $barcode = $generator->getBarcode($data, $barcodeType, 3, 50);

        // Guardar temporalmente
        $tempFile = sys_get_temp_dir() . '/barcode_' . uniqid() . '.png';
        file_put_contents($tempFile, $barcode);

        // Agregar al PDF
        $this->pdf->Image($tempFile, $x, $y, $width, $height, 'PNG');

        // Limpiar archivo temporal
        @unlink($tempFile);

        return true;
    }

    /**
     * Agregar imagen al PDF
     *
     * @param string $imagePath Ruta a la imagen
     * @param int $x Posición X en mm
     * @param int $y Posición Y en mm
     * @param int $width Ancho en mm (0 = automático)
     * @param int $height Alto en mm (0 = automático)
     * @param int $page Número de página (null = página actual)
     * @return bool
     */
    public function addImage($imagePath, $x, $y, $width = 0, $height = 0, $page = null)
    {
        if (!file_exists($imagePath)) {
            throw new \Exception("La imagen no existe: $imagePath");
        }

        if ($page !== null && $page > 0 && $page <= $this->pageCount) {
            $this->pdf->setPage($page);
        }

        $this->pdf->Image($imagePath, $x, $y, $width, $height);

        return true;
    }

    /**
     * Agregar texto/etiqueta al PDF
     *
     * @param string $text Texto a agregar
     * @param int $x Posición X en mm
     * @param int $y Posición Y en mm
     * @param int $fontSize Tamaño de fuente
     * @param string $fontFamily Familia de fuente
     * @param string $fontStyle Estilo: '' (regular), 'B' (bold), 'I' (italic), 'BI'
     * @param array $color Color RGB [R, G, B] (0-255)
     * @param string $align Alineación: 'L' (izquierda), 'C' (centro), 'R' (derecha)
     * @param int $width Ancho del cuadro de texto (0 = sin límite)
     * @param int $page Número de página (null = página actual)
     * @return bool
     */
    public function addText($text, $x, $y, $fontSize = 12, $fontFamily = 'helvetica',
                           $fontStyle = '', $color = [0, 0, 0], $align = 'L', $width = 0, $page = null)
    {
        if ($page !== null && $page > 0 && $page <= $this->pageCount) {
            $this->pdf->setPage($page);
        }

        $this->pdf->SetFont($fontFamily, $fontStyle, $fontSize);
        $this->pdf->SetTextColor($color[0], $color[1], $color[2]);
        $this->pdf->SetXY($x, $y);

        if ($width > 0) {
            $this->pdf->Cell($width, 0, $text, 0, 1, $align);
        } else {
            $this->pdf->Write(0, $text, '', 0, $align);
        }

        return true;
    }

    /**
     * Agregar cuadro de texto con múltiples líneas
     *
     * @param string $text Texto a agregar
     * @param int $x Posición X en mm
     * @param int $y Posición Y en mm
     * @param int $width Ancho del cuadro
     * @param int $height Alto del cuadro
     * @param int $fontSize Tamaño de fuente
     * @param string $fontFamily Familia de fuente
     * @param string $align Alineación: 'L', 'C', 'R', 'J' (justificado)
     * @param int $page Número de página (null = página actual)
     * @return bool
     */
    public function addMultiLineText($text, $x, $y, $width, $height, $fontSize = 10,
                                    $fontFamily = 'helvetica', $align = 'L', $page = null)
    {
        if ($page !== null && $page > 0 && $page <= $this->pageCount) {
            $this->pdf->setPage($page);
        }

        $this->pdf->SetFont($fontFamily, '', $fontSize);
        $this->pdf->SetXY($x, $y);
        $this->pdf->MultiCell($width, $height, $text, 0, $align);

        return true;
    }

    /**
     * Obtener número de páginas del documento
     *
     * @return int
     */
    public function getPageCount()
    {
        return $this->pdf->getNumPages();
    }

    /**
     * Agregar una página nueva
     *
     * @param string $orientation Orientación: 'P' o 'L'
     * @param array $size Tamaño de página
     */
    public function addPage($orientation = 'P', $size = 'A4')
    {
        $this->pdf->AddPage($orientation, $size);
    }

    /**
     * Guardar el PDF modificado
     *
     * @param string $outputPath Ruta donde guardar el PDF
     * @param string $mode Modo: 'F' (archivo), 'I' (inline browser), 'D' (descarga), 'S' (string)
     * @return mixed
     */
    public function save($outputPath, $mode = 'F')
    {
        $dir = dirname($outputPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return $this->pdf->Output($outputPath, $mode);
    }

    /**
     * Obtener el PDF como string
     *
     * @return string
     */
    public function getPDFString()
    {
        return $this->pdf->Output('', 'S');
    }

    /**
     * Descargar el PDF directamente
     *
     * @param string $filename Nombre del archivo
     */
    public function download($filename = 'documento.pdf')
    {
        $this->pdf->Output($filename, 'D');
    }

    /**
     * Mostrar el PDF en el navegador
     *
     * @param string $filename Nombre del archivo
     */
    public function display($filename = 'documento.pdf')
    {
        $this->pdf->Output($filename, 'I');
    }
}
