<?php
session_start();

require 'databse.php';
if (isset($_SESSION['user_id'])) {
  $records = $conn->prepare('SELECT idusers, name, email, password, user_ids FROM users WHERE idusers = :id');
  $records->bindParam(':id', $_SESSION['user_id']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  $user = null;

  if (count($results) > 0) {
    $user = $results;
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Bienvenido a la App</title>
  <script src="JsBarcode.all.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="styleLogin.css" />
</head>

<body>
  <div class="container">
    <div class="logo">
      <h1>Bienvenido <?= $user['email'] ?></h1>
      <h1>Tu ID es <?= $user['user_ids'] ?></h1>
      <h2><a id="logoutButton" href="logout.php">LOGOUT</a></h2>
      
    </div>



    <svg id="barcode"></svg>

    <button id="updateUserIdsButton">Actualizar Código de Barras</button>
    <button id="saldoButton">Saldo</button>

    <script>
      $(document).ready(function() {
        $("#updateUserIdsButton").click(function() {
          // Realiza una solicitud AJAX al servidor para actualizar el user_ids
          $.ajax({
            type: "POST",
            url: "actualizar.php", // Ruta al script PHP que manejará la actualización
            data: {
              // Envía el ID del usuario u otros datos necesarios
              user_id: <?= $user['idusers'] ?> // Reemplaza con el ID de usuario real
            },
            success: function(response) {
              if (response === 'success') {
                // alert('user_ids actualizado con éxito.');
                location.reload();
              } else {
                alert('Hubo un error al actualizar user_ids.');
              }
            }
          });
        });
      });
    </script>

    <script>
      $(document).ready(function() {
        // Agrega esta sección para manejar el clic en el botón de saldo
        $("#saldoButton").click(function() {
          // Realiza una solicitud AJAX al servidor para obtener el saldo del usuario
          $.ajax({
            type: "POST",
            url: "obtenerSaldo.php", // Ruta al script PHP que manejará la obtención del saldo
            data: {
              // Envía el ID del usuario u otros datos necesarios
              user_id: <?= $user['idusers'] ?> // Reemplaza con el ID de usuario real
            },
            success: function(response) {
              
              window.location.href = 'mostrarSaldo.php?saldo=' + response;
            },
            error: function() {
              // Muestra un mensaje de error en caso de fallo
              alert('Hubo un error al obtener el saldo.');
            }
          });
        });
      });

    </script>




  </div>

  <script type="text/javascript">
    var id_usuario = <?= $user['user_ids'] ?>;
    JsBarcode("#barcode", id_usuario.toString(), {
      format: "CODE128",
      lineColor: "#000000",
      width: 3,
      height: 50,
      displayValue: true
    });
  </script>
</body>

</html>
