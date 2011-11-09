@ECHO OFF

IF NOT DEFINED DATABASE (
    CALL scripts\setup.bat -noterminal
)

SET SQL_CONTROL="%TEMP%\sqlavailable"
SET ZIP_CONTROL="%TEMP%\zipavailable"

CALL :CLEAN_TEMP

IF NOT EXIST "%MANGOS%\sql" (
    CALL "update mangos.bat"
)

IF NOT EXIST "%DATABASE%" (
    ECHO.
    ECHO No se encuentra la base de datos.

    CALL :DOWNLOAD_DATABASE    
    
    IF EXIST %ZIP_CONTROL% (
        CALL :EXTRACT_DATABASE
    )

    GOTO END
    
) ELSE (
    CALL :UPDATE_DATABASE    
    DEL "%DATABASE%\*.sql" > NUL
    CALL :EXTRACT_DATABASE

    GOTO END
)


:CLEAN_TEMP
DEL %SQL_CONTROL% 2> NUL
DEL %ZIP_CONTROL% 2> NUL
GOTO:EOF


:DOWNLOAD_DATABASE
ECHO Descargando la ultima revision...
ECHO ====================================

FOR /F %%R IN (%CONFIG%\repos\database) DO (
    svn co %%R "%DATABASE%"
)
    
FOR %%F IN ("%DATABASE%\*.7z") DO (
    ECHO dummy > %ZIP_CONTROL%
)

IF EXIST %ZIP_CONTROL% (
    ECHO Descarga correcta.
    ECHO.
    
) ELSE (
    ECHO Descarga fallida.
    ECHO.
    
    CALL "%SCRIPTS%\util" sleep 2
    GOTO END
)
GOTO:EOF


:EXTRACT_DATABASE
ECHO Extrayendo ...
ECHO =================
    
FOR %%F IN ("%DATABASE%\*.7z") DO (    
    7za x "%%F" -o"%DATABASE%\"
)        

FOR %%F IN ("%DATABASE%\*.sql") DO (
    ECHO dummy > %SQL_CONTROL%
)

IF EXIST %SQL_CONTROL% (
    ECHO Extraccion correcta.
    ECHO.
    
) ELSE (
    ECHO Extraccion fallida.
    ECHO.
    
    CALL "%SCRIPTS%\util" sleep 2
    GOTO END
)
GOTO:EOF


:UPDATE_DATABASE
ECHO.
ECHO Actualizando base de datos ...
ECHO =================================

svn update "%DATABASE%"

ECHO.
ECHO Terminado.
ECHO.
GOTO:EOF


:END
CALL "%SCRIPTS%\util" sleep 2
CALL :CLEAN_TEMP
GOTO:EOF