<?php
    include_once "constants.php";
    
    if($argc > 1)
    {
        $host = $argv[1];
        $user = $argv[2];
        $pass = $argv[3];
        $port = $argv[4];
    }
    else
    {
        $host = $_POST["host"];
        $user = $_POST["user"];
        $pass = $_POST["pass"];
        $port = $_POST["port"];
    }
    
    $ytdbLastFixQuery = "SELECT column_name FROM information_schema.columns WHERE ".
                        "table_schema='mangos' AND table_name='db_version_ytdb' AND ".
                        "column_name LIKE '%_FIX_%';";
                        
    $ytdbLastFix = "";
    
    $dbLink = mysql_connect($host.":".$port, $user, $pass);
    
    if(!$dbLink)
    {
        echo($MYSQL_CONN_ERROR);
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
            
            mysql_free_result($result);
            mysql_close($dbLink);
            
            echo($ytdbLastFix);
        }
        else if($result == FALSE || (mysql_error($dbLink) != ""))
        {
            mysql_free_result($result);
            mysql_close($dbLink);
            
            echo($LAST_YTDB_FIX_RETRIEVAL_ERROR);
        }
    }
?>