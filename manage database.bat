@ECHO OFF

IF NOT DEFINED SCRIPTS (
    CALL scripts\setup.bat -noconsole
)

ECHO Iniciando la web de administracion de la base de datos ...
ECHO.

CALL "%SCRIPTS%\util" sleep 2

CALL "start webserver.bat"
START http://localhost:81/database/index.html

CALL "%SCRIPTS%\util" sleep 2
