<?php
    include "paths.php";
    
    $mysqlConfig = Array();
    
    $mysqlConfig[0] = file_get_contents($config."\\mysql\\host");
    $mysqlConfig[1] = file_get_contents($config."\\mysql\\port");
    $mysqlConfig[2] = file_get_contents($config."\\mysql\\user");
    $mysqlConfig[3] = file_get_contents($config."\\mysql\\pass");
    
    echo(json_encode($mysqlConfig));
?>