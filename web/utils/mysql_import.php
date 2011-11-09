<?php
    /**
     * Importa (o ejecuta) un script SQL en la
     * base de datos indicada.
     */
    function mysql_import($script, $database, $dbLink)
    {
        if($dbLink != NULL)
        {            
            mysql_select_db($database, $dbLink) or die ("Wrong MySQL Database");
             
            $sqlFile = fopen($script,"r+");
            
            $sqlData = fread($sqlFile, filesize($script));
            $sqlQueries = explode(';',$sqlData);
            
            
            foreach ($sqlQueries as $query)
            {
              if (isValidQuery($query))
              {
                $queryResult = mysql_query($query);
                
                if (!$queryResult)
                {
                  $sqlErrorCode = mysql_errno();
                  $sqlErrorText = mysql_error();                  
                  break;
                }
              }
            }
            
            
            if ($sqlErrorCode == 0)
            {
                echo "Script is executed succesfully!";
            } 
            else 
            {
                echo "An error occured during installation!<br/>";
                echo "Error code:".$sqlErrorCode."<br/>";
                echo "Error text:".$sqlErrorText."<br/>";
                echo "Statement:<br/>."$query."<br/>";
            }
        }
    }
    
    
    /**
     * Comprueba si una consulta SQL es válida o no.     
     */
    function isValidQuery($query)
    {
        if((strlen($query) > 3) &&
           (substr(ltrim($query),0,2) !="/*") &&
           (substr(ltrim($query),0,2) !=" *") &&
           (substr(ltrim($query),0,3) !="-- ")
        {
            return true;
        }
        
        return false;
    }
?>