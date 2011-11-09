<?php
    include "mysql_import.php";
    
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
        $dbLink = mysql_connect($mysqlHost.":".$mysqlPort, $mysqlUser, $mysqlPass);
        
        if($dbLink == NULL)
        {
            echo("MYSQL_CONN_ERROR");
        }
        else
        {
            mysql_close($dbLink);
            echo("DB_INSTALL_OK");
        }
    }
?>