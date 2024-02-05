<?php
require 'databse.php';



// Verifica si el usuario ha iniciado sesión y está autorizado

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];



    function getNextUserID($conn) {
        $sql = "SELECT MAX(user_ids) FROM users"; // Obtener el último ID registrado
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $lastID = $stmt->fetchColumn();

        if ($lastID < 1000001) {
          // Si no hay usuarios registrados o el último ID es menor que 1000001, comenzar desde 1000001
          return 1000001;
        } elseif ($lastID >= 9999999) {
          // Si el último ID alcanza el valor máximo, no es posible registrar más usuarios
          return false;
        } else {
          // Calcular el próximo ID en el rango
          return $lastID + 1;
        }
    }

    // Realiza una consulta para obtener el próximo valor de user_ids
    $nextUserIds = getNextUserID($conn); // Puedes usar la función getNextUserID que proporcionaste en tu código anterior

    if ($nextUserIds !== false) {
        // Actualiza el valor de user_ids para el usuario correspondiente
        $sql = "UPDATE users SET user_ids = :user_ids WHERE idusers = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_ids', $nextUserIds);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            echo 'success'; // Devuelve una respuesta exitosa
        } else {
            echo 'error'; // Devuelve una respuesta de error
        }
    } else {
        echo 'error'; // No es posible asignar más user_ids
    }
} else {
    echo 'error'; // El usuario no está autorizado o no ha iniciado sesión
}
?>
