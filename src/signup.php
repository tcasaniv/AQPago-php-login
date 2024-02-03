<?php

  require 'databse.php';

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





  $message = '';

  if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])) { 

    $user_ids = getNextUserID($conn);

    if ($user_ids !== false) {

      $sql = "INSERT INTO users (name, email, password, user_ids, saldo) VALUES (:name, :email, :password, :user_ids, 0)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':name', $_POST['name']);
      $stmt->bindParam(':email', $_POST['email']);
      $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
      $stmt->bindParam(':password', $password);
      $stmt->bindParam(':user_ids', $user_ids);

      if ($stmt->execute()) {
        $message = 'Creacion de nuevo usuario exitoso';
      } else {
        $message = 'Lo sentimos hubo u error al crear el usuario';
      }

    } else {
      $message = 'No es posible registrar mas usuarios';
    }
    
  }
  
?>

<!DOCTYPE html>
<!-- Website - www.codingnepalweb.com -->
  
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="description" content=" Today in this blog you will learn how to create a responsive Login & Registration Form in HTML CSS & JavaScript. The blog will cover everything from the basics of creating a Login & Registration in HTML, to styling it with CSS and adding with JavaScript." />
    <meta
      name="keywords"
      content=" 
 Animated Login & Registration Form,Form Design,HTML and CSS,HTML CSS JavaScript,login & registration form,login & signup form,Login Form Design,registration form,Signup Form,HTML,CSS,JavaScript,
"
    />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login & Signup Form HTML CSS | CodingNepal</title>
    <link rel="stylesheet" href="style.css" />
    <script src="../custom-scripts.js" defer></script>
  </head>
  <body>
    
    

    <section class="wrapper">
      <div class="form signup">
        <header>Signup</header>
        <form action="signup.php" method="POST">
          <input name = "name" type="text" placeholder="Nombre Completo" required />
          <input name = "email" type="text" placeholder="Correo electronico" required />
          <input name = "password" type="password" placeholder="Contraseña" required />
          <div class="checkbox">
            <input type="checkbox" id="signupCheck" />
            <label for="signupCheck">Acepto todo los terminos y condiciones</label>
          </div>
            <div style="position: center; bottom: 2px; padding: 10px; color: #fff;background: #373737; width: 320px; margin: 30px auto; margin-top: 0;border: 0;border-radius: 3px;cursor: pointer; text-decoration: none; border: #eee;" >
              <a href="/php-login/index.php" style="text-decoration: none ;"><font color="white">Ir a Inicio</font></a> 
            </div>
      

          <input type="submit" value="Signup" />
        </form>
      </div>




      <div>
        <?php if(!empty($message)): ?>
          <p> <?= $message ?></p>
        <?php endif; ?>
      </div>
      


      <script>
        const wrapper = document.querySelector(".wrapper"),
          signupHeader = document.querySelector(".signup header"),
          loginHeader = document.querySelector(".login header");

        loginHeader.addEventListener("click", () => {
          wrapper.classList.add("active");
        });
        signupHeader.addEventListener("click", () => {
          wrapper.classList.remove("active");
        });
      </script>


      
      

    </section>




  
    

  </body>


</html>