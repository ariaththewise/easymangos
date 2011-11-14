<?php
    $host = $_POST['mysql_host'];
    $user = $_POST['mysql_user'];
    $pass = $_POST['mysql_pass'];
    $port = $_POST['mysql_port'];
    
    if(($dbLink = mysql_connect($host.":".$port, $user, $pass)) == TRUE)
    {
        mysql_close($dbLink);
        echo(json_encode(true));
    }    
?>