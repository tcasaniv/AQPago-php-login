<?php
session_start();

require 'databse.php';
if (isset($_SESSION['user_id'])) {
    $records = $conn->prepare('SELECT idusers, name, email, password, user_ids, saldo FROM users WHERE idusers = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0) {
        $user = $results;
    }
}else{
    header('Location: /php-login/index.php');
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Dashboard</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">




    <script src="JsBarcode.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li class="has-sub">
                            <a class="js-arrow" href="index3.php">
                                <i class="fas fa-tachometer-alt"></i>Saldo</a>
                        </li>
                        <li>
                            <a href="codigoBarras.php">
                                <i class="fas fa-chart-bar"></i>Codigo QR</a>
                        </li>
                        <li>
                            <a href="historialPagos.php">
                                <i class="fas fa-table"></i>Historial de pagos</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li class="active has-sub">
                            <a class="js-arrow" href="index3.php">
                                <i class="fas fa-tachometer-alt"></i>Saldo</a>
                        </li>
                        <li>
                            <a href="codigoBarras.php">
                                <i class="fas fa-chart-bar"></i>Código QR</a>
                        </li>
                        <li>
                            <a href="historialPagos.php">
                                <i class="fas fa-table"></i>Historial de Pagos</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">

                            <div class="header-button">

                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="images/usuario.png" alt="<?= $user['name'] ?>" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">
                                                <?= $user['name'] ?>
                                            </a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="images/usuario.png" alt="Foto de perfil" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <span class="email">
                                                        <?= $user['email'] ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="logout.php">
                                                    <i class="zmdi zmdi-power"></i>Cerrar sesión</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">




                    <div class="container-fluid">


                        <div class="row m-t-25" style="justify-content: center">
                            <canvas id="qr" style="	max-width: 250px;max-height: 250px;width: 100%;"></canvas>
                        </div>
                    </div>






                    <div class="container-fluid" style="display: flex;justify-content: center;">
                        <br>
                        <button id="updateUserIdsButton" align-items="center">Actualizar Código QR</button>




                        <script>
                            $(document).ready(function () {
                                $("#updateUserIdsButton").click(function () {
                                    // Realiza una solicitud AJAX al servidor para actualizar el user_ids
                                    $.ajax({
                                        type: "POST",
                                        url: "actualizar.php", // Ruta al script PHP que manejará la actualización
                                        data: {
                                            // Envía el ID del usuario u otros datos necesarios
                                            user_id: <?= $user['idusers'] ?> // Reemplaza con el ID de usuario real
                                        },
                                        success: function (response) {
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
                    </div>



                </div>

            </div>
            <!-- END MAIN CONTENT-->
            <!-- END PAGE CONTAINER-->
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>






    <!-- Main JS-->
    <script src="js/qrious.js"></script>
    <script src="js/jspdf.debug.js"></script>



    <script>
        var id_usuario = <?= $user['user_ids'] ?>;
        var qr = new QRious({
            element: document.getElementById('qr'),
            value: id_usuario.toString()
        });

    </script>





    <script src="js/main.js"></script>

</body>

</html>
<!-- end document-->
