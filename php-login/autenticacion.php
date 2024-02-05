<?php

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: /php-login/index3.php');
}
require 'databse.php';

if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $records = $conn->prepare('SELECT idusers, name, email, password, user_ids FROM users WHERE email = :email');
    $records->bindParam(':email', $_POST['email']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = '';

    if (count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
        $_SESSION['user_id'] = $results['idusers'];
        header("Location: /php-login/index3.php");
    } else {
        $message = 'Lo sentimos datos incorrectos ';
    }
}

?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="description"
        content="Registrarse o iniciar sesión en AQPago en sencillo. Realízalo aquí en un clic." />
    <meta name="keywords" content="registro,inicio de sesión, signup,login,aqpago" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>AQPago | Regístrate o inicia sesión</title>
    <link rel="stylesheet" href="style.css" />
    <!-- <script src="/custom-scripts.js" defer></script> -->
</head>

<body background="images/logo2.png">

    <section class="wrapper">
        <div class="form signup">
            <header>Regístrate</header>
            <form action="signup.php" method="POST">
                <input name="name" type="text" placeholder="Nombre Completo" required />
                <input name="email" type="text" placeholder="Correo electronico" required />
                <input name="password" type="password" placeholder="Contraseña" required />
                <div class="checkbox">
                    <input type="checkbox" id="signupCheck" />
                    <label for="signupCheck">Acepto todo los terminos y condiciones</label>
                </div>
                <!-- <div
                    style="position: center; bottom: 2px; padding: 10px; color: #fff;background: #373737; width: 320px; margin: 30px auto; margin-top: 0;border: 0;border-radius: 3px;cursor: pointer; text-decoration: none; border: #eee;">
                    <a href="/php-login/index.php" style="text-decoration: none ;">
                        <font color="white">Ir a Inicio</font>
                    </a>
                </div> -->


                <input type="submit" value="Registrarse" />
            </form>
        </div>
        <div class="form login">
            <header>Inicia sesión</header>
            <form action="autenticacion.php" method="POST">
                <input name="email" type="text" placeholder="Correo electronico" required />
                <input name="password" type="password" placeholder="Contraseña" required />
                <a href="#">Olvido la contraseña?</a>
                <!-- <div
                    style="position: center; bottom: 2px; padding: 10px; color: #fff;background: #373737; width: 320px; margin: 30px auto; margin-top: 0;border: 0;border-radius: 3px;cursor: pointer; text-decoration: none; border: #eee;">
                    <a href="/php-login/index.php" style="text-decoration: none;">
                        <font color="white">Ir a Inicio</font>
                    </a>
                </div> -->
                <input type="submit" value="Iniciar sesión" />
            </form>
        </div>


        <?php if (!empty($message)): ?>
            <p>
                <?= $message ?>
            </p>
        <?php endif; ?>




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

        <!--form action="actualizarUbicacion.php" method="POST">
        <label for="user_ids">User IDs:</label>
        <input type="text" name="user_ids" required>

        <label for="latitud">Latitud:</label>
        <input type="text" name="latitud" required>

        <label for="longitud">Longitud:</label>
        <input type="text" name="longitud" required>

        <button type="submit">Actualizar Ubicación</button>
      </form-->


    </section>







</body>


</html>