<?php


    $dsn = "mysql:host=127.0.0.1;dbname=study";
    $name = 'root';
    $pass = '';

    try{

        $connect = new PDO($dsn, $name, $pass);

        echo "Connected successfully.";

    }
    catch(PDOException $e){
        echo $e->getMessage();
    }


?>