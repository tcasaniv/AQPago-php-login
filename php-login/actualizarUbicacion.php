<?php
// actualizar_ubicacion.php

session_start();

require 'databse.php';

function calcularDistancia($latitud1, $longitud1, $latitud2, $longitud2) {
    // Radio de la Tierra en metros
    $radio_tierra = 6371000;

    // Convierte las latitudes y longitudes de grados a radianes
    $latitud1_rad = deg2rad($latitud1);
    $longitud1_rad = deg2rad($longitud1);
    $latitud2_rad = deg2rad($latitud2);
    $longitud2_rad = deg2rad($longitud2);

    // Calcula las diferencias entre las latitudes y longitudes
    $delta_latitud = $latitud2_rad - $latitud1_rad;
    $delta_longitud = $longitud2_rad - $longitud1_rad;

    // Fórmula de la distancia haversiana
    $a = sin($delta_latitud / 2) * sin($delta_latitud / 2) +
         cos($latitud1_rad) * cos($latitud2_rad) *
         sin($delta_longitud / 2) * sin($delta_longitud / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Distancia en metros
    $distancia = $radio_tierra * $c;

    return $distancia;
}


if($conn){
    echo "Conexion con base de datos exitosa";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica si se enviaron los datos necesarios
    if (isset($_POST['user_ids']) && isset($_POST['latitud']) && isset($_POST['longitud'])) {

        // Obtiene los datos del formulario
        
        $user_ids = $_POST['user_ids'];
        $latitud = $_POST['latitud'];
        $longitud = $_POST['longitud'];


        // Obtiene el estado actual y verificación_saldo
        $stmt_estado_actual = $conn->prepare('SELECT estado, latitud, longitud, saldo, verificacion_saldo FROM users WHERE user_ids = :user_ids');
        $stmt_estado_actual->bindParam(':user_ids', $user_ids);
        $stmt_estado_actual->execute();
        $result_estado_actual = $stmt_estado_actual->fetch(PDO::FETCH_ASSOC);

        if ($result_estado_actual) {
            $estado_actual = $result_estado_actual['estado'];
            $latitud_estado_actual = $result_estado_actual['latitud'];
            $longitud_estado_actual = $result_estado_actual['longitud'];
            $saldo_actual = $result_estado_actual['saldo'];
            $verificacion_saldo = $result_estado_actual['verificacion_saldo'];

            // Verifica si hay una transición de estado 1 a 0
            if ($estado_actual == 1) {
                // Calcula la distancia entre el estado 1 y el estado 0
                $distancia_en_metros = calcularDistancia($latitud_estado_actual, $longitud_estado_actual, $latitud, $longitud);

                // Supongamos que deseas disminuir el saldo en 1 por cada 5000 metros de distancia
                $nuevo_saldo = $saldo_actual - $distancia_en_metros / 5000;

                // Verifica si el saldo es menor que 0 y actualiza la verificación del saldo
                $verificacion_saldo = ($nuevo_saldo < 0) ? 1 : 0;

                

                // Realiza la actualización en la base de datos solo si el nuevo saldo es positivo
                if ($nuevo_saldo >= 0) {
                    $sql = "UPDATE users SET latitud = :latitud, longitud = :longitud, saldo = :nuevo_saldo, verificacion_saldo = :verificacion_saldo, estado = 0 WHERE user_ids = :user_ids";
                    $stmt = $conn->prepare($sql);

                    $stmt->bindParam(':latitud', $latitud);
                    $stmt->bindParam(':longitud', $longitud);
                    $stmt->bindParam(':nuevo_saldo', $nuevo_saldo);
                    $stmt->bindParam(':verificacion_saldo', $verificacion_saldo);
                    $stmt->bindParam(':user_ids', $user_ids);

                    if ($stmt->execute()) {
                        //echo "Ubicación actualizada con éxito. La distancia entre el estado 1 y el estado 0 es: $distancia_en_metros metros. Saldo actualizado: $nuevo_saldo";
                        header("Location: autenticacion.php");
                    } else {
                        echo 'Hubo un error al actualizar la ubicación y el saldo.';
                    }
                } else {
                    echo 'El nuevo saldo es negativo. No se pueden realizar actualizaciones.';

                    $sql = "UPDATE users SET verificacion_saldo = :verificacion_saldo WHERE user_ids = :user_ids";
                    $stmt = $conn->prepare($sql);

                    $stmt->bindParam(':verificacion_saldo', $verificacion_saldo);
                    $stmt->bindParam(':user_ids', $user_ids);
                    $stmt->execute();


                }

                // Después de la actualización del saldo
                if ($stmt->execute()) {
                    // Actualiza el historial de pagos en la tabla users solo si el estado cambia a 0
                    if ($estado_actual == 1 && $verificacion_saldo == 0) {
                        $monto_pagado = $distancia_en_metros / 5000;

                        $sql_update_historial = "UPDATE users SET fecha_pago = CURRENT_TIMESTAMP, monto_pagado = :monto_pagado WHERE user_ids = :user_ids";
                        $stmt_update_historial = $conn->prepare($sql_update_historial);
                        $stmt_update_historial->bindParam(':monto_pagado', $monto_pagado);
                        $stmt_update_historial->bindParam(':user_ids', $user_ids);
                        
                        $stmt_update_historial->execute();

                         // Inserta el nuevo pago en la tabla de historial
                        $sql_insert_historial = "INSERT INTO historial_pagos (user_ids, fecha_pago, monto_pagado) VALUES (:user_ids, CURRENT_TIMESTAMP, :monto_pagado)";
                        $stmt_insert_historial = $conn->prepare($sql_insert_historial);
                        $stmt_insert_historial->bindParam(':user_ids', $user_ids);
                        $stmt_insert_historial->bindParam(':monto_pagado', $monto_pagado);
                        $stmt_insert_historial->execute();
                    }

                    // Redirige a la página de inicio o a donde desees
                    header("Location: autenticacion.php");
                } else {
                    echo 'Hubo un error al actualizar la ubicación y el saldo.';
                }



            } else {
                // Si el estado actual no es 1, simplemente actualiza las coordenadas y establece el estado a 1
                $sql = "UPDATE users SET latitud = :latitud, longitud = :longitud, estado = 1 WHERE user_ids = :user_ids";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':latitud', $latitud);
                $stmt->bindParam(':longitud', $longitud);
                $stmt->bindParam(':user_ids', $user_ids);

                if ($stmt->execute()) {
                    //echo "Ubicación actualizada con éxito. Saldo no afectado.";
                    header("Location: autenticacion.php");
                } else {
                    echo 'Hubo un error al actualizar la ubicación.';
                }
            }
        } else {
            echo "No se encontraron coordenadas para el usuario.";
        }
    } else {
        echo 'Por favor, proporciona todos los datos necesarios.';
    }
}
?>
