<?php require "../php/db_conciliacion.php";

// Obtener los parámetros enviados por POST
$fecha_desde = $_POST['fecha-desde'];
$fecha_hasta = $_POST['fecha-hasta'];
$codigo_marcacion = $_POST['nombres'];

// Cambiar idioma de MySQL al español
$conn->exec("SET lc_time_names = 'es_ES'");

// Obtener los datos del reporte
$data_reporte = $conn->prepare("SELECT fecha, CONCAT(UCASE(LEFT(DATE_FORMAT(fecha, '%a'), 1)), LCASE(SUBSTRING(DATE_FORMAT(fecha, '%a'), 2)), '.') as dia, MIN(hora) as min_hora, MAX(hora) as max_hora FROM datos WHERE codigo = :codigo_marcacion AND fecha BETWEEN :fecha_desde AND :fecha_hasta GROUP BY fecha");
$data_reporte->bindParam(":codigo_marcacion", $codigo_marcacion);
$data_reporte->bindParam(":fecha_desde", $fecha_desde);
$data_reporte->bindParam(":fecha_hasta", $fecha_hasta);
$data_reporte->execute();
$datos = $data_reporte->fetchAll(PDO::FETCH_ASSOC);

// Obtener el nombre del reporte
$nombre_reporte = $conn->prepare("SELECT nombre1, apellido1 FROM rrhh WHERE codigo_marcacion = :codigo_marcacion");
$nombre_reporte->bindParam(':codigo_marcacion', $codigo_marcacion);
$nombre_reporte->execute();
$resultado = $nombre_reporte->fetch(PDO::FETCH_ASSOC);

$nombre = $resultado['nombre1'];
$apellido = $resultado['apellido1'];
?>
<h4><?php echo "Reporte de: " . htmlspecialchars($nombre) . " " . htmlspecialchars($apellido); ?></h4>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        font-weight: bold;
        background-color: #f4f4f4;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
</style>
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Día</th>
            <th>Hora Mínima</th>
            <th>Hora Máxima</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($datos as $fila): ?> 
            <tr>
                <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($fila['fecha']))); ?></td>
                <td><?php echo htmlspecialchars($fila['dia']); ?></td>
                <td><?php echo htmlspecialchars($fila['min_hora']); ?></td>
                <td><?php echo htmlspecialchars($fila['max_hora']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
