@ECHO OFF

CALL scripts\setup.bat -noterminal
CALL "%SCRIPTS%\util" checkmysqlptatus

IF "%MYSQL_STATUS%"=="ON" (
    ECHO.
    ECHO Deteniendo MySQL ...
    ECHO =======================

    IF "%PORTABLE_MYSQL%"=="true" (
        TASKKILL /F /IM mysqldp.exe 1> NUL 2> NUL
        
    ) ELSE (
        NET STOP MySQL
    )

    CALL "%SCRIPTS%\util" checkmysqlptatus

    IF "%MYSQL_STATUS%"=="OFF" (
        ECHO Detenido.
        ECHO.
        
    ) ELSE (
        ECHO No se pudo detener.
        ECHO.
    )
    
) ELSE (
    ECHO El servidor ya esta detenido.
    ECHO.
)

CALL "%SCRIPTS%\util" SLEEP 2
GOTO:EOF