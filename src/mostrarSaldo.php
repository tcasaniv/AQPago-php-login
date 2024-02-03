<?php
// mostrar_saldo.php

if (isset($_GET['saldo'])) {
  $saldo = $_GET['saldo'];
} else {
  // Si no se proporciona el saldo, redirige a una página de error o maneja la situación de alguna otra manera
  header("Location: error.php");
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Mostrar saldo</title>
  <script src="JsBarcode.all.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
  <link rel="stylesheet" href="styleLogin.css" />
</head>

<body>
  <div class="container">
    <div class="logo">
      <h1>Tu saldo actual es <?= $saldo ?></h1>
      <h2><a id="return" href="index2.php">Regresar</a></h2>  
    </div>
    
    <form action="aumentar_reducir_saldo.php" method="post">
      <label for="aumentar">Aumentar Saldo:</label>
      <input type="number" name="aumentar" id="aumentar" required>
      <button type="submit">Aumentar</button>
    </form>

    <form action="aumentar_reducir_saldo.php" method="post">
      <label for="reducir">Reducir Saldo:</label>
      <input type="number" name="reducir" id="reducir" required>
      <button type="submit">Reducir</button>
    </form>


  </div>

</body>

</html>
