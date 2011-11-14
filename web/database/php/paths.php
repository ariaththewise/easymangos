<?php
    $startDir = getcwd();
    chdir("../../../");
    
    $root = getcwd();    
    
    $cache = ($root."\\cache");
    $config = ($root."\\config");
    $server = ($root."\\server");
    $tools = ($root."\\tools");
    
    $mangos = ($cache."\\mangos");
    $database = ($cache."\\database");
    $acid = ($cache."\\acid");
    
    $git = ($tools."\\git\\bin\\git.exe");
    $svn = ($tools."\\svn\\svn.exe");
    
    chdir($startDir);
?>