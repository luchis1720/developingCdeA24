<?php

include_once("includes/db.php");

// Funcion para marcar un codigo como dado de baja
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["marcar_baja"])) {
    $id = $_POST["id"];

    // Consulta SQL para marcar el codigo como dado de baja
    $sql_marcar_baja = "UPDATE codigos SET baja = TRUE WHERE id = $id";
    mysqli_query($conn, $sql_marcar_baja);
}

// Consulta SQL para seleccionar todos los registros de la tabla codigos
$sql_codigos = "SELECT * FROM codigos";
$resultado_codigos = mysqli_query($conn, $sql_codigos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Códigos de Acceso</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Administración de Códigos de Acceso</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Código de Acceso</th>
                    <th>DNI del Alumno</th>
                    <th>Fecha de Expiración</th>
                    <th>Estado Expirado</th>
                    <th>Estado de Baja</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado_codigos)) { ?>
                    <tr>
                        <td><?php echo $fila['id']; ?></td>
                        <td><?php echo $fila['codigo_acceso']; ?></td>
                        <td><?php echo $fila['dni_alumno']; ?></td>
                        <td><?php echo $fila['fecha_expiracion']; ?></td>
                        <td><?php echo $fila['estado_expirado'] ? 'Caducado' : 'Activo'; ?></td>
                        <td><?php echo $fila['baja'] ? 'Sí' : 'No'; ?></td>
                        <td><?php echo $fila['estado'] ? 'adentro' : 'afuera'; ?></td>
                        <td>
                            <form id="form_marcar_baja_<?php echo $fila['id']; ?>" action="" method="POST">
                                <input type="hidden" name="id" value="<?php echo $fila['id']; ?>">
                                <button type="button" class="btn btn-danger" onclick="marcarBaja(<?php echo $fila['id']; ?>)" <?php echo $fila['baja'] ? 'disabled' : ''; ?>>Marcar Baja</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        function marcarBaja(id) {
            if (confirm("¿Estás seguro de marcar este código como dado de baja?")) {
                var form = document.getElementById("form_marcar_baja_" + id);
                form.innerHTML += '<input type="hidden" name="marcar_baja">';
                form.submit();
            }
        }
    </script>
</body>
</html>

<?php
// Cierro la conexion a la base de datos
mysqli_close($conn);
?>
