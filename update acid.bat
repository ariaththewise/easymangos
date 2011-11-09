@ECHO OFF

IF NOT DEFINED ACID (
    CALL scripts\setup.bat -noterminal
)

IF NOT EXIST "%ACID%" (
    ECHO.
    ECHO No se encuentra ACID.

    CALL :DOWNLOAD_ACID

    GOTO END
    
) ELSE (
    CALL :UPDATE_ACID
    
    GOTO END
)


:DOWNLOAD_ACID
ECHO Descargando la ultima revision...
ECHO ====================================

FOR /F %%R IN (%CONFIG%\repos\acid) DO (
    svn co %%R "%ACID%"
)


IF EXIST "%ACID%" (
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


:UPDATE_ACID
ECHO.
ECHO Actualizando ACID ...
ECHO ==============================

svn update "%ACID%"

ECHO.
ECHO Terminado.
ECHO.
GOTO:EOF


:END
CALL "%SCRIPTS%\util" sleep 2
GOTO:EOF