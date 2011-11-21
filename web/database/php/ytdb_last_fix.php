<?php
    include_once "constants.php";
    
    if($argc > 1)
    {
        $host = $argv[1];
        $port = $argv[2];
        $user = $argv[3];
        $pass = $argv[4];
    }
    else
    {
        $host = $_POST["host"];
        $port = $_POST["port"];
        $user = $_POST["user"];
        $pass = $_POST["pass"];
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
                $db_Version_ytdb = explode("_", $row["column_name"]);
                $ytdbLastFix = $db_Version_ytdb[0];
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