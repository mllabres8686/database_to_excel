<?php
/**
 * Created by PhpStorm.
 * User: miguel.llabres
 * Date: 09/03/2017
 * Time: 14:53
 */

session_start();
$_SESSION['tables'] = array();
$_SESSION['database'] = $_POST['database'];
$con = mysqli_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass'], $_SESSION['database'], $_SESSION['port']);

/*BUSCAMOS TODAS LAS VISTAS LE LA BASE DE DATOS*/
$sql = "SHOW FULL TABLES IN ".$_SESSION['database']." WHERE TABLE_TYPE LIKE 'BASE TABLE'";
$response = $con->query($sql);
while($row = $response->fetch_assoc()) {
    //array_push($_SESSION['vistas'], $row['Tables_in_' . $_POST['database']]);
    array_push($_SESSION['tables'],"<input type='checkbox' name='tables' value='".$row['Tables_in_' . $_SESSION['database']]."'>".$row['Tables_in_' . $_SESSION['database']]."<br>");

}

if(!$response){
    echo "HA HABIDO UN ERROR";
}else{
    echo "Tablas recuperadas";
}