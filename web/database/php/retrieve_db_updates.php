<?php
    include_once "constants.php";
    include_once "paths.php";
    
    if($argc > 1)
    {
        $dbName = $argv[1];
        $lastUpdate = $argv[2];        
    }
    else
    {
        $dbName = $_POST["dbName"];
        $lastUpdate = $_POST["lastUpdate"];
    }
    
    $updateDirs = { "0.14", "0.15", "0.16", "" };
    
    
    $allUpdates = Array();
    $neededUpdates = Array();
    
    $allUpdatesCount = 0;
    $neededUpdatesCount = 0;    
    $updateSubdirCount = 0;
    
    if(file_exists($mangos."\\sql\\updates"))
    {
        
        $dir = opendir($mangos."\\sql\\updates");
        
        while($currentEntry = readdir($dir))
        {
            if(is_dir($currentEntry) && !strstr($currentEntry, "."))
            {
                $updateSubdir = Array();
                $subDir = $openDir($mangos."\\sql\\updates\\".$currentEntry);
                
                while($subDirEntry = read($subDir))
                {
                    $updateSubdir[$updateSubdirCount] = $subDirEntry;
                }
            }
            
            if(strstr($currentEntry, ".sql"))
            {
                $update = explode("_", $currentEntry);
                
                if($update[2] == $dbName)
                {
                    $allUpdates[$allUpdatesCount] = $currentEntry;
                    $allUpdatesCount ++;
                }
            }
        }
        
        closedir($dir);
        
        $lastUpdateIndex = array_search(($lastUpdate.".sql"), $allUpdates);
        
        for($u = ($lastUpdateIndex + 1); $u <= ($allUpdatesCount - 1); $u ++)
        {
            $neededUpdates[$neededUpdatesCount] = $allUpdates[$u];
            $neededUpdatesCount ++;
        }
        
        if($neededUpdatesCount > 0)
        {
            echo(json_encode($neededUpdates));
        }
        else
        {
            switch($dbName)
            {
                case "characters":
                    echo($NO_CHARACTERS_UPDATE_FOUND);
                    break;
                
                case "mangos":
                    echo($NO_MANGOS_UPDATE_FOUND);
                    break;
                    
                case "realmd":
                    echo($NO_REALMD_UPDATE_FOUND);
                    break;
            }
        }
    }
    else
    {
        echo($MANGOS_UPDATE_DIR_NOT_FOUND);
    }
?>