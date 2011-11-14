<?php
    $mysqlHost = $_POST["mysqlHost"];
    $mysqlUser = $_POST["mysqlUser"];
    $mysqlPass = $_POST["mysqlPass"];
    $mysqlPort = $_POST["mysqlPort"];

    
    if($mysqlHost == "" ||
       $mysqlUser == "" ||
       $mysqlPass == "" ||
       $mysqlPort == "")
    {
        echo("MYSQL_SETUP_MISSING");
    }
    else
    {
        $dbLink = mysqli_connect($mysqlHost, $mysqlUser, $mysqlPass, $mysqlPort);
        
        if($dbLink == NULL)
        {
            echo("MYSQL_CONN_ERROR");
        }
        else
        {
            mysqli_close($dbLink);
            echo("DB_INSTALL_OK");
        }
    }
?>