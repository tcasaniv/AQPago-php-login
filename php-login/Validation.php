<?php
include 'databse2.php';

if ($conn) {
    if(isset($_POST['user_ids'])) {
        $user_ids = $_POST['user_ids'];

        // Consulta para verificar el estado del usuario
        $consulta = "SELECT verificacion_saldo FROM users WHERE user_ids = '$user_ids'";
        $resultado = mysqli_query($conn, $consulta);

        if ($resultado) {
            if (mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_assoc($resultado);
                $state = $fila['verificacion_saldo'];

                // Enviar el estado al ESP32
                echo $state;
            } else {
                // Usuario no encontrado
                echo "Usuario no encontrado";
            }
        } else {
            // Error en la consulta
            echo "Error en la consulta SQL";
        }
    } else {
        // No se proporcionó user_id
        echo "No se proporcionó el user_id";
    }
} else {
    // Fallo la conexión con la base de datos
    echo "Falla en la conexión con la base de datos";
}
?>
