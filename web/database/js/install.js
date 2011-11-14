function getMySQLConfig()
{
    $.ajax({
        async:      true,
        type:       "POST",
        url:        "./php/load_mysql_config.php",
        
        beforeSend: function()
                    {
                        $("#mysqlHost").attr("value", "");
                        $("#mysqlPort").attr("value", "");
                        $("#mysqlUser").attr("value", "");
                        $("#mysqlPass").attr("value", "");
                    },
        
        success:    function(data)
                    {
                        if(data != "")
                        {
                            fillMySQLConfig(JSON.parse(data));
                        }                        
                    }
    });
}


function fillMySQLConfig(mysqlConfig)
{
    $("#mysqlHost").attr("value", mysqlConfig[0]);
    $("#mysqlPort").attr("value", mysqlConfig[1]);
    $("#mysqlUser").attr("value", mysqlConfig[2]);
    $("#mysqlPass").attr("value", mysqlConfig[3]);
}