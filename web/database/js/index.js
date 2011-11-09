function litBackup()
{
    $("#butBackup").attr("src", "img/backup_selected.png");    
}


function litInstall()
{
    $("#butInstall").attr("src", "img/install_selected.png");
}


function litUpdate()
{
    $("#butUpdate").attr("src", "img/update_selected.png");
}


function litUninstall()
{
    $("#butUninstall").attr("src", "img/uninstall_selected.png");
}


function goInstall()
{
    window.location = "./install.html";
}


function goUpdate()
{
    window.location = "./update.html";
}


function unlitBackup()
{
    $("#butBackup").attr("src", "img/backup_unselected.png");    
}


function unlitInstall()
{
    $("#butInstall").attr("src", "img/install_unselected.png");
}


function unlitUpdate()
{
    $("#butUpdate").attr("src", "img/update_unselected.png");
}


function unlitUninstall()
{
    $("#butUninstall").attr("src", "img/uninstall_unselected.png");
}


function uninstall()
{
    var doUninstall = confirm("Se va a desinstalar la base de datos, ¿Estás seguro?");
    
    if(doUninstall == true)
    {
        alert("Aceptado");
    }
    else
    {
        alert("Cancelado");
    }
}
