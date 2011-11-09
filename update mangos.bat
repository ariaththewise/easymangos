@ECHO OFF

IF NOT DEFINED MANGOS (
    CALL scripts\setup.bat -noterminal
)

IF NOT EXIST "%MANGOS%\src\shared\revision_nr.h" (
    ECHO.
    ECHO No se encuentra MaNGOS.

    CALL :DOWNLOAD_MANGOS

    GOTO END
    
) ELSE (
    CALL :UPDATE_MANGOS
    
    GOTO END
)


:DOWNLOAD_MANGOS
ECHO Descargando la ultima revision...
ECHO ====================================

FOR /F %%R IN (%CONFIG%\repos\mangos) DO (
    git clone %%R "%MANGOS%"
)


IF EXIST "%MANGOS%\src\shared\revision_nr.h" (
    ECHO.
    ECHO Descarga correcta.
    ECHO.
    
    GOTO END
    
) ELSE (
    ECHO.
    ECHO Descarga fallida.
    ECHO.
    
    GOTO END
)
GOTO:EOF


:UPDATE_MANGOS
PUSHD
CD /D "%MANGOS%"
    
ECHO.
ECHO Actualizando MaNGOS ...
ECHO ==========================

git pull

ECHO.
ECHO Terminado.
ECHO.
POPD
GOTO:EOF


:END
CALL "%SCRIPTS%\util" sleep 2
GOTO:EOF