@ECHO OFF

IF NOT DEFINED MYSQL (
    CALL scripts\setup.bat -noterminal
)

CALL "%SCRIPTS%\util" checkmysqlstatus

IF NOT "%MYSQL_STATUS%"=="ON" (
    ECHO.
    ECHO Iniciando MySQL ...
    ECHO ======================

    IF "%PORTABLE_MYSQL%"=="true" (
        START /D "%MYSQL%" /B mysqldp.exe --standalone > NUL
        
    ) ELSE (
        NET START MySQL
    )

    CALL "%SCRIPTS%\util" checkmysqlstatus

    IF "%MYSQL_STATUS%"=="ON" (
        ECHO Iniciado.
        ECHO.
        
    ) ELSE (
        ECHO No se pudo iniciar.
        ECHO.
    )
    
) ELSE (
    ECHO El servidor ya esta iniciado.
    ECHO.
)

CALL "%SCRIPTS%\util" SLEEP 2
GOTO:EOF