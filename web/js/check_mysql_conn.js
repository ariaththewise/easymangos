function checkMySQLConn()
{
    mysqlHost = $("#mysqlHost").val();
    mysqlUser = $("#mysqlUser").val();
    mysqlPass = $("#mysqlPass").val();
    mysqlPort = $("#mysqlPort").val();
    
    $.ajax({
        async:      true,
        type:       "POST",
        url:        "../php/mysqlconn_test.php",
        
        beforeSend: function()
                    {
                        $("#mysqlConnState").css("background-color", "white");
                    },
        
        data:       { mysql_host : mysqlHost ,
                      mysql_user : mysqlUser ,
                      mysql_pass : mysqlPass ,
                      mysql_port : mysqlPort },
        
        error:      function()
                    {
                        $("#mysqlConnState").css("background-color", "red");
                    },
        
        success:    function(data)
                    {
                        if(data == "")
                        {
                            $("#mysqlConnState").css("background-color", "red");
                        }
                        else
                        {
                            if($.parseJSON(data) == true)
                            {
                                $("#mysqlConnState").css("background-color", "lime");
                            }
                        }
                    }
    });
}