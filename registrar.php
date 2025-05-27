<?php
// Datos de conexión
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "farmebot";

// Crear conexión
$conn = new mysqli($servidor, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$pais = $_POST['pais'];

// Verificar si el email ya existe
$sql_verificar = "SELECT id FROM formulario WHERE email = ?";
$stmt = $conn->prepare($sql_verificar);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Correo ya registrado
    header("Location: mensaje.html?estado=duplicado");
    exit();
} else {
    // Insertar datos
    $sql_insertar = "INSERT INTO formulario (nombre, email, telefono, pais) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insertar);
    $stmt->bind_param("ssss", $nombre, $email, $telefono, $pais);

    if ($stmt->execute()) {
        // Registro exitoso
        header("Location: mensaje.html?estado=ok");
        exit();
    } else {
        // Error al registrar
        header("Location: mensaje.html?estado=error");
        exit();
    }
}

$stmt->close();
$conn->close();
?>
