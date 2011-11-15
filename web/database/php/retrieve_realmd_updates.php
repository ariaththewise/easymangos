<?php
    include_once "constants.php";
    include_once "paths.php";
    
    $realmdUpdates = Array();
    $updatesCount = 0;
    
    if(file_exists($mangos."\\sql\\updates"))
    {
        $dir = opendir($mangos."\\sql\\updates");
        
        while(($currentFile = readdir($dir)) != FALSE)
        {
            if(strstr($currentFile, ".sql") != FALSE)
            {                
                $update = explode("_", $currentFile);
                
                if($update[2] == "realmd")
                {
                    $realmdUpdates[$updatesCount] = $currentFile;
                    $updatesCount ++;
                }
            }
        }
        
        closedir($dir);
        
        if($updatesCount > 0)
        {
            print_r($realmdUpdates);
        }
        else
        {
            echo($NO_REALMD_UPDATE_FOUND);
        }
    }
    else
    {
        echo($MANGOS_UPDATE_DIR_NOT_FOUND);
    }
?>