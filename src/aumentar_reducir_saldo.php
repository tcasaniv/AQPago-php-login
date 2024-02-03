<?php
// aumentar_reducir_saldo.php

session_start();

require 'databse.php';

if (isset($_SESSION['user_id']) && isset($_POST['aumentar'])) {
  $user_id = $_SESSION['user_id'];
  $aumentar_cantidad = $_POST['aumentar'];

  // Realiza la actualización del saldo aumentándolo
  $sql = "UPDATE users SET saldo = saldo + :aumentar_cantidad WHERE idusers = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':aumentar_cantidad', $aumentar_cantidad);
  $stmt->bindParam(':user_id', $user_id);

  if ($stmt->execute()) {
    // Obtén el saldo actualizado de la base de datos
    $records = $conn->prepare('SELECT saldo FROM users WHERE idusers = :id');
    $records->bindParam(':id', $user_id);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
      $saldo_actualizado = $results['saldo'];
      
      // Redirige a la página mostrar_saldo.php con el nuevo saldo
      header("Location: index3.php");
      exit();
    } else {
      echo 'Hubo un error al aumentar el saldo.';
    }
  }
} elseif (isset($_SESSION['user_id']) && isset($_POST['reducir'])) {
  $user_id = $_SESSION['user_id'];
  $reducir_cantidad = $_POST['reducir'];

  // Realiza la actualización del saldo reduciéndolo
  $sql = "UPDATE users SET saldo = saldo - :reducir_cantidad WHERE idusers = :user_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':reducir_cantidad', $reducir_cantidad);
  $stmt->bindParam(':user_id', $user_id);

  if ($stmt->execute()) {
    // Obtén el saldo actualizado de la base de datos
    $records = $conn->prepare('SELECT saldo FROM users WHERE idusers = :id');
    $records->bindParam(':id', $user_id);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    if (count($results) > 0) {
      $saldo_actualizado = $results['saldo'];
      
      // Redirige a la página mostrar_saldo.php con el nuevo saldo
      header("Location: index3.php");
      exit();
    } else {
      echo 'Hubo un error al reducir el saldo.';
    }  
  } 
} else {
  // Maneja el caso si no se proporciona la información necesaria
  header("Location: error.php");
  exit();
}
?>
