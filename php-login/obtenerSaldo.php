<?php
// obtener_saldo.php

session_start();

require 'databse.php';

if (isset($_POST['user_id'])) {
  $user_id = $_POST['user_id'];

  // Realiza una consulta SQL para obtener el saldo del usuario (reemplaza esto con tu lógica)
  $records = $conn->prepare('SELECT saldo FROM users WHERE idusers = :id');
  $records->bindParam(':id', $user_id);
  $records->execute();
  $result = $records->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    echo $result['saldo'];
  } else {
    echo 'Error al obtener el saldo.';
  }
} else {
  echo 'ID de usuario no proporcionado.';
}
?>
