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
    
    $mangosLastUpdateQuery = "SELECT column_name FROM information_schema.columns WHERE ".
                             "table_schema='mangos' AND table_name='db_version' AND ".
                             "column_name LIKE 'required_%';";
                        
    $mangosLastUpdate = "";
    
    $dbLink = mysql_connect($host.":".$port, $user, $pass);
    
    if(!$dbLink)
    {
        echo($MYSQL_CONN_ERROR);
    }
    else
    {
        $result = mysql_query($mangosLastUpdateQuery, $dbLink);
        
        if($result)
        {
            while($row = mysql_fetch_assoc($result))
            {
                $mangosLastUpdate = substr($row["column_name"], 9);
            }
            
            mysql_free_result($result);
            mysql_close($dbLink);
        
            echo($mangosLastUpdate);
        }
        else
        {
            mysql_free_result($result);
            mysql_close($dbLink);
            
            echo($LAST_MANGOS_UPDATE_RETRIEVAL_ERROR);
        }
    }
?>