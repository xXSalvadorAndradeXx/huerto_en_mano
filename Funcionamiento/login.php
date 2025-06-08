<?php
session_start();

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

// Recoger datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];

// Buscar usuario en la base de datos
$stmt = $conn->prepare("SELECT id, nombre, apellido, password FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verificar contraseña
    if (password_verify($password, $user['password'])) {
        // Iniciar sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'] . ' ' . $user['apellido'];
        $_SESSION['logged_in'] = true;
        
        // Redirigir al usuario
        header("Location: carga-inicio.html");
        exit();
    } else {
        // Contraseña incorrecta
        $_SESSION['error'] = "Correo electrónico o contraseña incorrectos";
        header("Location: iniciar-sesion.html");
        exit();
    }
} else {
    // Usuario no encontrado
    $_SESSION['error'] = "Correo electrónico o contraseña incorrectos";
    header("Location: iniciar-sesion.html");
    exit();
}

$stmt->close();
$conn->close();
?>