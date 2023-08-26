<?php
/**
 * Created by PhpStorm.
 * User: miguel.llabres
 * Date: 09/03/2017
 * Time: 14:10
 */
session_start();

$_SESSION['connection'] = "";
$_SESSION['databases'] = array();

$host= $_POST['host'];
$user =$_POST['user'];
$pass= $_POST['password'];
$port= $_POST['port'];

$_SESSION['host'] = $host;
$_SESSION['user'] = $user;
$_SESSION['pass'] = $pass;
$_SESSION['port'] = $port;



$con = mysqli_connect($host, $user, $pass, '', $port);
//$con = mysqli_connect("172.25.25.24", "Ihgitop1", "iHg1t0pi", '', 3306);
// Check connection
if(!$con){
    echo "Error de depuracion: " . mysqli_connect_errno() . PHP_EOL."error de depuracion: " . mysqli_connect_error() . PHP_EOL;
}else{
    $_SESSION['connection'] = $con;


    /*BUSCAMOS TODAS LAS VISTAS LE LA BASE DE DATOS*/
    $sql = "SHOW databases";
    $response = $con->query($sql); //TRUE o FALSE
    while($row = $response->fetch_assoc()) {
        array_push($_SESSION['databases'], "<option value='".strtolower($row['Database'])."'>".$row['Database']."</option>");
        //print_r($row);
    }

    if(!$response){
        echo "HA HABIDO UN ERROR";
    }else{
        echo "Conexión establecida";
    }
}
