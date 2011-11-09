@ECHO OFF

CALL scripts\setup.bat -noterminal

IF NOT EXIST "%SERVER%\realmd.exe" (
    ECHO.
    ECHO --- MaNGOS no esta compilado aun ---
    ECHO.
    
    "%SCRIPTS%\util" sleep 2
    GOTO:EOF
    
) ELSE (
    CD "%SERVER%"
    GOTO START_REALMD
)

:START_REALMD
realmd.exe
GOTO START_REALMD