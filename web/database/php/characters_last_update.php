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
    
    $charactersLastUpdateQuery = "SELECT column_name FROM information_schema.columns WHERE ".
                                 "table_schema='characters' AND table_name='character_db_version' AND ".
                                 "column_name LIKE 'required_%';";
                        
    $charactersLastUpdate = "";
    
    $dbLink = mysql_connect($host.":".$port, $user, $pass);
    
    if(!$dbLink)
    {
        echo($MYSQL_CONN_ERROR);
    }
    else
    {
        $result = mysql_query($charactersLastUpdateQuery, $dbLink);
        
        if($result != FALSE)
        {
            while($row = mysql_fetch_assoc($result))
            {
                $charactersLastUpdate = substr($row["column_name"], 9);
            }
            
            mysql_free_result($result);
            mysql_close($dbLink);
        
            echo($charactersLastUpdate);
        }
        else if($result == FALSE || (mysql_error($dbLink) != ""))
        {
            mysql_free_result($result);
            mysql_close($dbLink);
            
            echo($LAST_CHARACTERS_UPDATE_RETRIEVAL_ERROR);
        }
    }
?>