<?php
include_once("includes/db.php");
include_once("includes/codGen.php");

// Funcion para limpiar la entrada de datos
function limpiarEntrada($dato) {
    $dato = trim($dato); // Elimina espacios en blanco al principio y al final
    $dato = stripslashes($dato); // Elimina barras invertidas (\)
    $dato = htmlspecialchars($dato); // Convierte caracteres especiales en entidades HTML
    return $dato;
}

// Proceso el formulario para agregar un nuevo alumno
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtengo y limpio los datos del formulario
    $nombre = limpiarEntrada($_POST["nombre"]);
    $direccion = limpiarEntrada($_POST["direccion"]);
    $email = limpiarEntrada($_POST["email"]);
    $fecha_nacimiento = limpiarEntrada($_POST["fecha_nacimiento"]);
    $telefono = limpiarEntrada($_POST["telefono"]);
    $dni = limpiarEntrada($_POST["dni"]);
    $fecha_ingreso = limpiarEntrada($_POST["fecha_ingreso"]);

    // Inserto el nuevo alumno en la tabla alumnos
    $sql_alumno = "INSERT INTO alumnos (nombre, direccion, email, fecha_nacimiento, telefono, dni, fecha_ingreso)
                   VALUES ('$nombre', '$direccion', '$email', '$fecha_nacimiento', '$telefono', '$dni', '$fecha_ingreso')";
    mysqli_query($conn, $sql_alumno);

    // Genero el codigo de acceso para el nuevo alumno
    $codigo_acceso = generarCodigoAcceso($dni);

    // Inserto el codigo de acceso generado en la tabla codigos
    $sql_codigo = "INSERT INTO codigos (codigo_acceso, dni_alumno, fecha_expiracion, estado)
                   VALUES ('$codigo_acceso', '$dni', DATE_ADD('$fecha_ingreso', INTERVAL 1 YEAR), FALSE)";
    mysqli_query($conn, $sql_codigo);
}

// Proceso la solicitud de eliminacion de un alumno
if (isset($_GET["eliminar_id"])) {
    $eliminar_id = $_GET["eliminar_id"];
    // Elimino el registro de alumno
    $sql_eliminar = "DELETE FROM alumnos WHERE id = '$eliminar_id'";
    mysqli_query($conn, $sql_eliminar);
}

// Consulta SQL para obtener todos los alumnos
$sql_consulta_alumnos = "SELECT * FROM alumnos";
$resultado_alumnos = mysqli_query($conn, $sql_consulta_alumnos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hard Entry</title>
</head>
<body>

<h2>Agregar Nuevo Alumno</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <!-- Campos para agregar nuevo alumno -->
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" required><br>
    <label for="direccion">Dirección:</label>
    <input type="text" name="direccion" id="direccion" required><br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>
    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required><br>
    <label for="telefono">Teléfono:</label>
    <input type="tel" name="telefono" id="telefono" required><br>
    <label for="dni">DNI:</label>
    <input type="text" name="dni" id="dni" required><br>
    <label for="fecha_ingreso">Fecha de Ingreso:</label>
    <input type="date" name="fecha_ingreso" id="fecha_ingreso" required><br>
    <input type="submit" value="Agregar Alumno">
</form>

<h2>Registros Actuales de Alumnos</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Dirección</th>
        <th>Email</th>
        <th>Fecha de Nacimiento</th>
        <th>Teléfono</th>
        <th>DNI</th>
        <th>Fecha de Ingreso</th>
        <th>Acciones</th>
    </tr>
    <?php
    // Muestro los registros de alumnos en una tabla
    while ($fila = mysqli_fetch_assoc($resultado_alumnos)) {
        echo "<tr>";
        echo "<td>" . $fila["id"] . "</td>";
        echo "<td>" . $fila["nombre"] . "</td>";
        echo "<td>" . $fila["direccion"] . "</td>";
        echo "<td>" . $fila["email"] . "</td>";
        echo "<td>" . $fila["fecha_nacimiento"] . "</td>";
        echo "<td>" . $fila["telefono"] . "</td>";
        echo "<td>" . $fila["dni"] . "</td>";
        echo "<td>" . $fila["fecha_ingreso"] . "</td>";
        echo "<td><a href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?eliminar_id=" . $fila["id"] . "'>Eliminar</a></td>"; // Agrego enlace para eliminar
        echo "</tr>";
    }
    ?>
</table>

<a href="hardEntryCod.php">Códigos de Acceso</a>

</body>
</html>

<?php
// Cierro la conexion a la base de datos
mysqli_close($conn);
?>
