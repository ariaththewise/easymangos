@ECHO OFF

IF NOT DEFINED MANGOS (
    CALL scripts\setup.bat
)

IF "%VCVER%"=="NULL" (
    CALL :VC_NOT_FOUND
    GOTO :END
)


IF EXIST "%MANGOS%\src\bindings\scriptdev2\scriptVC%VCVER%.sln" (
    ECHO.
    ECHO Compilando ScriptDev2 ...
    ECHO ============================
    
    CALL scripts\vc build "%MANGOS%\src\bindings\scriptdev2\scriptVC%VCVER%.sln"
    GOTO END
    
) ELSE (
    CALL :SD2_NOT_FOUND
    GOTO END
)


:SD2_NOT_FOUND
ECHO.
ECHO --- No se ha encontrado ScriptDev2 ---
ECHO.
GOTO:EOF


:VC_NOT_FOUND
ECHO.
ECHO --- No se ha encontrado Visual C++ ---
ECHO.
GOTO:EOF


:END
CALL scripts\util sleep 2
GOTO:EOF