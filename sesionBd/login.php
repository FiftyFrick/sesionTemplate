<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Usuario y contraseña son obligatorios.";
    } else {
        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password);
            $stmt->fetch();
            
            // Verificar la contraseña
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: welcome.php");
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "No existe una cuenta con ese nombre de usuario.";
        }
        $stmt->close();
    }
}
$conn->close();
?>
