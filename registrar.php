<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "huerto_en_mano";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Recoger y validar datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar contraseña
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : null;
$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : null;

// Verificar que los correos coincidan
if ($_POST['email'] !== $_POST['confirm-email']) {
    die("Los correos electrónicos no coinciden");
}

// Verificar que las contraseñas coincidan
if ($_POST['password'] !== $_POST['confirm-password']) {
    die("Las contraseñas no coinciden");
}

// Preparar y ejecutar la consulta SQL
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, password, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nombre, $apellido, $email, $password, $direccion, $telefono);

if ($stmt->execute()) {
    echo "Registro exitoso. Bienvenido, " . htmlspecialchars($nombre) . "!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>