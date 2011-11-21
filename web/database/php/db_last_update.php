<?php
    include_once "constants.php";
    
    if($argc > 1)
    {
        $host = $argv[1];
        $port = $argv[2];
        $user = $argv[3];
        $pass = $argv[4];
        $dbName = $argv[5];
    }
    else
    {
        $host = $_POST["host"];
        $port = $_POST["port"];
        $user = $_POST["user"];
        $pass = $_POST["pass"];
        $dbName = $_POST["dbName"];
    }
    
    
    switch($dbName)
    {
        case "characters":
            $lastUpdateTable = "character_db_version";            
            break;
        
        case "mangos":
            $lastUpdateTable = "db_version";
            break;
            
        case "realmd":
            $lastUpdateTable = "realmd_db_version";
            break;
    }
    
    
    $lastUpdateQuery = "SELECT column_name FROM information_schema.columns WHERE ".
                       "table_schema='".$dbName."' AND table_name='".$lastUpdateTable."' AND ".
                       "column_name LIKE 'required_%';";
                        
    $lastUpdate = "";
    
    $dbLink = mysql_connect($host.":".$port, $user, $pass);
    
    if(!$dbLink)
    {
        echo($MYSQL_CONN_ERROR);
    }
    else
    {
        $result = mysql_query($lastUpdateQuery, $dbLink);
        
        if($result != FALSE)
        {
            while($row = mysql_fetch_assoc($result))
            {
                $lastUpdate = substr($row["column_name"], 9);
            }
            
            mysql_free_result($result);
            mysql_close($dbLink);
        
            echo($lastUpdate);
        }
        else if($result == FALSE || (mysql_error($dbLink) != ""))
        {
            mysql_free_result($result);
            mysql_close($dbLink);
            
            switch($dbName)
            {
                case "characters":
                    echo($LAST_CHARACTERS_UPDATE_RETRIEVAL_ERROR);
                    break;
                
                case "mangos":
                    echo($LAST_MANGOS_UPDATE_RETRIEVAL_ERROR);
                    break;
                    
                case "realmd":
                    echo($LAST_REALMD_UPDATE_RETRIEVAL_ERROR);
                    break;
            }            
        }
    }
?>