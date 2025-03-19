<?php
error_reporting(E_ALL); // Activa todos los errores
ini_set('display_errors', 1); // Muestra los errores en el navegador

session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../modelos/Persona.php';
require_once __DIR__ . '/../modelos/Proveedor.php';
require_once __DIR__ . '/../modelos/Categoria.php';
require_once __DIR__ . '/../public/librerias/TCPDF-main/tcpdf.php';

$database = new Database();
$db = $database->getConnection();

$persona = new Persona($db);
$proveedor = new Proveedor($db);
$categoria = new Categoria($db);

// Obtener datos para el reporte
$personas = $persona->leer()->fetchAll(PDO::FETCH_ASSOC);
$proveedores = $proveedor->leer()->fetchAll(PDO::FETCH_ASSOC);

// Obtener datos de proveedores por categoría
$proveedores_por_categoria = $proveedor->contarProveedoresPorCategoria();

// Crear un nuevo PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Configurar el documento
$pdf->SetCreator('Sistema de Gestión');
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Reporte de Personas y Proveedores');
$pdf->SetSubject('Reporte PDF');
$pdf->SetKeywords('PDF, Reporte, Personas, Proveedores, Categorías');

// Eliminar encabezado y pie de página predeterminados
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Establecer márgenes
$pdf->SetMargins(10, 10, 10);

// Agregar una página
$pdf->AddPage();

// Contenido del PDF
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Reporte de Personas y Proveedores', 0, 1, 'C');

// Agregar fecha actual
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Fecha: ' . date('d/m/Y H:i:s'), 0, 1, 'R');
$pdf->Ln(5);

// Resumen estadístico
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Resumen Estadístico', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

// Total de registros
$total_personas = $persona->contar();
$total_proveedores = $proveedor->contar();
$total_categorias = $categoria->contar();

$html_resumen = '<table border="0" cellpadding="3">
    <tr>
        <td width="33%"><strong>Total Personas:</strong> ' . number_format($total_personas) . '</td>
        <td width="33%"><strong>Total Proveedores:</strong> ' . number_format($total_proveedores) . '</td>
        <td width="33%"><strong>Total Categorías:</strong> ' . number_format($total_categorias) . '</td>
    </tr>
</table>';
$pdf->writeHTML($html_resumen, true, false, false, false, '');
$pdf->Ln(5);

// Tabla de Proveedores por Categoría
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Proveedores por Categoría', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$html_categorias = '<table border="1" cellpadding="5">
    <tr style="background-color:#f2f2f2;">
        <th width="60%"><strong>Categoría</strong></th>
        <th width="20%"><strong>Cantidad</strong></th>
        <th width="20%"><strong>Porcentaje</strong></th>
    </tr>';

$total = 0;
foreach ($proveedores_por_categoria as $item) {
    $total += $item['total'];
}

foreach ($proveedores_por_categoria as $item) {
    $porcentaje = $total > 0 ? round(($item['total'] / $total) * 100, 1) : 0;
    $html_categorias .= '<tr>
        <td>' . $item['categoria'] . '</td>
        <td align="center">' . $item['total'] . '</td>
        <td align="center">' . $porcentaje . '%</td>
    </tr>';
}

$html_categorias .= '<tr style="background-color:#f2f2f2;">
    <th>Total</th>
    <th align="center">' . $total . '</th>
    <th align="center">100%</th>
</tr>';

$html_categorias .= '</table>';
$pdf->writeHTML($html_categorias, true, false, false, false, '');
$pdf->Ln(5);

// Tabla de Personas
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Personas Registradas', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

$html_personas = '<table border="1" cellpadding="5">
    <tr style="background-color:#f2f2f2;">
        <th width="10%"><strong>ID</strong></th>
        <th width="25%"><strong>Nombre</strong></th>
        <th width="25%"><strong>Apellido</strong></th>
        <th width="20%"><strong>Teléfono</strong></th>
        <th width="20%"><strong>Email</strong></th>
    </tr>';

foreach ($personas as $row) {
    $html_personas .= '<tr>
        <td>' . $row['id'] . '</td>
        <td>' . $row['nombre'] . '</td>
        <td>' . $row['apellido'] . '</td>
        <td>' . $row['telefono'] . '</td>
        <td>' . $row['email'] . '</td>
    </tr>';
}

$html_personas .= '</table>';
$pdf->writeHTML($html_personas, true, false, false, false, '');
$pdf->AddPage();

// Tabla de Proveedores
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Proveedores Registrados', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 9); // Tamaño más pequeño para esta tabla que tiene más columnas

$html_proveedores = '<table border="1" cellpadding="5">
    <tr style="background-color:#f2f2f2;">
        <th width="8%"><strong>ID</strong></th>
        <th width="20%"><strong>Empresa</strong></th>
        <th width="8%"><strong>Contact. ID</strong></th>
        <th width="15%"><strong>Teléfono</strong></th>
        <th width="20%"><strong>Email</strong></th>
        <th width="15%"><strong>Dirección</strong></th>
        <th width="14%"><strong>Categoría</strong></th>
    </tr>';

foreach ($proveedores as $row) {
    $html_proveedores .= '<tr>
        <td>' . $row['id'] . '</td>
        <td>' . $row['nombre_empresa'] . '</td>
        <td>' . $row['contacto_id'] . '</td>
        <td>' . $row['telefono'] . '</td>
        <td>' . $row['email'] . '</td>
        <td>' . $row['direccion'] . '</td>
        <td>' . $row['categoria_nombre'] . '</td>
    </tr>';
}

$html_proveedores .= '</table>';
$pdf->writeHTML($html_proveedores, true, false, false, false, '');

// Agregar pie de página con números de página
$pdf->SetFont('helvetica', 'I', 8);
$pdf->writeHTML('<hr>', true, false, false, false, '');
$pdf->Cell(0, 10, 'Página ' . $pdf->getAliasNumPage() . ' de ' . $pdf->getAliasNbPages(), 0, 0, 'C');

// Salida del PDF
$pdf->Output('reporte_personas_proveedores.pdf', 'I');
?>