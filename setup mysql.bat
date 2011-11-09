@ECHO OFF

IF NOT DEFINED MYSQL (
    CALL scripts\setup.bat -noterminal
)

SET /P CURRENT_MYSQL_HOST=<"%CONFIG%\mysql\host"
SET /P CURRENT_MYSQL_PORT=<"%CONFIG%\mysql\port"
SET /P CURRENT_MYSQL_USER=<"%CONFIG%\mysql\user"
SET /P CURRENT_MYSQL_PASS=<"%CONFIG%\mysql\pass"
SET /P CURRENT_MYSQL_PORTABLE=<"%CONFIG%\mysql\portable"

ECHO.
ECHO --- Configuracion del servidor MySQL a usar ---
ECHO.

ECHO El servidor MySQL actualmente configurado es este:
ECHO ===================================================
ECHO Host: %CURRENT_MYSQL_HOST%
ECHO Puerto: %CURRENT_MYSQL_PORT%
ECHO Usuario: %CURRENT_MYSQL_USER%
ECHO Password: %CURRENT_MYSQL_PASS%
ECHO Portable: %CURRENT_MYSQL_HOST%
ECHO ===================================================

SETLOCAL EnableDelayedExpansion
SET /P CHANGE="Deseas cambiarlo? (s/n): "
ECHO.

IF "!CHANGE!"=="s" (
    SET /P NEW_MYSQL_HOST="Host: "
    SET /P NEW_MYSQL_PORT="Puerto: "
    SET /P NEW_MYSQL_USER="Usuario: "
    SET /P NEW_MYSQL_PASS="Password: "
    SET NEW_MYSQL_PORTABLE=false
    
    ECHO !NEW_MYSQL_HOST!>"%CONFIG%\mysql\host"
    ECHO !NEW_MYSQL_PORT!>"%CONFIG%\mysql\port"
    ECHO !NEW_MYSQL_USER!>"%CONFIG%\mysql\user"
    ECHO !NEW_MYSQL_PASS!>"%CONFIG%\mysql\pass"
    ECHO !NEW_MYSQL_PORTABLE!>"%CONFIG%\mysql\portable"
    
    ECHO.
    ECHO Nuevo servidor configurado.
    
) ELSE (
    ECHO.
    ECHO Saliendo sin cambios.
)
ENDLOCAL

"%SCRIPTS%\util" sleep 2