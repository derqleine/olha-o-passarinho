<?php
    define('DB_SERVER','localhost');
    define('DB_USERNAME','root');
    define('DB_PASSWORD','');
    define('DB_NAME','polaroidexpocic');
    
    
  

    $link = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

    if($link->connect_errno){
        printf("Ocorreu um Erro - Ligação Falhou: %s\n", $link->connect_error);
        exit();
    }
    
    else{
        
        echo("Ligado");
    }
    /*
    header("Content-type: text/html; charset=utf-8");

    session_start();
*/


    //<link rel="stylesheet" href="http://use.fontawesome.comm/releases/v5.7.1/css/all.css">
?>