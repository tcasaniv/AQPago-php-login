<?php
include 'databse2.php';

if ($conn) {
    echo "Conexion con base de datos exitosa! ";
    
    if(isset($_POST['user_ids'])) {
        $user_ids = $_POST['user_ids'];
        echo " user ids : ".$user_ids;
    }
    if(isset($_POST['latitud'])) {
        $latitud = $_POST['latitud'];
        echo " latitud : ".$latitud;
    }
 
    if(isset($_POST['longitud'])) { 
        $longitud = $_POST['longitud'];
        echo " longitud : ".$longitud;
        
        $consulta = "UPDATE users SET latitud = '$latitud', longitud = '$longitud' WHERE user_ids = '$user_ids'";
       // $consulta = "UPDATE DHT11 SET Temperatura='$temperatura',Humedad='$humedad' WHERE Id = 1";
        $resultado = mysqli_query($conn, $consulta);
        if ($resultado){
            echo " Registo en base de datos OK! ";
        } else {
            echo " Falla! Registro BD";
        }
    }else{
        echo "Falla! no agrego ";   
    }
    
    
} else {
    echo "Falla! conexion con Base de datos ";   
}


?>