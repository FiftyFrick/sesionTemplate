<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "Usuario y contraseña son obligatorios.";
    } else {
        // Verificar si el nombre de usuario ya existe
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo "El nombre de usuario ya está en uso.";
        } else {
            // Insertar el nuevo usuario
            $stmt->close();
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);
            
            if ($stmt->execute()) {
                echo "Registro exitoso. Ahora puedes <a href='index.html'>iniciar sesión</a>.";
            } else {
                echo "Hubo un error en el registro. Por favor, intenta de nuevo.";
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>
