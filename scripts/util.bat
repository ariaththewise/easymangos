@ECHO OFF

IF NOT "%1"=="" (
    CALL:%1 %*
    
) ELSE (
    CALL :help
)

GOTO:EOF


:checkmysqlstatus
SET /P PORTABLE_MYSQL=<"%CONFIG%\mysql\portable"

IF "%PORTABLE_MYSQL%"=="true" (
    TASKLIST | findstr mysqldp.exe > NUL
    
) ELSE (
    TASKLIST | findstr mysqld.exe > NUL
)

IF ERRORLEVEL 0 (    
    SET MYSQL_STATUS=ON
    
) ELSE (    
    SET MYSQL_STATUS=OFF
)
GOTO:EOF


:checkmysqlconn
SHIFT

SET /P HOST=<"%CONFIG%\mysql\host"
SET /P USER=<"%CONFIG%\mysql\user"
SET /P PASS=<"%CONFIG%\mysql\pass"
SET /P PORT=<"%CONFIG%\mysql\port"

mysql --host=%HOST% --user=%USER% --password=%PASS% --port=%PORT% -e"SELECT * FROM information_schema.schemata;" 1> NUL 2> NUL

IF ERRORLEVEL 0 (
    ECHO Conexion a MySQL exitosa.
    SET MYSQL_CONN=OK
    
) ELSE (
    ECHO Conexion a MySQL fallida.
    SET MYSQL_CONN=ERROR
)
GOTO:EOF


:sleep
SHIFT

SET /A TIME_TO_SLEEP=%1*1000

PING 1.1.1.1 -n 1 -w %TIME_TO_SLEEP% > NUL
GOTO:EOF


:showinfo
CALL :checkmysqlstatus
ECHO.
ECHO ==============================================================
ECHO Version de Visual C++ encontrada: %VCNAME%
ECHO Estado del servidor MySQL: %MYSQL_STATUS% ^| Portable: %PORTABLE_MYSQL%
ECHO ==============================================================
GOTO:EOF


:help
ECHO Uso: util checkmysqlstatus: Comprueba el estado de la
ECHO                             conexion con el servidor
ECHO                             mysql configurado.
ECHO                              
GOTO:EOF