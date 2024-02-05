<?php
session_start();

require 'databse.php';
if (isset($_SESSION['user_id'])) {
    $records = $conn->prepare('SELECT idusers, name, email, password, user_ids, saldo, latitud, longitud, estado, verificacion_saldo FROM users WHERE idusers = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;


    if (count($results) > 0) {
        $user = $results;
        // $user['user_ids'] = $results[''];
        $user_ids = $user['user_ids'];
        $estado = $user['estado'];

    }
} else {
    $user = null;
    $user_ids = '';
    $estado = '';
}
// if (!empty($_POST['email']) && !empty($_POST['password'])) {
//     $records = $conn->prepare('SELECT idusers, name, email, password, user_ids FROM users WHERE email = :email');
//     $records->bindParam(':email', $_POST['email']);
//     $records->execute();
//     $results = $records->fetch(PDO::FETCH_ASSOC);

//     $message = '';

//     if (count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
//         $_SESSION['user_id'] = $results['idusers'];
//         header("Location: /php-login/index3.php");
//     } else {
//         $message = 'Lo sentimos datos incorrectos ';
//     }
// }

?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="description" content="Registrarse o iniciar sesión en AQPago en sencillo. Realízalo aquí en un clic." />
    <meta name="keywords" content="registro,inicio de sesión, signup,login,aqpago" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>AQPago | Regístrate o inicia sesión</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        body {
            flex-direction: column;
        }

        .ubicacion {

            position: relative;
            max-width: 470px;
            width: 100%;
            border-radius: 12px;
            padding: 20px 30px 120px;
            background: #4070f4;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 10px;
            padding: 20px 30px 20px;
        }

        .ubicacion header {
            font-size: 30px;
            text-align: center;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
        }

        .mybutton {
            outline: none;
            border: none;
            padding: 0 15px;
            color: #333;
            border-radius: 8px;
            background: #fff;
            margin-top: 15px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            height: 60px;
        }

        .ubicacion .content {
            display: flex;
            flex-direction: column;
            gap: 20px;
            /* margin-top: 40px; */
            color: #fff;
        }

        .ubicacion a {
            /* margin-top: 40px; */
            color: #fff;
        }

        .ubicacion ul {
            margin: 0 2rem;
            padding: 0.75rem;
        }
    </style>
    <!-- <script src="/custom-scripts.js" defer></script> -->
</head>

<body background="images/logo2.png">

    <div class="ubicacion">
        <header>¿Cuál es mi ubicación?</header>
        <div class="content">
            <button class="mybutton" onclick="getLocation()">Obtener ubicación</button>
            <div id="ubicacion"></div>
        </div>
        <div class="content" <?= $user ? '' : 'style="display: none"' ?> >
            <table class="content-table">
                <tr>
                    <td>Nombre:</td>
                    <td>
                        <?= $user ? $user['name'] : '' ?>
                    </td>
                </tr>
                <tr>
                    <td>Correo Electrónico:</td>
                    <td>
                        <?= $user ? $user['email'] : '' ?>
                    </td>
                </tr>
                <tr>
                    <td>Saldo disponible:</td>
                    <td>
                        S/ <?= $user ? $user['saldo'] : '' ?>
                    </td>
                </tr>
                <tr>
                    <td>Última latitud:</td>
                    <td>
                        <?= $user ? $user['latitud'] : '' ?>
                    </td>
                </tr>
                <tr>
                    <td>Última longitud:</td>
                    <td>
                        <?= $user ? $user['longitud'] : '' ?>
                    </td>
                </tr>
                <tr>
                    <td>Estado del pasajero:</td>
                    <td>
                        <?= $user ? ($user['estado']?"En el bus":"Fuera del bus") : '' ?>
                    </td>
                </tr>
                <tr>
                    <td>Verificación Saldo:</td>
                    <td>
                        <?= $user ? $user['verificacion_saldo'] : '' ?>
                    </td>
                </tr>
            </table>

        </div>
    </div>
    <section class="wrapper">
        <div class="form signup">
            <header><?= $user ? ($user['estado']?'Baja del':'Sube al') : 'Sube o baja del' ?> bus</header>
            <!-- https://www.google.com/maps/dir/'-16.4114,-71.4953'/-16.4014,-71.5343/ -->
            <form action="actualizarUbicacion.php" method="POST">
                <!-- <label for="user_ids">User IDs:</label> -->
                <input name="user_ids" type="text" placeholder="User IDs:" required
                    value="<?= $user ? $user['user_ids'] : '' ?>" />
                <!-- <label for="latitud">Latitud:</label> -->
                <input name="latitud" type="text" placeholder="Latitud:" required
                    value="<?= $user ? $user['latitud'] : '' ?>" />
                <!-- <label for="longitud">Longitud:</label> -->
                <input name="longitud" type="text" placeholder="Longitud:" required
                    value="<?= $user ? $user['longitud'] : '' ?>" />
                <!-- <input type="submit" value="Actualizar Ubicación" /> -->
                <button class="mybutton" type="submit">Actualizar Ubicación</button>
            </form>
        </div>
        <!-- <div class="form login">
            <header>Baja del bus</header>
            <form action="actualizarUbicacion.php" method="POST">
                <label for="user_ids">User IDs:</label>
                <input name="user_ids" type="text" placeholder="User IDs:" required value="<?= $user_ids ?>" />
                <label for="latitud">Latitud:</label>
                <input name="latitud" type="text" placeholder="Latitud:" required />
                <label for="longitud">Longitud:</label>
                <input name="longitud" type="text" placeholder="Longitud:" required />
                <input type="submit" value="Actualizar Ubicación" />
                <button class="mybutton" type="submit">Actualizar Ubicación</button>
            </form>
        </div> -->


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

        <script>
            const x = document.getElementById("ubicacion");

            // Obtener ubicación del dispositivo
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    x.innerHTML = "La geolocalización no es compatible con este navegador.";
                }
            }

            function showPosition(position) {
                x.innerHTML = "Latitud: " + position.coords.latitude +
                    "<br>Longitud: " + position.coords.longitude +
                    `<br> <a aria-label="Ve tu ubicación en un mapa."
                        target="_blank"
                        href="https://maps.google.com/maps/dir/`+
                    position.coords.latitude + `,` +
                    position.coords.longitude + `/`+
                    <?= $user ? $user['latitud'] : '""' ?>+
                    <?= $user ?'","' : '""' ?>+
                    <?= $user ? $user['longitud'] : '""' ?>+
                    `">
                        
                        <span>Ver en el mapa</span>
                    </a>`;
            }
        </script>

    </section>

</body>


</html>