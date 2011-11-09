@ECHO OFF

IF NOT DEFINED SD2 (
    CALL scripts\setup.bat -noterminal
)

IF NOT EXIST "%MANGOS%\src\shared\revision_nr.h" (
    CALL "update mangos.bat"
)

IF NOT EXIST "%SD2%" (
    ECHO.
    ECHO No se encuentra ScriptDev2.
    
    CALL :DOWNLOAD_SD2   
    
    GOTO END
    
) ELSE (
    CALL :UPDATE_SD2
    
    GOTO END
)


:DOWNLOAD_SD2
ECHO Descargando la ultima revision...
ECHO ====================================

FOR /F %%R IN (%CONFIG%\repos\sd2) DO (
    git clone %%R "%SD2%"
)


IF EXIST "%SD2%\sd2_revision_sql.h" (
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


:UPDATE_SD2
PUSHD
CD /D "%SD2%"
    
ECHO.
ECHO Actualizando ScriptDev2 ...
ECHO ==============================

git pull

ECHO.
ECHO Terminado.
ECHO.
POPD
GOTO:EOF


:END
CALL "%SCRIPTS%\util" sleep 2
GOTO:EOF