<?php
    // Establece el caracter fin de línea a usar.
    // Si se pasa por GET la variable output como
    // web, se usará el salto de línea web <BR>.
    //
    // Caso contrario, se usará el salto de línea
    // de consola \n .
    //
    
    $outputTo = $_GET['output'];
    
    if($outputTo == "web")
    {
        $newline = "<BR>";
    }
    else
    {
        $newline = "\n";
    }
    
    $ytdbLastFixQuery = "SELECT column_name FROM information_schema.columns WHERE ".
                        "table_schema='mangos' AND table_name='db_version_ytdb' AND ".
                        "column_name LIKE '%_FIX_%';";
                        
    $ytdbLastFix = "";
    
    echo($newline."Intentando conectar con MySQL ...");
    $dbLink = mysql_connect("localhost:3307", "root", "root");
    
    if(!$dbLink)
    {
        die("No se ha podido conectar con MySQL.");
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
        else
        {
            die("Consulta invalida: ".mysql_error().$newline);
        }
        
        echo($newline."Conexion con MySQL finalizada.");
        mysql_free_result($result);
        mysql_close($dbLink);
        
        echo($newline.$newline."Ultimo fix: ".$ytdbLastFix);
        
        // Ruta de actualizaciones de YTDB.
        $ytdbUpdatesDir = "../../cache/database/Updates/";
        
        $rawUpdates = scandir($ytdbUpdatesDir);
        
        // Actualizaciones encontradas para cada base de datos.
        $charactersUpdates = Array();
        $mangosFixes = Array();
        $mangosUpdates = Array();
        $realmdUpdates = Array();
        
        $cUpdCount = 0;
        $mUpdCount = 0;    
        $rUpdCount = 0;
        $mFixCount = 0;
        
        
        for($f = 0; $f <= (count($rawUpdates) - 1); $f ++)
        {
            if(intval(substr($rawUpdates[$f], 0, 3)) > intval($ytdbLastFix))
            {
                if(substr($rawUpdates[$f], (strlen($rawUpdates[$f]) - 4), 4) == ".sql")
                {
                    $FirstTokenLOffset = (stripos($rawUpdates[$f], "_") + 1);
                    $FirstTokenROffset = stripos($rawUpdates[$f], "_", $FirstTokenLOffset);
                    
                    $SecondTokenLOffset = (stripos($rawUpdates[$f], "_", $FirstTokenROffset) + 1);
                    $SecondTokenROffset = stripos($rawUpdates[$f], "_", $SecondTokenLOffset);
                    
                    $FirstToken = substr($rawUpdates[$f], $FirstTokenLOffset, ($FirstTokenROffset - $FirstTokenLOffset));
                    $SecondToken = substr($rawUpdates[$f], $SecondTokenLOffset, ($SecondTokenROffset - $SecondTokenLOffset));
                    
                    if($FirstToken == "corepatch")
                    {
                        switch($SecondToken)
                        {
                            case "characters":                        
                                $charactersUpdates[$cUpdCount] = ($ytdbUpdatesDir . $rawUpdates[$f]);
                                $cUpdCount ++;
                                break;
                            
                            case "mangos":                        
                                $mangosUpdates[$mUpdCount] = ($ytdbUpdatesDir . $rawUpdates[$f]);
                                $mUpdCount ++;
                                break;
                                
                            case "realmd":                        
                                $realmdUpdates[$rUpdCount] = ($ytdbUpdatesDir . $rawUpdates[$f]);
                                $rUpdCount ++;
                                break;
                        }
                    }
                    else
                    {                
                        $mangosFixes[$mFixCount] = ($ytdbUpdatesDir . $rawUpdates[$f]);
                        $mFixCount ++;
                    }
                }
            }
        }
        
        echo($newline.$newline."Encontradas ".count($charactersUpdates)." actualizacion/es para Characters.");
        foreach($charactersUpdates as $update)
        {
            echo($newline.$update);
        }
        
        echo($newline.$newline."Encontradas ".count($mangosUpdates)." actualizacion/es para Mangos.");
        foreach($mangosUpdates as $update)
        {
            echo($newline.$update);
        }
        
        echo($newline.$newline."Encontradas ".count($realmdUpdates)." actualizacion/es para Realmd.");
        foreach($realmdUpdates as $update)
        {
            echo($newline.$update);
        }
        
        echo($newline.$newline."Encontradas ".count($mangosFixes)." correccion/es para Mangos.");
        foreach($mangosFixes as $fix)
        {
            echo($newline.$fix);
        }
    }
?>