<?php
/**
 * Created by PhpStorm.
 * User: miguel.llabres
 * Date: 01/03/2017
 * Time: 11:28
 */
session_start();

foreach ($_POST as $postindex=>$post){
    $_SESSION[$postindex]=$post;
}


?>