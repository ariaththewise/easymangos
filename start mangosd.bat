@ECHO OFF

CALL scripts\setup.bat -noterminal

IF NOT EXIST "%SERVER%\mangosd.exe" (
    ECHO.
    ECHO --- MaNGOS no esta compilado aun ---
    ECHO.
    
    "%SCRIPTS%\util" sleep 2
    GOTO:EOF
    
) ELSE (
    CD "%SERVER%"
    GOTO START_MANGOSD
)

:START_MANGOSD
mangosd.exe
GOTO START_MANGOSD