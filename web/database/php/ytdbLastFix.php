<?php
    $host = $_POST["host"];
    $user = $_POST["user"];
    $pass = $_POST["pass"];
    $port = $_POST["port"];
    
    $ytdbLastFixQuery = "SELECT column_name FROM information_schema.columns WHERE ".
                        "table_schema='mangos' AND table_name='db_version_ytdb' AND ".
                        "column_name LIKE '%_FIX_%';";
                        
    $ytdbLastFix = "";
    
    $dbLink = mysql_connect($host.":".$port, $user, $pass);
    
    if(!$dbLink)
    {
        die("ERROR");
    }
    else
    {
        $result = mysql_query($ytdbLastFixQuery, $dbLink);
        
        if($result)
        {
            while($row = mysql_fetch_assoc($result))
            {
                $ytdbLastFix = substr($row["column_name"], 0, 3);
            }
        }
        
        mysql_free_result($result);
        mysql_close($dbLink);
        
        echo($ytdbLastFix);
    }
?>